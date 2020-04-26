<?php
/* REVIEW: SQL Queries */
/* Se alle stutterier */
$basepath = '../../../..';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
?>
<?php $dead = 'død'; ?>
<?php $user_info = user::get_info(['user_id' => $_SESSION['user_id']]); ?>
<section class="tabs">
	<section>
		<div class="grid">
			<?php
			$target_date = new DateTime('NOW');
			$target_date->sub(new DateInterval('P3M'));
			$target_date_display = $target_date->format('d/m/Y');
			$target_date = $target_date->format('Y-m-d');
			$number_of_users = $link_new->query("SELECT count(old.id) AS amount "
				. "FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere AS old "
				. "LEFT JOIN user_data_timing AS last_active "
				. "ON last_active.parent_id = old.id AND last_active.name = 'last_active' "
				. "WHERE last_active.value > '{$target_date} 00:00:00' ")->fetch_object()->amount;
			?>
			<div data-section-type="info_square">
				<header>
					<h1>Stutterier på Net-Hesten - <div style="display: inline-block;font-size:0.5em;overflow: hidden;">( <?= $number_of_users; ?> aktive siden <?= $target_date_display; ?> )</div>
					</h1>
				</header>
				<a class="btn btn-info" href="?page=<?= max(($_GET['page'] - 1), 0); ?>" style="line-height: 30px;">Forrige side</a>
				<a class="btn btn-info" href="?page=<?= max(($_GET['page'] + 1), 0); ?>" style="line-height: 30px;">Næste side</a>
			</div>
			<div class="horse_square horse_object" style="height:70px;">
				<form action="" method="post" style="padding:20px;">
					<b>Søg på stutterinavn</b><input name="visit_search_stud" list="active_usernames" style="margin-left:20px;" /><input class="btn btn-info compact_bottom_button" type="submit" value="søg" style="margin-left:20px;" />
				</form>
			</div>
			<?php
			echo '<br />';
			$pr_page = 10;
			$page = (int) max(filter_input(INPUT_GET, 'page'), 0);
			if (!$page || $page < 0) {
				$page == 0;
			}
			$order_by = "ORDER BY CASE 
 WHEN old.thumb LIKE '%/%' THEN 10 
 ELSE 20 END, old.id ";
 
			$offset = $page * $pr_page;
			$sql = 'SELECT old.stutteri AS username, old.thumb, old.penge AS `money`, old.id, old.navn AS `name`, last_active.value AS last_online '
				. "FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere AS old "
				. "LEFT JOIN `{$GLOBALS['DB_NAME_NEW']}`.user_data_timing AS last_active "
				. 'ON last_active.parent_id = old.id AND last_active.name = "last_active" '
				. "WHERE last_active.value > '{$target_date} 00:00:00' "
				. "AND old.id NOT IN ({$GLOBALS['hidden_system_users_sql']}) "
				. (($filter_username = (filter_input(INPUT_POST, 'visit_search_stud') ?? false)) ? "AND old.stutteri LIKE '%{$filter_username}%' " : '')
				. " {$order_by} "
				. "LIMIT {$pr_page} OFFSET {$offset}";
			$result = $link_new->query($sql);
			if ($result) {
				while ($data = $result->fetch_object()) {
			?>
					<div class="horse_square horse_object">
						<div style="left:170px;" class="info">
							<?php
							$privileges = $link_new->query("SELECT privilege_id FROM user_privileges WHERE user_id = {$data->id}");
							$user_style = '';
							while ($privilege = $privileges->fetch_object()->privilege_id) {
								if ($privilege === '1') {
									$user_style = 'highlight_user_as_admin';
									break;
								}
								if ($privilege === '5') {
									$user_style = 'highlight_user_as_ht';
									break;
								}
							}
							?>
							<style>
								.highlight_user_as_admin:before {
									content: "";
									padding: 1px;
									height: 10px;
									border-left: 2px blue solid;
									display: inline-block;
									position: relative;
									top: -1px;
								}

								.highlight_user_as_ht:before {
									border-left: 2px red solid;
								}
							</style>
							<span class="name">
								<span style="font-weight:bold;" class="<?= $user_style; ?>"><?= $data->username; ?></span><br />
								<span>Penge: <?= number_dotter($data->money); ?> <span class="wkr_symbol">wkr</span></span><br />
								<span>Heste: <?= $link_new->query("SELECT count(id) AS amount FROM `{$GLOBALS['DB_NAME_OLD']}`.Heste WHERE bruger = '{$data->username}' AND status <> 'død'")->fetch_object()->amount; ?></span>
								<!--<span>Værdi: <?= number_dotter($data->id); ?> <span class="wkr_symbol">wkr</span></span>-->
							</span>
							<div style="position:absolute;left:300px;top:20px;max-height: 70px;">
								<span>Sidst online: <?= ((new DateTime($data->last_online))->format('Y-m-d')); ?></span><br />
							</div>
							<span class='value hide_on_compact'>Værdi: <?= number_dotter($horse->value); ?> wkr</span>
							<a class='btn btn-info compact_bottom_button' href="/area/world/visit/visit.php?user=<?= $data->id; ?>" style="position:absolute;">Besøg</a>
						</div>
						<?php
						if ($data->thumb) {
							$stud_thumbnail = "//files." . HTTP_HOST . "/users/{$data->thumb}";
						} else {
							$stud_thumbnail = "//files." . HTTP_HOST . "/graphics/logo/default_logo.png";
						}
						?>
						<img style="max-width:175px;left: 105px;" src='<?= $stud_thumbnail; ?>' />
						<img style='display: none;' class='zoom_img' src='<?= $stud_thumbnail; ?>' />
					</div>
			<?php
				}
			}
			?>
		</div>
	</section>
</section>
<?php
require_once("{$basepath}/global_modules/footer.php");
