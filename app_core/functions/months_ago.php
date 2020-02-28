<?php

function months_ago($date) {
	$i = 0;
	$one_month = new DateInterval("P1M");
	$date_now = new DateTime('now');
	while ($date < $date_now->format('Y-m-d H:i:s')) {
		++$i;
		$date_now->sub($one_month);
	}
	return $i;
}
