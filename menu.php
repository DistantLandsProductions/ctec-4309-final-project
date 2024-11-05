<?php
include("db/dbconn.inc.php");
include("components/nav.php");
include("components/footer.php");
include("components/menu-item.php");

$conn = dbConnect();
?>
<!DOCTYPE html>
<html>
<head>
	<title>CTEC4321 Code Example</title>
	<link rel='stylesheet' href='style/styles.css'>
</head>
<body>
    <?php 
    GetNavbar("menu.php"); 
    
    $sql = "SELECT UProducts.Name, ImgURL, Description, Price, PID, UProductCategory.Name FROM UProducts, UProductCategory WHERE UProducts.CID = UProductCategory.CID ORDER BY UProducts.CID, UProducts.Name ASC;";
    $stmt = $conn->stmt_init();
    		
    if ($stmt->prepare($sql)) {
    	$stmt->execute();
    	$stmt->bind_result($productName, $imgURL, $description, $price, $pid, $categoryName);
    	$currentCategory = "";
    
    	print ("<div class='container'>");
    	while ($stmt->fetch()) {
    		if ($currentCategory != $categoryName) {
    			if ($currentCategory != "") {
    		        print GetAddMenuItem()."\n";
    			    print ("</div>");
    			}
    
    			print ("<h2>$categoryName</h2>");
    			$currentCategory = $categoryName; 
    			print ("<div class='row'>");
    		}
    	    
    		print GetMenuItem($productName, $imgURL, $price, $description, $pid)."\n";
    
    	}
    	print GetAddMenuItem()."\n";
    	print ("</div>");
    	print ("</div>");   
    			
    } else {
    	print ("<div class='error'>Query failed</div>");
    }
    		
    $stmt->close();

    GetFooter();
    ?>
</body>
</html>