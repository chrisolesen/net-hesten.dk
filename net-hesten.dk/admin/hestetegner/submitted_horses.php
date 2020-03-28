<?php
$basepath = '../../..';
$title = 'Heste typer';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";

if (!in_array('global_admin', $_SESSION['rights']) && !in_array('hestetegner_admin', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
	exit();
}
?>
<script src="https://<?= filter_input(INPUT_SERVER ,'HTTP_HOST');?>/scripts/tinymce/tinymce.min.js"></script>
<script>tinymce.init({selector: 'textarea[name="description"]'});</script>
<style>
	@font-face {
		font-family: 'tinymce';
		src:url('/admin/elements/fonts/tinymce.woff') format('woff2');
	}
	#race_add_form input {
		width:150px;
		margin: 0;
	}
	#race_add_form input:first-child {
		width: 300px;
	}
	#race_add_form textarea {
		clear: both;
		display: block;
		width: 600px;
	}
	table {
		width: 100%;
	}
	td {
		border:1px rgba(33,33,33,0.5) solid;
		padding: 1em;
	}
</style>
<?php
$occasions = ['default' => 'Normal indsending', 'artist_request' => 'HT Anmodning'];
$themes = [];
$types = [];
?>
<section>
	<style>
		td {
			vertical-align: middle;
		}
	</style>
	<header><h2 class="raised">Indsendte heste</h2></header>
	<table>
		<thead>
		<td>Billed</td>
		<td>Type</td>
		<td>Tema</td>
		<td>Anledning</td>
		<td>Race</td>
		<td>Tegner</td>
		<td>Dato</td>
		<td>Handlinger</td>
		</thead>
		<?php
		$submissions = artist_center::fetch_drawings(['mode' => 'approve']);
		foreach ($submissions as $submission) {
			if ($submission['occasion'] == 'artist_request') {
				$style = 'style="opacity:0.2;"';
			} else {
				$style = '';
			}
			$user_name_artist = $link_new->query("SELECT stutteri FROM {$_GLOBALS['DB_NAME_OLD']}.Brugere WHERE id = " . $submission['artist'])->fetch_object()->stutteri;
			?>
			<tr <?= $style; ?>>
				<td><div><img height="200px" src="//<?= filter_input(INPUT_SERVER,'HTTP_HOST');?>/horses/artist_submissions/<?= $submission['image']; ?>" /></div></td>
				<td><?= $submission['type']; ?></td>
				<td><?= $submission['theme']; ?></td>
				<td><?= $occasions[$submission['occasion']]; ?></td>
				<td><?= $cached_races[$submission['race']]['name']; ?></td>
				<td><?= $user_name_artist; ?></td>
				<td><?= $submission['date']; ?></td>
				<td style='position: relative;'>
					<div style='height: 200px;'>
						<div style='line-height: 40px;'><a href="?approve_artist_submission=<?= $submission['id']; ?>">Godkend</a> / <a href="?reject_artist_submission=<?= $submission['id']; ?>">Afvis</a></div>
						<textarea style='height: calc(100% - 40px);width:100%'></textarea>
					</div>
				</td>
			</tr>
			<?php
		}
		?>
	</table>
	<br />
	<br />
</section>
<?php
require "$basepath/global_modules/footer.php";
