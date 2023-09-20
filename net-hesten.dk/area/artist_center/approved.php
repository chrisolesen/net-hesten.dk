<?php
$basepath = '../../..';
$title = 'Heste Tegner Panel';
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";
?>
<style>
	[data-section-type="right-side"] [data-section-type="info_square"] div {
		width: 100%;
		margin: 0 !important;
	}

	[data-section-type="right-side"] [data-section-type="info_square"] .title {
		/*font-weight: bold;*/
		display: inline-block;
		width: 200px;
	}

	[data-section-type="right-side"] [data-section-type="info_square"] .value {
		display: inline-block;
		width: calc(100% - 200px);
		text-align: right;
	}

	@media all and (max-width:640px) {
		body #htc_section {
			grid-template-columns: 1fr;
			display: block;
		}

		body #htc_section [data-section-type=submissions] {
			grid-template-columns: 1fr;
			padding: 0 1em;
		}

		body #htc_section [data-section-type=submissions] h2 {
			grid-column: 1 !important;
		}

		body [data-section-type=info_square] {
			height: auto !important;
		}

		body [data-section-type="right-side"] {
			float: none;
			width: 100% !important;
		}

		body [data-section-type="ht-tab-content"] [data-section-type="object_square"] {
			height: auto !important;
		}

		body [data-section-type="ht-tab-content"] [data-section-type="object_square"] input,
		body [data-section-type="ht-tab-content"] [data-section-type="object_square"] select {
			display: block;
			float: none;
		}
	}
</style>
<section class="tabs">
	<nav>
		<ul>
		</ul>
	</nav>
	<section id="htc_section">
		<div data-section-type="page_title">
			<header>
				<h1>Heste Tegner Panel<?= in_array('hestetegner', $_SESSION['rights']) ? ' - Oversigt' : ''; ?></h1>
			</header>
		</div>
		<div data-section-type="right-side">
			<div data-section-type="info_square">
				<header>
					<h2>Dine data</h2>
				</header>
				<?php if (array_intersect(['hestetegner', 'global_admin'], $_SESSION['rights'])) { ?>
					<div><a href="#" title="Points kan ikke benyttes endnu"><span class="title">HT Points:</span><span class="value"><?= artist_center::yield_points(['user_id' => $_SESSION['user_id']]); ?></span></a></div>
				<?php } ?>
				<div><a href="//<?= HTTP_HOST; ?>//area/artist_center/"><span class="title">Afventende Tegninger:</span><span class="value"><?= artist_center::yield_waiting(['user_id' => $_SESSION['user_id']]); ?></span></a></div>
				<div><a href="//<?= HTTP_HOST; ?>/area/artist_center/approved.php"><span style="text-decoration:underline;" class="title">Godkendte Tegninger:</span><span class="value"><?= artist_center::yield_approved(['user_id' => $_SESSION['user_id']]); ?></span></a></div>
				<div><a href="//<?= HTTP_HOST; ?>/area/artist_center/rejected.php"><span class="title">Afviste Tegninger:</span><span class="value"><?= artist_center::yield_rejected(['user_id' => $_SESSION['user_id']]); ?></span></a></div>
			</div>
			<?php if (in_array('global_admin', $_SESSION['rights'])) { ?>
				<div data-section-type="info_square">
					<header>
						<h2>Admin data</h2>
					</header>
					<div><a style="text-decoration: underline;" href="//<?= HTTP_HOST; ?>/admin/hestetegner/submitted_horses.php">Til HT Admin panel</a></div>
				</div>
			<?php } ?>
		</div>
		<style>
			#htc_section {
				display: grid;
				grid-template-columns: 1fr 300px;
				grid-gap: 1em;
			}

			[data-section-type="page_title"] {
				grid-column: 1 / span 2;
				grid-row: 1;
			}

			[data-section-type="ht-tab-content"] {
				grid-row: 2;
				grid-column: 1;
			}



			[data-section-type="right-side"] {
				grid-row: 2 / span 2;
				grid-column: 2;
			}
		</style>
		<div data-section-type="submissions">
			<h2 style="grid-column:1 / span 4;margin:0;">Dine godkendte</h2>
			<?php
			$submissions = artist_center::fetch_drawings(['user_id' => $_SESSION['user_id'], 'status' => 28]);
			foreach ($submissions as $submission) {
				if ($submission['occasion'] == 'artist_request') {
					$style = 'style="opacity:0.2;"';
				} else {
					$style = '';
				}
				$user_name_artist = $link_new->query("SELECT `stutteri` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE `id` = " . $submission['artist'])->fetch_object()->stutteri;
			?>

				<div class="artist_center_horse_list">
					<div class="btn btn-white race_name"><?= $submission['race_name']; ?></div>
					<img src="//files.<?= HTTP_HOST; ?>/horses/artist_submissions/<?= $submission['image']; ?>" />
					<div class="btn btn-white date"><?= $submission['date']; ?></div>
				</div>
			<?php
			}
			?>
		</div>
	</section>
</section>

<script type="text/javascript">
	// iOS Hover Event Class Fix
	if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
		$(".horse_square").click(function() {
			// Update '.change-this-class' to the class of your menu
			// Leave this empty, that's the magic sauce
		});
	}
	if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
		$(".icon-vcard").click(function() {
			// Update '.change-this-class' to the class of your menu
			// Leave this empty, that's the magic sauce
		});
	}
</script>
<?php
require_once("{$basepath}/global_modules/footer.php");
