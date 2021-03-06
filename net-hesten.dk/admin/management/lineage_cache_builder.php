<?php

$basepath = '../../..';
$responsive = true;
require "{$basepath}/app_core/object_loader.php";
require "{$basepath}/global_modules/header.php";
?>
<?php

if (!is_array($_SESSION['rights']) || !in_array('tech_admin', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
	exit();
}
?>
<section id="error_logs_page">
	<header>
		<h2 class="raised">Build lineage cache</h2>
	</header>
	<button class='foel btn btn-info compact_top_button' data-button-type='modal_activator' data-horse-id="650590" data-target='horse_linage'>Stamtræ</button>
</section>
<div id="horse_linage" class="modal">
	<script>
		function horse_linage(caller) {
			horse_id = jQuery(caller).attr('data-horse-id');
			jQuery.get({
				url: "//ajax.<?= HTTP_HOST; ?>/index.php?request=fecth_linage&horse_id=" + horse_id,
				cache: false
			}).then(function(data) {
				linage_data = false;
				jQuery("#horse_linage .children, #horse_linage .self").html('');
				jQuery("#horse_linage .father a img,#horse_linage .mother a img").attr('src', '');
				jQuery("#horse_linage .father a,#horse_linage .mother a").attr('data-horse-id', '');
				jQuery("#horse_linage .father .name,#horse_linage .mother .name").html('');


				linage_data = JSON.parse(data);

				jQuery(".linage_display_zone").html(data);
				path = "//".filter_input(INPUT_SERVER, 'HTTP_HOST');
				if (linage_data.hasOwnProperty('parents')) {
					jQuery("#horse_linage .parents .father .name").html(linage_data.parents.father.name + '<br />' + linage_data.parents.father.id);
					jQuery("#horse_linage .parents .mother .name").html(linage_data.parents.mother.name + '<br />' + linage_data.parents.mother.id);
					jQuery("#horse_linage .parents .father a img").attr('src', path + linage_data.parents.father.image);
					jQuery("#horse_linage .parents .father a").attr('data-horse-id', linage_data.parents.father.id);
					jQuery("#horse_linage .parents .mother a img").attr('src', path + linage_data.parents.mother.image);
					jQuery("#horse_linage .parents .mother a").attr('data-horse-id', linage_data.parents.mother.id);
				}
				if (linage_data.hasOwnProperty('children')) {
					jQuery.each(linage_data.children, function(index, value) {
						if (this.gender == 'female') {
							gender_icon = 'venus';
						} else {
							gender_icon = 'mars';
						}
						horse_square = '';
						horse_square += '<div class="child">';
						horse_square += '<i class="gender_icon fa fa-' + gender_icon + ' fa-2x" style="color:black;display:block;"></i>';
						horse_square += "<a data-button-type='modal_activator' data-target='horse_linage' " +
							"data-horse-id='" + this.id + "' >" +
							"<img src='//<?= filter_input(INPUT_SERVER ,'HTTP_HOST');?>" + this.image + "'/></a>";
						horse_square += "<div class='name'>" + this.name + '<br />' + this.id + '</div>';
						horse_square += '</div>';
						jQuery("#horse_linage .children").append(horse_square);
						ready_modal_activator("[data-horse-id='" + this.id + "']");
					});
				}
				if (linage_data.self.gender == 'female') {
					gender_icon = 'venus';
				} else {
					gender_icon = 'mars';
				}
				horse_square = '';
				horse_square += '<i class="gender_icon fa fa-' + gender_icon + ' fa-2x" style="color:black;display:block;"></i>';
				horse_square += "";
				horse_square += "<img src='//<?= filter_input(INPUT_SERVER ,'HTTP_HOST');?>" + linage_data.self.image + "'/>";
				horse_square += "<div class='name'>" + linage_data.self.name + '<br />' + linage_data.self.id + '</div>';
				jQuery("#horse_linage .self").append(horse_square);
			});
		}
	</script>
	<style>
		#horse_linage .child,
		#horse_linage .mother,
		#horse_linage .self,
		#horse_linage .father {
			display: inline-block;
			margin: 10px;
			height: 160px;
			width: 120px;
			position: relative;
			cursor: pointer;
		}

		#horse_linage .parents {
			text-align: center;
			margin: 0 auto;
			width: 290px;
		}

		#horse_linage .self {
			margin: 20px 0;
			text-align: center;
		}

		#horse_linage .content {
			text-align: center;
			width: 600px;
		}

		#horse_linage .gender_icon {
			position: absolute;
			top: -15px;
			right: -15px;
		}

		#horse_linage img {
			max-height: 120px;
			max-width: 120px;
		}

		.name {
			font-weight: bold;
		}
	</style>
	<div class="shadow"></div>
	<div class="content" style="min-width: 400px;">
		<h2>Stamtræ</h2>
		<div class="parents">
			<div class="father"><i class="gender_icon fa fa-mars fa-2x" style="color:black;display:block;"></i>
				<a data-button-type='modal_activator' data-horse-id="" data-target='horse_linage'><img src="" /></a>
				<span class="name"></span>
			</div>
			<div class="mother"><i class="gender_icon fa fa-venus fa-2x" style="color:black;display:block;"></i>
				<a data-button-type='modal_activator' data-horse-id="" data-target='horse_linage'><img src="" /></a>
				<span class="name"></span>
			</div>
		</div>
		<div class="self"></div>
		<div class="children"></div>

	</div>
</div>
</div>
<?php

require "{$basepath}/global_modules/footer.php";
