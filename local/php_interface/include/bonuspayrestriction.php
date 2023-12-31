<?

use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Services\PaySystem\Restrictions\Price;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Internals\Entity;
use Bitrix\Sale\Order;
use Bitrix\Sale\PaymentCollection;
use Bitrix\Sale\Services\Base;
use Bitrix\Sale\Payment;
use Bitrix\Catalog\PriceTable;

class BonusPayRestriction extends Price
{
	
	public static $params;
    public static $kpNalPriceType = 15;
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

        if ($params['PRICE_TYPE'] === self::$kpNalPriceType)
            return false;

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
	
	
	protected static function extractParamsBack(Entity $entity){
		$params = self::$params;
		if(isset($params['PROP_CODE']) && $params['PROP_CODE']){
			$property_item = "PROPERTY_".$params['PROP_CODE'];
		}
		$orderPrice = null;
		$paymentPrice = null;
		if ($entity instanceof Payment){
			/** @var PaymentCollection $collection */
			$collection = $entity->getCollection();
			/** @var Order $order */
			$order = $collection->getOrder();
            $maxUserBudget = 0;
			foreach($order->getBasket() as $basketItem){
				$productID = $basketItem->getProductId();
                $maxUserBudget += static::getBudget($basketItem);
                if(0 < $maxUserBudget) $PRODUCT_IDS[] = $productID;
			}
			$res = CIBlockElement::GetList(['IBLOCK_ID'=>16],['ID'=>array_merge(["LOGIC"=>"OR"],$PRODUCT_IDS)], false, false, ['ID',$property_item]);
			while($ar_fields = $res->GetNext()){
				if($ar_fields[$property_item.'_VALUE']=='Да') $PRODUCTS[$ar_fields['ID']] = 0;
			}
			$paymentPrice = $entity->getField('SUM');
			$orderPrice = $order->getPrice();
			$maxUserBudget = static::checkBudget($orderPrice, $maxUserBudget);
		}
		return array(
			'PRICE_PAYMENT' => $paymentPrice,
			'PRICE_ORDER' => $orderPrice,
			'MAX_BUDGET' => $maxUserBudget
		);
	}
    protected static function extractParams(Entity $entity){
        $params = self::$params;
        if(isset($params['PROP_CODE']) && $params['PROP_CODE']){
            $property_item = "PROPERTY_".$params['PROP_CODE'];
        }
        $orderPrice = null;
        $paymentPrice = null;
        $maxUserBudget = 0;
        $priceType = null;
        if ($entity instanceof Payment){
            /** @var PaymentCollection $collection */
            $collection = $entity->getCollection();
            /** @var Order $order */
            $order = $collection->getOrder();
            $budget = array();
            $PRODUCT_IDS = array();
            $basket = $order->getBasket();
            /**@var $basketItem BasketItem*/
            foreach($basket as $basketItem){
                $productID = $basketItem->getProductId();
                $budget[$productID] = static::getBudget($basketItem);
                if(0 < $maxUserBudget) $PRODUCT_IDS[] = $productID;
            }
            $filter = array('IBLOCK_ID' => 16, 'ID' => $PRODUCT_IDS);
            $select = array('ID','IBLOCK_ID','PROPERTY_NELZYA_OPLACHIVAT_BONUSAMI');
            $res = CIBlockElement::GetList(array(), $filter, false, false, $select);
            while($ar_fields = $res->GetNext()){
                if($ar_fields['PROPERTY_NELZYA_OPLACHIVAT_BONUSAMI_VALUE'] === 'Да') unset($budget[$ar_fields['ID']]);
            }
            $priceType = self::getPriceType($basket);
            $maxUserBudget = array_sum($budget);
            $paymentPrice = $entity->getField('SUM');
            $orderPrice = $order->getPrice();
            $maxUserBudget = static::checkBudget($orderPrice, $maxUserBudget);
        }
        return array(
            'PRICE_PAYMENT' => $paymentPrice,
            'PRICE_ORDER' => $orderPrice,
            'MAX_BUDGET' => $maxUserBudget,
            'PRICE_TYPE' => $priceType
        );
    }
    protected static function getPriceType(\Bitrix\Sale\Basket $basket){
        $kpNalPriceTypeId = self::$kpNalPriceType;
        $basketItems = $basket->getBasketItems();
        $basketItemLastKey = array_key_last($basketItems);
        /**@var $item BasketItem*/
        foreach ($basketItems as $key => $item){
            $arPrice = array();
            if (!$item->isCustomPrice()) continue;
            $db = PriceTable::getList(array('filter' => array('PRODUCT_ID' => (int)$item->getProductId(), 'PRICE' => (float)$item->getPrice()), 'select' => array('CATALOG_GROUP_ID')));
            while($ar = $db->fetch()){
                $arPrice[] = (int)$ar['CATALOG_GROUP_ID'];
            }
            if (!is_array($arPrice)) return null;
            if (count($arPrice) > 1){
                if ($key === $basketItemLastKey){
                    if (array_search($kpNalPriceTypeId, $arPrice) !== false){
                        return $kpNalPriceTypeId;
                    }else{
                        return array_shift($arPrice);
                    }
                }else{
                    continue;
                }
            }else{
                return array_shift($arPrice);
            }
        }

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
	

    protected static function getBudget($basketItem){
        /** @var BasketItem $basketItem */
        $params = array(
            'filter' => array(
                'PRODUCT_ID' => $basketItem->getProductId(),
                'CATALOG_GROUP_ID' => 9),
            'select' => array('PRICE')
        );
        $minPrice = (float)PriceTable::getRow($params)['PRICE'];
        $thisPrice = (float)$basketItem->getPrice();
        return ($thisPrice - $minPrice) * $basketItem->getQuantity();
    }
    protected static function checkBudget($orderPrice, $maxUserBudget){
        $maxBudget = $orderPrice * 0.5;
        if ($maxUserBudget > $maxBudget) return $maxBudget;
        return $maxUserBudget;
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