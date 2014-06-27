<?php
include_once "resources/includes/functions.php";
$title = "";
?>

<?php include "$sitepath/resources/includes/header.php"; ?>

	<section>
		<div class="one-cont">
			<ul class="boxes grey">
				<li><div class="container">
					<header><span class="head count left">4</span><a href="/mapo/ec.php">Products</a></header>
					<ul>
						<li><a href="ec.php">Express Checkout</a></li>
						<li><a href="lipp.php">LIPP</a></li>
						<li><a href="icc.php">In-Context Checkout</a></li>
					</ul>
				</div></li>
				<li><div class="container">
					<header><span class="head count left">4</span><a href="/mapo/api.php">APIs</a></header>
					<ul>
						<li><a href="api-setec.php">SetExpressCheckout</a></li>
						<li><a href="api-getec.php">GetExpressCheckoutDetails</a></li>
						<li><a href="api-doec.php">DoExpressCheckoutPayment</a></li>
					</ul>
				</div></li>
			</ul>
		</div>
	</section>

<?php include "$sitepath/resources/includes/footer.php"; ?>