<?php
$basepath = '../../..';
$responsive = true;
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";

if (!(is_array($_SESSION['rights']) && in_array('global_admin', $_SESSION['rights']))) {
    ob_end_clean();
    header('Location: /');
}
?>
<a href="/admin/lists">Tilbage</a><br /><br /> 
<section>
    <header>
        <h1>Overvågning - Privat Heste Handel</h1>
	</header>
    <style>
    th,td {
        padding:0.25em 1em;
        text-align:left;
    }
    th {
        font-weight:bold;
        border-bottom:3px double #333;
    }
    td {
        border-bottom:1px solid rgba(33,33,33,0.5);
    }
    tr:nth-child(2n + 0){
        background:rgba(0,0,0,0.1);
    }
    td.number {
        text-align:right;
    }
    </style>
    <table>
    <?php
    $sql = "SELECT DISTINCT(seller) AS seller_id, buyer as buyer_id , sellers.stutteri AS seller_name, buyers.stutteri AS buyer_name,  
    (SELECT count(id) FROM `game_data_private_trade` WHERE (seller = seller_id AND buyer = buyer_id)) AS direction_one, 
    (SELECT count(id) FROM `game_data_private_trade` WHERE (seller = buyer_id AND buyer = seller_id)) AS direction_two, 
    (SELECT GROUP_CONCAT(DISTINCT LEAST(buyer,seller),' ', GREATEST(seller, buyer)) FROM `game_data_private_trade` WHERE (seller = seller_id AND buyer = buyer_id) OR (seller = buyer_id AND buyer = seller_id)) AS pair  
    FROM `game_data_private_trade` 
    LEFT JOIN `{$_GLOBALS['DB_NAME_OLD']}`.`Brugere` sellers ON sellers.id = seller 
    LEFT JOIN `{$_GLOBALS['DB_NAME_OLD']}`.`Brugere` buyers ON buyers.id = buyer
    GROUP BY pair 
    ORDER BY direction_two + direction_one DESC";
    $result = $link_new->query($sql) or print("<br /><br />Query Failed! SQL: <br />$sql<br /> - Error: <br /><br />".mysqli_error($link_new).'<br /><br />');
    if ($result) {
        while ($data = $result->fetch_object()) {
            ?>
            <tr>
                <td><?= min($data->seller_name,$data->buyer_name); ?></td>
                <td><?= max($data->seller_name,$data->buyer_name); ?></td>
                <td class="number"><?= $data->direction_one; ?></td>
                <td class="number"><?= $data->direction_two; ?></td>
            </td>
            <?php
        }
    } 
    ?>
        <thead>
            <tr>
                <th>Bruger 1</th>
                <th>Bruger 2</th>
                <th>Frem</th>
                <th>Tilbage</th>
            </tr>
        </thead>
    </table>
</section>
<?php
require "$basepath/global_modules/footer.php";