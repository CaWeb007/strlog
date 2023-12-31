<?php

namespace Sale\Handlers\PaySystem;

use Bitrix\Iblock\ElementPropertyTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Config\Option;
use Bitrix\Main\GroupTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Request;
use Bitrix\Main\Type\Date;
use Bitrix\Main\UserGroupTable;
use Bitrix\Sale;
use Bitrix\Sale\PaySystem;

Loc::loadMessages(__FILE__);
/**
 * Class BillHandler
 * @package Sale\Handlers\PaySystem
 */
class BillHandler extends PaySystem\BaseServiceHandler
{
    /**
     * @param Sale\Payment $payment
     * @param Request|null $request
     * @return PaySystem\ServiceResult
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     */
    public function initiatePay(Sale\Payment $payment, Request $request = null)
    {
        $template = 'template';

        if (array_key_exists('pdf', $_REQUEST))
            $template .= '_pdf';

        $extraParams = $this->getPreparedParams($payment, $request);
        $this->setExtraParams($extraParams);

        return $this->showTemplate($payment, $template);
    }

    /**
     * @param Sale\Payment|null $payment
     * @param string $template
     * @return PaySystem\ServiceResult
     */
    public function showTemplate(Sale\Payment $payment = null, $template = '')
    {
        \CCurrencyLang::disableUseHideZero();

        return parent::showTemplate($payment, $template);
    }

    /**
     * @param Sale\Payment $payment
     * @param Request|null $request
     * @return array
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     */
    protected function getPreparedParams(Sale\Payment $payment, Request $request = null)
    {
        /** @var \Bitrix\Sale\PaymentCollection $paymentCollection */
        $paymentCollection = $payment->getCollection();

        /** @var \Bitrix\Sale\Order $order */
        $order = $paymentCollection->getOrder();
        $extraParams = array(
            'ACCOUNT_NUMBER' => (IsModuleInstalled('intranet')) ? $order->getField('ACCOUNT_NUMBER') : $payment->getField('ACCOUNT_NUMBER'),
            'CURRENCY' => $payment->getField('CURRENCY'),
            'DATE_BILL' => $payment->getField('DATE_BILL'),
            'SUM' => Sale\PriceMaths::roundPrecision($payment->getSum()),
            'SUM_PAID' => Sale\PriceMaths::roundPrecision($paymentCollection->getPaidSum()),
            'DISCOUNT_PRICE' => Sale\PriceMaths::roundPrecision($order->getDiscountPrice())
        );
        $extraParams['GROUP_ID'] = (int)UserGroupTable::getRow(array(
            'filter' => array(
                'USER_ID' => $order->getUserId(),
                'GROUP_ID' => array(10,11,12,14)
            )
        ))['GROUP_ID'];
        $taxes = $order->getTax();
        $extraParams['TAXES'] = $taxes->getTaxList();

        /** @var \Bitrix\Sale\ShipmentCollection $shipmentCollection */
        $shipmentCollection = $order->getShipmentCollection();

        /** @var \Bitrix\Sale\Shipment $shipment */
        foreach ($shipmentCollection as $shipment)
        {
            if ($storeId  = Sale\Delivery\ExtraServices\Manager::getStoreIdForShipment($shipment->getId(), $shipment->getDeliveryId()))
                $extraParams['STORE_ID'] = (int)$storeId;
            if (!$shipment->isSystem())
            {
                $extraParams['DELIVERY_NAME'] = $shipment->getDeliveryName();
                $extraParams['DELIVERY_PRICE'] = $shipment->getPrice();
                $extraParams['DELIVERY_VAT_RATE'] = $shipment->getVatRate();
                break;
            }
        }
        $weightUnit = Option::get("sale", "weight_unit", "", SITE_ID);
        $weightKoef = Option::get("sale", "weight_koef", 1, SITE_ID);
        $weight = $shipmentCollection->getWeight();
        $extraParams['DELIVERY_WEIGHT'] = roundEx(
                doubleval($weight / $weightKoef),
                3)." ".$weightUnit;
        $basket = $order->getBasket();

        $extraParams['BASKET_ITEMS'] = array();

        $userColumns = $this->getBusinessValue($payment, 'USER_COLUMNS');
        $ids = array();
        if ($userColumns !== null)
        {
            $extraParams['USER_COLUMNS'] = array();
            $userColumns = unserialize($userColumns);
            if ($userColumns)
            {
                foreach ($userColumns as $id => $columns)
                {
                    $extraParams['USER_COLUMNS']['PROPERTY_'.$id] = array(
                        'NAME' => $columns['NAME'],
                        'SORT' => $columns['SORT']
                    );
                    $ids[] = $id;
                }
            }
        }
        Loader::includeModule('iblock');

        /** @var \Bitrix\Sale\BasketItem $basketItem */
        foreach ($basket->getBasketItems() as $basketItem)
        {
            $item = array(
                'NAME' => $basketItem->getField("NAME"),
                'IS_VAT_IN_PRICE' => $basketItem->isVatInPrice(),
                'PRICE' => $basketItem->getPrice(),
                'VAT_RATE' => $basketItem->getVatRate(),
                'QUANTITY' => $basketItem->getQuantity(),
                'MEASURE_NAME' => $basketItem->getField("MEASURE_NAME"),
                'CURRENCY' => $basketItem->getCurrency(),
                'ARTICLE' => \CIBlockElement::GetByID($basketItem->getProductId())->GetNextElement()->GetProperty(88)['VALUE']
            );

            $item['PROPS'] = array();
            foreach ($basketItem->getPropertyCollection() as $basketPropertyItem)
            {
                $item['PROPS'][] = array(
                    'CODE' => $basketPropertyItem->getField('CODE'),
                    'NAME' => $basketPropertyItem->getField('NAME'),
                    'VALUE' => $basketPropertyItem->getField('VALUE')
                );
            }

            if ($ids && Loader::includeModule('crm') && Loader::includeModule('iblock'))
            {
                $product = \CCrmProduct::GetByID($basketItem->getProductId(), true);

                $rsProperties = \CIBlockElement::GetProperty(
                    isset($product['CATALOG_ID']) ? intval($product['CATALOG_ID']) : \CCrmCatalog::EnsureDefaultExists(),
                    $basketItem->getProductId(),
                    array(),
                    array('ACTIVE' => 'Y', 'EMPTY' => 'N', 'CHECK_PERMISSIONS' => 'N')
                );

                while ($arProperty = $rsProperties->Fetch())
                {
                    $value = $arProperty['VALUE'];
                    if (is_array($value))
                        $value = implode("\n", $value);
                    $item['PROPERTY_'.$arProperty['ID']] = $value;
                }
            }

            $extraParams['BASKET_ITEMS'][] = $item;
        }

        return $extraParams;
    }

