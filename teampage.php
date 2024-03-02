<?php
    session_start();
    if (isset($_GET["team_id"])){
        $_SESSION["team"] = $_GET["team_id"];
        echo $_SESSION["team"];
    }
    else
        echo "Ошибка"; 