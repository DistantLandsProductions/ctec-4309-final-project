<?php
include("db/dbconn.inc.php");
include("components/nav.php");
include("components/footer.php");

$conn = dbConnect();
?>
<!DOCTYPE html>
<html>
<head>
	<title>CTEC4321 Code Example</title>
	<link rel='stylesheet' href='styles/styles.css' type='text/css'>
</head>
<body>
    <?php GetNavbar("index.php"); ?>
    <?php GetFooter(); ?>
</body>
</html>