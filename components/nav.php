<?php

    $pages = array("index.php" => "Home", "menu.php" => "Menu", "contact.php" => "Contact Us", "login.php" => "Log In" );

    function GetNavbar($pageURL) {
        global $pages;
        
        echo "<nav>\n";
        echo "<ul>\n";
        foreach ($pages as $url => $title)
        {
            $class = '';
                
            if ($pageURL == $url)
                $class = 'active';
                
            echo "<li class='$class'><a href='$url'>$title</a></li>";
        }
        echo "</ul>\n";
        echo "</nav>\n";
    }

?>