<?php if (in_array('tech_admin', ($_SESSION['rights'] ?? []))) { ?>
	<style>
		@media all and (max-width: 900) {
			.json_feedback_modal {
				display: none !important;
			}
		}

		.json_feedback_modal.active {
			transition: all 0.2s linear;
			opacity: 1;
		}

		.json_feedback_modal {
			opacity: 0;
			pointer-events: none;
			z-index: 2;
			position: absolute;
			bottom: 25px;
			right: 0;
			height: 50px;
			width: 400px;
			background-color: rgba(51, 51, 51, 0.8);
			padding: 15px 15px;
			color: white;
			overflow: hidden;
			border-radius: 15px 0 0 0;
			transition: all 1s linear;
		}
	</style>
	<div class="json_feedback_modal">
	</div>
<?php } ?>