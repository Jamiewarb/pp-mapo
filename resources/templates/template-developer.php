<?php 
session_start();
include_once("../includes/functions.php"); ## This path needs to be set ##
//APP-80W284485P519543T - This is the AppID for Sandbox Tests when an AppID is required
//https://api-3t.sandbox.paypal.com/nvp - The end point for name value pair when using a signature
$title = "Template - ";
$sel = "template"; // This decides which page in the sidebar is selected. Edit the sidebar.php include to check this value for each li
?>

<?php include "$sitepath/resources/includes/header.php"; ?>
		<section>
			<?php include "$sitepath/resources/includes/sidebar.php"; ?>
			<div class="content">
				<h1>Title</h1>
				<p>Text goes here</p>
				<p class="nextpage"><a href="ecauth.php">Next Page >></a></p>
			</div>
			<div class="clear"></div>
		</section>
	</body>
	
</html>