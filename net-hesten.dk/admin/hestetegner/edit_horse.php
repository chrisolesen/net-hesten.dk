<?php
$basepath = '../../..';
$responsive = true;
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
?>
<?php
if (!in_array('global_admin', $_SESSION['rights']) && !in_array('hestetegner_admin', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
}
$Foelbox = mb_convert_encoding('Følkassen', 'latin1', 'UTF-8');

if (filter_input(INPUT_POST, 'search_id')) {
	$search_id = filter_input(INPUT_POST, 'search_id');
} else if (filter_input(INPUT_GET, 'horse_id')) {
	$search_id = filter_input(INPUT_GET, 'horse_id');
}
?>
<section>
    <header>
        <h2 class="raised">Rediger hest</h2>
    </header>
	<style>
		body {
			line-height: 25px;
		}
		select,
		input[type='text'],
		input[type='number'],
		input[type='tel']
		{
			height: 20px;
			padding: 0 5px !important;
			border:0;
			background: white !important;
			border-radius: 0;
			color:black;
			border:black 1px solid;
			text-transform: none !important;
		}
		section section {
			background: white;
			color:black;
		}
		section section form > span {
			width:75px;
			display:inline-block;
		}
	</style>
	<form method="post" action="">
		<input type="tel" name="search_id" placeholder="ID:" value="<?= $search_id; ?>" />
		<input type="submit" name="search" value="Vis" />
	</form>
	<form method="post" action="">
		<input type="hidden" name="action" value="edit_horse" />
		<?php
		if (filter_input(INPUT_POST, 'action') === 'edit_horse') {
			$id = filter_input(INPUT_POST, 'search_id');
			$race = mb_convert_encoding(filter_input(INPUT_POST, 'horse_race'), 'latin1', 'UTF-8');
			$user = mb_convert_encoding(filter_input(INPUT_POST, 'user_name'), 'latin1', 'UTF-8');
			$wanted_age = filter_input(INPUT_POST, 'age');

			$horse_age_to_irl_days = $wanted_age * 40;
			$current_date = new DateTime('now');
			$target_date = $current_date->sub(new DateInterval("P{$horse_age_to_irl_days}D"))->format('Y-m-d H:i:s');
			$gender = ((filter_input(INPUT_POST, 'gender') == 'stallion') ? 'Hingst' : ((filter_input(INPUT_POST, 'gender') == 'mare') ? 'Hoppe' : 'no-gender'));

			$status = '';
			if (filter_input(INPUT_POST, 'status') == 'død') {
				$status = mb_convert_encoding('død', 'latin1', 'UTF-8');
			}
			if (filter_input(INPUT_POST, 'status') == 'føl') {
				$status = mb_convert_encoding('føl', 'latin1', 'UTF-8');
			}
			if (filter_input(INPUT_POST, 'status') == 'hest') {
				$status = 'hest';
			}
			if (filter_input(INPUT_POST, 'status') == 'avl') {
				$status = 'avl';
			}

			$unique = filter_input(INPUT_POST, 'unique') == 'on' ? 'ja' : '';
			$original = filter_input(INPUT_POST, 'original') == 'on' ? 'ja' : '';
			$rebirth = filter_input(INPUT_POST, 'rebirth') == 'on' ? 'ja' : '';

			$sql = "UPDATE {$_GLOBALS['DB_NAME_OLD']}.Heste SET status = '{$status}', genfodes = '{$rebirth}', original = '{$original}', unik = '{$unique}', race = '{$race}', bruger = '{$user}', kon = '{$gender}', date = '{$target_date}', changedate = '{$target_date}', alder = '{$wanted_age}' WHERE id = {$id} LIMIT 1";
			$link_old->query($sql);
		}

		$result = $link_old->query("SELECT * FROM {$_GLOBALS['DB_NAME_OLD']}.Heste WHERE id = {$search_id} LIMIT 1");
		while ($data = $result->fetch_object()) {
			?>
			<input type="hidden" name="search_id" value="<?= $data->id; ?>" />
			<img style='float:left;margin-right:20px;margin-bottom: 60px;' src="http://files.<?= filter_input(INPUT_SERVER,'HTTP_HOST');?>/imgHorse/<?= $data->thumb; ?>" />
			<span>ID:</span><?= $data->id; ?><br />
			<span>Race:</span><input type="text" list="horse_races" name="horse_race" value="<?= mb_convert_encoding($data->race, 'UTF-8', 'latin1'); ?>" /><br />
			<span>Bruger:</span><input type="text" list="user_names" name="user_name" value="<?= mb_convert_encoding($data->bruger, 'UTF-8', 'latin1'); ?>" /><br />
			<span>Alder:</span><input type="number" name="age" value="<?= mb_convert_encoding($data->alder, 'UTF-8', 'latin1'); ?>" /><br />
			<span>Unik:</span><input type="checkbox" name="unique" <?= $data->unik == 'ja' ? 'checked' : ''; ?> /><br />
			<span>Original:</span><input type="checkbox" name="original" <?= $data->original == 'ja' ? 'checked' : ''; ?>  /><br />
			<span>Genfødes:</span><input type="checkbox" name="rebirth" <?= $data->genfodes == 'ja' ? 'checked' : ''; ?>  /><br />
			<span>Køn:</span><select name="gender">
				<option value='no-gender'>Ingen køn angivet</option>
				<option value='stallion' <?= (strtolower($data->kon) == 'hingst' ? 'selected' : ''); ?>>Hingst</option>
				<option value='mare' <?= (strtolower($data->kon) == 'hoppe' ? 'selected' : ''); ?>>Hoppe</option>
			</select><br />
			<span>Status:</span><select name="status">
				<option value='no-status'>Ingen status angivet</option>
				<option value='død' <?= (mb_convert_encoding(strtolower($data->status), 'UTF-8', 'latin1') == 'død' ? 'selected' : ''); ?>>Død</option>
				<option value='føl' <?= (mb_convert_encoding(strtolower($data->status), 'UTF-8', 'latin1') == 'føl' ? 'selected' : ''); ?>>Føl</option>
				<option value='hest' <?= (strtolower($data->status) == 'hest' ? 'selected' : ''); ?>>Hest</option>
				<option value='avl' <?= (strtolower($data->status) == 'avl' ? 'selected' : ''); ?>>Avl</option>
			</select>
			<br />
			<?php
		}
		?>
		<input type="submit" value="Gem ændringer" style="margin-top: 25px;"/>
	</form>
	<datalist id="horse_races">
		<?php
		$result = $link_old->query("SELECT hesterace FROM {$_GLOBALS['DB_NAME_OLD']}.Hesteracer");
		while ($data = $result->fetch_object()) {
			?><option value="<?= mb_convert_encoding($data->hesterace, 'UTF-8', 'latin1'); ?>" /><?php
		}
		?>
	</datalist>
	<datalist id="user_names">
		<?php
		$result = $link_old->query("SELECT stutteri FROM {$_GLOBALS['DB_NAME_OLD']}.Brugere");
		while ($data = $result->fetch_object()) {
			?><option value="<?= mb_convert_encoding($data->stutteri, 'UTF-8', 'latin1'); ?>" /><?php
		}
		?>
	</datalist>
</section>
<?php
require "$basepath/global_modules/footer.php";
