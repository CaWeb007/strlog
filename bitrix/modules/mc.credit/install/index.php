<?php

use Bitrix\Main\Localization\Loc;

IncludeModuleLangFile(__FILE__);

class mc_credit extends CModule
{

    public $MODULE_ID = 'mc.credit';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_PATH;

    public $PAYMENT_HANDLER_PATH;

    public function __construct()
    {
        $path = str_replace("\\", '/', __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/install/index.php'));

        include($path . '/install/version.php');
        include($path . '/config.php');

        $this->MODULE_PATH = $path;
        $this->MODULE_NAME = Loc::getMessage('MC_PAYMENT_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MC_PAYMENT_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('MC_PAYMENT_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('MC_PAYMENT_PARTNER_URI');

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->PAYMENT_HANDLER_PATH = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/sale_payment/' . str_replace(".", "_", $this->MODULE_ID) . "/";
    }

    public function changeFiles($files)
    {
        foreach ($files as $file) {
            if ($file->isDot() === false) {
                $path_to_file = $file->getPathname();
                $file_contents = file_get_contents($path_to_file);
                $file_contents = str_replace('{module_path}', $this->MODULE_ID, $file_contents);
                file_put_contents($path_to_file, $file_contents);
            }
        }
    }

    public function InstallFiles($arParams = array())
    {
        CopyDirFiles($this->MODULE_PATH . '/install/setup/handler_include', $this->PAYMENT_HANDLER_PATH, true, true);
        CopyDirFiles($this->MODULE_PATH . '/install/setup/moneycare', $_SERVER['DOCUMENT_ROOT'] . '/moneycare/');
        CopyDirFiles($this->MODULE_PATH . '/install/setup/images/logo', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/images/sale/sale_payments/');
        $this->changeFiles(new DirectoryIterator($this->PAYMENT_HANDLER_PATH));
        $this->changeFiles(new DirectoryIterator($this->PAYMENT_HANDLER_PATH . 'template/'));
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx('/bitrix/php_interface/include/sale_payment/' . str_replace(".", "_", $this->MODULE_ID));
    }

    public function DoInstall()
    {
        $this->InstallFiles();
        $this->installDB();
        RegisterModule($this->MODULE_ID);
        COption::SetOptionInt($this->MODULE_ID, 'delete', false);
    }

    public function DoUninstall()
    {
        COption::SetOptionInt($this->MODULE_ID, "delete", true);
        DeleteDirFilesEx('/bitrix/php_interface/include/sale_payment/' . str_replace(".", "_", $this->MODULE_ID));
        DeleteDirFilesEx($this->MODULE_ID);

        UnRegisterModule($this->MODULE_ID);

        return true;
    }

    public function installDB()
    {
        global $DB, $APPLICATION;
        $errors = false;

        $result = $DB->Query("SELECT * FROM b_sale_status WHERE ID = 'A'", true);

        if ($result->result->num_rows === 0) {
            if (mb_strtoupper(SITE_CHARSET) === 'UTF-8') {
                $errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/mc.credit/install/db/install.sql');
            }else{
                $errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/mc.credit/install/db/install-1251.sql');
            }

        }
        if ($errors !== false) {
            $APPLICATION->ThrowException(implode('<br>', $errors));

            return false;
        }

        return true;
    }
}
