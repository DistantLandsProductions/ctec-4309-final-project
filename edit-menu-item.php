<?php
include("db/dbconn.inc.php");
include("components/nav.php");
include("components/footer.php");

$conn = dbConnect();

$pid = ""; 

// Set all values for the form as empty.  To prepare for the "adding a new item" scenario.  
$name = "";
$description = "";
$imgURL = "";
$price = 0;
$cid = 1;
$output = "";

$errMsg = "";

if (isset($_POST['Submit'])) {
    
    $pid = $_POST['pid'];
	
	$required = array("name", "price", "cid"); 
	$expected = array("name", "description", "imgURL", "price", "cid"); 
    $label = array ('name'=>'Name', "description"=>'Description', "imgURL"=>'Icon', "price"=>'Price', "cid" => "Category");

	$missing = array();
	
	foreach ($expected as $field){
	    
		if (in_array($field, $required) && empty($_POST[$field])) {
			array_push ($missing, $field);
		
		} else {
			if (!isset($_POST[$field])) {
				${$field} = "";
			} else {
				${$field} = $_POST[$field];
			}
		}
	}
	
    print $name;

	if (empty($missing)){
		$stmt = $conn->stmt_init();
        
		if ($pid != "") {
			$pid = intval($pid); 
			$sql = "Update UProducts SET Name = ?, Description = ?, ImgURL = ?, Price = ?, CID = ? WHERE PID = ?";
				
			if($stmt->prepare($sql)){
			    
				$stmt->bind_param('sssiii', $name, $description, $imgURL, $price, $cid, $pid);
				$stmt_prepared = 1;
			}

		} else {
			$sql = "Insert Into UProducts (Name, Description, ImgURL, Price, CID) values (?, ?, ?, ?, ?)";

			if($stmt->prepare($sql)){

				$stmt->bind_param('sssii', $name, $description, $imgURL, $price, $cid);
				$stmt_prepared = 1;
			}
		}

		if ($stmt_prepared == 1){
			if ($stmt->execute()){
                
                // NOTE: the following code does not produce most user-friendly message.  Particularly the category information is presented as an number which the user will have no idea about.  Can you fix it?
                
				$output = "<span class='success'>Success!</span><p>The following information has been saved in the database:</p>";
                
                // NOTE: With this output, the category id will be displayed.  Not very user friendly.  See if you can find a way to display the category name instead.
				foreach($expected as $key){
				    if ($key == "categoryID"){
				        $categoryDisplayName = GetCategoryName($_POST[$key]);
					    $output .= "<b>{$label[$key]}</b>: {$categoryDisplayName} <br>"; 
				    }
				    else
					    $output .= "<b>{$label[$key]}</b>: {$_POST[$key]} <br>"; 
				}
				$output .= "<p>Back to the <a href='admin_productList.php'>product list page</a></p>";
			} else {
				$output = "<div class='error'>Database operation failed.  Please contact the webmaster.</div>";
			}
		} else {
			$output = "<div class='error'>Database query failed.  Please contact the webmaster.</div>";
		}

	} else { 
		$output = "<div class='error'><p>The following required fields are missing in your form submission.  Please check your form again and fill them out.  <br>Thank you.<br>\n<ul>\n";
		foreach($missing as $m){
			$output .= "<li>{$label[$m]}\n";
		}
		$output .= "</ul></div>\n";
	}

} else if (isset($_GET['pid'])) { 
    
	$pid = intval($_GET['pid']); 
	if ($pid > 0){
	    
		$sql = "SELECT Name, Description, ImgURL, Price, CID from UProducts WHERE PID = ?";
		$stmt = $conn->stmt_init();

		if($stmt->prepare($sql)){
			$stmt->bind_param('i',$pid);
			$stmt->execute();
				
			$stmt->bind_result($name, $description, $imgURL, $price, $cid);
			$stmt->store_result();
				
			if($stmt->num_rows == 1){
				$stmt->fetch();
			} else {
				$errMsg = "<div class='error'>Information on the record you requested is not available.  If it is an error, please contact the webmaster.  Thank you.</div>";
				$pid = "";
			}

		} else {
			$pid = "";
			$errMsg = "<div class='error'> If you are expecting to edit an exiting item, there are some error occured in the process -- the selected product is not recognizable.  Please follow the link below to the product adminstration interface or contact the webmaster.  Thank you.</div>";
		}
		
		$stmt->close();
	}
	
}


function CategoryOptionList($selectedCID){
	
	$list = "";
	
	global $conn;
	$sql = "SELECT CID, Name FROM UProductCategory ORDER BY Name";
	
	$stmt = $conn->stmt_init();

	if ($stmt->prepare($sql)){
		
		$stmt->execute();
		$stmt->bind_result($CategoryID, $CategoryName);

		while ($stmt->fetch()) {
			if ($CategoryID == $selectedCID){
				$selected = "Selected";
			} else {
				$selected = "";
			}

			$list = $list."<option value='$CategoryID' $selected>$CategoryName</option>";
		}
	}
	
	$stmt->close();
	return $list;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>CTEC4321 Code Example</title>
	<link rel='stylesheet' href='style/styles.css'>
    <script>
        function confirmDel(title, pid) {
        
        	url = "admin-delete.php?pid="+pid;
        	var agree = confirm("Delete this " + title + " from the menu?");
        	if (agree) {
        		location.href = url;
        	}
        	else {
        		return;
        	}
        }
    </script>
</head>
<body>
    <?php GetNavbar("edit-menu.php"); ?>
    <?php echo $output ?>
    <div class="container">
        <div class="row">
            <form action="edit-menu-item.php" method="POST">
            * Required
            	<input type="hidden" name="pid" value="<?=$pid?>">
            
            	<table class='formTable'>
            		<tr><th>Name*:</th><td><input type="text" name="name" size="45" value="<?= htmlentities($name) ?>"></td></tr>
            		<tr><th>Description:</th><td><input type="text" name="description" size="45" value="<?= htmlentities($description) ?>"></td></tr>
            		<tr><th>Icon:</th><td><input type="text" name="imgURL" size="45" value="<?= htmlentities($imgURL) ?>"></td></tr>
            		<tr><th>Price:</th><td><input type="text" name="price" size="45" value="<?= htmlentities($price) ?>"></td></tr>
            		<tr><th>Category:</th><td><select name="cid"><?= CategoryOptionList($cid)?></select></td></tr>
            		<tr>
            		    <td><input type=submit name="Submit" value="Submit Product Information"></td>
            		    <?php 
            		        if (isset($_GET['pid']))
            		            echo "<td><a href='javascript:confirmDel(\"$name\", $pid)'>Delete</a></td>" 
            		    ?>
            		</tr>
            	</table>
            </form>
        </div>
    </div>
    <?php GetFooter(); ?>
</body>
</html>