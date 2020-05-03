<?php
/* REVIEW: SQL Queries */
$basepath = '../../..';
$responsive = true;
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
?>
<h1>Net-Hesten - Spil Data</h1>
<?php
$dead = 'død';
$foel = 'føl';

if (!in_array('global_admin', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
}

$fun_facts = [];
$fun_facts['users'] = $link_new->query("SELECT sum(penge) AS total_wkr FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE stutteri NOT IN ('Net-hesten', 'DennisTest', 'B', 'auktionshuset', 'Carsten', 'hestehandleren','{$foel}kassen') LIMIT 1")->fetch_object();
?>
<div>
	Total mængde wkr: <?= number_dotter($fun_facts['users']->total_wkr); ?><br />
</div><br /><br />
<?php
$i = 0;

$result = $link_new->query("SELECT * FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere");
$i = 0;
while ($data = $result->fetch_assoc()) {
	++$i;
}
echo "<pre>";
echo "$i users have never logged in<br />";

$result = $link_new->query("SELECT * FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere");
$i = 0;

$rige = 0;
$wealthy = 0;
$super_rich = 0;
$whatwhat = 0;

$richest_wkr = 0;
$richest = '';

$nyhedsbrev = 0;

$stats = [];

$total_wkr = 0;

while ($data = $result->fetch_assoc()) {
	if (in_array(strtolower($data['stutteri']), ['net-hesten', 'dennistest', 'b', 'auktionshuset', 'carsten', 'hestehandleren', 'techhesten'])) {
		continue;
	}
	$total_wkr = (int) $total_wkr + (int) $data['penge'];
	if ($data['nyhedsbrev'] == 'ja') {
		++$nyhedsbrev;
	}
	if (($data['penge'] > $richest_wkr)) {
		$richest_wkr = $data['penge'];
		$richest = $data['stutteri'];
	}

	if ($data['penge'] >= 100000000) {
		++$whatwhat;
	} elseif ($data['penge'] >= 25000000) {
		++$super_rich;
	} elseif ($data['penge'] >= 10000000) {
		++$rige;
	} elseif ($data['penge'] >= 1000000) {
		++$wealthy;
	}
/*
	if ($data['penge'] >= 25000000) {
		++$stats[months_ago($data['logindate'])]['super_rich'];
	} elseif ($data['penge'] >= 10000000) {
		++$stats[months_ago($data['logindate'])]['rich'];
	} elseif ($data['penge'] >= 1000000) {
		++$stats[months_ago($data['logindate'])]['wealthy'];
	}
	++$stats[months_ago($data['logindate'])]['active'];
*/

	++$i;
}
//$sql_horses = "SELECT sum(pris) AS combined_value, count(id) AS amount FROM `{$GLOBALS['DB_NAME_OLD']}`.Heste where status != '$dead' and bruger != 'hestehandleren*' and bruger !='carsten' and bruger !='net-hesten' and bruger != '{$foel}kassen'";
$sql_horses = "SELECT sum(pris) AS combined_value, count(id) AS amount FROM `{$GLOBALS['DB_NAME_OLD']}`.Heste where status != '$dead' and bruger NOT IN ('Net-hesten', 'DennisTest', 'B', 'auktionshuset', 'Carsten', 'hestehandleren','{$foel}kassen')";
$result_horses = $link->query($sql_horses);
while ($data_horses = $result_horses->fetch_object()) {
	echo PHP_EOL . '#Horses ' . number_dotter($data_horses->amount) . ' = ' . number_dotter($data_horses->combined_value) . ',- wkr' . PHP_EOL . PHP_EOL;
}

echo "$i users have ever logged in" . PHP_EOL;
echo "$nyhedsbrev users er tilmeldt nyhedsbrevet." . PHP_EOL;
for ($i = 1; $i <= 30; $i++) {
	echo "{$stats[$i]['active']} users were active less than $i months ago" . PHP_EOL;
//	echo " - of them {$stats[$i]['super_rich']} are super rich and {$stats[$i]['rich']} are rich and {$stats[$i]['wealthy']} are wealthy." . PHP_EOL;
}
//print_r($stats);

echo 'Combined platform wealth = ' . number_dotter($total_wkr) . PHP_EOL;
//echo '01/04 2016 = 5.079.530.828 wkr ' . PHP_EOL;
//echo '01/05 2016 = 5.124.913.255 wkr ' . PHP_EOL;
//echo '01/07 2016 = 5.130.091.565 wkr ' . PHP_EOL;
echo '<br />';
echo '26/07 2016 = 5.129.821.877 wkr ' . ' + horses 58.746 = 858.242.736,- wkr (Total: 5.988.000.000)' . PHP_EOL;
echo '23/08 2016 = 5.132.986.613 wkr ' . ' + horses 59.418 = 875.555.595,- wkr (Total: 6.008.000.000)' . PHP_EOL;
echo '15/09 2016 = 5.137.951.963 wkr ' . ' + horses 59.973 = 885.814.312,- wkr (Total: 6.023.000.000)' . PHP_EOL;
echo '30/11 2016 = 5.153.371.000 wkr ' . ' + horses 61.652 = 927.694.108,- wkr (Total: 6.065.000.000)' . PHP_EOL;
echo '12/04 2017 = 5.187.524.080 wkr ' . ' + horses 63.934 = 981.632.887,- wkr (Total: 6.169.000.000)' . PHP_EOL;
echo '28/05 2017 = 5.737.345.035 wkr ' . ' + horses 43.005 = 589.038.105,- wkr (Total: 6.326.000.000)' . PHP_EOL;
echo '28/06 2017 = 5.890.473.611 wkr ' . ' + horses 41.581 = 550.754.567,- wkr (Total: 6.441.000.000)' . PHP_EOL;
echo '31/07 2017 = 5.949.642.394 wkr ' . ' + horses 42.149 = 557.377.819,- wkr (Total: 6.517.000.000)' . PHP_EOL;
echo '09/09 2017 = 6.019.595.681 wkr ' . ' + horses 39.653 = 512.183.381,- wkr (Total: 6.531.000.000)' . PHP_EOL;
echo '08/10 2017 = 6.041.220.218 wkr ' . ' + horses 38.904 = 504.410.651,- wkr (Total: 6.545.000.000)' . PHP_EOL;
echo '28/03 2018 = 6.159.476.239 wkr ' . ' + horses 39.283 = 520.483.096,- wkr (Total: 6.680.000.000)' . PHP_EOL;

echo "</pre>";
echo '<br />';
echo '17-07 2017 ændrede session gap-længde fra 30 min til 15 min, for concurrent session' . PHP_EOL;
require "$basepath/global_modules/footer.php";
