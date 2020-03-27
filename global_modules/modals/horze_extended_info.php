<div id="horze_extended_info" class="modal"><?php /* */ ?>
    <script>
        function horze_extended_info(caller) {
            jQuery.getJSON({
                dataType: 'jsonp',
                data: {
                    'request': 'fecth_extended_info',
                    'horse_id': jQuery(caller).parent().attr('data-horse-id')
                },
                crossDomain: true,
                url: "https://ajax.<?= HTTP_HOST; ?>/index.php",
                cache: false
            }).always(function(data) {
                horse_data = data.horse_data;
                jQuery('#horze_extended_info .name').html(horse_data.name);
                jQuery('#horze_extended_info .id').html(horse_data.id);
                jQuery('#horze_extended_info .age').html(horse_data.age);
                jQuery('#horze_extended_info .gender').html(horse_data.gender);
                jQuery('#horze_extended_info .race').html(horse_data.race);
                jQuery('#horze_extended_info .artist').html(horse_data.artist);
                jQuery('#horze_extended_info .value').html(horse_data.value);
                jQuery('#horze_extended_info .owner_name').html(horse_data.owner_name);
                jQuery('#horze_extended_info .talent').html(horse_data.talent);
                jQuery('#horze_extended_info .ulempe').html(horse_data.ulempe);
                jQuery('#horze_extended_info .egenskab').html(horse_data.egenskab);
                console.log(horse_data.type);
                if (horse_data.type == null) {
                    jQuery('#horze_extended_info .type').parent().hide();
                } else {
                    jQuery('#horze_extended_info .type').parent().show();
                    jQuery('#horze_extended_info .type').html(horse_data.type);
                }
                if (horse_data.gold_medal == 0) {
                    jQuery('#horze_extended_info .gold_medal').parent().hide();
                } else {
                    jQuery('#horze_extended_info .gold_medal').parent().show();
                    jQuery('#horze_extended_info .gold_medal').html(horse_data.gold_medal);
                }
                if (horse_data.silver_medal == 0) {
                    jQuery('#horze_extended_info .silver_medal').parent().hide();
                } else {
                    jQuery('#horze_extended_info .silver_medal').parent().show();
                    jQuery('#horze_extended_info .silver_medal').html(horse_data.silver_medal);
                }
                if (horse_data.bronze_medal == 0) {
                    jQuery('#horze_extended_info .bronze_medal').parent().hide();
                } else {
                    jQuery('#horze_extended_info .bronze_medal').parent().show();
                    jQuery('#horze_extended_info .bronze_medal').html(horse_data.bronze_medal);
                }
                if (horse_data.junior_medal == 0) {
                    jQuery('#horze_extended_info .junior_medal').parent().hide();
                } else {
                    jQuery('#horze_extended_info .junior_medal').parent().show();
                    jQuery('#horze_extended_info .junior_medal').html(horse_data.junior_medal);
                }
                jQuery('#horze_extended_info [data-target="horse_linage"]').attr('data-horse-id', horse_data.id);
                if (data.status !== true) {
                    console.log('Der skete en fejl ved omdøbning, vent lidt tid, hvis fejlen bliver ved, så skriv hestens ID til Tækhesten');
                }
            });
        }
    </script>
    <style>
        #horze_extended_info div {
            line-height: 25px;
        }

        #horze_extended_info span {
            font-family: 'Merienda One', cursive;
        }
    </style>
    <div class="shadow"></div>
    <div class="content">
        <div style="position:absolute;top:6px;right:10px;" onclick="jQuery(this).parent().parent().removeClass('active');"><i class="fa fa-times fa-2x nh-error-color"></i></div>
        <h2>Mere om: <span class="name"></span> <span class="age"></span> år</h2>
        <div>
            <span class="label">ID:</span> <span class="id"></span>
        </div>
        <div>
            <span class="label">Køn:</span> <span class="gender"></span>
        </div>
        <div>
            <span class="label">Race:</span> <span class="race"></span>
        </div>
        <div>
            <span class="label">Tegner:</span> <span class="artist"></span>
        </div>
        <div>
            <span class="label">Værdi:</span> <span class="value"></span>
        </div>
        <div>
            <span class="label">Ejer:</span> <span class="owner_name"></span>
        </div>
        <div>
            <span class="label">Talent:</span> <span class="talent"></span>
        </div>
        <div>
            <span class="label">Ulempe:</span> <span class="ulempe"></span>
        </div>
        <div>
            <span class="label">Egenskab:</span> <span class="egenskab"></span>
        </div>
        <div>
            <span class="label">Type:</span> <span class="type"></span>
        </div>
        <div>
            <span class="label">Guld medaljer:</span> <span class="gold_medal"></span>
        </div>
        <div>
            <span class="label">Sølv medaljer:</span> <span class="silver_medal"></span>
        </div>
        <div>
            <span class="label">Bronze medaljer:</span> <span class="bronze_medal"></span>
        </div>
        <div>
            <span class="label">Føl kåringer:</span> <span class="junior_medal"></span>
        </div>
        <div>
            <span class="label">Udstyr:</span> <span class="">Kommer snart!</span>
        </div>
        <div>
        </div>
        <div>
            <span class="label">Opdrætter:</span> <span class="">Kommer snart!</span>
        </div>
        <div style="margin-top:10px;">
            <button class='btn btn-info' data-button-type='modal_activator' data-horse-id="" data-target='horse_linage'>Åben stamtavle</button>
        </div>
    </div>
</div>