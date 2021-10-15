<?$this->__component->arResultCacheKeys = array_merge($this->__component->arResultCacheKeys, array('ID', 'NAME', 'LIST_PAGE_URL', 'DISPLAY_PROPERTIES'));?>
<?
/**@var $arResult array*/
$arResult['DETAIL_TEXT'] = preg_replace_callback(
    '/<\?(php)?[\s+?\n?\s+]*(\$APPLICATION->IncludeComponent\(.*)\?>/Us', function ($matches) {

    global $APPLICATION;

    ob_start();
    eval($matches[2]);
    return ob_get_clean();
}, $arResult['DETAIL_TEXT']
);
?>
