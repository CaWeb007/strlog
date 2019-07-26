<?php

namespace Sale\Handlers\PaySystem;

use Bitrix\Main\Error;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Request;
use Bitrix\Main\Text\Encoding;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Web;
use Bitrix\Sale\BusinessValue;
use Bitrix\Sale\PaySystem;
use Bitrix\Sale\Payment;
use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

require_once dirname(dirname(__FILE__)) . '/config.php';
Loader::includeModule('rbs.credit');

/**
 * Class rbsPaymentHandler
 * @package Sale\Handlers\PaySystem
 */
class rbs_creditHandler extends PaySystem\ServiceHandler implements PaySystem\IRefundExtended, PaySystem\IHold
{
	/**
	 * @param Payment $payment
	 * @param Request|null $request
	 * @return PaySystem\ServiceResult
	 */
	public function initiatePay(Payment $payment, Request $request = null)
	{

		$moduleId = 'rbs.credit';
		
		$RBS_Gateway = new \Rbs\Credit\Gateway;
		$RBS_Orders = new \Rbs\Credit\Orders;

		
		// module settings
		$RBS_Gateway->setOptions([
			'module_id' => Option::get($moduleId, 'MODULE_ID'),
			'gate_url_prod' => Option::get($moduleId, 'RBS_PROD_URL'),
			'gate_url_test' => Option::get($moduleId, 'RBS_TEST_URL'),
			'module_version' => Option::get($moduleId, 'MODULE_VERSION'),
			'iso' => unserialize(Option::get($moduleId, 'ISO')),
			'cms_version' => 'Bitrix ' . SM_VERSION,
			'language' => 'ru',
			'creditType' => $this->getBusinessValue($payment, 'CREDIT_TYPE') == 'CREDIT' ? 'CREDIT' : 'INSTALLMENT'
		]);

		// handler settings
		$RBS_Gateway->setOptions([

			'ofd_enabled' => 1,
			'ffd_version' => $this->getBusinessValue($payment, 'FFD_VERSION'),
			'ffd_payment_object' => $this->getBusinessValue($payment, 'FFD_PAYMENT_OBJECT'),
			'ffd_payment_method' => $this->getBusinessValue($payment, 'FFD_PAYMENT_METHOD'),
			'test_mode' => $this->getBusinessValue($payment, 'API_TEST_MODE') == 'Y' ? 1 : 0,
			'handler_logging' => $this->getBusinessValue($payment, 'HANDLER_LOGGING') == 'Y' ? 1 : 0,
			
		    'failUrl' => $this->getBusinessValue($payment, 'API_FAIL_URL'),
		]);

		$RBS_Gateway->buildData([
			'orderNumber' => $this->getBusinessValue($payment, 'ORDER_NUMBER') . '_' . $payment->getField('ID'),
		    'amount' => $this->getBusinessValue($payment, 'ORDER_AMOUNT'),
		    'userName' => $this->getBusinessValue($payment, 'API_LOGIN'),
		    'password' => $this->getBusinessValue($payment, 'API_PASSWORD'),
		    'currency' => $RBS_Gateway->getCurrencyCode( $payment->getField('CURRENCY') ),
		    'description' => $this->getBusinessValue($payment, 'ORDER_DESCRIPTION'),
		]);

		$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== "off" ? 'https://' : 'http://';
		$returnUrlparams = '?PAYMENT=RBS_CREDIT&ORDER_ID=' . $payment->getField('ORDER_ID') . '&PAYMENT_ID=' . $payment->getField('ID') . '&USER_RETURN=1';
		

		if(strlen($this->getBusinessValue($payment, 'API_SUCCESS_URL')) > 0) {
			$RBS_Gateway->buildData(['returnUrl' => $this->getBusinessValue($payment, 'API_RETURN_URL') . $returnUrlparams]);
		} else {
			$RBS_Gateway->buildData(['returnUrl' => $protocol . $_SERVER['SERVER_NAME'] . '/bank/credit.php' . $returnUrlparams]);
		}
		if(strlen($this->getBusinessValue($payment, 'API_FAIL_URL')) > 0) {
			$RBS_Gateway->buildData(['failUrl' => $this->getBusinessValue($payment, 'API_FAIL_URL') . $returnUrlparams]);
		} 

		$Order = Order::load($payment->getOrderId());
		$orderProperties = $Order->getPropertyCollection();

		if ($RBS_Gateway->ofdEnable()) {
			$RBS_Gateway->buildData([
				'description' => $this->getBusinessValue($payment, 'ORDER_DESCRIPTION')
			]);
			$Basket = $Order->getBasket();
			$basketItems = $Basket->getBasketItems();

			$phone = (int) preg_replace('/\D+/', '', $this->getPropertyValueByCode($orderProperties, 'PHONE'));
			$RBS_Gateway->setOptions([
				'customer_name' => $this->getPropertyValueByCode($orderProperties, 'FIO'),
				'customer_email' => $this->getPropertyValueByCode($orderProperties, 'EMAIL'),
				'customer_phone' => $phone,
			]);

			$lastIndex = 0;
			foreach ($basketItems as $key => $BasketItem) {
				$lastIndex = $key + 1;
		        $RBS_Gateway->setPosition([
		            'positionId' => $key,
		            'itemCode' => $BasketItem->getProductId(),
		            'name' => $BasketItem->getField('NAME'),
		            'itemAmount' => $BasketItem->getFinalPrice(),
		            'itemPrice' => $BasketItem->getPrice(),
		            'quantity' => array(
		                'value' => $BasketItem->getQuantity(),
		                'measure' => $BasketItem->getField('MEASURE_NAME'),
		            ),
		            'tax' => array(
		                'taxType' =>  $RBS_Gateway->getTaxCode( $BasketItem->getField('VAT_RATE') * 100 ),
		            ),
		        ]);
			}

			if($Order->getField('PRICE_DELIVERY') > 0) {
				
				Loader::includeModule('catalog');
				$deliveryInfo = \Bitrix\Sale\Delivery\Services\Manager::getById($Order->getField('DELIVERY_ID'));

				$deliveryVatItem = \CCatalogVat::GetByID($deliveryInfo['VAT_ID'])->Fetch();
				$RBS_Gateway->setOptions([
				    'delivery' => true,
				]);
				$RBS_Gateway->setPosition([
		            'positionId' => $lastIndex + 1,
		            'itemCode' => 'DELIVERY_' . $Order->getField('DELIVERY_ID'),
		            'name' => Loc::getMessage('RBS_CREDIT_FIRLD_DELIVERY'),
		            'itemAmount' => $Order->getField('PRICE_DELIVERY'),
		            'itemPrice' => $Order->getField('PRICE_DELIVERY'),
		            'quantity' => array(
		                'value' => 1,
		                'measure' => Loc::getMessage('RBS_CREDIT_FIELD_MEASURE'),
		            ),
		            'tax' => array(
		                'taxType' => $RBS_Gateway->getTaxCode($deliveryVatItem['RATE']),
		            ),
		        ]);	
			}
		}

		$gateResponse = $RBS_Gateway->registerOrder();

		$params = array(
	        'rbs_result' => $gateResponse,
	        'payment_link' => $RBS_Gateway->getPaymentLink(),
	        'currency' => $payment->getField('CURRENCY'),
	        'rbs_result' => $gateResponse
	    );
	    $this->setExtraParams($params);

	    $db_result = $RBS_Orders->GetByPaymentID($payment->getField('ID'))->Fetch();
	    if(!$db_result) {
	    	$RBS_Orders->Add([
	    		'USER_INFO' => $this->getPropertyValueByCode($orderProperties, 'FIO'),
	    		'PAYMENT_SUM' => $this->getBusinessValue($payment, 'ORDER_AMOUNT'),
	    		'CMS_ORDER_ID' => $payment->getOrderId(),
	    		'CMS_PAYMENT_ID' => $payment->getField('ID'),
		    ]);	
	    }
	    
	    return $this->showTemplate($payment, "payment");
	}

