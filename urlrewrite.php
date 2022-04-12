<?php
$arUrlRewrite=array (
  0 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/([\\w\\d\\-]+)?(/)?(([\\w\\d\\-]+)(/)?)?#',
    'RULE' => 'REQUEST_OBJECT=$1&METHOD=$4',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => '',
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/personal/history-of-orders/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.order',
    'PATH' => '/personal/history-of-orders/index.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  52 => 
  array (
    'CONDITION' => '#^/spec-catalog/reviwes/#',
    'RULE' => '',
    'ID' => 'bitrix:forum',
    'PATH' => '/spec-catalog/reviwes/index.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/stssync/calendar/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/calendar/index.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^/contacts/stores/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store',
    'PATH' => '/company/stores/index.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/contacts/stores/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/contacts/stores/index.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/contacts/stores/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.store',
    'PATH' => '/contacts/stores/index.php',
    'SORT' => 100,
  ),
  9 => 
  array (
    'CONDITION' => '#^/personal/order/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.order',
    'PATH' => '/personal/order/index.php',
    'SORT' => 100,
  ),
  10 => 
  array (
    'CONDITION' => '#^/company/stores/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/stores/index.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/company/brands/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/brands/index.php',
    'SORT' => 100,
  ),
  12 => 
  array (
    'CONDITION' => '#^/info/articles/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/info/articles/index.php',
    'SORT' => 100,
  ),
  51 => 
  array (
    'CONDITION' => '#^/help/articles/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/help/articles/index.php',
    'SORT' => 100,
  ),
  15 => 
  array (
    'CONDITION' => '#^/company/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/news/index.php',
    'SORT' => 100,
  ),
  14 => 
  array (
    'CONDITION' => '#^/info/article/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/info/article/index.php',
    'SORT' => 100,
  ),
  50 => 
  array (
    'CONDITION' => '#^/necondition/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/necondition/index.php',
    'SORT' => 100,
  ),
  16 => 
  array (
    'CONDITION' => '#^/info/brands/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/info/brands/index.php',
    'SORT' => 100,
  ),
  17 => 
  array (
    'CONDITION' => '#^/info/brand/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/info/brand/index.php',
    'SORT' => 100,
  ),
  49 => 
  array (
    'CONDITION' => '#^/info/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/info/news/index.php',
    'SORT' => 100,
  ),
  19 => 
  array (
    'CONDITION' => '#^/services/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/services/index.php',
    'SORT' => 100,
  ),
  33 => 
  array (
    'CONDITION' => '#^/landings/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/landings/index.php',
    'SORT' => 100,
  ),
  43 => 
  array (
    'CONDITION' => '#^/personal/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.section',
    'PATH' => '/personal/index.php',
    'SORT' => 100,
  ),
  48 => 
  array (
    'CONDITION' => '#^/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  45 => 
  array (
    'CONDITION' => '#^/salers/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/salers/index.php',
    'SORT' => 100,
  ),
  25 => 
  array (
    'CONDITION' => '#^/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/news/index.php',
    'SORT' => 100,
  ),
  41 => 
  array (
    'CONDITION' => '#^/sale/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/sale/index.php',
    'SORT' => 100,
  ),
);
