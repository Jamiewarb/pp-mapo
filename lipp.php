<?php 
session_start();
## This path needs to be set ##
include_once("resources/includes/functions.php");
include_once("$sitepath/resources/includes/config.php");

$title = "LIPP - ";
$sel = "lipp"; // This decides which page in the sidebar is selected. Edit the sidebar.php include to check this value for each li

$scope = "openid email profile address https://uri.paypal.com/services/expresscheckout";
?>

<?php include "$sitepath/resources/includes/header.php"; 
	$scope = "openid email profile address https://uri.paypal.com/services/expresscheckout"; ?>
		<section>
			<?php include "$sitepath/resources/includes/sidebar.php"; ?>
			<div class="content">
				<h1>Log In with PayPal</h1>
				<p>Log In with PayPal is a secure sign-on system that utilizes cutting-edge security standards, to manage the customer log in process; the merchant doesn't have to store user data on their system.</p>
				<p>It also gives the added benefit of enabling Seamless Checkout, which, once the user is logged in through LIPP, enables the customer to use those credentials to quickly, and seamlessly, checkout items in a cart.</p>
				<h2>User Experience</h2>
				<p>When a customer clicks the Log In with PayPal button, a small browser window appears for the customer to log in directly to PayPal servers. See the <a href="https://lipp.ebaystratus.com/loginwithpaypal-live/">demo site</a>.</p>
				<p>When they login, they are presented with a screen asking them to agree to share their information with the merchant, and links to the merchant's Privacy Policy and User Agreement pages.</p>
				<p>Once the user accepts, PayPal returns a one-time authorization code which the merchant can use to get an access code. This is used to retrieve the user's information, as well as activating seamless checkout.</p>
				<h2>Benefits</h2>
				<p>PayPal Identity frees the merchant from the need to implement a customer-management system. The Identity API manages and verifies their customer account data for them. This lets them focus on the important details of their business website: their business transactions.</p>
				<h2>LIPP Developer Documentation</h2>
				<p><a href="https://developer.paypal.com/docs/integration/direct/identity/log-in-with-paypal/">https://developer.paypal.com/docs/integration/direct/identity/log-in-with-paypal/</a></p>
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