	public function processRequest(Payment $payment, Request $request)
	{
		global $APPLICATION;
		$moduleId = 'rbs.credit';


		$RBS_Gateway = new \Rbs\Credit\Gateway;
		$RBS_Orders = new \Rbs\Credit\Orders;

		$RBS_Gateway->setOptions([
			'gate_url_prod' => Option::get($moduleId, 'RBS_PROD_URL'),
			'gate_url_test' => Option::get($moduleId, 'RBS_TEST_URL'),
			'test_mode' => $this->getBusinessValue($payment, 'API_TEST_MODE') == 'Y' ? 1 : 0,
		]);

		$RBS_Gateway->buildData([
		    'userName' => $this->getBusinessValue($payment, 'API_LOGIN'),
		    'password' => $this->getBusinessValue($payment, 'API_PASSWORD'),
		    'orderId' => $request->get('mdOrder'),
		]);

		$gateResponse = $RBS_Gateway->checkOrder();



        $successPayment = true;
        
        if($this->getBusinessValue($payment, 'ORDER_NUMBER') != $request->get('ORDER_ID')) {
        	$successPayment = false;
        }
		if(explode("_", $gateResponse['orderNumber'] )[0] != $this->getBusinessValue($payment, 'ORDER_NUMBER')) {
			$successPayment = false;
		}

        if( $gateResponse['errorCode'] != 0 || ($gateResponse['orderStatus'] != 5 && $gateResponse['orderStatus'] != 2) ) {
        	$successPayment = false;
        }
        

        if($successPayment && !$payment->isPaid()) {

        	// set payment status
        	$order = Order::load($payment->getOrderId());
			$paymentCollection = $order->getPaymentCollection();
			foreach ($paymentCollection as $col_payment) {
				if($col_payment->getField('ID') == $payment->getField('ID')) {
					$col_payment->setPaid("Y");
					$col_payment->setFields([
		                "PS_SUM" => $gateResponse["amount"] / 100,
		                "PS_CURRENCY" => $gateResponse["currency"],
		                "PS_RESPONSE_DATE" => new DateTime(),
		                "PS_STATUS" => "Y",
		                "PS_STATUS_DESCRIPTION" => $gateResponse["cardAuthInfo"]["pan"] . ";" . $gateResponse['cardAuthInfo']["cardholderName"],
		                "PS_STATUS_MESSAGE" => $gateResponse["paymentAmountInfo"]["paymentState"],
		                "PS_STATUS_CODE" =>  $gateResponse['orderStatus'],
	        		]);

	        		break;
				}
			}
			if($order->isPaid()) {
				// // set order status
				$order->setField('STATUS_ID', Option::get($moduleId, 'RESULT_ORDER_STATUS'));

				// set delivery status
				if($this->getBusinessValue($payment, 'HANDLER_SHIPMENT') == 'Y') {
					$shipmentCollection = $order->getShipmentCollection();
					foreach ($shipmentCollection as $shipment){
					    if (!$shipment->isSystem()) {
			        		$shipment->allowDelivery();
					    }
			    	}
		    	}
	    	}
		    $order->save();
        }

        if($gateResponse['errorCode'] == 0) {
		    $db_result = $RBS_Orders->GetByPaymentID($payment->getField('ID'))->Fetch();
		    if($db_result) {
		    	$RBS_Orders->Update(
		    		$db_result['ID'],
		    		[
		    			'BANK_ORDER_ID' => $gateResponse['orderNumber'],
		    			'BANK_MD_ORDER' => $request->get('mdOrder'),
		    			'BANK_SUM' => $gateResponse["amount"] / 100,
		    			'BANK_ORDER_STATUS' => $gateResponse['orderStatus'],
		    			'CMS_ORDER_STATUS' => 'PAYED'
		    		]

		    	);	
		    }
		}
		if($request->get('USER_RETURN')) {
			$APPLICATION->SetTitle(Loc::getMessage('RBS_CREDIT_MESSAGE_THANKS'));
			if($payment->isPaid()) {
				echo Loc::getMessage('RBS_CREDIT_MESSAGE_THANKS_DESCRIPTION') . $payment->getField('ORDER_ID');
			} else {
        		echo Loc::getMessage('RBS_CREDIT_MESSAGE_PROCESSING') . $payment->getField('ORDER_ID');
			}
		}
        else if ($successPayment) {
        	$APPLICATION->SetTitle(Loc::getMessage('RBS_CREDIT_MESSAGE_THANKS'));
        	echo Loc::getMessage('RBS_CREDIT_MESSAGE_THANKS_DESCRIPTION') . $payment->getField('ORDER_ID');
        } else {
        	$APPLICATION->SetTitle(Loc::getMessage('RBS_CREDIT_MESSAGE_ERROR'));
        }
       
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
		return array('PAYMENT' => 'RBS_CREDIT');
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