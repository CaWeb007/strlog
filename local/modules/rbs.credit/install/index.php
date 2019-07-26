<?php
use Bitrix\Main\Localization\Loc;
IncludeModuleLangFile(__FILE__);

Class rbs_credit extends CModule {

    var $MODULE_ID = 'rbs.credit';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_PATH;

    var $PAYMENT_HANDLER_PATH;

    function __construct() {
    	$path = str_replace("\\", "/", __FILE__);
    	$path = substr($path, 0, strlen($path) - strlen("/install/index.php"));

    	include($path."/install/version.php");
    	include($path."/config.php");
        
        $this->MODULE_PATH = $path;
        $this->MODULE_NAME =  $this->GetEncodeMessage('RBS_CREDIT_MODULE_NAME');
        $this->MODULE_DESCRIPTION = $this->GetEncodeMessage('RBS_CREDIT_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = $this->GetEncodeMessage('RBS_CREDIT_PARTNER_NAME');
        $this->PARTNER_URI = $this->GetEncodeMessage('RBS_CREDIT_PARTNER_URI');
        
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->PAYMENT_HANDLER_PATH = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/php_interface/include/sale_payment/" . str_replace(".", "_", $this->MODULE_ID) . "/";
    }

    function GetEncodeMessage($text) {
    	$siteEncode = SITE_CHARSET;
    	$message = Loc::getMessage($text);
        if(mb_detect_encoding($message,mb_list_encodings()) == 'UTF-8') {
            $old_enc = 'UTF-8';
        } else {
            $old_enc = 'windows-1251';
        }
        if($siteEncode == $old_enc) {
            return $message;   
        }
    	return mb_convert_encoding( $message, $siteEncode, $old_enc);
    }

    function reEncode($folder, $enc) {
	    $files = scandir($folder);
	    foreach( $files as $file ) {
	        if( $file == "." || $file == ".." ) { continue; }

	        $path = $folder . DIRECTORY_SEPARATOR . $file;
	        $content = file_get_contents($path);
      
            if( is_dir($path) ) {
                $this->reEncode( $path, $enc );
            } 
            else {
                
                if(mb_detect_encoding($content,mb_list_encodings()) == 'UTF-8') {
                    $old_enc = 'UTF-8';
                } else {
                    $old_enc = 'windows-1251';
                }
                if($enc == $old_enc) {
                    continue;
                }
                $content = mb_convert_encoding( $content, $enc, $old_enc );
	            if( is_writable($path) ) {
	            	unlink($path);
	                $ff = fopen($path,'w');
	                fputs($ff,$content);
	                fclose($ff);
	            }
	        }
	    }
	}

    function changeFiles($files) {

        foreach ($files as $file) {
            if ($file->isDot() === false) {
                $path_to_file = $file->getPathname();
                $file_contents = file_get_contents($path_to_file);
                $file_contents = str_replace("{module_path}", $this->MODULE_ID, $file_contents);
                file_put_contents($path_to_file, $file_contents);
            }
        }
    }
    function InstallFiles($arParams = array()) {

        CopyDirFiles($this->MODULE_PATH . "/install/setup/handler_include", $this->PAYMENT_HANDLER_PATH, true, true);
        CopyDirFiles($this->MODULE_PATH . "/install/setup/bank", $_SERVER['DOCUMENT_ROOT'] . '/bank/');
        CopyDirFiles($this->MODULE_PATH . "/install/setup/images/logo", $_SERVER['DOCUMENT_ROOT'] . '/bitrix/images/sale/sale_payments/');
        CopyDirFiles($this->MODULE_PATH . "/install/setup/images/assets", $_SERVER['DOCUMENT_ROOT'] . '/bitrix/images/rbs.credit/');

        // for services
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/setup/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/");
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/setup/themes", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes", false, true);

        $this->reEncode($this->MODULE_PATH . "/lang/", SITE_CHARSET);
        $this->changeFiles(new DirectoryIterator($this->PAYMENT_HANDLER_PATH));
        $this->changeFiles(new DirectoryIterator($this->PAYMENT_HANDLER_PATH . 'template/'));
    }

    function UnInstallFiles() {
        DeleteDirFilesEx($this->PAYMENT_HANDLER_PATH);
        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/". $this->MODULE_ID ."/install/setup/admin", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/". $this->MODULE_ID ."/install/setup/themes/.default/", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes/.default");
        DeleteDirFilesEx($_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes/.default/icons/" . $this->MODULE_ID );
        DeleteDirFilesEx("/bitrix/images/rbs.credit/");
    }



    function InstallDB($arParams = array()) {
        global $DB, $APPLICATION;
        $this->errors = false;
        // Database tables creation
        $this->errors = $DB->RunSqlBatch($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/setup/db/" . strtolower($DB->type) . "/install.sql");
        
        if ($this->errors !== false)
        {
            $APPLICATION->ThrowException(implode("<br>", $this->errors));
            return false;
        }
        return true;
    }

    function UnInstallDB($arParams = array()) {
        // global $DB, $APPLICATION;
        // $this->errors = false;
        // if (!array_key_exists("save_tables", $arParams) || ($arParams["save_tables"] != "Y"))
        // {
        //     $this->errors = $DB->RunSqlBatch($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/setup/db/" . strtolower($DB->type) . "/uninstall.sql");
        // }
        // if ($this->errors !== false)
        // {
        //     $APPLICATION->ThrowException(implode("<br>", $this->errors));
        //     return false;
        // }

        return true;
    }

	function DoInstall() {
        $this->InstallFiles();
        RegisterModule($this->MODULE_ID);
        COption::SetOptionInt($this->MODULE_ID, "delete", false);
        $this->InstallDB();

	}

	function DoUninstall() {
        COption::SetOptionInt($this->MODULE_ID, "delete", true);
        DeleteDirFilesEx($this->PAYMENT_HANDLER_PATH);
        DeleteDirFilesEx($this->MODULE_ID);

        UnRegisterModule($this->MODULE_ID);
        $this->UnInstallDB(array(
          "save_tables" => $_REQUEST["save_tables"],
        ));
        return true;        
	}
}

?>