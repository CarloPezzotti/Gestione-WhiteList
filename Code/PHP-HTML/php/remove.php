<?php
    require_once "database.php";
    foreach ($_GET as $key => $value) {
        Database::removeFromTable("user",$key,$value);
    }
    header("location: admin.php")
?>