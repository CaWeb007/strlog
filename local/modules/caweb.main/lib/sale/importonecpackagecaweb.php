<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 08.05.2019
 * Time: 21:03
 */

namespace Caweb\Main\Sale;

use Bitrix\Sale\Exchange\EntityType;
use Bitrix\Sale\Exchange\ImportOneCPackageSale;
use Bitrix\Sale\Exchange\OneC\OrderDocument;
use Bitrix\Sale\Exchange\OneC\PaymentCardDocument;
use Bitrix\Sale\Exchange\OneC\PaymentCashDocument;
use Bitrix\Sale\Exchange\OneC\PaymentDocument;
use Bitrix\Sale\Exchange\OneC\ShipmentDocument;
use Bitrix\Sale\Order;
use Bitrix\Sale\Payment;
use Bitrix\Sale\Shipment;
use Caweb\Main\Log\Write;

final class ImportOneCPackageCaweb extends ImportOneCPackageSale {
    protected $debugMode = true;
    protected $logArray = array();
    protected function convert(array $documents)
    {
        $documentPaymentExist = false;
        $documentShipmentExist = false;
        $orderShipment = null;
        $orderPayment = null;
        $documentOrder = null;
        if($documentOrder = $this->getDocumentByTypeId(EntityType::ORDER, $documents)){
            $order = Order::load($documentOrder->getField('ID'));
            if ($order instanceof Order){
                $this->logArray['ORDER_ID'] = $order->getId();
                /**@var $shipment Shipment*/
                $shipmentCollection = $order->getShipmentCollection();
                foreach ($shipmentCollection as $shipment){
                    if ($shipment->isSystem()) continue;
                    $orderShipment = $shipment;
                }
                /**@var $payment Payment*/
                $paymentCollection = $order->getPaymentCollection();
                foreach ($paymentCollection as $payment){
                    if ($payment->isInner()) continue;
                    $orderPayment = $payment;
                }
            }
            foreach($documents as $document)
            {
                if(($document instanceof PaymentDocument) && ($orderPayment instanceof Payment))
                {
                    $paymentFields = $document->getFieldValues();
                    $paymentFields['ID'] = $orderPayment->getId();
                    $paymentFields['REK_VALUES']['PAY_SYSTEM_ID'] = $orderPayment->getPaymentSystemId();
                    $document->setFields($paymentFields);
                    $documentPaymentExist = true;
                    $this->logArray['PAYMENT_MODIFY'] = $paymentFields['ID'];
                    if ((int)$paymentFields['REK_VALUES']['PAY_SYSTEM_ID'] === 9)
                        $this->logArray['FIELDS'] = $document->getFieldValues();
                }

                if(($document instanceof ShipmentDocument) && ($orderShipment instanceof Shipment))
                {
                    $shipmentFields = $document->getFieldValues();
                    $shipmentFields['ID'] = $orderShipment->getId();
                    $shipmentFields['REK_VALUES']['DELIVERY_SYSTEM_ID'] = $orderShipment->getDeliveryId();
                    $document->setFields($shipmentFields);
                    $documentShipmentExist = true;
                    $this->logArray['SHIPMENT_MODIFY'] = $shipmentFields['ID'];
                }
            }
            if (!$documentShipmentExist){
                $documents[] = $this->createShipmentDocument($documentOrder, $orderShipment);
            };
            if (!$documentPaymentExist){
                $documents[] = $this->createPaymentDocument($documentOrder, $orderPayment);

            }
        }
        if ($this->debugMode) Write::file('saleExchange', $this->logArray);
        return parent::convert($documents);
    }

