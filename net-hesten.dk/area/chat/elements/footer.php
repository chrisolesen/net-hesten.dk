<?php
$target_date = new DateTime('NOW');
$target_date->sub(new DateInterval('P3M'));
$target_date_display = $target_date->format('d/m/Y');
$target_date = $target_date->format('Y-m-d');
?>
<datalist id="active_usernames">
	<?php
	$sql = 'SELECT old.stutteri AS username '
			. "FROM {$GLOBALS['DB_NAME_OLD']}.Brugere AS old "
			. 'LEFT JOIN user_data_timing AS last_active '
			. 'ON last_active.parent_id = old.id AND last_active.name = "last_active" '
			. "WHERE last_active.value > '{$target_date} 00:00:00' "
			. "AND old.id NOT IN ({$GLOBALS['hidden_system_users_sql']}) "
			. "ORDER BY old.stutteri";
	$result = $link_new->query($sql);
	while ($data = $result->fetch_object()) {
		?><option value='<?= $data->username; ?>' /><?php
	}
	?>

</datalist>
<?php
if (!$_SESSION['logged_in'] == true || !isset($basepath)) {
	ob_end_clean();
	exit();
}
?>
</body>
</html>
<?php
ob_flush();
ob_end_clean();
