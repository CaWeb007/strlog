<?
namespace Sale\Handlers\PaySystem;

use Bitrix\Main\Error;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Request;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Web\Uri;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Order;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PaySystem;
use Bitrix\Sale\PropertyValue;

Loc::loadMessages(__FILE__);

class MoneyCareHandler extends PaySystem\ServiceHandler {
    const CREATE_URL = 'https://rc1.moneycare.su/broker/api/v2/orders/create';
    const ONLINE_URL = 'https://rc1.moneycare.su/broker/online/';
    const CHECK_URL = 'https://rc1.moneycare.su/broker/api/v2/orders/{id}/details';
    const REQUEST_URL = 'https://xn--80afpacjdwcqkhfi.xn--p1ai/bitrix/tools/sale_ps_result.php';
    const PHONE_PREG = array(' ', '(', ')', '-');
    const HANDLER_NAME = 'MONEYCARE';
    private $orderEntity = null;
    private $paymentEntity = null;
    private $status = array(
        'online_form' => 'A',
        'processing' => 'B',
        'contruct' => 'C',
        'end' => 'D',
        'cancel' => 'E',
        'decline' => 'F',
        'scoring' => 'G'
    );
    private function setEntities(Payment $payment){
        $this->paymentEntity = $payment;
        $this->orderEntity = Order::load($payment->getOrderId());
    }
    private function saveEntities(){
        $this->getPayment()->save();
        $this->getOrder()->save();
    }
    /**@return Order*/
    private function getOrder(){
        return $this->orderEntity;
    }
    /**@return Payment*/
    private function getPayment(){
        return $this->paymentEntity;
    }
    private function getStatusMessage($status = ''){
        return strtoupper(array_flip($this->status)[$status]);
    }
    public function initiatePay(Payment $payment, Request $request = null)
    {
        $this->setEntities($payment);
        $psStatus = $this->getPsDbStatus();
        if (empty($psStatus) || ($psStatus === 'A')){
            $createUrl = $this->getCreateUrl($psStatus);
            $this->setExtraParams(array('CREATE_URL' => $createUrl));
            return $this->showTemplate($payment, 'form');
        }
        if (($psStatus === 'B') || ($psStatus === 'C')){
            $psStatus = $this->getCheckStatus();
        }
        if ($psStatus === 'G')
            return $this->showTemplate('error');
        $this->setExtraParams(array('STATUS' => $this->getStatusMessage($psStatus)));
        return $this->showTemplate($payment, 'template');
    }
    private function getPsDbStatus (){
        $result = $this->getPayment()->getField('PS_STATUS');
        if (empty($result)) return false;
        return $result;
    }
    private function getCreateUrl($psStatus = ''){
        if ($psStatus === 'A') return $this->getCreateUrlFromDb();
        $data = $this->getCreateData();
        $response = $this->createPost($data);
        if (empty($response)){
            $data = array_intersect_key($data, array_flip(array('point_id', 'goods')));
            $response = $this->createPost($data);
        }
        if ($response !== false){
            $this->getOrder()->setField('STATUS_ID', 'PW');
            $this->getPayment()->setField('PS_STATUS', 'A');
            $this->getPayment()->setField('PS_STATUS_MESSAGE', 'online_form');
            $this->getPayment()->setField('PAY_VOUCHER_NUM', (int)$response['id']);
            $this->getPayment()->setField('XML_ID', $this->getToken($response['formUrl']));
            $this->saveEntities();
            return $response['formUrl'];
        }
        return false;
    }
    private function getCreateUrlFromDb() {
        $obj = new Uri(self::ONLINE_URL);
        $obj->addParams(array(
            'orderId' => $this->getPayment()->getField('PAY_VOUCHER_NUM'),
            'token' => $this->getPayment()->getField('XML_ID')
        ));
        return $obj->getUri();
    }
    private function getCreateData(){
        $data['order_id'] = $this->getOrder()->getId();
        $data['pointId'] = $this->getBusinessValue($this->getPayment(), 'POINT_ID');
        $data['generateForm'] = true;
        $data['forceScore'] = false;
        $data['goods'] = $this->getProducts();
        $data += $this->getRequestUrl();
        $data += $this->getProperty();
        return $data;
    }
    private function getProducts(){
        $result = array();
        $items = $this->getOrder()->getBasket()->getOrderableItems();
        /**@var $item BasketItem*/
        foreach ($items as $item) {
            $tmp['title'] = $item->getField('NAME');
            $tmp['price'] = $item->getPrice();
            $tmp['count'] = $item->getQuantity();
            $result[] = $tmp;
        }
        return $result;
    }
    private function getProperty(){
        $result = array();
        $properties = $this->getOrder()->getPropertyCollection();
        $personType = (int)$this->getOrder()->getPersonTypeId();
        $phone = $properties->getPhone();
        if ($personType === 2){
            $firstName = $properties->getItemByOrderPropertyId(36);
            $secondName = $properties->getItemByOrderPropertyId(37);
            $lastName = $properties->getItemByOrderPropertyId(38);
        }else{
            $firstName = $properties->getItemByOrderPropertyId(32);
            $secondName = $properties->getItemByOrderPropertyId(33);
            $lastName = $properties->getItemByOrderPropertyId(34);
        }
        if ($firstName instanceof PropertyValue) $result['firstName'] = $firstName->getValue();
        if ($secondName instanceof PropertyValue) $result['secondName'] = $secondName->getValue();
        if ($lastName instanceof PropertyValue) $result['lastName'] = $lastName->getValue();
        if ($phone instanceof PropertyValue)
            $result['phone'] = substr(str_replace(self::PHONE_PREG, '', $phone->getValue()), -10, 10);
        return $result;
    }
    private function getRequestUrl(){
        $result = array();
        $requestUrl = new Uri(self::REQUEST_URL);
        $requestUrl->addParams(array(
            'PAYMENT' => self::HANDLER_NAME, 'PAYMENT_ID' => $this->getPayment()->getId(), 'SUCCESS' => 'Y'));
        $result['formSuccessUrl'] = $requestUrl->getUri();
        $requestUrl->addParams(array('SUCCESS' => 'N'));
        $result['formCancelUrl'] = $requestUrl->getUri();
        return $result;
    }
    private function createPost($data = array()){
        $login = $this->getBusinessValue($this->getPayment(), 'LOGIN');
        $password = $this->getBusinessValue($this->getPayment(), 'PASSWORD');
        $http = new HttpClient();
        $http->setAuthorization($login, $password);
        $http->setHeader('Content-Type', 'application/json');
        $response = $http->post(self::CREATE_URL, json_encode($data));
        $response = json_decode($response, true);
        if(($http->getStatus() === 200) && $response['accepted'] && !empty($response['id']))
            return $response;
        return false;
    }
    private function getToken($formUrl = '') {
        if (empty($formUrl)) return '';
        $result = array();
        $obj = new Uri($formUrl);
        parse_str($obj->getQuery(), $result);
        return $result['token'];
    }
    private function getCheckStatus(){
        $login = $this->getBusinessValue($this->getPayment(), 'LOGIN');
        $password = $this->getBusinessValue($this->getPayment(), 'PASSWORD');
        $http = new HttpClient();
        $http->setAuthorization($login, $password);
        $http->setHeader('Content-Type', 'application/json');
        $response = $http->get($this->getCheckUrl());
        $response = json_decode($response, true);
        if(($http->getStatus() === 200) && $response['accepted'] && !empty($response['id'])){
            $status = $this->status[$response['status']];
            $this->setData($response);
            return $status;
        }
        return false;
    }
    private function getCheckUrl(){
        $result = '';
        $result = str_replace(
            '{id}', $this->getPayment()->getField('PAY_VOUCHER_NUM'), self::CHECK_URL);
        return $result;
    }
    private function setData($response) {
        $message = $response['status'];
        $status = $this->status[$message];
        $this->getPayment()->setField('PS_STATUS', $status);
        $this->getPayment()->setField('PS_STATUS_MESSAGE', $message);
        switch ($status){
            case 'D':
                $this->getOrder()->setField('STATUS_ID', 'P');
                break;
            case 'E':
            case 'F':
                $this->getOrder()->setField('STATUS_ID', 'N');
        }
        $this->saveEntities();
    }

    public function processRequest(Payment $payment, Request $request)
    {
        $result = new PaySystem\ServiceResult();
        if ($request->get('SUCCESS') === 'N'){
            $result->addError(new Error(Loc::getMessage('MC_ERROR_REQUEST')));
        }else{
            $result->setPsData(array(
                'PS_STATUS_MESSAGE' => 'processing',
                'PS_STATUS' => $this->status['processing']
            ));
        }
        return $result;
    }
    public function getPaymentIdFromRequest(Request $request)
    {
        $paymentId = $request->get('PAYMENT_ID');
        return intval($paymentId);
    }
    public static function getIndicativeFields()
    {
        return array('PAYMENT' => self::HANDLER_NAME);
    }
    static protected function isMyResponseExtended(Request $request, $paySystemId)
    {
        return true;
    }
    public function getCurrencyList()
    {
        return 'RUB';
    }
    public function isTuned(){}
    public function sendResponse(PaySystem\ServiceResult $result, Request $request){
        LocalRedirect('/personal/orders/');
    }
}