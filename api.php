<?php
include_once "resources/includes/functions.php";
$title = "";
?>

<?php include "$sitepath/resources/includes/header.php"; ?>

	<section>
		<div class="one-cont">
			<h1 class="headline">APIs</h1>
			<div class="area blue">
				<div class="inside">
					<div class="contain">
						<p>Express Checkout</p>
						<div class="box white">
							<ul>
								<li><a href="api-setec">SetExpressCheckout</a></li>
								<li><a href="#">GetExpressCheckoutDetails</a></li>
								<li><a href="#">DoExpressCheckoutPayment</a></li>
							</ul>
						</div>
					</div>
					<div class="contain">
						<p>Reference Transactions</p>
						<div class="box white">
							<ul>
								<li><a href="#">CreateBillingAgreement</a></li>
								<li><a href="#">DoReferenceTransaction</a></li>
							</ul>
						</div>
					</div>
					<div class="contain">
						<p>MassPay</p>
						<div class="box white">
							<ul>
								<li><a href="#">MassPay</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="clear"></div>
				<div class="inside">
					<div class="contain">
						<p>Recurring Payment</p>
						<div class="box white">
							<ul>
								<li><a href="#">CreateRecurringPaymentsProfile</a></li>
								<li><a href="#">GetRecurringPaymentsProfileDetails</a></li>
								<li><a href="#">UpdateRecurringPaymentsProfile</a></li>
								<li><a href="#">ManageRecurringPaymentsProfileStatus</a></li> 
							</ul>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</section>

<?php include "$sitepath/resources/includes/footer.php"; ?>