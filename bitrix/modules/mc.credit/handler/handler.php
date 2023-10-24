<?php

namespace Sale\Handlers\PaySystem;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Request;
use Bitrix\Sale\Order;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PaySystem;
use CSaleStatus;
use Exception;
use Mc\Credit\Api\MoneyCareAPI;
use Mc\Credit\GatewayMoneyCare;

IncludeModuleLangFile(__FILE__);
require_once dirname(dirname(__FILE__)) . '/config.php';
Loader::includeModule('mc.credit');


class mc_creditHandler extends PaySystem\ServiceHandler implements PaySystem\IPrePayable
{

    const LOG_FILE = '/logs/moneycare.log';

    const ID = 'moneycare';

    private $moduleId = 'mc.credit';

    const STATUS_ID_A = 'A';
    const STATUS_ID_R = 'R';

    /**
     * @param Payment $payment
     * @param Request|null $request
     * @return PaySystem\ServiceResult
     */
    public function initiatePay(Payment $payment, Request $request = null)
    {
        global $APPLICATION;
        $post = $this->requestData($payment);

        if (mb_strtoupper(SITE_CHARSET) !== 'UTF-8') {
            $post = $APPLICATION->ConvertCharsetArray($post, 'windows-1251', 'UTF-8');
        }

        $post = json_encode($post);
        $host = $this->getBusinessValue($payment, 'API_HOST');
        $login = $this->getBusinessValue($payment, 'LOGIN');
        $password = $this->getBusinessValue($payment, 'PASSWORD');

        $moneyCare = new MoneyCareAPI($host, $login, $password);
        $response = $moneyCare->create($post);
        $responseArray = json_decode($response, true);

        $this->log('Request :' . $post);
        $this->log('Response :' . $response);

        $formUrl = $responseArray['formUrl'];

        $this->redirect($formUrl);


        $_SESSION['mc_form_url'] = $formUrl;

        return $this->showTemplate($payment, 'payment');
    }

