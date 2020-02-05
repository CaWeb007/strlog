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
    const PHONE_PREG = array(' ', '(', ')', '-');
    const HANDLER_NAME = 'MONEYCARE';
    private $status = array(
        'online_form' => 'A',
        'processing' => 'B',
        'contruct' => 'C',
        'end' => 'D',
        'cancel' => 'E',
        'decline' => 'F',
        'scoring' => 'G'
    );
    public function initiatePay(Payment $payment, Request $request = null)
    {
        $order = Order::load($payment->getOrderId());
        if (!$status = $this->getStatus($payment,$order))
            $status = $this->linkQuery($payment, $order);
        if ($status === false)
            return $this->showTemplate($payment, 'error');
        if ($status === 'A'){
            $this->setExtraParams(array('url' => $this->getLink($payment)));
            return $this->showTemplate($payment, 'form');
        }
        $extraParam = $this->checkQuery($order, $payment);
        return $this->showTemplate($payment, 'template');
    }



    private function linkQuery(Payment $payment, Order $order){
        $data = $this->getData($payment, $order);
        $response = array();
        if (empty($response = $this->http($payment, $data, self::CREATE_URL))){
            $data = array_intersect_key($data, array_flip(array('point_id', 'goods')));
            $response = $this->http($payment, $data, self::CREATE_URL);
        }
        if ($response !== false){
            $order->setField('STATUS_ID', 'PW');
            $payment->setField('PS_STATUS', 'A');
            $payment->setField('PS_STATUS_MESSAGE', 'online_form');
            $payment->setField('PAY_VOUCHER_NUM', (int)$response['id']);
            $payment->setField('XML_ID', $this->getToken($response['formUrl']));
            $order->save();
            $payment->save();
            return 'A';
        }
        return false;
    }
    private function http(Payment $payment ,$data, $url){
        $login = $this->getBusinessValue($payment, 'LOGIN');
        $password = '1234567';//todo getBuisValue
        $http = new HttpClient();
        $http->setAuthorization($login, $password);
        $http->setHeader('Content-Type', 'application/json');
        $response = $http->post($url, json_encode($data));
        $response = json_decode($response, true);
        if(($http->getStatus() === 200) && $response['accepted'] && !empty($response['id']))
            return $response;
        return false;
    }
    private function getData(Payment $payment,Order $order){
        $data['order_id'] = $order->getId();
        $data['pointId'] = 'tt_test_1'; //todo getBuisValue
        $data['generateForm'] = true;
        $data['forceScore'] = false;
        $requestUrl = new Uri("https://xn--80afpacjdwcqkhfi.xn--p1ai/bitrix/tools/sale_ps_result.php");
        $requestUrl->addParams(array(
            'PAYMENT' => self::HANDLER_NAME, 'PAYMENT_ID' => $payment->getId(), 'SUCCESS' => 'Y'));
        $data['formSuccessUrl'] = $requestUrl->getUri();
        $requestUrl->addParams(array('SUCCESS' => 'N'));
        $data['formCancelUrl'] = $requestUrl->getUri();
        $data['goods'] = array();
        $items = $order->getBasket()->getOrderableItems();
        /**@var $item BasketItem*/
        foreach ($items as $item){
            $tmp['title'] = $item->getField('NAME');
            $tmp['price'] = $item->getPrice();
            $tmp['count'] = $item->getQuantity();
            $data['goods'][] = $tmp;
        }
        $properties = $order->getPropertyCollection();
        $phone = $properties->getPhone()->getValue();
        $data['phone'] = substr(
            str_replace(self::PHONE_PREG, '', $phone), -10, 10);
        $personType = (int)$order->getPersonTypeId();
        if ($personType === 1){
            $firstName = $properties->getItemByOrderPropertyId(32);
            $secondName = $properties->getItemByOrderPropertyId(33);
            $lastName = $properties->getItemByOrderPropertyId(34);
        }else{
            $firstName = $properties->getItemByOrderPropertyId(36);
            $secondName = $properties->getItemByOrderPropertyId(37);
            $lastName = $properties->getItemByOrderPropertyId(38);
        }
        if ($firstName instanceof PropertyValue) $data['firstName'] = $firstName->getValue();
        if ($secondName instanceof PropertyValue) $data['secondName'] = $secondName->getValue();
        if ($lastName instanceof PropertyValue) $data['$lastName'] = $lastName->getValue();
        return $data;
    }
    private function checkQuery(Order $order, Payment $payment){
        $login = $this->getBusinessValue($payment, 'LOGIN');
        $password = '1234567';//todo getBuisValue
        $http = new HttpClient();
        $http->setAuthorization($login, $password);
        $http->setHeader('Content-Type', 'application/json');
        $response = $http->get(str_replace('{id}', $payment->getField('PAY_VOUCHER_NUM'), self::CHECK_URL));
        $response = json_decode($response, true);
        if(($http->getStatus() === 200) && $response['accepted'] && !empty($response['id'])){
            $this->setData($order, $payment, $response);
            return $response;
        }
        return false;
    }
    private function getStatus (Payment $payment, Order $order){
        $dbStatus = $payment->getField('PS_STATUS');
        if (empty($dbStatus)) return false;
        return $dbStatus;
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
    public function getCurrencyList()
    {
        return 'RUB';
    }
    public static function getIndicativeFields()
    {
        return array('PAYMENT' => self::HANDLER_NAME);
    }
    static protected function isMyResponseExtended(Request $request, $paySystemId)
    {
            return true;
    }
    public function isTuned(){}
    public function sendResponse(PaySystem\ServiceResult $result, Request $request){}
    private function getToken($formUrl) {
        $obj = new Uri($formUrl);
        $query = array();
        parse_str($obj->getQuery(), $query);
        return $query['token'];
    }
    private function getLink(Payment $payment) {
        $obj = new Uri(self::ONLINE_URL);
        $obj->addParams(array(
            'orderId' => $payment->getField('PAY_VOUCHER_NUM'),
            'token' => $payment->getField('XML_ID')
        ));
        return $obj->getUri();
    }
    private function setData(Order $order,Payment $payment, $response) {
        $status = $this->status[$response['status']];
        $payment->setField('PS_STATUS_MESSAGE', $response['status']);
        $payment->setField('PS_STATUS', $status);
        switch ($status){
            case 'D':
                $order->setField('STATUS_ID', 'P');
                break;
            case 'E':
            case 'F':
                $order->setField('STATUS_ID', 'N');
        }
        $payment->save();
        $order->save();
    }
}