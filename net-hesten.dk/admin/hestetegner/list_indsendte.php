<?php
require '../../../app_core/db_conf.php';
require '../../../app_core/user_validate.php';
if ($_SESSION['logged_in'] == true) {
	if (in_array('hestetegner_admin', $_SESSION['rights'])) {
?>
		<!DOCTYPE html>
		<html>

		<head>
			<style>
				.horse {
					float: left;
					width: 400px;
					height: 400px;
				}

				.horse img {
					display: block;
					clear: both;
				}

				ul,
				li {
					list-style: none;
				}

				.search {
					position: fixed;
					top: 5px;
					left: 5px;
					border: 5px #000 solid;
				}
			</style>
		</head>

		<body>
			<div id="horses">
				<input class="search" placeholder="Search" /><br />
				<ul class="list">
					<?php
					$result = $link->query("SELECT * FROM {$GLOBALS['DB_NAME_OLD']}.Heste WHERE bruger = 'hestehandleren*' and date >= '2014-08-01 00:00:00'");
					$num_rows = $result->num_rows;

					while ($data = $result->fetch_assoc()) {
						if (!$data['tegner']) {
							continue;
						}
						$path = '';
						if (!strpos($data['thumb'], 'imgHorse')) {
							$path = 'imgHorse/';
						}
						if (strlen($data['thumb']) < 25) {
							$path .= 'imgHorse/';
						}
					?>

						<li>
							<div class="horse">
								<?php
								echo 'ID: ' . $data['id'] . '<br />';
								echo 'Dato: <span class="dato">' . $data['date'] . '</span><br />';
								//			echo $data['ownership_start'] . '<br />';
								echo '<span class="tegner">' . $data['tegner'] . '</span>' . '<br />';
								echo '<span class="beskrivelse">' . $data['beskrivelse'] . '</span>' . '<br />';
								echo $data['race'] . '<br />';
								echo $data['kon'] . '<br />';
								echo "<img src='https://" . (filter_input(INPUT_SERVER, 'HTTP_HOST')) . "/$path{$data['thumb']}' />" . '<br />';
								//			if (++$ii <= 1) {
								//				print_r($data) . PHP_EOL;
								//			}
								?>
							</div>
						</li>
					<?php
					}
					?>
				</ul>
			</div>
			<script type="text/javascript" src="https://<?= filter_input(INPUT_SERVER, 'HTTP_HOST'); ?>/scripts/listjs.js"></script>
			<script>
				var options = {
					valueNames: ['tegner', 'beskrivelse', 'dato', ]
				};

				var userList = new List('horses', options);
			</script>
		</body>

		</html>
	<?php
	}
} else {
	?>
	<h2>Login</h2>
	<form action="#" method="post">
		<input type="text" name="username" placeholder="Brugernavn" />
		<input type="password" name="password" placeholder="Kodeord" />
		<input type="submit" name="login" value="Login" />
	</form>
<?php
}
