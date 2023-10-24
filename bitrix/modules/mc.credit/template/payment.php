<?php

use Bitrix\Main\Localization\Loc;

IncludeModuleLangFile(__FILE__);
?>

<p><b><?= Loc::getMessage('MC_TEXT_CREDIT_FORM') ?></b></p>
<p><?= Loc::getMessage('MC_TEXT_ENTER_FORM') ?></p>
<p><?= Loc::getMessage('MC_TEXT_NOTE_FORM') ?></p>
<iframe src="<?= $_SESSION['mc_form_url'] ?>" width="100%" height="950">
    <?= Loc::getMessage('MC_TEXT_IFRAME_NOT_SUPPORT') ?>
</iframe>