    protected function createPaymentDocument($documentOrder, $orderPayment){
        /**@var $documentOrder OrderDocument*/
        /**@var $orderPayment Payment*/
        if ((int)$orderPayment->getPaymentSystemId() === 9){
            $document = new PaymentCardDocument();
            $defaultFields = PaymentCardDocument::getFieldsInfo();
            $operation = 'PAYMENT_CARD_TRANSACTION';
        }else{
            $document = new PaymentCashDocument();
            $defaultFields = PaymentCashDocument::getFieldsInfo();
            $operation = 'PAYMENT_CASH';
        }
        $fieldsArray = array();
        foreach ($defaultFields as $key=>$value){
            switch ($key){
                case 'CANCELED':
                    $fieldsArray[$key] = $orderPayment->getField('IS_RETURN');
                    break;
                case 'OPERATION':
                    $fieldsArray[$key] = $operation;
                    break;
                case 'VERSION_1C':
                    $fieldsArray[$key] = $documentOrder->getField('VERSION_1C');
                    break;
                case 'ID_1C':
                    if ($v = $orderPayment->getField('ID_1C')){
                        $fieldsArray[$key] = $v;
                    }else{
                        $fieldsArray[$key] = $documentOrder->getField('ID_1C');
                    }
                    break;
                case 'CASH_BOX_CHECKS':
                    $fieldsArray[$key] = array('ID' => '');
                    break;
                case 'REK_VALUES':
                    $fieldsArray[$key] = array(
                        '1C_PAYED_DATE' => $orderPayment->getField('DATE_PAID'),
                        '1C_PAYED_NUM' => '',
                        'CANCEL' => $orderPayment->getField('IS_RETURN'),
                        '1C_PAYED' => $orderPayment->getField('PAID'),
                        'PAY_SYSTEM_ID' => $orderPayment->getField('PAY_SYSTEM_ID'),
                    );
                    break;
                default:
                    if ($value['TYPE'] === 'string') $fieldsArray[$key] = '';
                    if($v = $orderPayment->getField($key)) $fieldsArray[$key] = $v;
                    break;
            }
        }
        $document->setFields($fieldsArray);
        $this->logArray['PAYMENT_CREATE'] = $fieldsArray['ID'];
        if ((int)$fieldsArray['REK_VALUES']['PAY_SYSTEM_ID'] === 9)
            $this->logArray['FIELDS'] = $document->getFieldValues();
        return $document;
    }
    protected function createShipmentDocument($documentOrder, $orderShipment){
        /**@var $documentOrder OrderDocument*/
        /**@var $orderShipment Shipment*/
        $fieldsArray = array();
        $document = new ShipmentDocument();
        $defaultFields = ShipmentDocument::getFieldsInfo();
        foreach ($defaultFields as $key=>$value){
            switch ($key){
                case 'ITEMS':
                    $fieldsArray[$key] = $this->getProductsItems($documentOrder->getFieldValues());
                    break;
                case 'OPERATION':
                    $fieldsArray[$key] = 'SHIPMENT';
                    break;
                case 'VERSION_1C':
                    $fieldsArray[$key] = $documentOrder->getField('VERSION_1C');
                    break;
                case 'ID_1C':
                    if ($v = $orderShipment->getField('ID_1C')){
                        $fieldsArray[$key] = $v;
                    }else{
                        $fieldsArray[$key] = $documentOrder->getField('ID_1C');
                    }
                    break;
                case 'AGENT':
                    $fieldsArray[$key] = $documentOrder->getField('AGENT');
                    break;
                case 'REK_VALUES':
                    $fieldsArray[$key] = array(
                        'CANCEL' => $orderShipment->getField('CANCELED'),
                        'DEDUCTED' => $orderShipment->getField('DEDUCTED'),
                        'DELIVERY_SYSTEM_ID' => $orderShipment->getDeliveryId()
                    );
                    break;
                default:
                    if ($value['TYPE'] === 'string') $fieldsArray[$key] = '';
                    if($v = $orderShipment->getField($key)) $fieldsArray[$key] = $v;
                    break;
            }
        }
        $document->setFields($fieldsArray);
        $this->logArray['SHIPMENT_CREATE'] = $fieldsArray['ID'];
        return $document;
    }
}