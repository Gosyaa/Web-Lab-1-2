<?php
    if (isset($user))
        $user_header = $user;
    else
        $user_header = "";
    if (isset($team))
        $team_header = $team;
    else
        $team_header = "";
?>

<!DOCTYPE html>

<html>
    <head>
        <link rel="icon" href="images/logo.png">
        <link rel="stylesheet" href="styles/header.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="header2">
            <p class="team"> <?php echo $team_header; ?> </p> 
            <div class="title-div">
              <a class="title" href="homepage.php"> Командные заметки </a>
            </div>
            <p class="user"> <?php echo $user_header; ?> </p>
        </div>
    </body>
</html>