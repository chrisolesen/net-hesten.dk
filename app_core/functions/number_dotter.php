<?php

function number_dotter($number) {
	$return_data = '';
	while (strlen((string) $number) >= 4) {
		$return_data = '.' . substr($number, -3) . $return_data;
		$number = substr($number, 0, (strlen($number) - 3));
	}
	$return_data = $number . $return_data;
	return $return_data;
}
