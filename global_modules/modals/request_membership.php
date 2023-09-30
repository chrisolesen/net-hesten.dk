<div id="request_membership" class="modal"><?php /* */ ?>
	<script>
		function request_membership(caller) {

		}
	</script>
	<style>
		#request_membership form > div:after {
			content:"";
			display: block;
			clear: both;
			height: 1em;
		}
		#request_membership label {
			margin-right: 1em;
			height: 30px;
			line-height: 30px;
			float: left;
			text-align: right;
			width: 100px;
		}
		#request_membership label + input {
			line-height: 30px;
			height: 30px;
			float: left;
		}
		#request_membership form div + div + div + div + div {
			display: none;
		}
	</style>
	<div class="shadow"></div>
	<div class="content">
		<h2>Anmod om et stutteri</h2>
		<p style="font-size: 14px;line-height: 1.5;">
			Her kan du oprette en bruger på Net-hesten. Du skal udfylde felterne nedenunder for at ansøge om medlemskab. 
		</p>
		<p style="font-size: 14px;line-height: 1.5;">
			Det er vigtigt, at du skriver en gyldig email-adresse. Du modtager en mail, hvor du skal bekræfte din ansøgning, og at du ejer den mailkonto du har angivet.
		</p>
		<p style="font-size: 14px;line-height: 1.5;">
			Vi godkender nye brugere manuelt, og bestræber os på at godkende brugere inden for 24 timer, oftest hurtigere. Du skal dog klikke på linket, til godkendelse af mailen først.
		</p>
		<p style="font-size: 14px;line-height: 1.5;">
			Dette gør vi for bedst muligt, at kunne sikre, at net-hesten forbliver et rart sted at være, for alle parter :-)
		</p>
		<p style="font-size: 14px;line-height: 1.5;">
			Mens du venter på at blive godkendt, er det en god idé, at læse vores <a style="text-decoration:underline;" href="/infosheets/regler.php">Regler</a> og <a style="text-decoration:underline;" href="/infosheets/help.php">Hjælpe-side</a>.
		</p>
		<form id="request_membership_form" action="" method="post">
			<input type="hidden" name="action" value="request_membership" />
			<div><label for="request_username">Stutterinavn:</label><input type="text" id="request_username" name="request_username" /></div>
			<div><label for="request_name">Dit navn:</label><input type="text" id="request_name" name="request_name" /></div>
			<div><label for="request_quest">Email:</label><input type="text" id="request_quest" name="request_quest" /></div>
			<div><label for="request_password">Kode:</label><input type="password" id="request_password" name="request_password" /></div>
			<div><label for="request_email">Udfyld ikke:</label><input type="text" id="request_email" name="request_email" /></div>
			<input type="submit" class="btn btn-success" value="Anmod" name="submit">
		</form>
	</div>
</div>
