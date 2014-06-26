<?php 
session_start();
include_once("resources/includes/functions.php");
//APP-80W284485P519543T - This is the AppID for Sandbox Tests when an AppID is required
//https://api-3t.sandbox.paypal.com/nvp - The end point for name value pair when using a signature
$title = "Seamless Checkout - "; 
$sel = "seamless"; // This decides which page in the sidebar is selected. Edit the sidebar.php include to check this value for each li

if (isset($_GET['page'])) {
	$page = $_GET['page'];
} else {
	$page = 1;
}
if (isset($_GET['rows'])) {
	$rows = $_GET['rows'];
} else {
	$rows = 20;
}

if ($stmt = $mysqli->prepare("SELECT `id` FROM `seamlesstrans` WHERE `type` = 'Sale';")) {
	$stmt->execute();
	$stmt->store_result();
	$num_rows = $stmt->num_rows;
	$stmt->close();
}

$max_page = ceil($num_rows / $rows);

if ($page > $max_page) {
	$page = 1;
}

$lower_limit = ($rows) * ($page - 1);
$limit_count = $rows;

$transactions = array();

if ($stmt = $mysqli->prepare("SELECT `id`, `product`, `type`, `trans_id`, `amt`, `datetime` FROM `seamlesstrans` WHERE `type` = 'Sale' ORDER BY `datetime` DESC LIMIT ?, ?;")) {
	$stmt->bind_param("ii", $lower_limit, $limit_count);
	$stmt->execute();
	$stmt->bind_result($id, $product, $type, $trans_id, $amt, $datetime);
	while($stmt->fetch()) {
		$trans = [
			"id" => $id,
			"product" => $product,
			"type" => $type,
			"trans_id" => $trans_id,
			"amt" => $amt,
			"datetime" => $datetime,
		];
		$transactions[] = $trans;
	}
	$stmt->close();
}
?>

<?php include "$sitepath/resources/includes/header.php"; ?>
		<section>
			<?php include "$sitepath/resources/includes/sidebar.php"; ?>
			<div class="content">
				<h1>Express Checkout - Sale Transaction</h1>
				<form method="post" action="resources/processing/seamlesssaleprocess.php">
					<div class="clear"></div>
					<ul class="unstyled products">
						<li>
							<h1><input type="radio" name="product" value="madhat" checked="checked"> Mad Hatter</h1>
							<img src="/mapo/resources/images/madhatter.png">
							<div class="price">£18.99</div>
						</li>
						<li>
							<h1><input type="radio" name="product" value="redtril"> Red Trilby</h1>
							<img src="/mapo/resources/images/redtrilby.png">
							<div class="price">£16.99</div>
						</li>
						<li>
							<h1><input type="radio" name="product" value="superman"> Superman Cap</h1>
							<img src="/mapo/resources/images/supercap.png">
							<div class="price">£11.99</div>
						</li>
						<li>
							<h1><input type="radio" name="product" value="stetson"> Stetson Hat</h1>
							<img src="/mapo/resources/images/stetson.png">
							<div class="price">£21.99</div>
						</li>
					<div class="clear"></div>
					</ul>
					<?php if (isset($_SESSION['access_token'])) {
						echo '<input type="image" class="checkout" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-medium.png" name="submit">';
					} ?>
				</form>
				<?php if (!isset($_SESSION['access_token'])) {
					echo "<a class=\"checkout\" href=\"#\" onClick='popUpWindow(\"https://www.sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize?client_id=".$client_id."&response_type=code&scope=".$scope."&redirect_uri=http://localhost/mapo/resources/processing/lippprocess.php\", 
										\"dataitem\", \"400\", \"550\", \"toolbar=no,menubar=no,scrollbars=no,\")'>",
								"<img src=\"https://www.paypalobjects.com/webstatic/en_US/developer/docs/lipp/loginwithpaypalbutton.png\">",
							"</a>";
				} ?>
				<div class="clear"></div>
				<div class="seperator"></div>
				<div class="table-operators">
					<span>Page: <select ONCHANGE="location = '/mapo/seamlesssale.php?rows=<?=$rows?>&page=' + this.options[this.selectedIndex].value;">
					<?php for ($i = 1; $i <= $max_page; $i++) {
					echo "<option"; 
					if ($i == $page) {
						echo " selected=\"selected\"";
					}
					echo " value=\"$i\">$i</option>\n";
					} ?>
					</select>
					</span>
					<span>Entries per Page: 
					<?php if ($rows != 20) echo "<a href=\"/mapo/seamlesssale.php?rows=20&page=1\">20 </a>"; else echo "20 ";
					if ($rows != 30) echo "<a href=\"/mapo/seamlesssale.php?rows=30&page=1\">30 </a>"; else echo "30 ";
					if ($rows != 50) echo "<a href=\"/mapo/seamlesssale.php?rows=50&page=1\">50 </a>"; else echo "50 ";
					if ($rows != 100) echo "<a href=\"/mapo/seamlesssale.php?rows=100&page=1\">100 </a>"; else echo "100 "; ?>
					</span>
				</div>
				<table class="rounded blue full-width">
					<col><col><col><col><col><col>
					<thead>
						<tr><th>ID <th>Product <th>Type <th>Transaction ID <th>Amount <th>Date
					</thead>
					<tbody>
					<?php if ($num_rows > 0) {
						foreach($transactions as $trans) {
							$datetime = new DateTime($trans['datetime']);
							$dt = $datetime->format('d/m/Y H:i:s');
							if ($trans['type'] == "Sale") {
								$type_text = "<div style=\"color:green\">".$trans['type']."</div>";
							} else {
								$type_text = $trans['type'];
							}
							echo "<tr><td>".$trans['id']." <td>".$trans['product']." <td>".$type_text." <td>".$trans['trans_id']." <td>£".$trans['amt']." <td>".$dt;
						} 
					} else {
						echo "<tr><td colspan=\"42\">There are no fields that meet the search criteria (Type = Sale)";
					} ?>
					</tbody>
				</table>
				<!--<p class="nextpage"><a href="ecauth.php">Auth Transaction >></a></p>-->
			</div>
			<div class="clear"></div>
		</section>
	</body>
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