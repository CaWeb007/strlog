<?php
/**
 * Created by PhpStorm.
 * User: p.reutov
 * Date: 17.03.2020
 * Time: 11:12
 */
class CatalogTabs extends \CBitrixComponent {
    public function onPrepareComponentParams($arParams) {
        $result = array();
        $tabsCount = (int)$arParams['TABS_COUNT'];
        for ($i = 1; $i <= $tabsCount; $i++){
            $result[$i] = array(
                'TITLE' => $arParams['TAB_TITLE'.$i],
                'FILTER' => $arParams['TAB_FILTER'.$i],
                'LAST' => ($i === $tabsCount)
            );
        }
        $this->arResult = $result;
        return $arParams;
    }
    public function executeComponent() {
        $this->includeComponentTemplate();
    }
}