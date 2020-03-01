<div id="unprovoked_bid" class="modal"><?php /* */ ?>
    <script>
		function unprovoked_bid(caller) {
			$('#unprovoked_bid img').attr('src', $(caller).parent().parent().find('.zoom_img').attr('src'));
			$('#unprovoked_bid input[name="horse_id"]').attr('value', $(caller).parent().parent().attr('data-horse-id'));
			$('#unprovoked_bid .name').html($(caller).parent().parent().find('.name').html());
			$('#unprovoked_bid .visited_name').html($('#visited_name').html());
		}
    </script>
    <style>

    </style>
    <div class="shadow"></div>
    <div class="content">
        <h2>Byd på <span class="name"></span></h2>
		<div>
			<div style="position:relative;float:left;width:160px;height: 220px">
				<img style="max-width: 100%;max-height: 100%;width:auto;height:auto;position: absolute;left: 50%;top: 50%;transform: translateX(-50%) translateY(-50%);" src='' />
			</div>
			<div style="position:relative;float:left;width:300px;padding-left:20px;padding-top: 10px;">
				<form action="" method="post">
					<input type="hidden" name="action" value="request_private_trade" />
					<input type="hidden" name="horse_id" value="" />
					<p style="font-size:14px;">Når du byder en hest, bliver wkr reserveret med det samme.</p>
					<p style="font-size:14px;">Du for først hesten, hvis <span class="visited_name"></span> accepterer din anmodning.</p>
					<p style="font-size:14px;">Du for alle dine wkr igen, hvis din anmodning bliver afvist.</p>
					<p style="font-size:14px;">Du kan altid selv annulerer anmodningen, det koster 500wkr, men resten for du igen.</p>
					<input type="text" name="bid_amount" placeholder="Angiv wkr" />
					<input class="btn btn-success" type="submit" name="bid" value="Byd" />
				</form>
			</div>
		</div>
	</div>
</div>