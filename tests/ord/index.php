<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
?>
<?
try {
    $ord = new \Caweb\Main\ORD(true);
    $ord->setBody(array(
        "name"=> "Реклама во ВКонтакте 2",
        "brand"=> "СПбГУ",
        "category"=> "Реклама санкт-петербургского государственного университета ",
        "description"=> "Приёмная кампания университета в 2023 году.",
        "texts"=> [
            "Мечтаешь учиться в СПбГУ?",
            "Приходи к нам прямо сегодня!",
            "Мы предлагаем ознакомиться с двумя направлениями:",
            "Реклама и связи с общественностью.",
            "Журналистика. Сообщество ВКонтакте.",
            "Элитный факультет.",
            "Лучший состав преподавателей на всех программах."
        ],
    ));
    $ord->setExternalId();
    $ord->doQuery();
    $marker = $ord->getMarker();
}catch (Exception $e){
    $errorMess = $e->getMessage();
}
echo ($marker)? $marker: $errorMess;
?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>