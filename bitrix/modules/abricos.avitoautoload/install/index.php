<?
IncludeModuleLangFile(__FILE__);
Class abricos_avitoautoload extends CModule
{
	const MODULE_ID = 'abricos.avitoautoload';
	var $MODULE_ID = 'abricos.avitoautoload';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("abricos.avitoautoload_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("abricos.avitoautoload_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("abricos.avitoautoload_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("abricos.avitoautoload_PARTNER_URI");
	}

	function InstallDB($arParams = array())
	{


		global $DB, $APPLICATION;
			$DB->Query("create table abr_avitoautoload
			(id integer not null auto_increment primary key,
			category varchar(100),
			goodstype varchar(100),
			apparel varchar(100)
			);", true);

            $this->errors = false;
	        $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/abricos.avitoautoload/lang/ru/install/db/install.php");
	        $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/abricos.avitoautoload/lang/ru/install/db/install2.php");
	        $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/abricos.avitoautoload/lang/ru/install/db/install3.php");
	        $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/abricos.avitoautoload/lang/ru/install/db/install4.php");
	        if (!$this->errors) {

	            return true;
	        } else
	            return $this->errors;
		//return true;
	}

	function UnInstallDB($arParams = array())
	{
	 UnRegisterModuleDependences('main', 'OnBuildGlobalMenu', self::MODULE_ID, 'CAbricosAvitoAutoload', 'OnBuildGlobalMenu');
	 global $DB, $APPLICATION;
			if(!$DB->Query("DROP TABLE IF EXISTS abr_avitoautoload", true))



		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles($arParams = array())
	{
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/catalog_export', $_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/include/catalog_export");
         CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/tools', $_SERVER["DOCUMENT_ROOT"]."/bitrix/tools/abricos.avitoautoload");

         CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/js', $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/abricos");

		return true;
	}

	function UnInstallFiles()
	{
       DeleteDirFilesEx('/bitrix/php_interface/include/catalog_export/avito_detail.php');
       DeleteDirFilesEx('/bitrix/php_interface/include/catalog_export/avito_run.php');
       DeleteDirFilesEx('/bitrix/php_interface/include/catalog_export/avito_setup.php');
       DeleteDirFilesEx('/bitrix/php_interface/include/catalog_export/avito_util.php');
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
		$this->InstallFiles();
		$this->InstallDB();
		RegisterModule(self::MODULE_ID);
	}

	function DoUninstall()
	{
		global $APPLICATION;
		UnRegisterModule(self::MODULE_ID);
		$this->UnInstallDB();
		$this->UnInstallFiles();
	}
}
?>
