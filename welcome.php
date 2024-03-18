<?php
    session_start();
    include("headers\header1.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Добро пожаловать </title>
        <link rel="stylesheet" href="styles/sign-in.css">
        <link rel="stylesheet" href="styles/buttons.css">
    </head>
    <body>
        <div class="sign-in-form">
            <div class="sign-in-center">
                <img class="logo" src="images/logo.png">
                <h1> Добро пожаловать </h1>
                <form action="welcome.php" method="POST">
                    <input type="text" name="username" placeholder="Логин"><br>
                    <input type="password" name="password" placeholder="Пароль"><br>
                    <input type="submit" value="Войти" name="submit" class="team-button"><br>
                </form>
                <a href="register.php"> <button class="team-button"> Зарегестрироваться </button> </a>
            </div>
        </div>
        <br>
    </body>
</html>

<?php
    if (isset($_POST["submit"])){
        if (empty($_POST["username"])){
            echo '<script> alert("Введите логин"); </script>';
            exit();
        }
        else if (empty($_POST["password"])){
            echo '<script> alert("Введите пароль"); </script>';
            exit();
        }
        
        $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM user WHERE login = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_POST["username"]));

        while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
            if (password_verify($_POST["password"], $row["password"])){
                $_SESSION["user"] = $row["id"];
                header("Location: http://localhost:8081/web_lab_1+2/homepage.php");
            }
            else {
                echo '<script> alert("Пароль неверный!"); </script>';
                exit();
            }
            exit();
        }
        echo "Try again <br>";
    }
?>