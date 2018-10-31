<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>
<?/*php
use \Bitrix\Highloadblock as HL;
$HLBlock = HL\HighloadBlockTable::getById(3)->fetch();
$HLObject = HL\HighloadBlockTable::compileEntity($HLBlock);
$HLObjectClass = $HLObject->getDataClass();
$HLResultArray = $HLObjectClass::getList(array(
    'select' => array('*'),
));
while ($HLResult = $HLResultArray->fetch()) {
    echo '<pre>';
    print_r($HLResult);
    echo '</pre>';
}
*/?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>