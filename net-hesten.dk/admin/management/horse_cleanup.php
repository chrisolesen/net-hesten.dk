<?php
$basepath = '../../..';
$responsive = true;
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";
?>
<?php
if (!in_array('tech_admin', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
}
$pr_page = (int) filter_input(INPUT_GET, 'pr_page') ?: 10;
$page = (int) filter_input(INPUT_GET, 'page') ?: 0;
$offset = $page * $pr_page;

/*
SELECT main.id, main.bruger,main.navn,main.race,main.kon,main.alder,main.status,main.foersteplads,main.andenplads,main.tredieplads,main.kaaringer,main.morid,main.farid,main.partnerid 
FROM praktisk_nethest_old_db.Heste main 
WHERE main.id > 663500 and main.id < 664000 and main.bruger = 'hestehandleren' and main.status = 'død' and foersteplads = 0 and andenplads = 0 and tredieplads = 0 and kaaringer = 0 and partnerid = '' and 
(CASE
    WHEN main.kon = 'hingst' THEN (SELECT id AS amount FROM praktisk_nethest_old_db.Heste subhorse WHERE subhorse.id > main.id AND main.id = subhorse.farid ORDER BY subhorse.id DESC LIMIT 1 ) 
    WHEN main.kon = 'hoppe' THEN (SELECT id AS amount FROM praktisk_nethest_old_db.Heste subhorse WHERE subhorse.id > main.id AND main.id = subhorse.morid ORDER BY subhorse.id DESC LIMIT 1 ) 
END) = 0 
ORDER BY main.id ASC
LIMIT 1;
*/
?>
<section>
    <style>
        ul {
            border:1px solid black;
            padding:5px;
            display: table;
        }
        ul li {
            display: table-row;
        }
        ul li span {
            display: table-cell;
            padding:2px 5px; 
            line-height: 1.2;
            border-bottom: 1px dashed black;
        }
        ul li span + span{
            border-left:1px dotted black;        }
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
    </style>
    <header>
        <h1>Bruger oprydning</h1>
		<br />
    </header>
    <ul>
        <li class="heading">
            <span>ID / IP</span>
            <span>Stutteri Navn / Navn </span>
            <span>WKR</span>
            <span>(PB) (Chat)</span>
            <span>Heste</span>
            <span>Konto</span>
            <!--<span>Mail</span>-->
            <span>Last Active</span>
            <span>Slet</span>
        </li>
		<?php
//        $sql = "SELECT * FROM Brugere LIMIT $pr_page OFFSET $offset";
		$sql = "SELECT timing.value AS last_online, user.id, user.stutteri, user.penge, user.navn, user.email, user.date, user.IP, user.logindate FROM {$_GLOBALS['DB_NAME_OLD']}.Brugere AS user "
				. "LEFT JOIN {$_GLOBALS['DB_NAME_NEW']}.user_data_timing AS timing ON timing.parent_id = user.id AND timing.name = 'last_active' "
				. "WHERE user.logindate < '2018-02-26 00:00:00' AND user.date < '2018-02-26 00:00:00' AND timing.value < '2018-02-26 00:00:00' "
				. "ORDER BY user.logindate ASC, timing.value ASC, user.id ASC "
				. ""
				. "";
		$result = $link_old->query($sql);
		$i = 0;
		$ihorses = 0;
		$iwkr = 0;
		while ($data = $result->fetch_object()) {
			if (in_array($data->id, $_GLOBALS['hidden_system_users'])) {
				continue;
			}
			$sql = "SELECT count(id) AS amount FROM game_data_private_messages WHERE target = '{$data->id}' OR origin = '{$data->id}'";
			$number_of_new_messages = $link_new->query($sql)->fetch_object()->amount;
			$sql = "SELECT count(id) AS amount FROM Heste WHERE bruger = '{$data->stutteri}'";
			$number_of_horses = $link_old->query($sql)->fetch_object()->amount;
			$sql = "SELECT count(id) AS amount FROM game_data_chat_messages WHERE creator = '{$data->id}'";
			$number_of_chat = $link_new->query($sql)->fetch_object()->amount;
//			if ($data->penge < 10000000) {
//				continue;
//			}
			++$i;
			?>
			<li>
				<span class="monospace">
					<i class='id'><?= $data->id; ?></i><br />
					<?= $data->IP; ?> 
				</span>
				<span>
					Stutteri: <i class='name'><?= mb_convert_encoding($data->stutteri, 'UTF-8', 'Latin1'); ?></i><br />
					Navn: <?= mb_convert_encoding($data->navn, 'UTF-8', 'Latin1'); ?><br />
					Mail: <?= mb_convert_encoding($data->email, 'UTF-8', 'Latin1'); ?>
				</span>
				<span class="wkr monospace"><?= number_dotter($data->penge); ?></span><?php $iwkr += $data->penge; ?>
				<span class="monospace center_text">(<?= $number_of_new_messages; ?>)(<?= $number_of_chat ?>)</span>
				<span class="monospace center_text"><?= $number_of_horses; ?></span><?php $ihorses += $number_of_horses; ?>
				<span class="monospace center_text"><?= $number_of_konto; ?></span>
				<?php /* <span><?= mb_convert_encoding($data->email, 'UTF-8', 'Latin1'); ?></span> */ ?>
				<span class="monospace center_text">
					<?php
					if ($data->logindate > $data->last_online) {
						$last_active = $data->logindate;
					} else {
						$last_active = $data->last_online;
					}
					?>
					<?= $last_active; ?><br />
					<i style='opacity: 0.7;font-size:0.8em;'>(<?= $data->date; ?>)</i>
				</span>
				<span class="monospace center_text">
					<?php if ($number_of_new_messages == 0 && $number_of_chat == 0 && $number_of_horses == 0 && $number_of_konto == 0 && $last_active < '2018-04-01 00:00:00') { ?>
						<a href="?delete_user=<?= $data->id; ?>">Slet</a>
					<?php } ?>
					<?php if ($number_of_new_messages > 0 || $number_of_messages) { ?>
						<a href="?clear_pb=<?= $data->id; ?>">FIX PB Beskeder</a><br />
					<?php } ?>
					<?php if ($number_of_konto > 0) { ?>
						<a href="?clear_account_view=<?= $data->id; ?>">Ryd kontooversigt</a><br />
					<?php } ?>
					<?php if ($number_of_horses > 0) { ?>
						<a href="?confiscate_horses=<?= $data->id; ?>">Konfisker heste</a><br />
					<?php } ?>
					<?php if ($number_of_chat > 0) { ?>
						<a href="?reasign_chat=<?= $data->id; ?>">Fiks chat beskeder</a><br />
					<?php } ?>
				</span>
			</li>
			<?php
		}
		?>
    </ul>
	<span style="display:block;position: fixed;top:1em;right:1em;background:white;border:1px solid black;padding:1em;z-index: 10;">
		Brugere: <?php echo $i; ?><br />
		Heste: <?= $ihorses; ?><br />
		WKR: <?= number_dotter($iwkr); ?>
	</span>
</section>
<?php
require "$basepath/global_modules/footer.php";
