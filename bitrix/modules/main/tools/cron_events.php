<?php
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../../..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define('BX_WITH_ON_AFTER_EPILOG', true);
define('BX_NO_ACCELERATOR_RESET', true);
define("BX_CRONTAB_SUPPORT", true);

require_once($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_before.php");

@set_time_limit(0);
@ignore_user_abort(true);

CEvent::CheckEvents();
define("BX_CRONTAB", false);
CEvent::CheckEvents();

CAgent::CheckAgents();
define("BX_CRONTAB", true);
CAgent::CheckAgents();

if(CModule::IncludeModule('sender'))
{
	\Bitrix\Sender\MailingManager::checkPeriod(false);
	\Bitrix\Sender\MailingManager::checkSend();
}

require($DOCUMENT_ROOT."/bitrix/modules/main/tools/backup.php");
require($DOCUMENT_ROOT."/bitrix/modules/main/include/epilog_after.php");

CMain::FinalActions();
?>