    private function redirect($url)
    {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $url);
        exit();
    }

    private function requestData(Payment $payment)
    {
        $orderId = $payment->getOrderId();

        $order = Order::load($payment->getOrderId());
        if (!$order) {
            throw new Exception();
        }
        $propertyCollection = $order->getPropertyCollection();

        $pointId = $this->getBySession('MC_POINT_ID', $payment);
        $installment = $this->getBySession('INSTALLMENT', $payment);
        $maxDiscount = $this->getBySession('MAX_DISCOUNT', $payment);
        $fullUserName = $this->getPropertyValueByCode($propertyCollection, 'FIO');
        $explodedUserName = explode(' ', $fullUserName);

        $data = [
            'orderId' => $orderId,
            'lastName' => isset($explodedUserName[0]) ? $explodedUserName[0] : null,
            'firstName' => isset($explodedUserName[1]) ? $explodedUserName[1] : null,
            'middleName' => isset($explodedUserName[2]) ? $explodedUserName[2] : null,
            'phone' => $this->formatPhone($this->getPropertyValueByCode($propertyCollection, 'PHONE')),
            'generateForm' => true,
            'creditTypes' => [MoneycareAPI::CREDIT_TYPE_CLASSIC],
            'pointId' => $pointId,
            'formSuccessUrl' => $this->successUrl($payment),
            'formCancelUrl' => $this->failUrl($payment),
            'forceScore' => false,
            'goods' => $this->goods($order, $payment)
        ];

        if ($installment && $maxDiscount) {
            $data['creditTypes'][] = MoneycareAPI::CREDIT_TYPE_INSTALLMENT;
            $data['maxDiscount'] = $maxDiscount;
        }

        return $data;
    }

    private function goods($order, $payment)
    {
        $basket = $order->getBasket();
        $items = $basket->getBasketItems();
        $productCategory = $this->getBusinessValue($payment, 'PRODUCT_CATEGORY');

        $goods = [];

        foreach ($items as $item) {
            $product = [
                'title' => $item->getField('NAME'),
                'price' => $item->getFinalPrice(),
                'groupId' => $productCategory,
            ];
            $goods[] = $product;
        }

        return $goods;
    }

    private function getBySession($attr, $payment)
    {
        return isset($_SESSION[self::ID][$attr]) ? $_SESSION[self::ID][$attr] : $this->getBusinessValue($payment, $attr);
    }

    private function formatPhone($phone)
    {
        $phone = ltrim($phone, '+78');
        $phone = preg_replace('/\D/', '', $phone);

        return $phone;
    }

    private function log($message)
    {
        $file = realpath((dirname(dirname(__FILE__)))) . $this::LOG_FILE;
        if (is_writable($file)) {
            $time = date('H:i:s');
            file_put_contents($file, $time . ': ' . $message . PHP_EOL, FILE_APPEND);
        }
    }

    private function successUrl($payment)
    {
        return $this->getProtocol() . $this->getDomain() . '/moneycare/result.php' . '?PAYMENT=MONEYCARE&ORDER_ID=' . $payment->getField('ORDER_ID') . '&PAYMENT_ID=' . $payment->getField('ID') . '&status=success';
    }

    private function failUrl($payment)
    {
        return $this->getProtocol() . $this->getDomain() . '/moneycare/result.php' . '?PAYMENT=MONEYCARE&ORDER_ID=' . $payment->getField('ORDER_ID') . '&PAYMENT_ID=' . $payment->getField('ID') . '&status=fail';
    }

    private function getDomain()
    {
        return $_SERVER['HTTP_HOST'];
    }

    private function getProtocol()
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://';
    }

    public function processRequest(Payment $payment, Request $request)
    {
        global $APPLICATION;
        $status = isset($_GET['status']) ? $_GET['status'] : false;
        if ($status === false) {
            throw new Exception();
        }

        $moduleId = $this->moduleId;
        $orderId = $payment->getOrderId();
        $order = Order::load($payment->getOrderId());

        if (!$order) {
            throw new Exception();
        }
        $statusId = $order->getField('STATUS_ID');

        echo '<div class="mc-result-message" style="margin:20px; text-align:center;"><span style="font-size:16px;">';
        if ($status === 'success' && $statusId !== self::STATUS_ID_A && $status !== self::STATUS_ID_R) {
            $successStatus = Option::get($moduleId, 'RESULT_ORDER_SUCCESS_STATUS');
            $this->changeStatus($order, $successStatus);

            $APPLICATION->SetTitle(Loc::getMessage('MC_PAYMENT_MESSAGE_SUCCESS'));
            echo Loc::getMessage('MC_PAYMENT_MESSAGE_SUCCESS') . ' №' . $orderId;
        } elseif ($status === 'fail' && $statusId !== self::STATUS_ID_A && $status !== self::STATUS_ID_R) {
            $successStatus = Option::get($moduleId, 'RESULT_ORDER_FAIL_STATUS');
            $this->changeStatus($order, $successStatus);

            $APPLICATION->SetTitle(Loc::getMessage('MC_PAYMENT_MESSAGE_FAIL'));
            echo Loc::getMessage('MC_PAYMENT_MESSAGE_FAIL') . ' №' . $orderId;
        } else {
            $APPLICATION->SetTitle(Loc::getMessage('MC_PAYMENT_MESSAGE_ERROR'));
            echo Loc::getMessage('MC_PAYMENT_MESSAGE_ERROR');
        }
        echo '</span></div>';

        return new PaySystem\ServiceResult();
    }

    private function changeStatus($order, $status){
        $statuses = $this->getStatuses();

        if (array_key_exists($status, $statuses)) {
            $order->setField('STATUS_ID', $status);
        } else {
            echo '<span style="display:block; font-size:16px; color:red;padding:20px 0;">ERROR! CANT CHANGE ORDER STATUS</span>';
        }

        $order->save();
    }

    private function getStatuses()
    {
        $statuses = array();
        $dbStatus = CSaleStatus::GetList(array('SORT' => 'ASC'), array('LID' => LANGUAGE_ID), false, false, array('ID', 'NAME', 'SORT'));
        while ($arStatus = $dbStatus->GetNext()) {
            $statuses[$arStatus['ID']] = '[' . $arStatus['ID'] . '] ' . $arStatus['NAME'];
        }

        return $statuses;
    }

    public function getPaymentIdFromRequest(Request $request)
    {
        $paymentId = $request->get('PAYMENT_ID');

        return intval($paymentId);
    }

    public function getCurrencyList()
    {
        return array('RUB');
    }

    public static function getIndicativeFields()
    {
        return array('PAYMENT' => 'MONEYCARE');
    }

    protected static function isMyResponseExtended(Request $request, $paySystemId)
    {
        $order = Order::load($request->get('ORDER_ID'));
        if (!$order) {
            $order = Order::loadByAccountNumber($request->get('ORDER_ID'));
        }
        if (!$order) {
            echo Loc::getMessage('RBS_MESSAGE_ERROR_BAD_ORDER');
            return false;
        }

        $paymentIds = $order->getPaymentSystemId();

        return in_array($paySystemId, $paymentIds);
    }

    private function getPropertyValueByCode($propertyCollection, $code)
    {
        $property = '';
        foreach ($propertyCollection as $property) {
            if ($property->getField('CODE') == $code) {
                return $property->getValue();
            }
        }
    }


    /**
     * @return array
     */
    protected function getUrlList()
    {
        return array();
    }

    /**
     * @return array
     */
    public function getProps()
    {
        return array();
    }

    /**
     * @param Payment $payment
     * @param Request $request
     * @return bool
     */
    public function initPrePayment(Payment $payment = null, Request $request)
    {
        return true;
    }

    /**
     * @param array $orderData
     */
    public function payOrder($orderData = array())
    {

    }

    /**
     * @param array $orderData
     * @return bool|string
     */
    public function BasketButtonAction($orderData = array())
    {
        return true;
    }

    /**
     * @param array $orderData
     */
    public function setOrderConfig($orderData = array())
    {
        if ($orderData) {
            $this->prePaymentSetting = array_merge($this->prePaymentSetting, $orderData);
        }
    }

    public function isTuned()
    {

    }

    public function confirm(Payment $payment)
    {

    }

    public function cancel(Payment $payment)
    {

    }

    public function refund(Payment $payment, $refundableSum)
    {

    }

    public function sendResponse(PaySystem\ServiceResult $result, Request $request)
    {

    }

}