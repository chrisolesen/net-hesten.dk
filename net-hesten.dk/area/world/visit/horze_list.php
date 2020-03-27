<section data-zone="horses" class="visible">
	<div class="grid">
		<div data-section-type="info_square">
			<header><h1>Heste</h1></header>
			<div class="page_selector">
				<span class="btn">Side: <?= $your_horses_page + 1; ?></span>&nbsp;<a class="btn btn-info" href="?user=<?= $visit_id; ?>&your_horses_page=<?= $your_horses_page - 1; ?>&tab=idle_horses">Forrige side</a>&nbsp;<a class="btn btn-info" href="?user=<?= $visit_id; ?>&your_horses_page=<?= $your_horses_page + 1; ?>&tab=idle_horses">Næste side</a>
				<a class="btn btn-info" style="line-height: 30px;" data-button-type='modal_activator' data-target='filter_horses' >Filtre</a>
			</div>
		</div>
		<?php
		if (isset($horse_tabs['idle_horses']) && is_array($horse_tabs['idle_horses'])) {
			foreach ($horse_tabs['idle_horses'] as $horse) {
				echo $horse . PHP_EOL;
			}
		}
		?>
	</div>
</section>