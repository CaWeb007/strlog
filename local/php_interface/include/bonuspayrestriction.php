<?

use Bitrix\Sale\Services\PaySystem\Restrictions\Price;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Internals\Entity;
use Bitrix\Sale\Order;
use Bitrix\Sale\PaymentCollection;
use Bitrix\Sale\Services\Base;
use Bitrix\Sale\Payment;

class BonusPayRestriction extends Price
{
	
	public static $params;
	
	public function setParams($params)
	{
		self::$params = $params;
	}
	
	
	/**
	 * @param $params
	 * @param array $restrictionParams
	 * @param int $serviceId
	 * @return bool
	 */
	 
	public static function check($params, array $restrictionParams, $serviceId = 0)
	{
		if ($params['PRICE_PAYMENT'] === null)
			return true;

		//return true;
		$maxValue = static::getPrice($params, $restrictionParams['MAX_VALUE']);
		$minValue = static::getPrice($params, $restrictionParams['MIN_VALUE']);
		$price = (float)$params['PRICE_PAYMENT'];

		if ($maxValue > 0 && $minValue > 0)
			return ($maxValue >= $price) && ($minValue <= $price);

		if ($maxValue > 0)
			return $maxValue >= $price;

		if ($minValue > 0)
			return $minValue <= $price;

		return false;
	}

	/**
	 * @param $entityParams
	 * @param $paramValue
	 * @return float
	 */
	protected static function getPrice($entityParams, $paramValue)
	{
		$percent = (float)$paramValue / 100;
		$price = (float)$entityParams['PRICE_ORDER'] * $percent;
		
		return roundEx(min($price,$entityParams['MAX_BUDGET']), SALE_VALUE_PRECISION);
	}

	/**
	 * @return mixed
	 */
	public static function getClassTitle()
	{
		return "% от стоимости заказа + бонус";
	}

	/**
	 * @return mixed
	 */
	public static function getClassDescription()
	{
		return "% от стоимости заказа и если на товар действует оплата бонусами";
	}
	
	
	protected static function extractParams(Entity $entity)
	{
		$params = self::$params;
		
		if(isset($params['PROP_CODE']) && $params['PROP_CODE']){
			$property_item = "PROPERTY_".$params['PROP_CODE'];
		}
		
		$orderPrice = null;
		$paymentPrice = null;

		if ($entity instanceof Payment)
		{
			global $USER; 
			$userID = $USER->GetID();

			$userGroups = \CUser::GetUserGroup($userID);
			$intersect = array_intersect([9,14,15], $userGroups);
			if($intersect){
				$PRICE_IDS = ['LOGIC'=>'OR',9,11];
			}
			
			$intersect = array_intersect([10,12], $userGroups);
			if($intersect){
				$PRICE_IDS = ['LOGIC'=>'OR',9,10];
			}
			
			$maxUserBudget = 0;
			
			
			/** @var PaymentCollection $collection */
			$collection = $entity->getCollection();
			/** @var Order $order */
			$order = $collection->getOrder();
			foreach($order->getBasket() as $basketItem){
				$productID = $basketItem->getProductId(); 
				
				$dbProductPrice = CPrice::GetListEx(
					['CATALOG_GROUP_ID'=>'DESC'],
					["PRODUCT_ID" => $productID,"=CATALOG_GROUP_ID"=>$PRICE_IDS],
					false,
					false,
					["ID", "CATALOG_GROUP_ID", "CATALOG_GROUP_NAME", "PRICE"]
				);
				
				$prices = [];
				
				while($ar_prices = $dbProductPrice->GetNext())
				{
					$prices[] = (float)$ar_prices['PRICE'];
				}
				
				if(2 == count($prices) && $prices[0] . $prices[1]){
					$maxUserBudget += $prices[0] - $prices[1];
				}
				
				if(0 < $maxUserBudget){
					$PRODUCT_IDS[] = $productID;
					$PRODUCTS[$productID] = $basketItem->getFinalPrice();
				}
			}
	
			$res = CIBlockElement::GetList(['IBLOCK_ID'=>16],['ID'=>array_merge(["LOGIC"=>"OR"],$PRODUCT_IDS)], false, false, ['ID',$property_item]);
			while($ar_fields = $res->GetNext())
			{
				if($ar_fields[$property_item.'_VALUE']=='Да'){
					$PRODUCTS[$ar_fields['ID']]=0;
				}
			}
			
			$orderPrice = array_sum($PRODUCTS);#$order->getPrice();
			$paymentPrice = $entity->getField('SUM');
			
		}

		return array(
			'PRICE_PAYMENT' => $paymentPrice,
			'PRICE_ORDER' => $orderPrice,
			'MAX_BUDGET' => $maxUserBudget
		);
	}

	/**
	 * @param $entityId
	 * @return array
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public static function getParamsStructure($entityId = 0)
	{
		return array(
			"MIN_VALUE" => array(
				'TYPE' => 'NUMBER',
				'DEFAULT' => 0,
				'LABEL' => "% от стоимости заказа от:"
			),
			"MAX_VALUE" => array(
				'TYPE' => 'NUMBER',
				'DEFAULT' => 0,
				'LABEL' => "% от стоимости заказа до:"
			),
			"PROP_CODE" => array(
				'TYPE' => 'STRING',
				'DEFAULT' => "CODE",
				"LABEL"    => "Код свойства зависимости"
			)
		);
	}

	/**
	 * @param Payment $payment
	 * @param $params
	 * @return array
	 * @throws ArgumentTypeException
	 */
	public static function getRange(Payment $payment, $params)
	{
		if ($payment instanceof Payment)
		{
			$p = static::extractParams($payment);
			return array(
				'MAX' => static::getPrice($p, $params['MAX_VALUE']),
				'MIN' => static::getPrice($p, $params['MIN_VALUE']),
			);
		}

		throw new ArgumentTypeException('');
	}
	
	

	public static function getParams($paySystemId)
	{
		$result = array();

		$class = "\\".get_called_class();

		$options = array(
			'select' => array('CLASS_NAME', 'PARAMS'),
			'filter' => array(
				'SERVICE_ID' => $paySystemId,
				'=CLASS_NAME' => $class
			)
		);

		$dbRes = Manager::getList($options);
		if ($data = $dbRes->fetch())
		{
			return $data['PARAMS'];
		}

		return $result;
	}
	
	/**
	 * @param $mode
	 * @return int
	 *
	public static function getSeverity($mode)
	{
		return \Manager::SEVERITY_SOFT;
	}*/
}
?>