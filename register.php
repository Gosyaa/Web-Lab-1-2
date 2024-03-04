<!DOCTYPE html>
<html>
    <head>
        <title> Регистрация </title>
    </head>
    <body>
        <h1> Добро пожаловать </h1>
        <form action="register.php">
            <label> Логин
                <input type="text" name="username">
            </label><br>
            <label> Пароль
                <input type="password" name="password">
            </label><br>
            <label> Повтор Пароля
                <input type="password" name="password_confirm">
            </label><br>
            <input type="submit" name="submit" value="Регистрация"> 
        </form>
        <a href="welcome.php"> Назад </a>
    </body>
</html>

<?php
    if (isset($_GET['submit'])){
        if (empty($_GET['username'])){
            echo "Введите логин";
            exit();
        }
        else if (empty($_GET["password"])){
            echo "Введите пароль"; 
            exit();
        }
        else if (empty($_GET["password_confirm"])){
            echo "Повторите пароль";
            exit();
        }
        else if ($_GET["password_confirm"] != $_GET["password"]){
            echo "Пароли не совпадают";
            exit();
        }
        
        $conn = new PDO("mysql:host=localhost;dbname=notes_db", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM user WHERE login = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($_GET["username"]));
        while ($row = $stmt->fetch(PDO::FETCH_LAZY)){
            echo "Имя пользователя уже занято";
            exit();
        }

        $sql = "INSERT INTO user (login, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $rowsNumber = $stmt->execute(array($_GET["username"], password_hash($_GET["password"], PASSWORD_DEFAULT)));
        echo '<script>
                alert("Успешная Регистрация");
                window.location.href="welcome.php";
                </script>';
        
    }
?>