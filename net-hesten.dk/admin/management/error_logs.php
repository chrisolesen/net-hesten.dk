<?php
$basepath = '../../..';
$responsive = true;
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
?>
<?php
if (!is_array($_SESSION['rights']) || !in_array('tech_admin', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
}
?>
<style>
	#error_logs_page td {
		padding: 0.5em 0.25em;


	}

	#error_logs_page td+td+td {
		text-align: right;
	}

	#error_logs_page td+td+td+td {
		text-align: left;
	}
</style>
<section id="error_logs_page">
	<header>
		<h2 class="raised">Error Logs</h2>
		<h3>Server time: <?= (new DateTime('now'))->format('Y-m-d H:i:s'); ?></h3>
	</header>
	<?php if (isset($_GET['file'])) { ?>
		<div style="height:200px;overflow:auto;">
			<pre><?php
					ob_start();
					include($_GET['file']);
					$output = ob_get_contents();
					ob_end_clean();
					echo str_replace([' Europe/Copenhagen', ' PHP Warning: ', '-2018', 'PHP Fatal error: '], ['', ' Warn:', '', 'Fatal:'], $output);
					?></pre>
		</div>
	<?php }
	?>
	<?php
	if (in_array('tech_admin', $_SESSION['rights'])) {
		if (isset($_GET['delete'])) {
			unlink($_GET['delete']);
		}
	}
	?>
	<h2>List Error Logs</h2>
	<table>
		<tr>
			<th>Type</th>
			<th>Path</th>
			<th>Size</th>
			<th>Time</th>
			<th>Action</th>
			<th></th>
		</tr>
		<?php
		$file_root = '../../';
		$d = dir("../../../");
		while (false !== ($entry = $d->read())) {
			if (is_dir("{$d->path}" . $entry)) {
				if (in_array($entry, ['..', '.'])) {
					continue;
				}
				$dir = '[D1] ';
				//echo '<li>'.$dir.str_replace('../../','/root/',$d->path).$entry."</li>";
				$d2 = dir($d->path . $entry);
				while (false !== ($entry = $d2->read())) {
					if (in_array($entry, ['..', '.'])) {
						continue;
					}
					if (is_dir("{$d2->path}" . '/' . $entry)) {
						$dir = '[D2] ';
						//echo '<li>'.$dir.str_replace('../../','/root/',$d2->path).'/'.$entry."</li>";
						$d3 = dir($d2->path . '/' . $entry);
						while (false !== ($entry = $d3->read())) {
							if (in_array($entry, ['..', '.'])) {
								continue;
							}
							if (is_dir("{$d3->path}/" . $entry)) {
								$dir = '[D3] ';
								//echo '<li>'.$dir.str_replace('../../','/root/',$d3->path).'/'.$entry."</li>";

								$d4 = dir($d3->path . '/' . $entry);
								while (false !== ($entry = $d4->read())) {
									if (in_array($entry, ['..', '.'])) {
										continue;
									}
									if (is_dir("{$d4->path}/" . $entry)) {
										$dir = '[D4] ';
										//echo '<li>'.$dir.str_replace('../../','/root/',$d4->path).'/'.$entry."</li>";
										$d5 = dir($d4->path . '/' . $entry);
										while (false !== ($entry = $d5->read())) {
											if (in_array($entry, ['..', '.'])) {
												continue;
											}
											if (is_dir("{$d5->path}/" . $entry)) {
												$dir = '[D5] ';
												//echo '<li>'.$dir.str_replace('../../','/root/',$d5->path).'/'.$entry."</li>";
											} else {
												$dir = '[F5] ';
												if ($entry !== 'error_log') {
													continue;
												}

												echo '<tr><td>' . $dir . '</td><td>' . str_replace('../../../', '/root/', $d5->path) . '/' . $entry . '</td><td>' . ' [' . number_dotter(filesize($d5->path . '/' . $entry)) . ']' . '</td><td>' . '[' . (date("Y-m-d H:i:s", filemtime($d5->path . '/' . $entry))) . "]</td><td><a href='?file=" . ($d5->path . '/' . $entry) . "'>Open</a>" . "</td><td><a href='?delete=" . ($d5->path . '/' . $entry) . "'>Delete</a>" . "</td></tr>";
											}
										}
									} else {
										$dir = '[F4] ';
										if ($entry !== 'error_log') {
											continue;
										}
										echo '<tr><td>' . $dir . '</td><td>' . str_replace('../../../', '/root/', $d4->path) . '/' . $entry . '</td><td>' . ' [' . number_dotter(filesize($d4->path . '/' . $entry)) . ']' . '</td><td>' . '[' . (date("Y-m-d H:i:s", filemtime($d4->path . '/' . $entry))) . "]" . "</td><td><a href='?file=" . ($d4->path . '/' . $entry) . "'>Open</a>" . "</td><td><a href='?delete=" . ($d4->path . '/' . $entry) . "'>Delete</a>" . "</td></tr>";
									}
								}
							} else {
								$dir = '[F3] ';
								if ($entry !== 'error_log') {
									continue;
								}
								echo '<tr><td>' . $dir . '</td><td>' . str_replace('../../../', '/root/', $d3->path) . '/' . $entry . '</td><td>' . ' [' . number_dotter(filesize($d3->path . '/' . $entry)) . ']' . '</td><td>' . '[' . (date("Y-m-d H:i:s", filemtime($d3->path . '/' . $entry))) . "]" . "</td><td><a href='?file=" . ($d3->path . '/' . $entry) . "'>Open</a>" . "</td><td><a href='?delete=" . ($d3->path . '/' . $entry) . "'>Delete</a>" . "</td></tr>";
							}
						}
					} else {
						$dir = '[F2] ';
						if ($entry !== 'error_log') {
							continue;
						}
						echo '<tr><td>' . $dir . '</td><td>' . str_replace('../../../', '/root/', $d2->path) . '/' . $entry . '</td><td>' . ' [' . number_dotter(filesize($d2->path . '/' . $entry)) . ']' . '</td><td>[' . (date("Y-m-d H:i:s", filemtime($d2->path . '/' . $entry))) . "]" . "</td><td><a href='?file=" . ($d2->path . '/' . $entry) . "'>Open</a>" . "</td><td><a href='?delete=" . ($d2->path . '/' . $entry) . "'>Delete</a>" . "</td></tr>";
					}
				}
			} else {
				$dir = '[F1] ';
				if ($entry !== 'error_log') {
					continue;
				}
				echo '<tr><td>' . $dir . '</td><td>' . str_replace('../../../', '/root/', $d->path) . $entry . '</td><td>' . ' [' . number_dotter(filesize($d->path . '/' . $entry)) . ']' . '</td><td>[' . (date("Y-m-d H:i:s", filemtime($d->path . '/' . $entry))) . "]" . "</td><td><a href='?file=" . ($d->path . '/' . $entry) . "'>Open</a>" . "</td><td><a href='?delete=" . ($d->path . '/' . $entry) . "'>Delete</a>" . "</td></tr>";
			}
		}
		?>
	</table>
</section>
<?php
require "$basepath/global_modules/footer.php";
