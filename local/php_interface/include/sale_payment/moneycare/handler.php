<?
namespace Sale\Handlers\PaySystem;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Request;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PaySystem;

Loc::loadMessages(__FILE__);

class MoneyCareHandler extends PaySystem\ServiceHandler implements PaySystem\IRefundExtended, PaySystem\IHold{
    public function initiatePay(Payment $payment, Request $request = null)
    {
        return $this->showTemplate($payment, "template");
    }

    public function processRequest(Payment $payment, Request $request)
    {
        return new PaySystem\ServiceResult();
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
        return array();
    }

    static protected function isMyResponseExtended(Request $request, $paySystemId)
    {
        return true;
    }

    private function getPropertyValueByCode($propertyCollection, $code) {
        $property = '';
        foreach ($propertyCollection as $property)
        {
            if($property->getField('CODE') == $code)
                return $property->getValue();
        }
    }


    public function isTuned(){}
    public function isRefundableExtended(){}
    public function confirm(Payment $payment){}
    public function cancel(Payment $payment){}
    public function refund(Payment $payment, $refundableSum){}
    public function sendResponse(PaySystem\ServiceResult $result, Request $request){}
}