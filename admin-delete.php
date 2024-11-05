<?php
include("db/dbconn.inc.php");
include("components/nav.php");
include("components/footer.php");

$conn = dbConnect();

$pid = "";

if (isset($_GET['pid'])) { 

	$pid = intval($_GET['pid']); 
	
		if ($pid>0 ){
			$sql = "DELETE from UProducts WHERE PID = ?";
			$stmt = $conn->stmt_init();

			if ($stmt->prepare($sql)){

				$stmt->bind_param('i',$pid);

				if ($stmt->execute()){ // $stmt->execute() will return true (successful) or false
                    $stmt->store_result();
                    //echo '$stmt->affected_rows: '.$stmt->affected_rows;
                    
                    if ($stmt->affected_rows === 1){
                        $output = "<span class='success'>Success!</span><p>The selected record has been seccessfully deleted.</p>";
                    } else if ($stmt->affected_rows === 0) { // no row was affected by the query. --> $pid number does not exist.
                        $output = "<div class='error'>The database operation to delete the record was not successful. Please try again or contact the system administrator with an error code of 0.</div>";
                    } else {
                        $output = "<div class='error'>The database operation was unexpected.  Please try again or contact the system administrator.</div>";
                    }
                    	
				} else { // $stmt->execute() was not successful
					$output = "<div class='error'>The database operation to delete the record was not successful.  Please try again or contact the system administrator.</div>";
				}
                
				
			}
				
			
		} else {
			// product id <= 0. reset $pid. prepare error message
			$pid = "";
			// compose an error message
			$output = "<div class='error'><b>!</b> If you are expecting to delete an exiting item, there are some error occured in the process -- the product you selected is not recognizable. Please contact the webmaster.  Thank you.</div>";
		}
} else {
	// $_GET['pid'] is not set, which means that no product id is provided
	$output = "<p><b>!</b> To manage product records, please follow the link below to visit the admin page.  Thank you. </p>";
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>CTEC4321 Code Example</title>
	<link rel='stylesheet' href='style/styles.css'>
</head>
<body>
    <?php GetNavbar("edit-menu.php"); ?>
    
    <?= $output ?>
    
    <?php GetFooter(); ?>
</body>
</html>