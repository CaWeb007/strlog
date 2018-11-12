<?php
header("Access-Control-Allow-Origin: *");
if(!empty($_POST['order_name']) && !empty($_POST['order_phone'])){
    $to = 'zakaz@strlog.ru, srtlog@mail.ru'; //Почта получателя, через запятую можно указать сколько угодно адресов
    $subject = 'Заявка на акцию с лендинга по джуту';
	$name = 'zakaz@strlog.ru';
    $message = '
        <html>
            <head>
                <title>'.$subject.'</title>
            </head>
            <body>
                <div><b>Имя: </b>'.$_POST['order_name'].'</div>
				<div><b>Телефон: </b>'.$_POST['order_phone'].'</div>
            </body>
        </html>'; //Текст нащего сообщения можно использовать HTML теги
    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; //Кодировка письма
	$headers .= "From: Компания Стройлогистика <zakaz@strlog.ru>\r\n";
	//$headers .= $name; //Наименование и почта отправителя
    mail($to, $subject, $message, $headers); //Отправка письма с помощью функции mail
}
if(!empty($_POST['action_data']) && !empty($_POST['action_tel'])){
    $to = 'zakaz@strlog.ru, srtlog@mail.ru'; //Почта получателя, через запятую можно указать сколько угодно адресов
    $subject = 'Заявка на акцию с лендинга по джуту';
	$name = 'zakaz@strlog.ru';
    $message = '
        <html>
            <head>
                <title>'.$subject.'</title>
            </head>
            <body>
                <div><b>Имя: </b>'.$_POST['action_data'].'</div>
				<div><b>Телефон: </b>'.$_POST['action_tel'].'</div>
            </body>
        </html>'; //Текст нащего сообщения можно использовать HTML теги
    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; //Кодировка письма
		$headers .= "From: Компания Стройлогистика <zakaz@strlog.ru>\r\n";
	//$headers .= $name; //Наименование и почта отправителя
    mail($to, $subject, $message, $headers); //Отправка письма с помощью функции mail
}
if(!empty($_POST['popup_order_name']) && !empty($_POST['popup_order_phone'])){
    $to = 'zakaz@strlog.ru, srtlog@mail.ru'; //Почта получателя, через запятую можно указать сколько угодно адресов
    $subject = 'Заявка с лендинга по джуту';
	$name = 'zakaz@strlog.ru';
	$soputka_1 = $_POST['soputka_1'];
	$soputka_2 = $_POST['soputka_2'];
	$soputka_3 = $_POST['soputka_3'];
	$soputka_4 = $_POST['soputka_4'];
	$soputka_5 = $_POST['soputka_5'];
	$soputka_6 = $_POST['soputka_6'];
    $message = '
        <html>
            <head>
                <title>'.$subject.'</title>
            </head>
            <body>
				<div><b>Наименование: </b>'.$_POST['popup_order_good'].'</div>
                <div><b>Имя: </b>'.$_POST['popup_order_name'].'</div>
				<div><b>Телефон: </b>'.$_POST['popup_order_phone'].'</div>
				<div><b>Сопутствуюший товар:</b></div>
				<div>'.$soputka_1.'</div>
				<div>'.$soputka_2.'</div>
				<div>'.$soputka_3.'</div>
				<div>'.$soputka_4.'</div>
				<div>'.$soputka_5.'</div>
				<div>'.$soputka_6.'</div>
            </body>
        </html>'; //Текст нащего сообщения можно использовать HTML теги
    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; //Кодировка письма
		$headers .= "From: Компания Стройлогистика <zakaz@strlog.ru>\r\n";
	//$headers .= $name; //Наименование и почта отправителя
    mail($to, $subject, $message, $headers); //Отправка письма с помощью функции mail
}
if(!empty($_POST['block_form_name']) && !empty($_POST['block_form_phone']) && !empty($_POST['block_form_city'])){
    $to = '960088@bk.ru, srtlog@mail.ru'; //Почта получателя, через запятую можно указать сколько угодно адресов
    $subject = 'Заявка с лендинга по джуту';
	$name = 'zakaz@strlog.ru';
    $message = '
        <html>
            <head>
                <title>'.$subject.'</title>
            </head>
            <body>
                <div><b>Имя: </b>'.$_POST['block_form_name'].'</div>
				<div><b>Телефон: </b>'.$_POST['block_form_phone'].'</div>
				<div><b>Организация: </b>'.$_POST['block_form_company'].'</div>
				<div><b>Город: </b>'.$_POST['block_form_city'].'</div>
            </body>
        </html>'; //Текст нащего сообщения можно использовать HTML теги
    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; //Кодировка письма
		$headers .= "From: Компания Стройлогистика <zakaz@strlog.ru>\r\n";
	//$headers .= $name; //Наименование и почта отправителя
    mail($to, $subject, $message, $headers); //Отправка письма с помощью функции mail
}

