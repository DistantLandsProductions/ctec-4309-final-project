<?php

function MenuItemThumbnail($name, $imgURL, $price, $desc)
{
    echo 
        "
        <div class=\"menu-item\">
            <img href=\"$imgURL\" />
            <h2>$name</h2>
            <div>$$price</div>
            <p>$$desc</p>
        </div>
        ";
}
