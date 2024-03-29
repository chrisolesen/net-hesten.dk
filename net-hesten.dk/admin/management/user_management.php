<?php
/* REVIEW: SQL Queries */
$basepath = '../../..';
$responsive = true;
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";

if (in_array('global_admin', $_SESSION['rights']) || in_array('hestetegner_admin', $_SESSION['rights'])) {


	$pr_page = (int) filter_input(INPUT_GET, 'pr_page') ?: 10;
	$page = (int) filter_input(INPUT_GET, 'page') ?: 0;
	$offset = $page * $pr_page;

	if (isset($_POST['adjust_wkr']) && in_array('global_admin', $_SESSION['rights'])) {
		$take = false;
		$user_id = $_POST['player_id'];
		$wkr = (string) $_POST['wkr'];
		$reason = $_POST['reason'];

		if (substr($wkr, 0, 1) == '-') {
			$take = true;
			$wkr = (int) substr($wkr, 1);
		}

		if (!$take) {
			accounting::add_entry(['amount' => $wkr, 'line_text' => "{$reason}", 'mode' => '+', 'user_id' => $user_id]);
		} else {
			accounting::add_entry(['amount' => $wkr, 'line_text' => "{$reason}", 'user_id' => $user_id]);
		}
	}
?>
	<section>
		<style>
			ul {
				border: 1px solid black;
				padding: 10px;
				display: table;
			}

			ul li {
				display: table-row;
			}

			ul li span {
				display: table-cell;
				padding: 2px 10px;
				line-height: 1.2;
				border-bottom: 1px dashed black;
			}

			ul li span+span {
				border-left: 1px dotted black;
			}

			.wkr {
				text-align: right;
			}

			.heading span {
				border-bottom: 3px double black;
				text-align: center;
			}

			.monospace {
				font-family: monospace;
			}

			.center_text {
				text-align: center;
			}

			.rights {
				position: relative;
			}

			.rights a {
				position: absolute;
			}

			.rights a img {
				opacity: 0.1;
				height: 3.2em;
				transition: all 0.2s linear;
			}

			.rights a.active img,
			.rights a:hover img {
				opacity: 1;
			}

			.rights a.active:hover img {
				opacity: 0.5;
			}

			.rights .make_artist {
				top: 0;
				left: 0;
			}
		</style>
		<header>
			<h1>Bruger administration</h1>
			<br />
			<a class="btn btn-info" href="/admin/">Tilbage</a>
		</header>
		<div class="page_selector">
			<a class="btn btn-info" href="?page=<?= $page - 1; ?>">Prev Page</a><span class="btn btn-info">Page: <?= $page + 1; ?></span><a class="btn btn-info" href="?page=<?= $page + 1; ?>">Next Page</a>
			<form action="" method="post" style="display: inline-block;">
				<input placeholder="ID" name="search_id" type="text" />
				<input placeholder="Stutteri / Mail" name="search_name" type="text" />
				<input class="btn btn-success" type="submit" value="search" />
				<input class="btn btn-danger" type="submit" name='reset' value="Nulstill" />
			</form>
			<br />
			<br />
		</div>
		<ul>
			<li class="heading">
				<span>ID</span>
				<span>Stutteri Navn / Navn </span>
				<span>WKR</span>
				<span>Mail</span>
				<span>Rettigheder</span>
				<span>
					<?php
					if (in_array('global_admin', $_SESSION['rights'])) {
					?>Signup date / <?php } ?>Last login
				</span>
				<span>Login som</span>
			</li>
			<?php
			$sql = "SELECT id, stutteri, penge, navn, email, date FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere LIMIT $pr_page OFFSET $offset";
			if (filter_input(INPUT_POST, 'search_id')) {
				$search_id = (int) trim(filter_input(INPUT_POST, 'search_id'));
				$_SESSION['user_list_search_type'] = 'id';
				$_SESSION['user_list_search'] = (int) $search_id;
			}
			if (filter_input(INPUT_POST, 'search_name')) {
				$search_name = (string) trim(filter_input(INPUT_POST, 'search_name'));
				$_SESSION['user_list_search_type'] = 'name';
				$_SESSION['user_list_search'] = $search_name;
			}
			if (filter_input(INPUT_POST, 'reset')) {
				unset($_SESSION['user_list_search_type']);
				unset($_SESSION['user_list_search']);
			}
			if (($_SESSION['user_list_search_type'] ?? false) === 'id') {
				$sql = "SELECT id, stutteri, penge, navn, email, date FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE id = {$_SESSION['user_list_search']} LIMIT $pr_page OFFSET $offset";
			}

			if (($_SESSION['user_list_search_type'] ?? false) === 'name') {
				if (strpos($_SESSION['user_list_search'], '@')) {
					$sql = "SELECT id, stutteri, penge, navn, email, date FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE email LIKE '%{$_SESSION['user_list_search']}%' LIMIT $pr_page OFFSET $offset";
				} else {
					$sql = "SELECT id, stutteri, penge, navn, email, date FROM `{$GLOBALS['DB_NAME_OLD']}`.Brugere WHERE stutteri LIKE '%{$_SESSION['user_list_search']}%' LIMIT $pr_page OFFSET $offset";
				}
			}
			$result = $link_new->query($sql);
			while ($data = $result->fetch_object()) {
				$last_active = ($link_new->query("SELECT value FROM user_data_timing WHERE parent_id = {$data->id} AND name = 'last_active'")->fetch_object()->value ?? '0000-00-00 00:00:00');
				$fetch_rights = "SELECT * FROM user_privileges WHERE user_id = {$data->id}";
				$rights = $link_new->query($fetch_rights);
				$rights_array = [];
				while ($right = $rights->fetch_object()) {
					if ($right->end == '0000-00-00 00:00:00') {
						$rights_array[] = $right->privilege_id;
					}
				}
			?>
				<li>
					<span class="monospace">
						<i class='id'><?= $data->id; ?></i>
					</span>
					<span>
						<i class='name'><a style="text-decoration:underline;" href="//<?= HTTP_HOST; ?>/area/world/visit/visit.php?user=<?= $data->id; ?>"><?= $data->stutteri; ?></a></i><br />
						<?= $data->navn; ?>
					</span>
					<span class="wkr monospace">
						<?php
						if (in_array('global_admin', $_SESSION['rights'])) {
						?>
							<a title="Alter WKR" data-lw-action="alter_wkr" href="javascript:void(0);"><?= number_dotter($data->penge); ?></a>
							<?php
						} else {
							?>-<?php
							}
								?>
					</span>

					<span>
						<?php
						if (in_array('global_admin', $_SESSION['rights'])) {
						?>
							<?= $data->email; ?><br /><a onclick="return confirm('Vil du virkelig nulstille koden til: <?= $data->stutteri; ?> ?');" href="?action=admin_user_password_reset&user_id=<?= $data->id; ?>">Reset password</a>
							<?php
						} else {
							?>-<?php
							}
								?>
					</span>


					<span class="rights monospace center_text">
						<?php if (in_array('5', $rights_array)) { ?>
							<a href="?page=<?= $page; ?>&action=remove_right_horse_artist&user_id=<?= $data->id; ?>" class="active make_artist"><img src="//files.<?= HTTP_HOST; ?>/graphics/artist.png" /></a>
						<?php } else { ?>
							<a href="?page=<?= $page; ?>&action=grant_right_horse_artist&user_id=<?= $data->id; ?>" class="make_artist"><img src="//files.<?= HTTP_HOST; ?>/graphics/artist.png" /></a>
						<?php } ?>
						<?php
						if (in_array('global_admin', $_SESSION['rights'])) {
						?>
							<?php if (in_array('2', $rights_array)) { ?>
								<a href="?page=<?= $page; ?>&action=remove_right_horse_artist_admin&user_id=<?= $data->id; ?>" class="active make_artist_admin">RHTA</a>
							<?php } else { ?>
								<a href="?page=<?= $page; ?>&action=grant_right_horse_artist_admin&user_id=<?= $data->id; ?>" class="make_artist_admin">GHTA</a>
							<?php } ?>
						<?php } ?>
					</span>
					<span class="monospace center_text">
						<?php
						if (in_array('global_admin', $_SESSION['rights'])) {
						?>
							<?= $data->date; ?><br />
						<?php } ?>
						<?= $last_active; ?>
					</span>
					<span class="monospace center_text">
						<?php
						if (in_array('global_admin', $_SESSION['rights'])) {
						?>
							<a href="?page=<?= $page; ?>&action=impersonate&user_id=<?= $data->id; ?>" class="impersonate">Impersonate</a>
						<?php } ?>
					</span>
				</li>
			<?php
			}
			?>
		</ul>
	</section>
	<div id="add_wkr_modal" class="modal">
		<h1>Juster WKR for.</h1>
		<h2 class='player_name'></h2>
		<form method="post" action="">
			<input type='hidden' name='player_id' value='' />
			<input type='text' name='wkr' placeholder="Wkr ændring:" required='required' />
			<input type='text' name='reason' placeholder='Årsag:' required='required' />
			<input type='submit' name='adjust_wkr' value='Udfør' />
		</form>
		<br />
		<div class='close'>Luk</div>
	</div>
	<style>
		#add_wkr_modal {
			position: absolute;
			display: none;
			top: 50%;
			left: 50%;
			transform: translateX(-50%) translateY(-50%);
			background: darkgray;
			height: 20em;
			width: 30em;
			padding: 2em;
		}

		#add_wkr_modal.visible {
			display: block;
		}
	</style>
	<script>
		jQuery('#add_wkr_modal .close').click(function() {
			jQuery(this).parent().removeClass('visible');
		});
		jQuery('[data-lw-action="alter_wkr"]').click(function() {
			jQuery('#add_wkr_modal').addClass('visible');
			jQuery('#add_wkr_modal').find('.player_name').html(jQuery(this).parent().parent().find('.name').html());
			jQuery('#add_wkr_modal').find('[name="player_id"]').val(jQuery(this).parent().parent().find('.id').html());
		});
	</script>
<?php
	require "{$basepath}/global_modules/footer.php";
} else {
	ob_end_clean();
	header('Location: /');
	exit();
}
