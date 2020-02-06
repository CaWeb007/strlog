<?
namespace Caweb\Main\Agent;

use Bitrix\Sale\Internals;
use Bitrix\Sale\Order;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PaymentCollection;

class MoneyCare {
    public static function checkStatus(){
        $paySystemId = array();
        $db = Internals\PaySystemActionTable::getList(array('filter' => array('ACTION_FILE' => 'moneycare'),
            'select' => array('PAY_SYSTEM_ID')));
        while ($ar = $db->fetch()) $paySystemId[] = (int)$ar['PAY_SYSTEM_ID'];
        $db = Internals\PaymentTable::getList(array('filter' => array('PAY_SYSTEM_ID' => $paySystemId),
            'select' => array('ORDER_ID')));
        while($ar = $db->fetch()){
            $pc = PaymentCollection::load(Order::load((int)$ar['ORDER_ID']));
            /**@var $item Payment*/
            foreach ($pc as $item) {
                if (!in_array((int)$item->getPaymentSystemId(), $paySystemId)) continue;
                $item->getPaySystem()->check($item);
            }
        }
        return '\Caweb\Main\Agent\MoneyCare::checkStatus();';
    }
}