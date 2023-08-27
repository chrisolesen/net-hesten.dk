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
				<div><a href="//<?= HTTP_HOST; ?>//area/artist_center/"><span style="text-decoration:underline;" class="title">Afventende Tegninger:</span><span class="value"><?= artist_center::yield_waiting(['user_id' => $_SESSION['user_id']]); ?></span></a></div>
				<div><a href="//<?= HTTP_HOST; ?>/area/artist_center/approved.php"><span class="title">Godkendte Tegninger:</span><span class="value"><?= artist_center::yield_approved(['user_id' => $_SESSION['user_id']]); ?></span></a></div>
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
		<div data-section-type="ht-tab-content">
			<div data-section-type="object_square">
				<form style="line-height:29px;" method="post" action="" enctype="multipart/form-data">
					<input type="hidden" name="action" value="submit_drawing" />
					<span>Indsend hest</span>
					<select required="" name="race">
						<option value="">Vælg race</option>
						<?php foreach ($cached_races as $race) { ?>
							<option value="<?= $race['id']; ?>"><?= $race['name']; ?></option>
						<?php } ?>
					</select>
					<select required="" name="type">
						<option value="">Vælg unik/normal</option>
						<option value="unique">Unik</option>
						<option value="normal">Normal</option>
					</select>
					<select required="" name="theme">
						<option>Vælg tema</option>
						<option value="default" selected="">Almindelig hest</option>
						<option value="christmas">Jul</option>
						<option value="halloween">Halloween</option>
						<option value="nh_birthday">NHs Fødselsdag</option>
						<option value="valentine">Valentinsdag</option>
					</select>
					<select required="" name="occasion">
						<option>Vælg anledning</option>
						<?php if (in_array('hestetegner', $_SESSION['rights'])) { ?>
							<option value="default" selected="">Almindelig indsending</option>
						<?php } else { ?>
							<option value="artist_request" selected="">HT Anmodning</option>
						<?php } ?>
					</select>
					<input required="" name="drawing_image" id="drawing_image" type="file" />
					<input type="submit" name="submit_drawing" class="btn btn-success" value="Gem" />
				</form>
			</div>
			<?php if (in_array('hestetegner', $_SESSION['rights'])) { ?>
				<br />
				<h2>Du er HesteTegner</h2>
				<p style="line-height: 1.2;">
					Skabelonerne finder du <a style="text-decoration: underline;" href="//files.<?= HTTP_HOST; ?>/templates/nethesten_skabeloner.zip">her</a>.<br />
				</p>
				<div>(OBS! Vores Skabelonner må kun bruges på net-hesten.dk. Du må dog godt bruge dem til privat ikke kommercielt brug.)</div><br />
			<?php } else { ?>
				<br />
				<h2>Du er ikke HesteTegner (HT): </h2>
				<p style="line-height: 1.2;">
					Du er velkommen til at søge om titlen, ved at sende tegninger ind. Husk at tegne dem i vores skabeloner som du finder <a style="text-decoration: underline;" href="//files.<?= HTTP_HOST; ?>/templates/nethesten_skabeloner.zip">her</a>.
				</p>
				<div>(OBS! Vores Skabelonner må kun bruges på net-hesten.dk. Du må dog godt bruge dem til privat ikke kommercielt brug.)</div><br />
			<?php
			}
			?>
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

			[data-section-type="submissions"] {
				display: grid;
				grid-template-columns: repeat(4, 1fr);
				grid-row: 3;
				grid-column: 1;
				grid-gap: 0.5em;
			}

			[data-section-type="right-side"] {
				grid-row: 2 / span 2;
				grid-column: 2;
			}
		</style>
		<div data-section-type="submissions">
			<h2 style="grid-column:1 / span 4;margin:0;">Dine indsendte</h2>
			<?php
			$submissions = artist_center::fetch_drawings(['user_id' => $_SESSION['user_id'], 'status' => 27]);
			foreach ($submissions as $submission) {
				if ($submission['occasion'] == 'artist_request') {
					$style = 'style="opacity:0.2;"';
				} else {
					$style = '';
				}
				$user_name_artist = $link_new->query("SELECT `stutteri` FROM `{$GLOBALS['DB_NAME_OLD']}`.`Brugere` WHERE `id` = {$submission['artist']}")->fetch_object()->stutteri;
			?>

				<div class="artist_center_submission">
					<a class="btn btn-danger delete_submission" href="?reject_artist_submission=<?= $submission['id']; ?>">Slet</a>
					<img src="//files.<?= HTTP_HOST; ?>/horses/artist_submissions/<?= $submission['image']; ?>" />
					<div>
						<?= $submission['date']; ?><br />
					</div>
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
