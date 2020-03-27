<?php

class horse_list_filters {

	public static function save_filter_settings($attr = []) {
		global $link_new;
		global $link_new;
		global $_POST;

		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
            $attr[$key] = $link_new->real_escape_string($value);
        }

		if (!filter_input(INPUT_POST, 'filter_zone') || !$_SESSION['user_id']) {
			if (!($attr['reset_all_filters'] == true)) {
				return false;
			}
		}
		if ($attr['reset_all_filters'] == true) {
			
		} else {
			$filter_data = false;
			$sql = "SELECT value FROM user_data_json WHERE parent_id = {$_SESSION['user_id']} AND name = 'list_filtering_settings' LIMIT 1";
			$result = $link_new->query($sql);
			if ($result) {
				$filter_data = unserialize($result->fetch_object()->value);
			} else {
				return false;
			}

			$target_zone = filter_input(INPUT_POST, 'filter_zone');

			if (filter_input(INPUT_POST, 'filter_age_min') && filter_input(INPUT_POST, 'filter_age_max')) {
				if (filter_input(INPUT_POST, 'filter_age_max') !== 'any') {
					$filter_data[$target_zone]['age_min'] = min(filter_input(INPUT_POST, 'filter_age_min'), filter_input(INPUT_POST, 'filter_age_max'));
					$filter_data[$target_zone]['age_max'] = max(filter_input(INPUT_POST, 'filter_age_min'), filter_input(INPUT_POST, 'filter_age_max'));
				} else {
					$filter_data[$target_zone]['age_min'] = filter_input(INPUT_POST, 'filter_age_min');
					$filter_data[$target_zone]['age_max'] = filter_input(INPUT_POST, 'filter_age_max');
				}
			} else {
				if (filter_input(INPUT_POST, 'filter_age_min')) {
					$filter_data[$target_zone]['age_min'] = filter_input(INPUT_POST, 'filter_age_min');
					$filter_data[$target_zone]['age_max'] = 16;
				}
				if (filter_input(INPUT_POST, 'filter_age_max')) {
					$filter_data[$target_zone]['age_min'] = 0;
					$filter_data[$target_zone]['age_max'] = filter_input(INPUT_POST, 'filter_age_max');
				}
			}

			$filter_data[$target_zone]['name'] = filter_input(INPUT_POST, 'filter_name');
			$filter_data[$target_zone]['id'] = filter_input(INPUT_POST, 'id');
			$filter_data[$target_zone]['gender'] = filter_input(INPUT_POST, 'gender');
			$filter_data[$target_zone]['artist'] = filter_input(INPUT_POST, 'filter_artist');


			$filter_data[$target_zone]['races'] = [];
			$races = $_POST['races'];
			if (is_array($races)) {

				foreach ($races as $race) {
					$filter_data[$target_zone]['races'][] = $race;
				}
			}
			if ($filter_type = filter_input(INPUT_POST, 'filter_type')) {
				$filter_data[$target_zone]['filter_type'] = $filter_type;
			} else {
				$filter_data[$target_zone]['filter_type'] = 'any';
			}

			if ($filter_status = filter_input(INPUT_POST, 'filter_status')) {
				$filter_data[$target_zone]['filter_status'] = $filter_status;
			} else {
				$filter_data[$target_zone]['filter_status'] = 'all_horses';
			}
		}
		if (filter_input(INPUT_POST, 'reset') || $attr['reset_all_filters'] == true) {
			$filter_data = false;
			$filter_data[] = [];
		}

		$filter_data_for_db = serialize($filter_data);

		$sql = "INSERT INTO user_data_json (parent_id, name, value, date) VALUES ({$_SESSION['user_id']},'list_filtering_settings', '{$filter_data_for_db}', NOW()) ON DUPLICATE KEY UPDATE value = '{$filter_data_for_db}'";
		$link_new->query($sql);

