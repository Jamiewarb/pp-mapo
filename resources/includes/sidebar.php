<div class="sidebar">
	<ul class="main">
		<li class="nav-title<?php if ($sel == "ec") echo " active"; ?>"><a href="ec.php">Express Checkout - NVP</a></li>
		<li>
			<ul class="sub">
				<li<?php if ($sel == "ecsale") echo " class=\"active\""; ?>><a href="ecsale.php">Sale</a></li>
				<li<?php if ($sel == "ecauth") echo " class=\"active\""; ?>><a href="ecauth.php">Authorization</a></li>
				<li<?php if ($sel == "ecrefund") echo " class=\"active\""; ?>><a href="ecrefund.php">Refunds</a></li>
				<li<?php if ($sel == "ecvoid") echo " class=\"active\""; ?>><a href="ecvoid.php">Voids</a></li>
				<!--<li<?php if ($sel == "bopis") echo " class=\"active\""; ?>><a href="ecsalebopis.php">BOPIS Test</a></li>-->
			</ul>
		</li>
		<li class="nav-title<?php if ($sel == "lipp") echo " active"; ?>"><a href="lipp.php">Log In with PayPal</a></li>
		<li>
			<ul class="sub">
				<li<?php if ($sel == "lipp-b") echo " class=\"active\""; ?>><a href="lipp-login.php">Login Buttons</a></li>
				<li<?php if ($sel == "seamless") echo " class=\"active\""; ?>><a href="seamlesssale.php">Seamless Checkout</a></li>
			</ul>
		</li>
		<li class="nav-title<?php if ($sel == "icc") echo " active"; ?>"><a href="icc.php">In-Context Checkout</a></li>
		<li>
			<ul class="sub">
				<li<?php if ($sel == "icc-i") echo " class=\"active\""; ?>><a href="icc-implement.php">Implementation</a></li>
			</ul>
		</li>
		<li class="nav-title<?php if ($sel == "rt") echo " active"; ?>"><a href="reftrans.php">Reference Transactions</a></li>
		<li>
			<ul class="sub">
				<li<?php if ($sel == "rt-i") echo " class=\"active\""; ?>><a href="reftrans-implement.php">Implementation</a></li>
			</ul>
		</li>
		<li class="nav-title<?php if ($sel == "wpp") echo " active"; ?>"><a href="wpp.php">Website Payments Pro</a></li>
	</ul>
</div>