<?php
$basepath = '../../../..';
$title = 'Vær din hest';
require "$basepath/app_core/object_loader.php";
require "$basepath/net-hesten.dk/area/chat/elements/header.php";
?>
<link rel="stylesheet" href="/area/chat/styles/version_two.css?v=<?= time(); ?>" />
<link rel="stylesheet" href="/style/messages.css?v=<?= time(); ?>" />
<style>
    body ul.message_list {
        height: calc(100% - 160px - 10px)
    }
</style>
<header>
    <a href="/area/chat/global/">Normal Chat</a>
</header>
<style>
    small {
        font-size: 0.75em;
    }
    .pinned {
        /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#f3c5bd+0,ff6600+51,c68477+100&0.5+0,0+6,0.5+13,0+21,0.5+29,0+37,0.5+45,0+52,0.5+58,0+66,0.5+72,0+80,0.5+88,0+94,0.5+100 */
        /*		background: -moz-linear-gradient(-45deg,  rgba(243,197,189,0.5) 0%, rgba(244,186,167,0) 6%, rgba(246,173,141,0.5) 13%, rgba(248,158,111,0) 21%, rgba(250,143,81,0.5) 29%, rgba(252,128,52,0) 37%, rgba(254,113,22,0.5) 45%, rgba(255,102,0,0.07) 51%, rgba(254,103,2,0) 52%, rgba(247,107,17,0.5) 58%, rgba(238,112,36,0) 66%, rgba(231,116,51,0.5) 72%, rgba(222,121,70,0) 80%, rgba(212,125,90,0.5) 88%, rgba(205,129,105,0) 94%, rgba(198,132,119,0.5) 100%);  FF3.6-15 
                        background: -webkit-linear-gradient(-45deg,  rgba(243,197,189,0.5) 0%,rgba(244,186,167,0) 6%,rgba(246,173,141,0.5) 13%,rgba(248,158,111,0) 21%,rgba(250,143,81,0.5) 29%,rgba(252,128,52,0) 37%,rgba(254,113,22,0.5) 45%,rgba(255,102,0,0.07) 51%,rgba(254,103,2,0) 52%,rgba(247,107,17,0.5) 58%,rgba(238,112,36,0) 66%,rgba(231,116,51,0.5) 72%,rgba(222,121,70,0) 80%,rgba(212,125,90,0.5) 88%,rgba(205,129,105,0) 94%,rgba(198,132,119,0.5) 100%);  Chrome10-25,Safari5.1-6 
                        background: linear-gradient(135deg,  rgba(243,197,189,0.5) 0%,rgba(244,186,167,0) 6%,rgba(246,173,141,0.5) 13%,rgba(248,158,111,0) 21%,rgba(250,143,81,0.5) 29%,rgba(252,128,52,0) 37%,rgba(254,113,22,0.5) 45%,rgba(255,102,0,0.07) 51%,rgba(254,103,2,0) 52%,rgba(247,107,17,0.5) 58%,rgba(238,112,36,0) 66%,rgba(231,116,51,0.5) 72%,rgba(222,121,70,0) 80%,rgba(212,125,90,0.5) 88%,rgba(205,129,105,0) 94%,rgba(198,132,119,0.5) 100%);  W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ 
                        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#80f3c5bd', endColorstr='#80c68477',GradientType=1 );  IE6-9 fallback on horizontal gradient */
        box-shadow: 0 0 2px 2px orange inset;
        border-top-right-radius: 10px;
    }
    pre {
        max-width: 100%;
        white-space: normal;
    }
</style>
<ul class="message_list">
    <li class="pinned">
        <div class="poster">
            <span class="username admin">TækHesten:</span>
        </div>
        <div class="msg">Velkommen til vær din hest.<br />Hold venligst en pæn tone, HYG JER! :-)</div>
    </li>
    <?php
    if (is_array(alias_chat::get_messages())) {
        foreach (alias_chat::get_messages() as $message) {
            ?>
            <li>
                <div class="poster">

                    <span class="username <?= (in_array(mb_strtolower($message['creator']), $admin_colors)) ? 'admin' : ''; ?>"><a href="/area/world/visit/visit.php?user=<?= $message['creator_id']; ?>" target="_top"><?= $message['creator']; ?></a>:</span>	<?= $message['date']; ?>

                </div>
                <div class="msg"><?= str_replace(["\n", "\r"], ['<br />', ''], $message['text']); ?></div>
            </li>
            <?php
        }
    }
    ?>
</ul>
<div class="new_message">
    <form action="" method="post">
        // $_SESSION['horse_rp_alias']
        <input name="action" value="post_alias_chat_message" type="hidden" />
        <textarea name="message_text" placeholder="Vrinsk noget som: hestenavn"></textarea>
        <input class="btn btn-green" type="submit" name="alias_chat" value="Send" style="width:65%;" />
        <button class='btn btn-info' data-button-type='modal_activator' data-target='select_roleplay_horse' style="width:calc(35% - 20px);margin-left:20px;">Vælg Hest</button>
    </form> 
</div>

<script type="text/javascript">

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
require "$basepath/net-hesten.dk/area/chat/elements/footer.php";
