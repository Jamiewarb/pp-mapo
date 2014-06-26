<?php 
session_start();
include_once("resources/includes/functions.php");
//APP-80W284485P519543T - This is the AppID for Sandbox Tests when an AppID is required
//https://api-3t.sandbox.paypal.com/nvp - The end point for name value pair when using a signature
$title = "EC - "; 
$sel = "ec"; // This decides which page in the sidebar is selected. Edit the sidebar.php include to check this value for each li
?>

<?php include "$sitepath/resources/includes/header.php"; ?>
		<section>
			<?php include "$sitepath/resources/includes/sidebar.php"; ?>
			<div class="content">
				<h1>Express Checkout</h1>
				<p>There are two flavours of EC - EC Shortcut (ECS) and EC Mark (ECM).</p>
				<p>ECS is when you Checkout with PayPal, from the basket page. The user is taken to PayPal, where they log in, and then
				PayPal returns the user's details, including their shipping info, which the merchant can then use.
				In this flavour of EC, the buyer is able to select an address from their PayPal account on the confirm page.</p>
				<p>ECM, however, is where a PayPal Mark is placed at the end of a checkout process, usually accompanied by a pay by card method
				too. This means the merchant passes us the buyer's information that they have stored on their website, and the buyer is unable to 
				change the address on the confirmation page.</p>
				<p class="centered"><img src="https://www.paypalobjects.com/webstatic/en_US/developer/docs/ec/overview-ec-howecworks.gif" alt="Example of PayPal Checkout Integration"></p>
				<p>The test integrations on these next few pages utilise Sandbox, and as such do not affect real, live accounts.</p>
				<p class="nextpage"><a href="/mapo/ecsale.php">Sale Transaction >></a></p>
			</div>
			<div class="clear"></div>
		</section>
	</body>
	
</html>