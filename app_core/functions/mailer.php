<?php

function pw_mailer($attr) {

	$reply = "admin@net-hesten.dk";
	$from_name = "Net-Hesten";
	$from_a = "admin@net-hesten.dk";

	$subject = "=?utf-8?b?" . base64_encode($attr['subject']) . "?=";
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "From: =?utf-8?b?" . base64_encode($from_name) . "?= <" . $from_a . ">\r\n";
	$headers .= "Content-Type: text/html;charset=UTF-8\r\n";
	$headers .= "Reply-To: $reply\r\n";
	$headers .= "X-Mailer: PHP/" . phpversion();

	$body = wordwrap(str_replace('\r\n', "<br />", $attr['message']), 70);

	mail($attr['to'], $subject, $body, $headers);
}
