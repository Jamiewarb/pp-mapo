<?php $pagename = basename($_SERVER['PHP_SELF']); ?>

<?php
include_once("resources/includes/functions.php");
include_once("$sitepath/resources/includes/config.php");

$scope = "openid email profile address https://uri.paypal.com/services/expresscheckout";
?>

<!DOCTYPE html>
<html>

	<head>
		<title><?=$title?> JW MaPO Test Suite</title>
		<link rel="stylesheet" type="text/css" href="/mapo/resources/css/style.css" />
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	</head>

	<body>
		<header>
			<div class="container">
				<a href="/mapo/"><img class="header-image" src="/mapo/resources/images/pp_sig.png" alt=""></a> <span class="title-text"><?=$title?>JW MaPO Test Suite</span> 
				<span class="right">
					
					<a href="#openModal" class="cred">Credentials</a>
					<?php if (isset($_SESSION['user_id'])) {
						echo '<ul class="dropdown-root">
							<li>
								'.$_SESSION['user_name'].'<div class="seperator"></div><i class="fa fa-caret-down"></i>
								<ul>
									<a href="/mapo/logout.php?re='.$pagename.'"><li>Logout</li></a>
								</ul>
							</li>
						</ul>';
					} else {
						echo "<a href=\"#\" onClick='popUpWindow(\"https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize?client_id=".$client_id."&response_type=code&scope=".$scope."&redirect_uri=http://localhost/mapo/resources/processing/lippprocess.php\", 
										\"dataitem\", \"400\", \"550\", \"toolbar=no,menubar=no,scrollbars=no,\")'>",
								"<img src=\"https://www.paypalobjects.com/webstatic/en_US/developer/docs/lipp/loginwithpaypalbutton.png\">",
							"</a>";
					} ?>
				</span>
				<div class="clear"></div>
			</div>
		</header>

		<div id="openModal" class="modalDialog">
			<div>
				<a href="#close" title="Close" class="modal-close">X</a>
				<h2>Classic Test API Credentials</h2>
				<ul class="credentials unstyled">
					<li><span class="field-label">Username:</span> jawarburton-facilitator_api1.paypal.com</li>
					<li><span class="field-label">Password:</span> 1403020202</li>
					<li><span class="field-label">Signature:</span> Ayeswnxh.fFaRbZUz0c6edE5rf1qAiwsh7e-XrGK9wZPGd76wvKwWg7a</li>
					<br>
					<li><span class="field-label">Endpoint:</span> api.sandbox.paypal.com</li>
					<li><span class="field-label">Client ID:</span> AXT7oxCdjqK3iPYVnb7SpISMhJeGquD_qs0lCAU1mk8Ivib2WHc8oOoW7efL</li>
					<li><span class="field-label">Secret:</span> EG8BTxBbEhsMTkbFMktBSmBiCS0iCmZZM26JninkmQWWjyBsJH51ClgPnOUr</li>
					<br>
					<li><span class="field-label">AppID for Sandbox Tests:</span> APP-80W284485P519543T</li>
					<li><span class="field-label">End Point for NVP:</span> https://api-3t.sandbox.paypal.com/nvp</li>
				</ul>
			</div>
		</div>