    /**
     * @return array
     */
    public function getCurrencyList()
    {
        return array('RUB');
    }

    /**
     * @return bool
     */
    public function isAffordPdf()
    {
        return true;
    }

    /**
     * @return array
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectException
     */
    public function getDemoParams()
    {
        $data = array(
            'ACCOUNT_NUMBER' => 'A1',
            'PAYMENT_DATE_INSERT' => new Date(),
            'DATE_INSERT' => new Date(),
            'CURRENCY' => 'RUB',
            'SUM' => 5900,
            'SUM_PAID' => 0,
            'TAXES' => array(
                array(
                    'TAX_NAME' => Loc::getMessage('SALE_HPS_BILL_TAX'),
                    'IS_IN_PRICE' => 'Y',
                    'CODE' => 'VAT',
                    'VALUE_MONEY' => 900,
                    'VALUE' => 18.00,
                    'IS_PERCENT' => 'Y'
                )
            ),
            'BASKET_ITEMS' => array(
                array(
                    'NAME' => Loc::getMessage('SALE_HPS_BILL_BASKET_ITEM_NAME'),
                    'IS_VAT_IN_PRICE' => false,
                    'PRICE' => 5000,
                    'VAT_RATE' => 0.18,
                    'QUANTITY' => 1,
                    'MEASURE_NAME' => Loc::getMessage('SALE_HPS_BILL_BASKET_ITEM_MEASURE'),
                    'CURRENCY' => 'RUB'
                )
            ),
            'SELLER_COMPANY_BANK_CITY' => Loc::getMessage('SALE_HPS_BILL_BANK_CITY'),
            'SELLER_COMPANY_ADDRESS' => Loc::getMessage('SALE_HPS_BILL_BANK_ADDRESS'),
            'SELLER_COMPANY_PHONE' => '+76589321451',
            'SELLER_COMPANY_BANK_NAME' => Loc::getMessage('SALE_HPS_BILL_BANK_NAME'),
            'SELLER_COMPANY_BANK_ACCOUNT' => '0000 0000 0000 0000 0000',
            'SELLER_COMPANY_INN' => '000011112222',
            'SELLER_COMPANY_KPP' => '123456789',
            'SELLER_COMPANY_NAME' => Loc::getMessage('SALE_HPS_BILL_COMPANY_NAME'),
            'SELLER_COMPANY_BANK_BIC' => '0123456',
            'SELLER_COMPANY_BANK_ACCOUNT_CORR' => '1111 1111 1111 1111',
            'BUYER_PERSON_COMPANY_NAME' => Loc::getMessage('SALE_HPS_BILL_BUYER_COMPANY_NAME'),
            'BUYER_PERSON_COMPANY_INN' => '0123456789',
            'BUYER_PERSON_COMPANY_PHONE' => '79091234523',
            'BUYER_PERSON_COMPANY_FAX' => '88002000600',
            'BUYER_PERSON_COMPANY_ADDRESS' => Loc::getMessage('SALE_HPS_BILL_BUYER_COMPANY_ADDRESS'),
            'BUYER_PERSON_COMPANY_NAME_CONTACT' => Loc::getMessage('SALE_HPS_BILL_BUYER_NAME_CONTACT'),
            'SELLER_COMPANY_DIRECTOR_POSITION' => Loc::getMessage('SALE_HPS_BILL_DIRECTOR_POSITION'),
            'SELLER_COMPANY_DIRECTOR_NAME' => Loc::getMessage('SALE_HPS_BILL_DIRECTOR_NAME'),
            'SELLER_COMPANY_ACCOUNTANT_POSITION' => Loc::getMessage('SALE_HPS_BILL_ACCOUNTANT_POSITION'),
            'SELLER_COMPANY_ACCOUNTANT_NAME' => Loc::getMessage('SALE_HPS_BILL_ACCOUNTANT_NAME'),
            'SELLER_COMPANY_EMAIL' => 'my@company.com',
        );

        if (Loader::includeModule('crm') && Loader::includeModule('iblock'))
        {
            $arFilter = array(
                'IBLOCK_ID' => intval(\CCrmCatalog::EnsureDefaultExists()),
                'CHECK_PERMISSIONS' => 'N',
                '!PROPERTY_TYPE' => 'G'
            );

            $dbRes = \CIBlockProperty::GetList(array(), $arFilter);
            while ($arRow = $dbRes->Fetch())
                $data['BASKET_ITEMS'][0]['PROPERTY_'.$arRow['ID']] = 'test';
        }

        return $data;
    }
}
