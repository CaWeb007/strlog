<?
if(isset($_GET['filename']) && $_GET['filename']){
	$file = rtrim($_SERVER['DOCUMENT_ROOT'],"/")."/bitrix/px_files/".$_GET['filename'];
	$ex = pathinfo($_GET['filename'],PATHINFO_EXTENSION);
	if (file_exists($file)) {
		$time = filemtime($file);
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="price_'.$time.'.'.$ex.'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		readfile($file);
		exit;
	}
}
?>