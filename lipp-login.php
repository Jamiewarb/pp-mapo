<?php 
session_start();
## This path needs to be set ##
include_once("resources/includes/functions.php");
include_once("$sitepath/resources/includes/config.php");

$title = "LIPP - ";
$sel = "lipp-b"; // This decides which page in the sidebar is selected. Edit the sidebar.php include to check this value for each li

$scope = "openid email profile address https://uri.paypal.com/services/expresscheckout";
?>

<?php include "$sitepath/resources/includes/header.php"; 
	$scope = "openid email profile address https://uri.paypal.com/services/expresscheckout"; ?>
		<section>
			<?php include "$sitepath/resources/includes/sidebar.php"; ?>
			<div class="content">
				<h1>Log In with PayPal - Login Buttons</h1>
				<p>This button uses the automatically generated Javascript from developer: <a href="https://developer.paypal.com/docs/integration/direct/identity/button-js-builder/">JS Button Builder</a><br>
				<span id="lipp-button" class="lipp-button"></span></p>
				<p>This button has been manually implemented following the LIPP integration guide:<br>
				<span id="lipp-button-manual" class="lipp-button">
					<a href="#" onClick='popUpWindow("https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize?client_id=<?=$client_id?>&response_type=code&scope=<?=$scope?>&redirect_uri=http://localhost/mapo/resources/processing/lippprocess.php", 
										"dataitem", "400", "550", "toolbar=no,menubar=no,scrollbars=no,")'>
						<img src="https://www.paypalobjects.com/webstatic/en_US/developer/docs/lipp/loginwithpaypalbutton.png">
					</a>
				</span></p>
				<div class="seperator"></div>
				<?php if (isset($_SESSION['user_id'])) {
					echo "<div class=\"left\"><p>You're logged in as: <span class=\"highlight\">" . $_SESSION['user_name'] . "</span></p>";
					echo "<p>Your User ID is: <span class=\"highlight\">" . $_SESSION['user_id'] . "</span></p></div>";
					echo "<a href=\"/mapo/logout.php?re=lipp-login.php\" class=\"highlight right\">Logout</div>";
				} else {
					echo "<p>When you log in, your details will appear here.</p>";
				}?>
				<?php //<p class="nextpage"><a href="ecauth.php">Next Page >></a></p> ?>
			</div>
			<div class="clear"></div>
		</section>
	</body>
	<script src="https://www.paypalobjects.com/js/external/api.js"></script>
	<script>
	paypal.use( ["login"], function(login) {
		login.render ({
			"appid": "AXT7oxCdjqK3iPYVnb7SpISMhJeGquD_qs0lCAU1mk8Ivib2WHc8oOoW7efL",
			"authend": "sandbox",
			"scopes": "openid email profile address https://uri.paypal.com/services/expresscheckout",
			"containerid": "lipp-button",
			"locale": "en-gb",
			"returnurl": "http://localhost/mapo/resources/processing/lippprocess.php"
		});
	});
	</script>

	<script>
		function popUpWindow(url, title, w, h, params) {
			var left = (screen.width/2)-(w/2);
			var top = (screen.height/2)-(h/2);
			return window.open(url, title, params+'width='+w+', height='+h+', top='+top+', left='+left);
		}
		function lippComplete(message) {
			location.reload();
		}
	</script>

</html>