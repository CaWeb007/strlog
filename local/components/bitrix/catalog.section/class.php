<?

use Bitrix\Catalog\Discount\DiscountManager;
use Bitrix\Catalog\ProductTable;
use Bitrix\Iblock\Component\Tools;
use \Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Error;
use \Bitrix\Main\Type\DateTime;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Iblock;
use \Bitrix\Iblock\Component\ElementList;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global CIntranetToolbar $INTRANET_TOOLBAR
 */

Loc::loadMessages(__FILE__);

if (!\Bitrix\Main\Loader::includeModule('iblock'))
{
	ShowError(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
	return;
}

class CatalogSectionComponent extends ElementList
{
	public function __construct($component = null)
	{
		parent::__construct($component);
		$this->setExtendedMode(false)->setMultiIblockMode(false)->setPaginationMode(true);
	}

	public function onPrepareComponentParams($params)
	{
		$params = parent::onPrepareComponentParams($params);
		$params['IBLOCK_TYPE'] = isset($params['IBLOCK_TYPE']) ? trim($params['IBLOCK_TYPE']) : '';

		if ((int)$params['SECTION_ID'] > 0 && (int)$params['SECTION_ID'].'' != $params['SECTION_ID'] && Loader::includeModule('iblock'))
		{
			$this->errorCollection->setError(new Error(Loc::getMessage('CATALOG_SECTION_NOT_FOUND'), self::ERROR_404));
			return $params;
		}

		$params['SECTION_ID_VARIABLE'] = (isset($params['SECTION_ID_VARIABLE']) ? trim($params['SECTION_ID_VARIABLE']) : '');
		if ($params['SECTION_ID_VARIABLE'] == '' || !preg_match(self::PARAM_TITLE_MASK, $params['SECTION_ID_VARIABLE']))
			$params['SECTION_ID_VARIABLE'] = 'SECTION_ID';

		$params['SHOW_ALL_WO_SECTION'] = isset($params['SHOW_ALL_WO_SECTION']) && $params['SHOW_ALL_WO_SECTION'] === 'Y';
		$params['USE_MAIN_ELEMENT_SECTION'] = isset($params['USE_MAIN_ELEMENT_SECTION']) && $params['USE_MAIN_ELEMENT_SECTION'] === 'Y';

		$params['BACKGROUND_IMAGE'] = isset($params['BACKGROUND_IMAGE']) ? trim($params['BACKGROUND_IMAGE']) : '';
		if ($params['BACKGROUND_IMAGE'] === '-')
		{
			$params['BACKGROUND_IMAGE'] = '';
		}

		// compatibility for bigData case with zero initial elements
		if ($params['PAGE_ELEMENT_COUNT'] <= 0 && !isset($params['PRODUCT_ROW_VARIANTS']))
		{
			$params['PAGE_ELEMENT_COUNT'] = 20;
		}

		$params['CUSTOM_CURRENT_PAGE'] = isset($params['CUSTOM_CURRENT_PAGE']) ? trim($params['CUSTOM_CURRENT_PAGE']) : '';

		$params['COMPATIBLE_MODE'] = (isset($params['COMPATIBLE_MODE']) && $params['COMPATIBLE_MODE'] === 'N' ? 'N' : 'Y');
		if ($params['COMPATIBLE_MODE'] === 'N')
		{
			$params['DISABLE_INIT_JS_IN_COMPONENT'] = 'Y';
			$params['OFFERS_LIMIT'] = 0;
		}

		$this->setCompatibleMode($params['COMPATIBLE_MODE'] === 'Y');

		$params['DISABLE_INIT_JS_IN_COMPONENT'] = isset($params['DISABLE_INIT_JS_IN_COMPONENT']) && $params['DISABLE_INIT_JS_IN_COMPONENT'] === 'Y' ? 'Y' : 'N';

		if ($params['DISABLE_INIT_JS_IN_COMPONENT'] !== 'Y')
		{
			CJSCore::Init(array('popup'));
		}
        if (\Caweb\Main\Tools::getInstance()->isTO()){
            $params['STORES'] = array(array_shift($params['STORES']));
        }
		return $params;
	}
    protected function getSort()
    {
        $sortFields = array();

        if (
            (
                $this->isIblockCatalog
                || (
                    $this->isMultiIblockMode()
                    || (!$this->isMultiIblockMode() && $this->offerIblockExist($this->arParams['IBLOCK_ID']))
                )
            )
            && $this->arParams['HIDE_NOT_AVAILABLE'] === 'L'
        )
        {
            $sortFields['CATALOG_AVAILABLE'] = 'desc,nulls';
        }

        if (!empty($this->arParams['CUSTOM_ELEMENT_SORT']))
            $sortFields += $this->arParams['CUSTOM_ELEMENT_SORT'];

        if (!isset($sortFields[$this->arParams['ELEMENT_SORT_FIELD']]))
        {
            $sortFields[$this->arParams['ELEMENT_SORT_FIELD']] = $this->arParams['ELEMENT_SORT_ORDER'];
        }

        if (!isset($sortFields[$this->arParams['ELEMENT_SORT_FIELD2']]))
        {
            $sortFields[$this->arParams['ELEMENT_SORT_FIELD2']] = $this->arParams['ELEMENT_SORT_ORDER2'];
        }

        return $sortFields;
    }

	protected function processResultData()
	{
        $this->getStoresInfo();

        if ($this->initSectionResult())
		{
			$this->initSectionProperties();
			parent::processResultData();
		}

	}

	protected function getStoresInfo(){
        if ($this->arParams['USE_STORE'] && is_array($this->arParams['STORES'])){
            $res = array();
            $db = \CCatalogStore::GetList(array('SORT' => 'ASC'), array('ID' => $this->arParams['STORES']));
            while ($ar = $db->Fetch())
                $res[$ar['ID']] = $ar;
            if(!empty($res))
                $this->arResult['STORES'] = $res;
        }
    }

	protected function initSectionResult()
	{
		$success = true;
		$selectFields = array();

		if (!empty($this->arParams['SECTION_USER_FIELDS']) && is_array($this->arParams['SECTION_USER_FIELDS']))
		{
			foreach ($this->arParams['SECTION_USER_FIELDS'] as $field)
			{
				if (is_string($field) && preg_match('/^UF_/', $field))
				{
					$selectFields[] = $field;
				}
			}
		}

		if (preg_match('/^UF_/', $this->arParams['META_KEYWORDS']))
		{
			$selectFields[] = $this->arParams['META_KEYWORDS'];
		}

		if (preg_match('/^UF_/', $this->arParams['META_DESCRIPTION']))
		{
			$selectFields[] = $this->arParams['META_DESCRIPTION'];
		}

		if (preg_match('/^UF_/', $this->arParams['BROWSER_TITLE']))
		{
			$selectFields[] = $this->arParams['BROWSER_TITLE'];
		}

		if (preg_match('/^UF_/', $this->arParams['BACKGROUND_IMAGE']))
		{
			$selectFields[] = $this->arParams['BACKGROUND_IMAGE'];
		}

		$filterFields = array(
			'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
			'IBLOCK_ACTIVE' => 'Y',
			'ACTIVE' => 'Y',
			'GLOBAL_ACTIVE' => 'Y',
		);

		// Hidden tricky parameter USED to display linked
		// by default it is not set
		if ($this->arParams['BY_LINK'] === 'Y')
		{
			$sectionResult = array(
				'ID' => 0,
				'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
			);
		}
		elseif ($this->arParams['SECTION_ID'] > 0)
		{
			$filterFields['ID'] = $this->arParams['SECTION_ID'];
			$sectionIterator = CIBlockSection::GetList(array(), $filterFields, false, $selectFields);
			$sectionIterator->SetUrlTemplates('', $this->arParams['SECTION_URL']);
			$sectionResult = $sectionIterator->GetNext();
		}
		elseif (strlen($this->arParams['SECTION_CODE']) > 0)
		{
			$filterFields['=CODE'] = $this->arParams['SECTION_CODE'];
			$sectionIterator = CIBlockSection::GetList(array(), $filterFields, false, $selectFields);
			$sectionIterator->SetUrlTemplates('', $this->arParams['SECTION_URL']);
			$sectionResult = $sectionIterator->GetNext();
		}
		elseif (strlen($this->arParams['SECTION_CODE_PATH']) > 0)
		{
			$sectionId = CIBlockFindTools::GetSectionIDByCodePath($this->arParams['IBLOCK_ID'], $this->arParams['SECTION_CODE_PATH']);
			if ($sectionId)
			{
				$filterFields['ID'] = $sectionId;
				$sectionIterator = CIBlockSection::GetList(array(), $filterFields, false, $selectFields);
				$sectionIterator->SetUrlTemplates('', $this->arParams['SECTION_URL']);
				$sectionResult = $sectionIterator->GetNext();
			}
		}
		else	// Root section (no section filter)
		{
			$sectionResult = array(
				'ID' => 0,
				'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
			);
		}

		if (empty($sectionResult))
		{
			$success = false;
			$this->abortResultCache();
			$this->errorCollection->setError(new Error(Loc::getMessage('CATALOG_SECTION_NOT_FOUND'), self::ERROR_404));
		}
		else
		{
			$this->arResult = array_merge($this->arResult, $sectionResult);
			if ($this->arResult['ID'] > 0 && $this->arParams['ADD_SECTIONS_CHAIN'])
			{
				$this->arResult['PATH'] = array();
				$pathIterator = CIBlockSection::GetNavChain(
					$this->arResult['IBLOCK_ID'],
					$this->arResult['ID'],
					array(
						'ID', 'CODE', 'XML_ID', 'EXTERNAL_ID', 'IBLOCK_ID',
						'IBLOCK_SECTION_ID', 'SORT', 'NAME', 'ACTIVE',
						'DEPTH_LEVEL', 'SECTION_PAGE_URL'
					)
				);
				$pathIterator->SetUrlTemplates('', $this->arParams['SECTION_URL']);
				while ($path = $pathIterator->GetNext())
				{
					$ipropValues = new Iblock\InheritedProperty\SectionValues($this->arParams['IBLOCK_ID'], $path['ID']);
					$path['IPROPERTY_VALUES'] = $ipropValues->getValues();
					$this->arResult['PATH'][] = $path;
				}
			}
		}

		return $success;
	}

	protected function initSectionProperties()
	{
		$arResult =& $this->arResult;

		$arResult['IPROPERTY_VALUES'] = array();
		if ($arResult['ID'] > 0)
		{
			$ipropValues = new Iblock\InheritedProperty\SectionValues($arResult['IBLOCK_ID'], $arResult['ID']);
			$arResult['IPROPERTY_VALUES'] = $ipropValues->getValues();
		}

		Iblock\Component\Tools::getFieldImageData(
			$arResult,
			array('PICTURE', 'DETAIL_PICTURE'),
			Iblock\Component\Tools::IPROPERTY_ENTITY_SECTION,
			'IPROPERTY_VALUES'
		);

		$arResult['BACKGROUND_IMAGE'] = false;
		if ($this->arParams['BACKGROUND_IMAGE'] != '' && !empty($arResult[$this->arParams['BACKGROUND_IMAGE']]))
		{
			$arResult['BACKGROUND_IMAGE'] = CFile::GetFileArray($arResult[$this->arParams['BACKGROUND_IMAGE']]);
		}
	}

	protected function initCatalogInfo()
	{
		parent::initCatalogInfo();
		$useCatalogButtons = array();
		if (
			!empty($this->storage['CATALOGS'][$this->arParams['IBLOCK_ID']])
			&& is_array($this->storage['CATALOGS'][$this->arParams['IBLOCK_ID']])
		)
		{
			$catalogType = $this->storage['CATALOGS'][$this->arParams['IBLOCK_ID']]['CATALOG_TYPE'];
			if ($catalogType == CCatalogSku::TYPE_CATALOG || $catalogType == CCatalogSku::TYPE_FULL)
			{
				$useCatalogButtons['add_product'] = true;
			}

			if ($catalogType == CCatalogSku::TYPE_PRODUCT || $catalogType == CCatalogSku::TYPE_FULL)
			{
				$useCatalogButtons['add_sku'] = true;
			}
			unset($catalogType);
		}

		$this->storage['USE_CATALOG_BUTTONS'] = $useCatalogButtons;
	}

	protected function getCacheKeys()
	{
		return array(
			'ID',
			'NAV_CACHED_DATA',
			'NAV_STRING',
			$this->arParams['META_KEYWORDS'],
			$this->arParams['META_DESCRIPTION'],
			$this->arParams['BROWSER_TITLE'],
			$this->arParams['BACKGROUND_IMAGE'],
			'NAME',
			'PATH',
			'IBLOCK_SECTION_ID',
			'IPROPERTY_VALUES',
			'ITEMS_TIMESTAMP_X',
			'BACKGROUND_IMAGE',
			'USE_CATALOG_BUTTONS'
		);
	}

	protected function getFilter()
	{
		$filterFields = parent::getFilter();

		if ($this->getAction() === 'bigDataLoad')
		{
			return $filterFields;
		}

		$filterFields['INCLUDE_SUBSECTIONS'] = $this->arParams['INCLUDE_SUBSECTIONS'] === 'N' ? 'N' : 'Y';

		if ($this->arParams['INCLUDE_SUBSECTIONS'] === 'A')
		{
			$filterFields['SECTION_GLOBAL_ACTIVE'] = 'Y';
		}

		if ($this->arParams['BY_LINK'] !== 'Y')
		{
			if ($this->arResult['ID'])
			{
				$filterFields['SECTION_ID'] = $this->arResult['ID'];
			}
			elseif (!$this->arParams['SHOW_ALL_WO_SECTION'])
			{
				$filterFields['SECTION_ID'] = 0;
			}
			else
			{
				unset($filterFields['INCLUDE_SUBSECTIONS']);
				unset($filterFields['SECTION_GLOBAL_ACTIVE']);
			}
		}

		return $filterFields;
	}

	protected function getSelect() {
        $select =  parent::getSelect();
        if (($this->arParams['USE_STORE'] === 'Y') && is_array($this->arParams['STORES'])){
            foreach ($this->arParams['STORES'] as $id)
                $select[] = 'CATALOG_STORE_AMOUNT_'.$id;
        }
        return $select;
    }
    protected function getStoreOffersSelect(&$offersSelect){
        if (($this->arParams['USE_STORE'] === 'Y') && is_array($this->arParams['STORES'])){
            foreach ($this->arParams['STORES'] as $id)
                $offersSelect['CATALOG_STORE_AMOUNT_'.$id] = 1;
        }
    }
    protected function getIblockOffers($iblockId)
    {
        $offers = array();
        $iblockParams = $this->storage['IBLOCK_PARAMS'][$iblockId];

        $enableCompatible = $this->isEnableCompatible();

        if (
            $this->useCatalog
            && $this->offerIblockExist($iblockId)
            && !empty($this->productWithOffers[$iblockId])
        )
        {
            $catalog = $this->storage['CATALOGS'][$iblockId];

            $productProperty = 'PROPERTY_'.$catalog['SKU_PROPERTY_ID'];
            $productPropertyValue = $productProperty.'_VALUE';

            $offersFilter = $this->getOffersFilter($catalog['IBLOCK_ID']);
            $offersFilter[$productProperty] = $this->productWithOffers[$iblockId];

            $offersOrder = $this->getOffersSort();

            $offersSelect = array(
                'ID' => 1,
                'IBLOCK_ID' => 1,
                $productProperty => 1,
                'CATALOG_TYPE' => 1,
                'CATALOG_STORE_AMOUNT_88' => 1,
                'CATALOG_STORE_AMOUNT_49' => 1,
            );
            if (!empty($iblockParams['OFFERS_FIELD_CODE']))
            {
                foreach ($iblockParams['OFFERS_FIELD_CODE'] as $code)
                    $offersSelect[$code] = 1;
                unset($code);
            }

            $checkFields = array();
            foreach (array_keys($offersOrder) as $code)
            {
                $code = strtoupper($code);
                $offersSelect[$code] = 1;
                if ($code == 'ID' || $code == 'CATALOG_AVAILABLE')
                    continue;
                $checkFields[] = $code;
            }
            unset($code);

            $offersSelect['PREVIEW_PICTURE'] = 1;
            $offersSelect['DETAIL_PICTURE'] = 1;
            $this->getStoreOffersSelect($offersSelect);
            $offersId = array();
            $offersCount = array();
            $iterator = \CIBlockElement::GetList(
                $offersOrder,
                $offersFilter,
                false,
                false,
                array_keys($offersSelect)
            );
            while($row = $iterator->GetNext())
            {
                $row['ID'] = (int)$row['ID'];
                $row['IBLOCK_ID'] = (int)$row['IBLOCK_ID'];
                $productId = (int)$row[$productPropertyValue];

                if ($productId <= 0)
                    continue;

                if ($enableCompatible && $this->arParams['OFFERS_LIMIT'] > 0)
                {
                    $offersCount[$productId]++;
                    if($offersCount[$productId] > $this->arParams['OFFERS_LIMIT'])
                        continue;
                }

                $row['SORT_HASH'] = 'ID';
                if (!empty($checkFields))
                {
                    $checkValues = '';
                    foreach ($checkFields as $code)
                        $checkValues .= (isset($row[$code]) ? $row[$code] : '').'|';
                    unset($code);
                    if ($checkValues != '')
                        $row['SORT_HASH'] = md5($checkValues);
                    unset($checkValues);
                }
                $row['LINK_ELEMENT_ID'] = $productId;
                $row['PROPERTIES'] = array();
                $row['DISPLAY_PROPERTIES'] = array();

                /* it is not the final version */
                $row['PRODUCT'] = array(
                    'TYPE' => null,
                    'AVAILABLE' => null,
                    'MEASURE' => null,
                    'VAT_ID' => null,
                    'VAT_RATE' => null,
                    'VAT_INCLUDED' => null,
                    'QUANTITY' => null,
                    'QUANTITY_TRACE' => null,
                    'CAN_BUY_ZERO' => null,
                    'SUBSCRIPTION' => null,
                    'BUNDLE' => null
                );

                if (isset($row['CATALOG_TYPE']))
                {
                    $row['CATALOG_TYPE'] = (int)$row['CATALOG_TYPE']; // this key will be deprecated
                    $row['PRODUCT']['TYPE'] = $row['CATALOG_TYPE'];
                }
                if (isset($row['CATALOG_MEASURE']))
                {
                    $row['CATALOG_MEASURE'] = (int)$row['CATALOG_MEASURE']; // this key will be deprecated
                    $row['PRODUCT']['MEASURE'] = $row['CATALOG_MEASURE'];
                }
                /*
                 * this keys will be deprecated
                 * CATALOG_*
                 */
                if (isset($row['CATALOG_AVAILABLE']))
                {
                    $row['PRODUCT']['AVAILABLE'] = $row['CATALOG_AVAILABLE'];
                    $row['PRODUCT']['VAT_RATE'] = $row['CATALOG_VAT'];
                    $row['PRODUCT']['VAT_INCLUDED'] = $row['CATALOG_VAT_INCLUDED'];
                    $row['PRODUCT']['QUANTITY'] = $row['CATALOG_QUANTITY'];
                    $row['PRODUCT']['QUANTITY_TRACE'] = $row['CATALOG_QUANTITY_TRACE'];
                    $row['PRODUCT']['CAN_BUY_ZERO'] = $row['CATALOG_CAN_BUY_ZERO'];
                    $row['PRODUCT']['SUBSCRIPTION'] = $row['CATALOG_SUBSCRIPTION'];
                    $row['PRODUCT']['BUNDLE'] = $row['CATALOG_BUNDLE'];
                }
                /* it is not the final version - end*/

                if ($row['PRODUCT']['TYPE'] == ProductTable::TYPE_OFFER)
                    $this->calculatePrices[$row['ID']] = $row['ID'];

                $row['ITEM_PRICE_MODE'] = null;
                $row['ITEM_PRICES'] = array();
                $row['ITEM_QUANTITY_RANGES'] = array();
                $row['ITEM_MEASURE_RATIOS'] = array();
                $row['ITEM_MEASURE'] = array();
                $row['ITEM_MEASURE_RATIO_SELECTED'] = null;
                $row['ITEM_QUANTITY_RANGE_SELECTED'] = null;
                $row['ITEM_PRICE_SELECTED'] = null;
                $row['CHECK_QUANTITY'] = $this->isNeedCheckQuantity($row['PRODUCT']);

                if ($row['PRODUCT']['MEASURE'] > 0)
                {
                    $row['ITEM_MEASURE'] = array(
                        'ID' => $row['PRODUCT']['MEASURE'],
                        'TITLE' => '',
                        '~TITLE' => ''
                    );
                }
                else
                {
                    $row['ITEM_MEASURE'] = array(
                        'ID' => null,
                        'TITLE' => $this->storage['DEFAULT_MEASURE']['SYMBOL_RUS'],
                        '~TITLE' => $this->storage['DEFAULT_MEASURE']['~SYMBOL_RUS']
                    );
                }
                if ($enableCompatible)
                {
                    $row['CATALOG_MEASURE'] = $row['ITEM_MEASURE']['ID'];
                    $row['CATALOG_MEASURE_NAME'] = $row['ITEM_MEASURE']['TITLE'];
                    $row['~CATALOG_MEASURE_NAME'] = $row['ITEM_MEASURE']['~TITLE'];
                }

                $row['PROPERTIES'] = array();
                $row['DISPLAY_PROPERTIES'] = array();

                Tools::getFieldImageData(
                    $row,
                    array('PREVIEW_PICTURE', 'DETAIL_PICTURE'),
                    Tools::IPROPERTY_ENTITY_ELEMENT,
                    ''
                );

                $offersId[$row['ID']] = $row['ID'];
                $offers[$row['ID']] = $row;
            }
            unset($row, $iterator);

            if (!empty($offersId))
            {
                $propertyList = $this->getPropertyList(
                    $catalog['IBLOCK_ID'],
                    $iblockParams['OFFERS_PROPERTY_CODE']
                );
                if (!empty($propertyList))
                {
                    \CIBlockElement::GetPropertyValuesArray($offers, $catalog['IBLOCK_ID'], $offersFilter);
                    foreach ($offers as &$row)
                    {
                        if ($this->useDiscountCache)
                        {
                            if ($this->storage['USE_SALE_DISCOUNTS'])
                                DiscountManager::setProductPropertiesCache($row['ID'], $row["PROPERTIES"]);
                            else
                                \CCatalogDiscount::SetProductPropertiesCache($row['ID'], $row["PROPERTIES"]);
                        }

                        foreach ($propertyList as $pid)
                        {
                            if (!isset($row["PROPERTIES"][$pid]))
                                continue;
                            $prop = &$row["PROPERTIES"][$pid];
                            $boolArr = is_array($prop["VALUE"]);
                            if(
                                ($boolArr && !empty($prop["VALUE"])) ||
                                (!$boolArr && (string)$prop["VALUE"] !== '')
                            )
                            {
                                $row["DISPLAY_PROPERTIES"][$pid] = \CIBlockFormatProperties::GetDisplayValue($row, $prop, "catalog_out");
                            }
                            unset($boolArr, $prop);
                        }
                        unset($pid);
                    }
                    unset($row);
                }

                if ($this->useDiscountCache)
                {
                    if ($this->storage['USE_SALE_DISCOUNTS'])
                    {
                        DiscountManager::preloadPriceData($offersId, $this->storage['PRICES_ALLOW']);
                        DiscountManager::preloadProductDataToExtendOrder($offersId, $this->getUserGroups());
                    }
                    else
                    {
                        \CCatalogDiscount::SetProductSectionsCache($offersId);
                        \CCatalogDiscount::SetDiscountProductCache($offersId, array('IBLOCK_ID' => $catalog['IBLOCK_ID'], 'GET_BY_ID' => 'Y'));
                    }
                }
            }
            unset($offersId);
        }

        return $offers;
    }

    protected function modifyOffers($offers)
    {
        //$urls = $this->storage['URLS'];
        foreach ($offers as &$offer)
        {
            $elementId = $offer['LINK_ELEMENT_ID'];

            if (!isset($this->elementLinks[$elementId]))
                continue;

            $offer['CAN_BUY'] = $this->elementLinks[$elementId]['ACTIVE'] === 'Y' && $offer['CAN_BUY'];

            $notEmptyStoreCountController = array();
            $offer['STORES'] = $this->arResult['STORES'];
            foreach ($this->arResult['STORES'] as $id=>$fields){
                if ((int)$offer['CATALOG_STORE_AMOUNT_'.$id] <= 0) continue;
                $this->elementLinks[$elementId]['STORES'][$id]['AMOUNT'] += $offer['CATALOG_STORE_AMOUNT_'.$id];
                $this->elementLinks[$elementId]['CATALOG_STORE_AMOUNT_'.$id] += $offer['CATALOG_STORE_AMOUNT_'.$id];
                $offer['STORES'][$id]['AMOUNT'] = $offer['CATALOG_STORE_AMOUNT_'.$id];
                $notEmptyStoreCountController[$id] = true;
                if (\Caweb\Main\Tools::getInstance()->isTO())
                    $offer['CATALOG_QUANTITY'] = $offer['~CATALOG_QUANTITY'] = $offer['STORES'][$id]['AMOUNT'];
            }
            $offer['STORES_COUNT'] = count($notEmptyStoreCountController);
            $this->elementLinks[$elementId]['OFFERS'][] = $offer;
            if (count($notEmptyStoreCountController) > $this->elementLinks[$elementId]['STORES_COUNT'])
                $this->elementLinks[$elementId]['STORES_COUNT'] = count($notEmptyStoreCountController);
            unset($elementId, $offer);
        }
    }

    protected function makeOutputResult()
	{
		parent::makeOutputResult();
		$this->arResult['USE_CATALOG_BUTTONS'] = $this->storage['USE_CATALOG_BUTTONS'];
	}

	protected function initialLoadAction()
	{
		parent::initialLoadAction();

		if (!$this->hasErrors())
		{
			$this->initAdminIconsPanel();
			$this->setTemplateCachedData($this->arResult['NAV_CACHED_DATA']);
			$this->initMetaData();
		}
	}

	protected function initAdminIconsPanel()
	{
		global $APPLICATION, $INTRANET_TOOLBAR, $USER;

		if (!$USER->IsAuthorized())
		{
			return;
		}

		$arResult =& $this->arResult;

		if (
			$APPLICATION->GetShowIncludeAreas()
			|| (is_object($INTRANET_TOOLBAR) && $this->arParams['INTRANET_TOOLBAR'] !== 'N')
			|| $this->arParams['SET_TITLE']
			|| isset($arResult[$this->arParams['BROWSER_TITLE']])
		)
		{
			if (Loader::includeModule('iblock'))
			{
				$urlDeleteSectionButton = '';

				if ($arResult['IBLOCK_SECTION_ID'] > 0)
				{
					$sectionIterator = CIBlockSection::GetList(
						array(),
						array('=ID' => $arResult['IBLOCK_SECTION_ID']),
						false,
						array('SECTION_PAGE_URL')
					);
					$sectionIterator->SetUrlTemplates('', $this->arParams['SECTION_URL']);
					$section = $sectionIterator->GetNext();
					$urlDeleteSectionButton = $section['SECTION_PAGE_URL'];
				}

				if (empty($urlDeleteSectionButton))
				{
					$urlTemplate = CIBlock::GetArrayByID($this->arParams['IBLOCK_ID'], 'LIST_PAGE_URL');
					$iblock = CIBlock::GetArrayByID($this->arParams['IBLOCK_ID']);
					$iblock['IBLOCK_CODE'] = $iblock['CODE'];
					$urlDeleteSectionButton = CIBlock::ReplaceDetailUrl($urlTemplate, $iblock, true, false);
				}

				$returnUrl = array(
					'add_section' => (
					strlen($this->arParams['SECTION_URL'])
						? $this->arParams['SECTION_URL']
						: CIBlock::GetArrayByID($this->arParams['IBLOCK_ID'], 'SECTION_PAGE_URL')
					),
					'delete_section' => $urlDeleteSectionButton,
				);
				$buttonParams = array(
					'RETURN_URL' => $returnUrl,
					'CATALOG' => true
				);

				if (isset($arResult['USE_CATALOG_BUTTONS']))
				{
					$buttonParams['USE_CATALOG_BUTTONS'] = $arResult['USE_CATALOG_BUTTONS'];
				}

				$buttons = CIBlock::GetPanelButtons(
					$this->arParams['IBLOCK_ID'],
					0,
					$arResult['ID'],
					$buttonParams
				);
				unset($buttonParams);

				if ($APPLICATION->GetShowIncludeAreas())
				{
					$this->addIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $buttons));
				}

				if (
					is_array($buttons['intranet'])
					&& is_object($INTRANET_TOOLBAR)
					&& $this->arParams['INTRANET_TOOLBAR'] !== 'N'
				)
				{
					Main\Page\Asset::getInstance()->addJs('/bitrix/js/main/utils.js');

					foreach ($buttons['intranet'] as $button)
					{
						$INTRANET_TOOLBAR->AddButton($button);
					}
				}

				if ($this->arParams['SET_TITLE'] || isset($arResult[$this->arParams['BROWSER_TITLE']]))
				{
					$this->storage['TITLE_OPTIONS'] = array(
						'ADMIN_EDIT_LINK' => $buttons['submenu']['edit_section']['ACTION'],
						'PUBLIC_EDIT_LINK' => $buttons['edit']['edit_section']['ACTION'],
						'COMPONENT_NAME' => $this->getName(),
					);
				}
			}
		}
	}
	
	protected function initMetaData()
	{
		global $APPLICATION;

		if ($this->arParams['SET_TITLE'])
		{
			if ($this->arResult['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] != '')
			{
				$APPLICATION->SetTitle($this->arResult['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'], $this->storage['TITLE_OPTIONS']);
			}
			elseif (isset($this->arResult['NAME']))
			{
				$APPLICATION->SetTitle($this->arResult['NAME'], $this->storage['TITLE_OPTIONS']);
			}
		}

		if ($this->arParams['SET_BROWSER_TITLE'] === 'Y')
		{
			$browserTitle = Main\Type\Collection::firstNotEmpty(
				$this->arResult, $this->arParams['BROWSER_TITLE'],
				$this->arResult['IPROPERTY_VALUES'], 'SECTION_META_TITLE'
			);
			if (is_array($browserTitle))
			{
				$APPLICATION->SetPageProperty('title', implode(' ', $browserTitle), $this->storage['TITLE_OPTIONS']);
			}
			elseif ($browserTitle != '')
			{
				$APPLICATION->SetPageProperty('title', $browserTitle, $this->storage['TITLE_OPTIONS']);
			}
		}

		if ($this->arParams['SET_META_KEYWORDS'] === 'Y')
		{
			$metaKeywords = Main\Type\Collection::firstNotEmpty(
				$this->arResult, $this->arParams['META_KEYWORDS'],
				$this->arResult['IPROPERTY_VALUES'], 'SECTION_META_KEYWORDS'
			);
			if (is_array($metaKeywords))
			{
				$APPLICATION->SetPageProperty('keywords', implode(' ', $metaKeywords), $this->storage['TITLE_OPTIONS']);
			}
			elseif ($metaKeywords != '')
			{
				$APPLICATION->SetPageProperty('keywords', $metaKeywords, $this->storage['TITLE_OPTIONS']);
			}
		}

		if ($this->arParams['SET_META_DESCRIPTION'] === 'Y')
		{
			$metaDescription = Main\Type\Collection::firstNotEmpty(
				$this->arResult, $this->arParams['META_DESCRIPTION'],
				$this->arResult['IPROPERTY_VALUES'], 'SECTION_META_DESCRIPTION'
			);
			if (is_array($metaDescription))
			{
				$APPLICATION->SetPageProperty('description', implode(' ', $metaDescription), $this->storage['TITLE_OPTIONS']);
			}
			elseif ($metaDescription != '')
			{
				$APPLICATION->SetPageProperty('description', $metaDescription, $this->storage['TITLE_OPTIONS']);
			}
		}

		if (!empty($this->arResult['BACKGROUND_IMAGE']) && is_array($this->arResult['BACKGROUND_IMAGE']))
		{
			$APPLICATION->SetPageProperty(
				'backgroundImage',
				'style="background-image: url(\''.\CHTTP::urnEncode($this->arResult['BACKGROUND_IMAGE']['SRC'], 'UTF-8').'\')"'
			);
		}

		if ($this->arParams['ADD_SECTIONS_CHAIN'] && is_array($this->arResult['PATH']))
		{
			foreach ($this->arResult['PATH'] as $path)
			{
				if ($path['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] != '')
				{
					$APPLICATION->AddChainItem($path['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'], $path['~SECTION_PAGE_URL']);
				}
				else
				{
					$APPLICATION->AddChainItem($path['NAME'], $path['~SECTION_PAGE_URL']);
				}
			}
		}

		if ($this->arParams['SET_LAST_MODIFIED'] && $this->arResult['ITEMS_TIMESTAMP_X'])
		{
			Main\Context::getCurrent()->getResponse()->setLastModified($this->arResult['ITEMS_TIMESTAMP_X']);
		}
	}

	protected function getElementList($iblockId, $products)
	{
		$elementIterator = parent::getElementList($iblockId, $products);

		if (
			!empty($elementIterator)
			&& $this->arParams['BY_LINK'] !== 'Y'
			&& !$this->arParams['SHOW_ALL_WO_SECTION']
			&& !$this->arParams['USE_MAIN_ELEMENT_SECTION']
		)
		{
			$elementIterator->SetSectionContext($this->arResult);
		}

		return $elementIterator;
	}

	protected function processElement(array &$element)
	{
		if ($this->arResult['ID'])
		{
			$element['IBLOCK_SECTION_ID'] = $this->arResult['ID'];
		}

        if ($this->arParams['USE_STORE'] && !empty($stores = $this->arResult['STORES'])){
            $storeCount = 0;
            foreach ($stores as $storeId => $store){
                $element['STORES'][$storeId] = $store;
                $element['STORES'][$storeId]['AMOUNT'] = (int)$element['CATALOG_STORE_AMOUNT_'.$storeId];
                if ($element['STORES'][$storeId]['AMOUNT'] > 0) $storeCount++;
                if (\Caweb\Main\Tools::getInstance()->isTO()){
                    $element['~CATALOG_QUANTITY'] = $element['CATALOG_QUANTITY'] = $element['STORES'][$storeId]['AMOUNT'];
                }
            }
            $element['STORES_COUNT'] = $storeCount;
        }

		parent::processElement($element);
		$this->checkLastModified($element);
	}

	protected function checkLastModified($element)
	{
		if ($this->arParams['SET_LAST_MODIFIED'])
		{
			$time = DateTime::createFromUserTime($element['TIMESTAMP_X']);
			if (
				!isset($this->arResult['ITEMS_TIMESTAMP_X'])
				|| $time->getTimestamp() > $this->arResult['ITEMS_TIMESTAMP_X']->getTimestamp()
			)
			{
				$this->arResult['ITEMS_TIMESTAMP_X'] = $time;
			}
		}
	}

	protected function initElementList()
	{
		parent::initElementList();

		// compatibility for old components
		if ($this->isEnableCompatible() && empty($this->arResult['NAV_RESULT']))
		{
			$this->initNavString(\CIBlockElement::GetList(
				array(),
				array_merge($this->globalFilter, $this->filterFields + array('IBLOCK_ID' => $this->arParams['IBLOCK_ID'])),
				false,
				array('nTopCount' => 1),
				array('ID')
			));
			$this->arResult['NAV_RESULT']->NavNum = Main\Security\Random::getString(6);
		}

		$this->storage['sections'] = array();

		if (!empty($this->elements) && is_array($this->elements))
		{
			foreach ($this->elements as &$element)
			{
				$this->modifyItemPath($element);
			}
		}
	}

	protected function modifyItemPath(&$element)
	{
		$sections =& $this->storage['sections'];

		if ($this->arParams['BY_LINK'] === 'Y')
		{
			if (!isset($sections[$element['IBLOCK_SECTION_ID']]))
			{
				$sections[$element['IBLOCK_SECTION_ID']] = array();
				$pathIterator = CIBlockSection::GetNavChain(
					$element['IBLOCK_ID'],
					$element['IBLOCK_SECTION_ID'],
					array(
						'ID', 'CODE', 'XML_ID', 'EXTERNAL_ID', 'IBLOCK_ID',
						'IBLOCK_SECTION_ID', 'SORT', 'NAME', 'ACTIVE',
						'DEPTH_LEVEL', 'SECTION_PAGE_URL'
					)
				);
				$pathIterator->SetUrlTemplates('', $this->arParams['SECTION_URL']);
				while ($path = $pathIterator->GetNext())
				{
					$sections[$element['IBLOCK_SECTION_ID']][] = $path;
				}
			}

			$element['SECTION']['PATH'] = $sections[$element['IBLOCK_SECTION_ID']];
		}
		else
		{
			$element['SECTION']['PATH'] = array();
		}
	}
}