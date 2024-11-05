<?php
    function GetMenuItem($itemName, $imgURL, $price, $description, $pid) {
        
        $price = number_format((float)$price, 2, '.', '');
        
        echo "<a href='edit-menu-item.php?pid=$pid' class='col-md-4 col-lg-3'>\n";
            echo "<div class='rounded-image'>\n";
                echo "<img src='$imgURL' alt='$itemName'> \n";
                echo "<div class='menu-item'>\n";
                    echo "<div class='h3'>$itemName</div>\n";
                    echo "<div class='h4'>\$$price</div>\n";
                    // echo "<p>$description</p>\n";
                echo "</div>";
            echo "</div>";
        echo "</a>\n";
    }
    
    function GetAddMenuItem() {
        global $loggedIn;
        
        if (true) {
        
        echo "<a href='edit-menu-item.php' class='col-md-4 col-lg-3'>\n";
            echo "<div class='rounded-image'>\n";
                echo "<div class='menu-item'>\n";
                    echo "<div class='h3'>Add New Item</div>\n";
                echo "</div>";
            echo "</div>";
        echo "</a>\n";
        
        }
    }
?>