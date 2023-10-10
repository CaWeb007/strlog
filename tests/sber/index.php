<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новый раздел");
?>

    <script src="https://widgetecom.sberbank.ru/widgets-sdk.js" type="text/javascript"></script>
    <div id="container" style="width: 100%; height: 300px"></div>
    <script>
        window.SberWidgetsSDK.create({
            widgetId: "kvklite",
            key: "28ac9f12-f2f1-11ed-a05b-0242ac120003",
            partnerId: "stroilogistika",
            container: document.getElementById('container')
        });
    </script>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>