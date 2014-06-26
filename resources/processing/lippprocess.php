	<?php 
session_start();
include_once("../includes/functions.php");

$includes = "$sitepath/resources/includes/";
include_once($includes."config.php");
include_once($includes."paypalapi.class.php");

// Was the LIPP successful?
if (isset($_GET['code']) && isset($_GET['scope'])) {

	$PayPalMode = "sandbox";
	$grant="authorization_code";
	$code = $_GET['code']; // The access token returned from LIPP
	$redirect_uri = "http://localhost/mapo/resources/processing/lippprocess.php";

	$uri = "/v1/identity/openidconnect/tokenservice";

	$data = "grant_type=".$grant.
			"&code=".$code.
			"&redirect_uri=".$redirect_uri;

	$paypal = new PayPalAPI();
	$httpParsedResponseAr = $paypal->PPHttpPostAPI($uri, $data, $client_id, $secret_id, $PayPalMode, null);

	if (isset($httpParsedResponseAr['expires_in']) && isset($httpParsedResponseAr['token_type'])) {
		$_SESSION['token_type'] = $httpParsedResponseAr['token_type'];
		$_SESSION['expires_in'] = $httpParsedResponseAr['expires_in'];
		$_SESSION['refresh_token'] = $httpParsedResponseAr['refresh_token'];
		$_SESSION['id_token'] = $httpParsedResponseAr['id_token'];
		$_SESSION['access_token'] = $httpParsedResponseAr['access_token'];
		
		$schema = "openid";
		$uri = "/v1/identity/openidconnect/userinfo/?schema=".$schema;

		$data = false;

		$headers = [
				0 => 'Content-type: application/json',
				1 => 'Authorization: Bearer '.$httpParsedResponseAr['access_token'],
		];
		echo "Test-2";

		$paypal = new PayPalAPI();
		$httpParsedResponseAr = $paypal->PPHttpPostAPI($uri, $data, $client_id, $secret_id, $PayPalMode, $headers);
		echo "Test-1";
		if (isset($httpParsedResponseAr['user_id'])) {
			$_SESSION['email'] = $httpParsedResponseAr['email'];
			$_SESSION['user_id'] = $httpParsedResponseAr['user_id'];
			$_SESSION['address'] = $httpParsedResponseAr['address'];
			$_SESSION['user_name'] = $httpParsedResponseAr['name'];
			$_SESSION['given_name'] = $httpParsedResponseAr['given_name'];
			echo "Test";
			echo "<script>",
				"opener.lippComplete(\"success\");",
				"window.close();",
				"</script>";
		} else {
			echo "<div style='color:red'>There was an error getting your details. Please try again.</div>";
		}

	} else {
		echo "<div style=\"color:red\"><strong>Error:<strong> Couldn't retrieve the access token</div>";
		echo "<p>Below is the returned response:</p>";
		echo "<pre>";
		print_r($_GET);
		echo "</pre>";
	}
} else {
	echo "<div style=\"color:red\"><strong>Error:<strong> LIPP was unsuccessful</div>";
	echo "<p>Below is the returned response:</p>";
	echo "<pre>";
	print_r($_GET);
	echo "</pre>";
}

?>