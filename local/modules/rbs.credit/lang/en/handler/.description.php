<?php
$MESS["RBS_MODULE_TITLE"] = 'Оплата банковской картой';
$MESS["RBS_GROUP_GATE"] = 'Параметры подключения платежного шлюза';
$MESS["RBS_GROUP_HANDLER"] = 'Параметры платежного обработчика';
$MESS["RBS_GROUP_ORDER"] = 'Параметры заказа';
$MESS["RBS_GROUP_FFD"] = 'Настройки ФФД';
$MESS["RBS_GROUP_OFD"] = "Фискализация";

$MESS["RBS_API_LOGIN_NAME"] = 'Логин';
$MESS["RBS_API_LOGIN_DESCR"] = '';
$MESS["RBS_API_PASSWORD_NAME"] = 'Пароль';
$MESS["RBS_API_PASSWORD_DESCR"] = '';
$MESS["RBS_API_TEST_MODE_NAME"] = 'Тестовый режим';
$MESS["RBS_API_TEST_MODE_DESCR"] = 'Если отмечено, плагин будет работать в тестовом режиме. При пустом значении будет стандартный режим работы.';
$MESS["RBS_CREDIT_API_SUCCESS_URL_NAME"] = 'Refund page, upon successful payment';
$MESS["RBS_CREDIT_API_SUCCESS_URL_DESCR"] = '';
$MESS["RBS_CREDIT_API_FAIL_URL_NAME"] = 'Refund page if payment failed';
$MESS["RBS_CREDIT_API_FAIL_URL_DESCR"] = '';


$MESS["RBS_HANDLER_AUTO_REDIRECT_NAME"] = 'Автоматический редирект на форму оплаты';
$MESS["RBS_HANDLER_AUTO_REDIRECT_DESCR"] = 'Если отмечено, после оформления заказа, покупатель будет автоматически перенаправлен на страницу платежной формы.';
$MESS["RBS_HANDLER_LOGGING_NAME"] = 'Логирование';
$MESS["RBS_HANDLER_LOGGING_DESCR"] = 'Если отмечено, плагин будет логировать запросы в файл.';
$MESS["RBS_HANDLER_TWO_STAGE_NAME"] = 'Двухстадийные платежи';
$MESS["RBS_HANDLER_TWO_STAGE_DESCR"] = 'Если отмечено, будет производиться двухстадийный платеж. При пустом значении будет производиться одностадийный платеж.';
$MESS["RBS_HANDLER_SHIPMENT_NAME"] = 'Разрешить отгрузку';
$MESS["RBS_HANDLER_SHIPMENT_DESCR"] = 'Если отмечено, то после успешной оплаты будет автоматически разрешена отгрузка заказа.';

$MESS["RBS_ORDER_NUMBER_NAME"] = 'Уникальный идентификатор заказа в магазине';
$MESS["RBS_ORDER_NUMBER_DESCR"] = '';
$MESS["RBS_ORDER_AMOUNT_NAME"] = 'Сумма заказа';
$MESS["RBS_ORDER_AMOUNT_DESCR"] = '';
$MESS["RBS_ORDER_DESCRIPTION_NAME"] = 'Описание заказа';
$MESS["RBS_ORDER_DESCRIPTION_DESCR"] = '';


