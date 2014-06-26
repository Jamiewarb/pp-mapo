<?php
class PayPalAPI {
    	
	function PPHttpPostAPI($url_, $nvpreq, $client_id, $secret_id, $PayPalMode, $headers) {
		// Set up your mode and therefore your API Endpoint
		$paypalmode = ($PayPalMode == 'sandbox') ? '.sandbox' : '';
		$API_Endpoint = "https://api".$paypalmode.".paypal.com" . $url_;

		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
	
		if ($headers) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

		// Set the request as a POST FIELD for curl.
		if ($nvpreq) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq."&client_id=".$client_id."&client_secret=".$secret_id);
		} else {
			curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id=".$client_id."&client_secret=".$secret_id);
		}
	
		// Get response from the server.
		$httpResponse = curl_exec($ch);

		if(!$httpResponse) {
			exit("APIPost failed: ".curl_error($ch).'('.curl_errno($ch).')');
		}

		$httpParsedResponseAr = json_decode($httpResponse, true);

		return $httpParsedResponseAr;
	}

}
?>