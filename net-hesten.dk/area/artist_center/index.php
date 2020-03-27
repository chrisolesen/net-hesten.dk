<?php
$basepath = '../../..';
$title = 'Heste Tegner Panel';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
?>
<style>
	[data-section-type="right-side"] [data-section-type="info_square"] div {
		width:100%;
		margin: 0 !important;
	}
	[data-section-type="right-side"] [data-section-type="info_square"] .title {
		/*font-weight: bold;*/
		display: inline-block;
		width:200px;
	}
	[data-section-type="right-side"] [data-section-type="info_square"] .value {
		display: inline-block;
		width:calc(100% - 200px);
		text-align: right;
	}
</style>
<section class="tabs">
	<nav>
		<ul>
		</ul>
	</nav>
	<section>
		<div data-section-type="page_title">
			<header><h1>Heste Tegner Panel<?= in_array('hestetegner', $_SESSION['rights']) ? ' - Oversigt' : ''; ?></h1></header>
		</div>
		<?php if (array_intersect(['hestetegner', 'global_admin'], $_SESSION['rights'])) { ?>
			<div data-section-type="right-side">
				<div data-section-type="info_square">
					<header><h2>Dine data</h2></header>
					<div><span class="title">Godkendte Tegninger:</span><span class="value">0</span></div>
					<div><span class="title">Afventende Tegninger:</span><span class="value"><?= artist_center::yield_waiting(['user_id' => $_SESSION['user_id']]); ?></span></div>
					<div><span class="title">HT Points:</span><span class="value">0</span></div>
					<div><span class="title">Afviste Tegninger:</span><span class="value">0</span></div>
					<!--<a class="btn btn-info" href="/area/world/horsetrader/">Vis tilfældige heste</a>-->
				</div>
				<?php if (in_array('global_admin', $_SESSION['rights'])) { ?>
					<div data-section-type="info_square">
						<header><h2>Admin data</h2></header>
						<div><a style="text-decoration: underline;" href="https://net-hesten.dk/admin/hestetegner/submitted_horses.php">Til HT Admin panel</a></div>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
		<div data-section-type="tab-content">

			<?php if (in_array('hestetegner', $_SESSION['rights'])) { ?>
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
			<?php } else { ?>
				<p>
					Du er desværre ikke HesteTegner (HT), endnu, men du er velkommen til at søge om titlen, ved at sende dine heste ind i et opslag der kommer på forummet.<br />
					Hestene skal være lavet i Net-hestens skabeloner, for at vi kan bedømme dem; skabelonerne finder du <a style="text-decoration: underline;" href="">her</a>.<br />
					Hvis du ikke bliver godkendt med det samme, så giv ikke op :) Husk, øvelse gør mester.<br />
				</p>
				<br />
				<br />
				<div>(OBS! Vores Skabelonner, er kun til privat ikke kommercielt brug, eller på net-hesten.dk)</div><br />
				<br />
				<br />
				<?php
			}
			?>
		</div>
	</section>
</section>

<script type = "text/javascript">

// iOS Hover Event Class Fix
	if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
		$(".horse_square").click(function () {
// Update '.change-this-class' to the class of your menu
// Leave this empty, that's the magic sauce
		});
	}
	if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
		$(".icon-vcard").click(function () {
			// Update '.change-this-class' to the class of your menu
			// Leave this empty, that's the magic sauce
		});
	}
</script>
<?php
require_once ("{$basepath}/global_modules/footer.php");