$MESS["RBS_FFD_VERSION_NAME"] = 'Формат фискальных документов';
$MESS["RBS_FFD_VERSION_DESCR"] = 'Формат версии требуется указать в личном кабинете банка и в кабинете сервиса фискализации';
$MESS["RBS_FFD_PAYMENT_METHOD_NAME"] = 'Тип оплаты';
$MESS["RBS_FFD_PAYMENT_METHOD_DESCR"] = 'Для ФФД версии 1.05 и выше';
$MESS['RBS_FFD_PAYMENT_METHOD_VALUE_1'] = "Полная предварительная оплата до момента передачи предмета расчёта";
$MESS['RBS_FFD_PAYMENT_METHOD_VALUE_2'] = "Частичная предварительная оплата до момента передачи предмета расчёта";
$MESS['RBS_FFD_PAYMENT_METHOD_VALUE_3'] = "Аванс";
$MESS['RBS_FFD_PAYMENT_METHOD_VALUE_4'] = "Полная оплата в момент передачи предмета расчёта";
$MESS['RBS_FFD_PAYMENT_METHOD_VALUE_5'] = "Частичная оплата предмета расчёта в момент его передачи с последующей оплатой в кредит";
$MESS['RBS_FFD_PAYMENT_METHOD_VALUE_6'] = "Передача предмета расчёта без его оплаты в момент его передачи с последующей оплатой в кредит";
$MESS['RBS_FFD_PAYMENT_METHOD_VALUE_7'] = "Оплата предмета расчёта после его передачи с оплатой в кредит";

$MESS["RBS_FFD_PAYMENT_OBJECT_NAME"] = 'Тип оплачиваемой позиции';
$MESS["RBS_FFD_PAYMENT_OBJECT_DESCR"] = 'Для ФФД версии 1.05 и выше';
$MESS["RBS_FFD_PAYMENT_OBJECT_VALUE_1"]  = "Товар";
$MESS["RBS_FFD_PAYMENT_OBJECT_VALUE_2"]  = "Подакцизный товар";
$MESS["RBS_FFD_PAYMENT_OBJECT_VALUE_3"]  = "Работа";
$MESS["RBS_FFD_PAYMENT_OBJECT_VALUE_4"]  = "Услуга";
$MESS["RBS_FFD_PAYMENT_OBJECT_VALUE_5"]  = "Ставка азартной игры";
$MESS["RBS_FFD_PAYMENT_OBJECT_VALUE_6"]  = "Выигрыш азартной игры";
$MESS["RBS_FFD_PAYMENT_OBJECT_VALUE_7"]  = "Лотерейный билет";
$MESS["RBS_FFD_PAYMENT_OBJECT_VALUE_8"]  = "Выигрыш лотереи";
$MESS["RBS_FFD_PAYMENT_OBJECT_VALUE_9"]  = "Предоставление РИД";
$MESS["RBS_FFD_PAYMENT_OBJECT_VALUE_10"] = "Платёж";
$MESS["RBS_FFD_PAYMENT_OBJECT_VALUE_11"] = "Агентское вознаграждение";
$MESS["RBS_FFD_PAYMENT_OBJECT_VALUE_12"] = "Составной предмет расчёта";
$MESS["RBS_FFD_PAYMENT_OBJECT_VALUE_13"] = "Иной предмет расчёта";


$MESS["RBS_OFD_RECIEPT_NAME"] = "Чек выпускает банк";
$MESS["RBS_OFD_RECIEPT_DESCR"] = "«Если значение 'Y', то сформирует и отправит клиенту чек. Опция платная, за подключением обратитесь в сервисную службу банка. При использовании необходимо настроить НДС продаваемых товаров»";

$MESS["RBS_OFD_RECIEPT_VALUE_0"] = "Общая";
$MESS["RBS_OFD_RECIEPT_VALUE_1"] = "Упрощённая, доход";
$MESS["RBS_OFD_RECIEPT_VALUE_2"] = "Упрощённая, доход минус расход";
$MESS["RBS_OFD_RECIEPT_VALUE_3"] = "Единый налог на вменённый доход";
$MESS["RBS_OFD_RECIEPT_VALUE_4"] = "Единый сельскохозяйственный налог";
$MESS["RBS_OFD_RECIEPT_VALUE_5"] = "Патентная система налогообложения";


$MESS["RBS_OFD_TAX_SYSTEM_NAME"] = "Система налогообложения";
$MESS["RBS_OFD_TAX_SYSTEM_DESCR"] = "";