<?php 
session_start();
include_once("resources/includes/functions.php"); ## This path needs to be set ##
//APP-80W284485P519543T - This is the AppID for Sandbox Tests when an AppID is required
//https://api-3t.sandbox.paypal.com/nvp - The end point for name value pair when using a signature
$title = "EC Refunds - ";
$sel = "ecrefund"; // This decides which page in the sidebar is selected. Edit the sidebar.php include to check this value for each li
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

if ($stmt = $mysqli->prepare("SELECT `id` FROM `transactions`;")) {
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

if ($stmt = $mysqli->prepare("SELECT `id`, `product`, `type`, `trans_id`, `amt`, `datetime` FROM `transactions` ORDER BY `datetime` DESC LIMIT ?, ?;")) {
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
				<h1>Express Checkout - Refunds</h1>
				<div class="table-operators">
					<span>Page: <select ONCHANGE="location = '/mapo/ecrefund.php?rows=<?=$rows?>&page=' + this.options[this.selectedIndex].value;">
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
					<a <?php if ($rows == 20) echo "class=\"active\" "; ?>href="/mapo/ecrefund.php?rows=20&page=1">20</a> 
					<a <?php if ($rows == 30) echo "class=\"active\" "; ?>href="/mapo/ecrefund.php?rows=30&page=1">30</a> 
					<a <?php if ($rows == 50) echo "class=\"active\" "; ?>href="/mapo/ecrefund.php?rows=50&page=1">50</a> 
					<a <?php if ($rows == 100) echo "class=\"active\" "; ?>href="/mapo/ecrefund.php?rows=100&page=1">100</a>
					</span>
				</div>
				<table class="rounded blue full-width">
					<col><col><col><col><col><col><col>
					<thead>
						<tr><th>ID <th>Product <th>Type <th>Transaction ID <th>Amount <th>Date <th>Refund
					</thead>
					<tbody>
					<?php foreach($transactions as $trans) {
						$datetime = new DateTime($trans['datetime']);
						$dt = $datetime->format('d/m/Y H:i:s');
						if ($trans['type'] == "Refunded" || $trans['type'] == "Voided") {
							$type_text = "<div style=\"color:red\">".$trans['type']."</div>";
							$refund = "";
						} else {
							$refund = "<a class=\"refund\" href=\"/mapo/resources/processing/ecrefundprocess.php?transid=".$trans['trans_id']."\">Refund</a>";
							if ($trans['type'] == "Sale" || $trans['type'] == "Captured") {
								$type_text = "<div style=\"color:green\">".$trans['type']."</div>";
							} else {
								$type_text = $trans['type'];
							}
						}
						echo "<tr><td>".$trans['id']." <td>".$trans['product']." <td>".$type_text." <td>".$trans['trans_id']." <td>£".$trans['amt']." <td>".$dt." <td>$refund";
					} ?>
					</tbody>
				</table>
				<p class="nextpage"><a href="/mapo/ecvoid.php">Voids >></a></p>
			</div>
			<div class="clear"></div>
		</section>
	</body>
	
</html>