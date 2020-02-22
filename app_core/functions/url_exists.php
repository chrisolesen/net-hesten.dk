<?php

function url_exists($url) {
	if (!$fp = curl_init($url)) {
		return false;
	}
	return true;
}
