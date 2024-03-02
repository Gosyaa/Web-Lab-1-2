<?php
    session_start();
    $user = $_SESSION["user"];
    //echo $user;
    echo "<h1> " . $user . " </h1>";