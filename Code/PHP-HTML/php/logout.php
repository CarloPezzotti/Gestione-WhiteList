<?php
    session_unset();
    session_abort();
    header("Location: login.php");
?>