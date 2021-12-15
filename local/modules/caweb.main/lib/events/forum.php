<?
namespace Caweb\Main\Events;

use Bitrix\Forum\MessageTable;
use Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__FILE__);
class Forum{
    public static function checkSpam($fields){
        $cPattern = '(?:(?:(?:http[s]?):\/\/)|(?:www.))(?:[-_0-9a-z]+.)+[-_0-9a-z]{2,4}[:0-9]*[\/]*';
        mb_regex_encoding('UTF-8');
        $vRegs = array();
        mb_eregi($cPattern, $fields['POST_MESSAGE'], $vRegs);
        if (count($vRegs) > 0){
            global $APPLICATION;
            $APPLICATION->throwException(Loc::getMessage('CAWEB_FORUM_URL_SPAM'));
            return false;
        }
    }
    public static function sendMessage($ID, $arMessage, $topicInfo, $forumInfo, $arFields){
        if ((int)$arMessage['FORUM_ID'] !== 1) return;
        if ($arMessage['NEW_TOPIC'] === 'Y') return;
        $messageFields = array();
        $elementFields = \CIBlockElement::GetByID((int)$arMessage['PARAM2'])->GetNextElement()->GetFields();
        $messageFields['ELEMENT_NAME'] = $elementFields['NAME'];
        $messageFields['ELEMENT_PATH'] = SITE_SERVER_NAME.$elementFields['DETAIL_PAGE_URL'];
        $messageFields['MESSAGE'] = $arMessage['POST_MESSAGE'];
        $messageFields['MESSAGE_EDIT'] = SITE_SERVER_NAME.'/bitrix/admin/perfmon_row_edit.php?lang=ru&table_name=b_forum_message&pk%5BID%5D='.$arMessage['ID'];
        $id = \CEvent::Send('NEW_PRODUCT_REVIEW', 's1', $messageFields, 'N', 98);
    }
}