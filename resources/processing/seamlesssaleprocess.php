<?php
session_start();
include_once("../includes/functions.php");

$PayPalReturnURL = 'http://localhost/mapo/resources/processing/seamlesssaleprocess.php'; //Point to process.php page
$PayPalCancelURL = 'http://localhost/mapo/seamlesssale.php?s=cancel'; //Cancel URL if user clicks cancel

$includes = "$sitepath/resources/includes/";
include_once($includes."config.php");
include_once($includes."paypal.class.php");

if ($_POST) {
	$ItemName = $_POST["product"]; //Item Name
	if ($ItemName == "madhat") {
		$ItemPrice 		= 18.99; //Item Price
		$ItemNumber 	= 1; //Item Number
		$ItemDesc 		= "The mad hatter's hat"; //Item Number
	} else if ($ItemName == "redtril") {
		$ItemPrice 		= 16.99;
		$ItemNumber 	= 2;
		$ItemDesc 		= "A nice red trilby";
	} else if ($ItemName == "superman") {
		$ItemPrice 		= 11.99;
		$ItemNumber 	= 3;
		$ItemDesc 		= "Superman's hat, if he wore a cap";
	} else if ($ItemName == "stetson") {
		$ItemPrice 		= 21.99;
		$ItemNumber 	= 4;
		$ItemDesc 		= "A cowboy's stetson hat";
	}
	$ItemQty 		= 1; // Item Quantity
	$ItemTotalPrice = ($ItemPrice * $ItemQty); //(Item Price x Quantity = Total) Get total amount of product;
	
	//Other important variables like tax, shipping cost
	$TotalTaxAmount 	= number_format((float)($ItemTotalPrice / 100 * 20), 2, '.', '');  //Sum of tax for all items in this order.
	$ShippingCost 		= 2.00; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
	
	//Grand total including all tax and shipping cost
	$GrandTotal = ($ItemTotalPrice + $TotalTaxAmount + $ShippingCost);

	$padata = 	'&RETURNURL='.urlencode($PayPalReturnURL).
				'&CANCELURL='.urlencode($PayPalCancelURL).
				'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
				
				'&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
				'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
				'&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
				'&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
				'&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).
				'&IDENTITYACCESSTOKEN='.urlencode($_SESSION['access_token']).
				
				/* 
				//Additional products (L_PAYMENTREQUEST_0_NAME0 becomes L_PAYMENTREQUEST_0_NAME1 and so on)
				'&L_PAYMENTREQUEST_0_NAME1='.urlencode($ItemName2).
				'&L_PAYMENTREQUEST_0_NUMBER1='.urlencode($ItemNumber2).
				'&L_PAYMENTREQUEST_0_DESC1='.urlencode($ItemDesc2).
				'&L_PAYMENTREQUEST_0_AMT1='.urlencode($ItemPrice2).
				'&L_PAYMENTREQUEST_0_QTY1='. urlencode($ItemQty2).
				*/
				
				/* 
				//Override the buyer's shipping address stored on PayPal, The buyer cannot edit the overridden address.
				'&ADDROVERRIDE=1'.
				'&PAYMENTREQUEST_0_SHIPTONAME=J Smith'.
				'&PAYMENTREQUEST_0_SHIPTOSTREET=1 Main St'.
				'&PAYMENTREQUEST_0_SHIPTOCITY=San Jose'.
				'&PAYMENTREQUEST_0_SHIPTOSTATE=CA'.
				'&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=US'.
				'&PAYMENTREQUEST_0_SHIPTOZIP=95131'.
				'&PAYMENTREQUEST_0_SHIPTOPHONENUM=408-967-4444'.
				*/
				
				'&NOSHIPPING=0'. //set 1 to hide buyer's shipping address, in-case products that does not require shipping
				
				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
				'&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
				'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippingCost).
				'&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
				'&LOCALECODE=GB'. //PayPal pages to match the language on your website.
				'&LOGOIMG=http://localhost/mapo/resources/images/pp_sig.png'. //site logo
				'&CARTBORDERCOLOR=0079C1'. //border color of cart
				'&ALLOWNOTE=1'; //Allow the buyer to add a note to the merchant

				## Set session variable we need later for "DoExpressCheckoutPayment" ##
				$_SESSION['ItemName'] 			=  $ItemName; //Item Name
				$_SESSION['ItemPrice'] 			=  $ItemPrice; //Item Price
				$_SESSION['ItemNumber'] 		=  $ItemNumber; //Item Number
				$_SESSION['ItemDesc'] 			=  $ItemDesc; //Item Number
				$_SESSION['ItemQty'] 			=  $ItemQty; // Item Quantity
				$_SESSION['ItemTotalPrice'] 	=  $ItemTotalPrice; //(Item Price x Quantity = Total) Get total amount of product; 
				$_SESSION['TotalTaxAmount'] 	=  $TotalTaxAmount;  //Sum of tax for all items in this order.
				$_SESSION['ShippingCost'] 		=  $ShippingCost; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
				$_SESSION['GrandTotal'] 		=  $GrandTotal;

	//We need to execute the "SetExpressCheckOut" method to obtain paypal token
	$paypal = new MyPayPal();
	$httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

	$paypalmode = ($PayPalMode == 'sandbox') ? '.sandbox' : '';
	//Respond according to message we receive from Paypal
	if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
		//Redirect user to PayPal store with Token received.
		$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
		header('Location: '.$paypalurl);
	} else {
		//Show error message
		echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
		echo '<pre>';
		print_r($httpParsedResponseAr);
		echo '</pre>';
	}

}

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if (isset($_GET["token"]) && isset($_GET["PayerID"])) {
	//we will be using these two variables to execute the "DoExpressCheckoutPayment"
	//Note: we haven't received any payment yet.

	$token = $_GET["token"];
	$payer_id = $_GET["PayerID"];
	
	//get session variables
	$ItemName 			= $_SESSION['ItemName']; //Item Name
	$ItemPrice 			= $_SESSION['ItemPrice'] ; //Item Price
	$ItemNumber 		= $_SESSION['ItemNumber']; //Item Number
	$ItemDesc 			= $_SESSION['ItemDesc']; //Item Number
	$ItemQty 			= $_SESSION['ItemQty']; // Item Quantity
	$ItemTotalPrice 	= $_SESSION['ItemTotalPrice']; //(Item Price x Quantity = Total) Get total amount of product; 
	$TotalTaxAmount 	= $_SESSION['TotalTaxAmount'] ;  //Sum of tax for all items in this order.
	$ShippingCost 		= $_SESSION['ShippingCost']; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
	$GrandTotal 		= $_SESSION['GrandTotal'];

	$padata = 	'&TOKEN='.urlencode($token).
				'&PAYERID='.urlencode($payer_id).
				'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
				
				//set item info here, otherwise we won't see product details later	
				'&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
				'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
				'&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
				'&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
				'&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).

				/* 
				//Additional products (L_PAYMENTREQUEST_0_NAME0 becomes L_PAYMENTREQUEST_0_NAME1 and so on)
				'&L_PAYMENTREQUEST_0_NAME1='.urlencode($ItemName2).
				'&L_PAYMENTREQUEST_0_NUMBER1='.urlencode($ItemNumber2).
				'&L_PAYMENTREQUEST_0_DESC1=Description text'.
				'&L_PAYMENTREQUEST_0_AMT1='.urlencode($ItemPrice2).
				'&L_PAYMENTREQUEST_0_QTY1='. urlencode($ItemQty2).
				*/

				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
				'&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
				'&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($ShippingCost).
				'&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);
	
	//We need to execute the "DoExpressCheckoutPayment" at this point to receive payment from user.
	$paypal = new MyPayPal();
	$httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
	
	//Check if everything went ok..
	if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {

		echo '<h2>Success</h2>';
		echo 'Your Transaction ID : '.urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
		
		/*
		//Sometimes Payment are kept pending even when transaction is complete. 
		//hence we need to notify user about it and ask him manually approve the transaction
		*/
		
		if ('Completed' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {
			echo '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
		} elseif ('Pending' == $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"]) {
			echo '<div style="color:red">Transaction Complete, but payment is still pending! '.
			'You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
		}

		// we can retrive transaction details using either GetTransactionDetails or GetExpressCheckoutDetails
		// GetTransactionDetails requires a Transaction ID, and GetExpressCheckoutDetails requires Token returned by SetExpressCheckOut
		$padata = '&TOKEN='.urlencode($token);
		$paypal = new MyPayPal();
		$httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

		if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
			
			echo "<script>",
					"(function(d, s, id) {",
						"var js, ref = d.getElementsByTagName(s)[0];",
						"if (!d.getElementById(id)) {",
							"js = d.createElement(s); js.id = id; js.async = true;",
							"js.src = \"//www.paypalobjects.com/js/external/paypal.v1.js\";",
							"ref.parentNode.insertBefore(js, ref);",
						"}",
					"}(document, \"script\", \"paypal-js\"));",
				"</script>";

			$saved = 1;

			$payer_id = urldecode($httpParsedResponseAr['PAYERID']);
			$product = urldecode($httpParsedResponseAr['L_NAME0']);
			$token = urldecode($httpParsedResponseAr['TOKEN']);
			$type = "Sale";
			$trans_id = urldecode($httpParsedResponseAr['PAYMENTREQUESTINFO_0_TRANSACTIONID']);
			$amt = urldecode($httpParsedResponseAr['AMT']);
			$address = urldecode($httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOSTREET']);
			$address .= ",".urldecode($httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOCITY']);
			$address .= ",".urldecode($httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOSTATE']);
			$address .= ",".urldecode($httpParsedResponseAr['PAYMENTREQUEST_0_SHIPTOZIP']);

			if ($stmt = $mysqli->prepare("SELECT `id` FROM `seamlesstrans` WHERE `trans_id` = ?;")) {
				$stmt->bind_param("s", $trans_id);
				$stmt->execute();
				$stmt->store_result();
				if ($stmt->num_rows > 0) {
					echo "<br><div>Transaction has already been saved. It has not been saved again.</div>";
					$saved = 1;
				} else {
					$saved = 0;
				}
				$stmt->close();
			} else {
				echo "<br><div style=\"color:red\">Error: Select transaction failed!<br>".$mysqli->error."</div>";
			}

			$dt = urldecode($httpParsedResponseAr['TIMESTAMP']);
			$dt_explode = explode("T", $dt);
			$dt = $dt_explode[0] . " " . $dt_explode[1];
			$dt = substr($dt, 0, -1);

			if ($saved == 0) {
				if ($stmt = $mysqli->prepare("INSERT INTO `seamlesstrans` (`payer_id`, `product`, `token`, `type`, `trans_id`, `amt`, `address`, `datetime`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);")) {
				    $stmt->bind_param('ssssssss', $payer_id, $product, $token, $type, $trans_id, $amt, $address, $dt);
				    if ($stmt->execute()) {
				    	echo "<br><div style=\"color:green\">This transaction has been successfully saved to the database!</div>";
				    }
					$stmt->close();
				}
			}
			if ($mysqli->error) {
				echo $mysqli->error;
			}

			echo '<br><b>Stuff to store in database :</b><br>';

			echo '<pre>';
			print_r($httpParsedResponseAr);
			echo '</pre>';
		} else  {
			echo '<div style="color:red"><b>GetTransactionDetails failed:</b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($httpParsedResponseAr);
			echo '</pre>';

		}

	} else {
			echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($httpParsedResponseAr);
			echo '</pre>';
	}
}
?>