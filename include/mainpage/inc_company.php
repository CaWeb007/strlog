<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<div class="wrap_md">
	<div class="md-25 img">
		 <?$APPLICATION->IncludeFile(SITE_DIR."include/mainpage/company/front_img.php", Array(), Array( "MODE" => "html", "NAME" => GetMessage("FRONT_IMG"), )); ?>
	</div>
	<div class="md-75 big">
		 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"front",
	Array(
		"AREA_FILE_SHOW" => "file",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_DIR."include/mainpage/company/front_info.php"
	)
);?>
	</div>
</div>