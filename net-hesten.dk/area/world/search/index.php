<?php
/* Mit Stutteri */
$basepath = '../../../..';
$title = 'Stutteri';
require "$basepath/app_core/object_loader.php";
require "$basepath/global_modules/header.php";


$search_horses_page = max(0, (int) filter_input(INPUT_GET, 'search_horses_page'));
$horses_pr_page = 10;
?>
<?php $user_info = user::get_info(['user_id' => $_SESSION['user_id']]); ?>
<?php $dead = 'død'; ?>
<style>
    .tabs {
        margin-top: 1em;
    }

    .foel {
        position: absolute;
        bottom: 45px;
        right: 12px;
    }

    .enter_graes {
        position: absolute;
        bottom: 12px;
        right: 12px;
    }

    .horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square+.horse_square {
        display: none;
    }
</style>
<?php
$horse_array = [];
$horse_tabs = [];
$attr = ['user_name' => $_SESSION['username'], 'mode' => 'search_all'];
$attr['custom_filter'] = horse_list_filters::get_filter_string(['zone' => "search_all"]);

$offset = $search_horses_page * $horses_pr_page;
$limit = ($search_horses_page * $horses_pr_page) + $horses_pr_page;
$attr['limit'] = 100;
$attr['offset'] = $offset;
$attr['custom_order'] = 'ORDER BY heste.pris * 1 DESC';
$i = 0;
foreach ((horses::get_all($attr) ?? []) as $horse) {
    $horse_array[] = render_horse_object($horse, 'horse_search');
}
?>

<section class="tabs">
    <section data-zone="horses">
        <div class="grid">
            <div data-section-type="info_square">
                <header>
                    <h1>Alle Heste</h1>
                </header>
                <div class="page_selector">
                    <span class="btn btn-white">Side:
                        <?= $search_horses_page + 1; ?></span>&nbsp;<a class="btn btn-info" href="?search_horses_page=<?= $search_horses_page - 1; ?>">Forrige side</a>&nbsp;<a class="btn btn-info" href="?search_horses_page=<?= $search_horses_page + 1; ?>">Næste side</a>
                    <a class="btn btn-info" style="line-height: 30px;" data-button-type='modal_activator' data-target='filter_horses'>Filtre</a>
                </div>
            </div>
            <?php
            foreach (($horse_array ?? []) as $horse) {
                echo $horse . PHP_EOL;
            }
            ?>
        </div>
    </section>
</section>
<?php
/* Define modal - start */
ob_start();
?>
<div id="filter_horses" class="modal">
    <script>
        function filter_horses(caller) {}
    </script>
    <div class="shadow"></div>
    <div class="content">
        <?= horse_list_filters::render_filter_settings(['zone' => "search_all"]); ?>
    </div>
</div>
<?php
require_once("{$basepath}/global_modules/modals/horse_linage.php");
$modals[] = ob_get_contents();
ob_end_clean();
/* Define modal - end */
require_once("{$basepath}/global_modules/footer.php");
