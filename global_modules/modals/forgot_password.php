<div id="forgot_password" class="modal"><?php ?>
	<script>
		function forgot_password(caller) {

		}
	</script>
	<style>
		#forgot_password form > div:after {
			content:"";
			display: block;
			clear: both;
			height: 1em;
		}
		#forgot_password label {
			margin-right: 1em;
			height: 30px;
			line-height: 30px;
			float: left;
			text-align: right;
			width: 100px;
		}
		#forgot_password label + input {
			line-height: 30px;
			height: 30px;
			float: left;
		}
		#forgot_password form div + div {
			display: none;
		}
	</style>
	<div class="shadow"></div>
	<div class="content">
		<h2>Anmod om nyt password</h2>
		<p style="font-size: 14px;line-height: 1.5;">
			Vi har ikke mulighed for at sende dig dit kodeord, da de opbevares via såkaldt envejs kryptering.
		</p>
		<p style="font-size: 14px;line-height: 1.5;">
			Men hvis du taster din mail herunder, sender vi dig en ny tilfældig kode, som du så nemt kan rette selv inde i spillet.
		</p>
		<p style="font-size: 14px;line-height: 1.5;">
			Hvis du har glemt din mail, eller ikke længere har adgang til den, kan vi desværre nok ikke hjælpe dig, men du kan evt. prøve at skrive til tech@net-hesten.dk og bede om hjælp.
		</p>
		<p style="font-size: 14px;line-height: 1.5;">
			Hvis du ikke modtager en mail indenfor gangske kort tid, og højst et par timer, så fandtes din mail nok ikke i vores system, af hensyn til privatliv og sikkerhed, for du ikke advide her med det samme, om vi fandt et stutteri på mailen. 
		</p>
		<form id="forgot_password_form" action="" method="post">
			<input type="hidden" name="action" value="request_password" />
			<div><label for="request_quest">Email:</label><input type="text" id="request_quest" name="request_quest" /></div>
			<div><label for="request_email">Udfyld ikke:</label><input type="text" id="request_email" name="request_email" /></div>
			<input type="submit" class="btn btn-success" value="Anmod" name="submit">
		</form>
	</div>
</div>