<?php 
session_start();
include_once("resources/includes/functions.php"); ## This path needs to be set ##
//APP-80W284485P519543T - This is the AppID for Sandbox Tests when an AppID is required
//https://api-3t.sandbox.paypal.com/nvp - The end point for name value pair when using a signature
$title = "EC Auth - ";
$sel = "ecauth"; // This decides which page in the sidebar is selected. Edit the sidebar.php file that's included to check this value for each li

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

if ($stmt = $mysqli->prepare("SELECT `id` FROM `transactions` WHERE `type` = 'Authorization' OR `type` = 'Captured';")) {
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

if ($stmt = $mysqli->prepare("SELECT `id`, `product`, `type`, `trans_id`, `amt`, `datetime` FROM `transactions` WHERE `type` = 'Authorization' OR `type` = 'Captured' ORDER BY `datetime` DESC LIMIT ?, ?;")) {
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
				<h1>Express Checkout - Authorization, Capture</h1>
				<form method="post" action="/mapo/resources/processing/ecauthprocess.php">
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
					<input type="image" class="checkout" src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-medium.png" name="submit">
				</form>
				<div class="clear"></div>
				<div class="seperator"></div>
				<div class="table-operators">
					<span>Page: <select ONCHANGE="location = '/mapo/ecauth.php?rows=<?=$rows?>&page=' + this.options[this.selectedIndex].value;">
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
					<?php if ($rows != 20) echo "<a href=\"/mapo/ecauth.php?rows=20&page=1\">20 </a>"; else echo "20 ";
					if ($rows != 30) echo "<a href=\"/mapo/ecauth.php?rows=30&page=1\">30 </a>"; else echo "30 ";
					if ($rows != 50) echo "<a href=\"/mapo/ecauth.php?rows=50&page=1\">50 </a>"; else echo "50 ";
					if ($rows != 100) echo "<a href=\"/mapo/ecauth.php?rows=100&page=1\">100 </a>"; else echo "100 "; ?>
					</span>
				</div>
				<table class="rounded blue full-width">
					<col><col><col><col><col><col><col>
					<thead>
						<tr><th>ID <th>Product <th>Type <th>Transaction ID <th>Amount <th>Date <th>Capture
					</thead>
					<tbody>
					<?php if ($num_rows > 0) {
						foreach($transactions as $trans) {
							$datetime = new DateTime($trans['datetime']);
							$dt = $datetime->format('d/m/Y H:i:s');
							if ($trans['type'] == "Captured") {
								$capture = "";
								$type_text = "<div style=\"color:green\">".$trans['type']."</div>";
							} else {
								$capture = "<a class=\"capture\" href=\"/mapo/resources/processing/ecauthprocess.php?capture=".$trans['trans_id']."\">Capture</a>";
								$type_text = $trans['type'];
							}
							echo "<tr><td>".$trans['id']." <td>".$trans['product']." <td>".$type_text." <td>".$trans['trans_id']." <td>£".$trans['amt']." <td>".$dt." <td>$capture";
						} 
					} else {
						echo "<tr><td colspan=\"42\">There are no fields that meet the search criteria (Type = Authorized or Captured)";
					} ?>
					</tbody>
				</table>
				<p class="nextpage"><a href="/mapo/ecrefund.php">Refunds >></a></p>
			</div>
			<div class="clear"></div>
		</section>
	</body>
	
</html>