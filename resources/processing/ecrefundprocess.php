<?php
session_start();
include_once("../includes/functions.php");

$includes = "$sitepath/resources/includes/";
include_once($includes."config.php");
include_once($includes."paypal.class.php");

if (isset($_GET['transid'])) {
	$trans_id = $_GET['transid'];
	$padata = 	'&TRANSACTIONID='.urlencode($trans_id).
				'&REFUNDTYPE='.urlencode("Full");

	$_SESSION['TransactionID']	=  $trans_id;

	//Now we execute the 'Refund Transaction'
	$paypal = new MyPayPal();
	$httpParsedResponseAr = $paypal->PPHttpPost('RefundTransaction', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

	$paypalmode = ($PayPalMode == 'sandbox') ? '.sandbox' : '';
	//Respond according to message we receive from Paypal
	if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
		
		if ($stmt = $mysqli->prepare("UPDATE `transactions` SET `type` = \"Refunded\" WHERE `trans_id` = ?;")) {
			$stmt->bind_param("s", $trans_id);
			$stmt->execute();
			$stmt->close();
		} else {
			echo "<br><div style=\"color:red\">Error: Update transaction type failed!<br>".$mysqli->error."</div>";
		}
		echo '<h2>Success</h2>';
		echo 'Your Refund Transaction ID : '.urldecode($httpParsedResponseAr["REFUNDTRANSACTIONID"]);
		echo "<div style=\"color:green\">Successfully Refunded Payment, and updated database</div>";
		echo "<p>Below is the response:</p>";

		echo '<pre>';
		print_r($httpParsedResponseAr);
		echo '</pre>';
	} else {
		echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
		echo '<pre>';
		print_r($httpParsedResponseAr);
		echo '</pre>';
	}

}
?>