		return true;
	}

	public static function render_filter_settings($attr = []) {
		global $link_new;
		global $link_new;
		global $cached_races;

		$dead = 'død';

		foreach ($cached_races as $key => $row) {
			$race_names[$row['name']] = ['name' => $row['name'], 'id' => $row['id']];
		}

		ksort($race_names, SORT_ASC);

		$return_data = [];
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
            $attr[$key] = $link_new->real_escape_string($value);
        }

		if (!isset($attr['zone'])) {
			return false;
		}

		if (in_array($attr['zone'], ['home_idle_horses', 'home_horses_on_grass', 'home_breeding_horses', 'home_horses_at_contest', 'home_foels', 'home_foels_at_contest', 'home_', 'home'])) {
			$match_zone = 'home';
		} else if (in_array($attr['zone'], ['visit_idle_horses', 'visit_horses_on_grass', 'visit_breeding_horses', 'visit_horses_at_contest', 'visit_foels', 'visit_foels_at_contest', 'visit_'])) {
			$match_zone = 'visit';
		} else {
			$match_zone = $attr['zone'];
		}
		$filter_zone_match_array = [
			'unique' => ['auctions_buy', 'auctions_sell', 'home', 'visit', 'horse_trader_sell', 'private_trade_sell', 'search_all'],
			'name' => ['auctions_buy', 'auctions_sell', 'home', 'visit', 'horse_trader_sell', 'private_trade_sell', 'search_all'],
			'horse_trader' => [],
			'horse_status' => ['home', 'visit']
		];


		$user_filter_data[$attr['zone']]['races'] = [];
		$sql = "SELECT value FROM user_data_json WHERE parent_id = {$_SESSION['user_id']} AND name = 'list_filtering_settings' LIMIT 1";
		$result = $link_new->query($sql);
		if ($result) {
			$user_filter_data = unserialize($result->fetch_object()->value);
			if (!is_array($user_filter_data[$attr['zone']]['races'])) {
				$user_filter_data[$attr['zone']]['races'] = [];
			}
		} else {
			return ['Kritisk fejl: Dit stutteri kunne ikke findes, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
		}
		ob_start();
		?>
		<!-- Filters - Start -->
		<style>
			.list_filters_form label{
				margin-right: 10px;
			}
			.list_filters_form .filter_line{
				margin-bottom: 0.5em;
			}
		</style>
		<h2>Filtrer på heste</h2>
		<?php
		$form_action = '';
		if ($attr['zone'] == 'auctions_sell') {
			$form_action = '?tab=your-horses';
		}
		if ($attr['zone'] == 'horse_trader') {
			$form_action = '?horse_trader_page=0';
		}
		?>
		<form class="list_filters_form" action="<?= $form_action; ?>" method="post" style="position: relative;height: 320px;">
			<input type="hidden" name="action" value="filter_horses" />
			<input type="hidden" name="filter_zone" value="<?= $attr['zone']; ?>" />
			<p style="font-size:16px;line-height: 20px;margin-top:10px;">Hold ctrl nede, for at vælge flere.</p>
			<select name="races[]" multiple="" size="14" style="float:left;margin-right: 20px;">
				<option value="all" <?= (in_array('all', $user_filter_data[$attr['zone']]['races']) ? 'SELECTED' : ''); ?>>Alle Racer</option>
				<?php foreach ($race_names as $race) { ?>
					<?php if ($attr['zone'] == 'horse_trader') { ?>
						<?php
						$antal = $link_new->query("SELECT count(id) AS amount FROM {$GLOBALS['DB_NAME_OLD']}.Heste WHERE id > 604000 AND bruger = 'hestehandleren' and status <> 'død' and alder < 18  AND race = '{$race['name']}'")->fetch_object()->amount;
						if ($antal == 0) {
							continue;
						}
						?>
						<option <?= (in_array($race['id'], $user_filter_data[$attr['zone']]['races']) ? 'SELECTED' : ''); ?> value="<?= $race['id']; ?>"><?= $race['name']; ?> (<?= $antal; ?>)</option>
					<?php } else {
						?>
						<option <?= (in_array($race['id'], $user_filter_data[$attr['zone']]['races']) ? 'SELECTED' : ''); ?> value="<?= $race['id']; ?>"><?= $race['name']; ?></option>
					<?php }
					?>
				<?php }
				?>
				<option value="all" <?= (in_array('all', $user_filter_data[$attr['zone']]['races']) ? 'SELECTED' : ''); ?>>Alle Racer</option>
			</select>
			<div class="filter_line">
				<label for="filter_id">
					ID
				</label>
				<input type="text" name="id" id="filter_id" value="" />
			</div>
			<?php if (in_array($match_zone, $filter_zone_match_array['name'])) { ?>
				<div class="filter_line">
					<label for="filter_name">
						Navn
					</label>
					<input type="text" name="filter_name" id="filter_name" value="<?= $user_filter_data[$attr['zone']]['name']; ?>" />
				</div>
			<?php }
			?>
			<?php if (in_array('global_admin', $_SESSION['rights'])) { ?>
			<?php }
			?>
			<div class="filter_line">
				<label for="filter_gender">
					Køn
				</label>
				<select name="gender" id="filter_gender">
					<option value="">Alle Køn</option>
					<option value="mare" <?= ($user_filter_data[$attr['zone']]['gender'] == 'mare') ? 'selected' : ''; ?>>Hoppe</option>
					<option value="stallion" <?= ($user_filter_data[$attr['zone']]['gender'] == 'stallion') ? 'selected' : ''; ?>>Hingst</option>
				</select>
			</div>
			<!--<div class="filter_line"><span class="name">Alder</span></div>-->
			<div class="filter_line">
				<label for="filter_age_min">
					Fra
				</label>
				<select name="filter_age_min" id="filter_age_min">
					<?php for ($value = 0; $value <= 18; $value++) { ?>
						<option <?= ($user_filter_data[$attr['zone']]['age_min'] == $value) ? 'selected' : ''; ?> value="
																												  <?= $value; ?>">
							<?= $value; ?> år</option>
					<?php }
					?>
				</select>
				<label for="filter_age_max" style="margin-left:5px;">
					Til
				</label>
				<select name="filter_age_max" id="filter_age_max">
					<?php for ($value = 0; $value <= 16; $value++) { ?>
						<option <?= ($user_filter_data[$attr['zone']]['age_max'] == $value) ? 'selected' : ''; ?> value="
																												  <?= $value; ?>">
							<?= $value; ?> år</option>
					<?php }
					?>
					<option <?= ($user_filter_data[$attr['zone']]['age_max'] == 'any' || !isset($user_filter_data[$attr['zone']]['age_max'])) ? 'selected' : ''; ?> value="any">Alle</option>
				</select>
			</div>
			<div class="filter_line">
				<label for="filter_artist">
					Tegner
				</label>
				<select name="filter_artist" id="filter_artist">
					<option value="all" <?= ($user_filter_data[$attr['zone']]['artist'] == 'all') ? 'selected' : ''; ?>>Alle Tegnere</option>
					<?php
					$artists = $link_new->query("SELECT DISTINCT users.stutteri AS name, users.id AS user_id FROM {$GLOBALS['DB_NAME_OLD']}.Heste AS horses LEFT JOIN {$GLOBALS['DB_NAME_OLD']}.Brugere AS users ON users.stutteri = horses.tegner WHERE status <> '{$dead}' ORDER BY users.stutteri ASC");
					if ($artists) {
						while ($artist = $artists->fetch_object()) {
							if ($artist->name == '') {
								continue;
							}
							?>
							<option value="<?= $artist->name; ?>" <?= ($user_filter_data[$attr['zone']]['artist'] == $artist->name) ? 'selected' : ''; ?>>
								<?= $artist->name; ?>
							</option>
							<?php
						}
					}
					?>
				</select>
			</div>
			<?php if (in_array($match_zone, $filter_zone_match_array['unique'])) { ?>
				<div class="filter_line">
					<label for="filter_type">
						Type
					</label>
					<select name="filter_type" id="filter_type">
						<option value="all" <?= ($user_filter_data[$attr['zone']]['filter_type'] == 'all') ? 'selected' : ''; ?>>Alle typer</option>
						<option value="unique" <?= ($user_filter_data[$attr['zone']]['filter_type'] == 'unique') ? 'selected' : ''; ?>>Unik</option>
						<option value="original" <?= ($user_filter_data[$attr['zone']]['filter_type'] == 'original') ? 'selected' : ''; ?>>Original (ikke unik)</option>
						<option value="normal" <?= ($user_filter_data[$attr['zone']]['filter_type'] == 'normal') ? 'selected' : ''; ?>>Normal</option>
					</select>
				</div>
			<?php }
			?>
			<?php if (in_array($match_zone, $filter_zone_match_array['horse_status'])) { ?>
				<div class="filter_line">
					<label for="filter_status">
						Status
					</label>
					<select name="filter_status" id="filter_status">
						<option value="all_horses" <?= ($user_filter_data[$attr['zone']]['filter_status'] == 'all_horses') ? 'selected' : ''; ?>>Alle heste</option>
						<option value="idle_horses" <?= ($user_filter_data[$attr['zone']]['filter_status'] == 'idle_horses') ? 'selected' : ''; ?>>Hest i stald</option>
						<option value="horses_on_grass" <?= ($user_filter_data[$attr['zone']]['filter_status'] == 'horses_on_grass') ? 'selected' : ''; ?>>Heste på græs</option>
						<option value="breeding_horses" <?= ($user_filter_data[$attr['zone']]['filter_status'] == 'breeding_horses') ? 'selected' : ''; ?>>Heste i avl</option>
						<option value="horses_at_contest" <?= ($user_filter_data[$attr['zone']]['filter_status'] == 'horses_at_contest') ? 'selected' : ''; ?>>Heste til stævner</option>
						<option value="foels" <?= ($user_filter_data[$attr['zone']]['filter_status'] == 'foels') ? 'selected' : ''; ?>>Føl</option>
						<option value="foels_at_contest" <?= ($user_filter_data[$attr['zone']]['filter_status'] == 'foels_at_contest') ? 'selected' : ''; ?>>Føl til kåring</option>
						<option value="unbred_mares" <?= ($user_filter_data[$attr['zone']]['filter_status'] == 'unbred_mares') ? 'selected' : ''; ?>>Ufolede hopper</option>
					</select>
				</div>
			<?php }
			?>
			<input style="position:absolute;bottom:0;right:0;" type="submit" class="btn btn-success" value="Filtrer" name="">
			<input style="position:absolute;bottom:0;right:90px;" type="submit" class="btn btn-danger" value="Nulstil" name="reset">
		</form>
		<!-- Filters - End -->
		<?php
		$return_data = ob_get_contents();
		ob_end_clean();


		return $return_data;
	}

	public static function get_filter_string($attr = []) {
		global $link_new;
		global $link_new;
		global $cached_races;

		$return_data = '';
		$defaults = [];
		foreach ($defaults as $key => $value) {
			isset($attr[$key]) ?: $attr[$key] = $value;
		}
		foreach ($attr as $key => $value) {
            $attr[$key] = $link_new->real_escape_string($value);
        }

		if (!isset($attr['zone'])) {
			return false;
		}


		$user_filter_data = false;
		$sql = "SELECT value FROM user_data_json WHERE parent_id = {$_SESSION['user_id']} AND name = 'list_filtering_settings' LIMIT 1";
		$result = $link_new->query($sql);
		if ($result) {
			$user_filter_data = unserialize($result->fetch_object()->value);
		} else {
			return ['Kritisk fejl: Dit stutteri kunne ikke findes, prøv igen eller kontakt en admin evt. på admin@net-hesten.dk', 'error'];
		}


		if (!empty($user_filter_data[$attr['zone']]['races'] && !in_array('all', $user_filter_data[$attr['zone']]['races']))) {
			$races = '';
			foreach ($user_filter_data[$attr['zone']]['races'] as $race_id) {
				$race_name = $cached_races[(int) $race_id]['name'];
//				$race_name = $cached_races[(int) $race_id]['name'];
				$races .= "'{$race_name}',";
			}
			$return_data .= " AND race IN ({$races}'') ";
		}

		if ($user_filter_data[$attr['zone']]['gender']) {
			if ($user_filter_data[$attr['zone']]['gender'] == 'mare') {
				$return_data .= " AND kon = 'hoppe' ";
			} else {
				$return_data .= " AND kon = 'hingst' ";
			}
		}

		if ($user_filter_data[$attr['zone']]['age_min']) {
			$return_data .= " AND CAST(alder AS UNSIGNED) >= {$user_filter_data[$attr['zone']]['age_min']} ";
		}
		if ($user_filter_data[$attr['zone']]['age_max']) {
			if ($user_filter_data[$attr['zone']]['age_max'] == 'any') {
				
			} else {

				$return_data .= " AND CAST(alder AS UNSIGNED) <= {$user_filter_data[$attr['zone']]['age_max']} ";
			}
		}

		if ($user_filter_data[$attr['zone']]['artist']) {
			if ($user_filter_data[$attr['zone']]['artist'] == 'all') {
				
			} else {
				$artist_for_db = $user_filter_data[$attr['zone']]['artist'];
				$return_data .= " AND tegner = '{$artist_for_db}' ";
			}
		}

		if (!empty($user_filter_data[$attr['zone']]['name'])) {

			$return_data .= " AND navn LIKE '%{$user_filter_data[$attr['zone']]['name']}%' ";
		}

		if ($user_filter_data[$attr['zone']]['filter_type'] && $user_filter_data[$attr['zone']]['filter_type'] != 'any') {
			if ($user_filter_data[$attr['zone']]['filter_type'] == 'unique') {
				$return_data .= " AND unik = 'ja' ";
			} elseif ($user_filter_data[$attr['zone']]['filter_type'] == 'original') {
				$return_data .= " AND original = 'ja' AND unik <> 'ja' ";
			} elseif ($user_filter_data[$attr['zone']]['filter_type'] == 'normal') {
				$return_data .= " AND original <> 'ja' AND unik <> 'ja' ";
			}
		}

		if ($user_filter_data[$attr['zone']]['filter_status'] && $user_filter_data[$attr['zone']]['filter_status'] != 'all_horses') {

			$foel = 'føl';

			if ($user_filter_data[$attr['zone']]['filter_status'] == 'idle_horses') {
				$return_data .= " AND graesning != 'ja' AND staevne <> 'ja' AND status <> 'avl' AND status <> '{$foel}' AND kaaring <> 'ja' AND competition_id IS NULL ";
			} elseif ($user_filter_data[$attr['zone']]['filter_status'] == 'horses_on_grass') {
				$return_data .= ' AND graesning = "ja" ';
			} elseif ($user_filter_data[$attr['zone']]['filter_status'] == 'breeding_horses') {
				$return_data .= ' AND ( status = "avl" OR breeding.meta_value IS NOT NULL ) ';
			} elseif ($user_filter_data[$attr['zone']]['filter_status'] == 'horses_at_contest') {
				$return_data .= ' AND STATUS = "hest" AND competition_id IS NOT NULL ';
			} elseif ($user_filter_data[$attr['zone']]['filter_status'] == 'foels') {
				$return_data .= " AND status = '{$foel}' ";
			} elseif ($user_filter_data[$attr['zone']]['filter_status'] == 'foels_at_contest') {
				$return_data .= " AND status = '{$foel}' AND competition_id IS NOT NULL ";
			} elseif ($user_filter_data[$attr['zone']]['filter_status'] == 'unbred_mares') {
				$return_data .= " AND kon = 'hoppe' AND breeding.meta_value IS NULL ";
			}
		}


		if ($user_filter_data[$attr['zone']]['id']) {
			$return_data = " AND id = '{$user_filter_data[$attr['zone']]['id']}' ";
		}
		return $return_data;
	}

}
