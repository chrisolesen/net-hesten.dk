<?php

$basepath = '../../../..';
$title = 'Afstemninger';
require "$basepath/net-hesten.dk/area/world/elements/header.php";
require "$basepath/app_core/object_loader.php";

if (!in_array('global_admin', $_SESSION['rights']) && !in_array('admin_panel_access', $_SESSION['rights'])) {
	ob_end_clean();
	header('Location: /');
}
?>
<style>

</style>
<section>
</section>	

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

require "$basepath/m.net-hesten.dk/area/world/elements/footer.php";
