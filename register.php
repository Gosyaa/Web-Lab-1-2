<?php
    include("headers\header1.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Регистрация </title>
        <link href="styles/sign-in.css" rel="stylesheet">
        <link href="styles/buttons.css" rel="stylesheet">
    </head>
    <body>
        <div class="sign-up-form">
            <div class="sign-up-center">
                <img class="logo" src="images/logo.png">
                <h1> Добро пожаловать </h1>
                <form action="register.php" method="post">
                    <input type="text" name="username" placeholder="Логин"><br>
                    <div class=pic-select>
                        <div class="option">
                            <img class="pic" src="images/empty.png">
                            <input type="radio" name="pic" value="images/empty.png" checked="checked">
                        </div>
                        <div class="option">
                            <img class="pic" src="images/capibara.jpeg">
                            <input type="radio" name="pic" value="images/capibara.jpeg">
                        </div>
                        <div class="option">
                            <img class="pic" src="images/kitten.jpg">
                            <input type="radio" name="pic" value="images/kitten.jpg">
                        </div>
                        <div class="option">
                            <img class="pic" src="images/floppa.jpg">
                            <input type="radio" name="pic" value="images/floppa.jpg">
                        </div>
                    </div>
                    <input type="password" name="password" placeholder="Пароль"><br>
                    <input type="password" name="password_confirm" placeholder="Повтор Пароля"><br>
                    <input type="submit" name="submit" value="Регистрация" class="team-button" id="submit0"> 
                </form>
                <a href="welcome.php"> <button class="leave" id="back0"> Назад </button> </a>
            </div>
        </div>
    </body>
</html>

<?php
    if (isset($_POST['submit'])){
        if (empty($_POST['username'])){
            echo '<script> alert("Введите логин"); </script>';
            exit();
        }
        else if (empty($_POST["password"])){
            echo '<script> alert("Введите пароль"); </script>';
            exit();
        }
        else if (empty($_POST["password_confirm"])){
            echo '<script> alert("Повторите пароль"); </script>';
            exit();
        }
        else if ($_POST["password_confirm"] != $_POST["password"]){
            echo '<script> alert("Пароли не совпадают"); </script>';
            exit();
        }
        
        $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM user WHERE login = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_POST["username"]));
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
            echo "Имя пользователя уже занято";
            exit();
        }

        $sql = "INSERT INTO user (login, password, picture) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $rowsNumber = $stmt->execute(array($_POST["username"], password_hash($_POST["password"], PASSWORD_DEFAULT), $_POST["pic"]));
        echo '<script>
                alert("Успешная Регистрация");
                window.location.href="welcome.php";
                </script>';
        
    }
?>