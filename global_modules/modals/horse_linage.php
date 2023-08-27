<div id="horse_linage" class="modal" style="z-index: 7;"><?php /* */ ?>
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
				path = '//files.<?= HTTP_HOST; ?>';
				if (linage_data.hasOwnProperty('parents')) {
					jQuery("#horse_linage .parents .father .name").html(linage_data.parents.father.name + '<br />' + linage_data.parents.father.id);
					jQuery("#horse_linage .parents .mother .name").html(linage_data.parents.mother.name + '<br />' + linage_data.parents.mother.id);
					jQuery("#horse_linage .parents .father a img").attr('src', path + linage_data.parents.father.image);
					jQuery("#horse_linage .parents .father a").attr('data-horse-id', linage_data.parents.father.id);
					jQuery("#horse_linage .parents .mother a img").attr('src', path + linage_data.parents.mother.image);
					jQuery("#horse_linage .parents .mother a").attr('data-horse-id', linage_data.parents.mother.id);
				}

				if (linage_data.hasOwnProperty('siblings')) {
					jQuery('.sibling_holder').removeClass('hide');
					if (linage_data.siblings.hasOwnProperty('mother')) {
						jQuery.each(linage_data.siblings.mother, function(index, value) {
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
								"<img src='//files.<?= HTTP_HOST; ?>" + this.image + "'/></a>";
							horse_square += "<div class='name'>" + this.name + '<br />' + this.id + '</div>';
							horse_square += '</div>';
							jQuery("#horse_linage .msiblings").append(horse_square);
							ready_modal_activator("[data-horse-id='" + this.id + "']");
						});
					}
					if (linage_data.siblings.hasOwnProperty('full')) {
						jQuery.each(linage_data.siblings.full, function(index, value) {
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
								"<img src='//files.<?= HTTP_HOST; ?>" + this.image + "'/></a>";
							horse_square += "<div class='name'>" + this.name + '<br />' + this.id + '</div>';
							horse_square += '</div>';
							jQuery("#horse_linage .siblings").append(horse_square);
							ready_modal_activator("[data-horse-id='" + this.id + "']");
						});
					}
					if (linage_data.siblings.hasOwnProperty('father')) {
						jQuery.each(linage_data.siblings.father, function(index, value) {
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
								"<img src='//files.<?= HTTP_HOST; ?>" + this.image + "'/></a>";
							horse_square += "<div class='name'>" + this.name + '<br />' + this.id + '</div>';
							horse_square += '</div>';
							jQuery("#horse_linage .fsiblings").append(horse_square);
							ready_modal_activator("[data-horse-id='" + this.id + "']");
						});
					}
				} else {
					jQuery('.sibling_holder').addClass('hide');
				}
				if (linage_data.hasOwnProperty('children')) {
					jQuery('.child_holder').removeClass('hide');
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
							"<img src='//files.<?= HTTP_HOST; ?>" + this.image + "'/></a>";
						horse_square += "<div class='name'>" + this.name + '<br />' + this.id + '</div>';
						horse_square += '</div>';
						jQuery("#horse_linage .children").append(horse_square);
						ready_modal_activator("[data-horse-id='" + this.id + "']");
					});
				} else {
					jQuery('.child_holder').addClass('hide');
				}
				if (linage_data.self.gender == 'female') {
					gender_icon = 'venus';
				} else {
					gender_icon = 'mars';
				}
				horse_square = '';
				horse_square += '<i class="gender_icon fa fa-' + gender_icon + ' fa-2x" style="color:black;display:block;"></i>';
				horse_square += "";
				horse_square += "<img src='//files.<?= HTTP_HOST; ?>" + linage_data.self.image + "'/>";
				horse_square += "<div class='name'>" + linage_data.self.name + '<br />' + linage_data.self.id + '</div>';
				jQuery("#horse_linage .self").append(horse_square);
				jQuery("#horse_linage .self_name").html(linage_data.self.name);
			});
		}
	</script>
	<div class="shadow"></div>
	<div class="content" style="min-width: 400px;">
		<h2>Stamtavle</h2>
		<div class="top_line">
			<div>
				<h3 class="self_name"></h3>
				<div class="self"></div>
			</div>
			<div class="parents_holder">
				<h3>Forældre</h3>
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
			</div>
		</div>
		<div class="sibling_holder">
			<h3>Søskende</h3>
			<div class="siblings"></div>
		</div>
		<div class="child_holder">
			<h3>Afkom</h3>
			<div class="children"></div>
		</div>
	</div>
</div>