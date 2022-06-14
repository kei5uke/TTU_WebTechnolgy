<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen&family=PT+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/animation.css">

    <title>Registration</title>
</head>

<body>
    <?php require_once('template/menu.php') ?>
    <?php
    //SQL <=> PHP Part
    require "db_connection.php";

    if ($_POST && isset($_POST["submit"])) {
        $username;
        switch (empty($_POST["username"])) {
            case false:
                switch (preg_match("/^[a-z0-9][a-z0-9_]*[a-z0-9]$/", $_POST["username"])) {
                    case 1:
                        if (strlen($_POST["username"]) > 20) {
                            exit("Username is too long (max 20 characters).");
                        } else {
                            $username = $_POST["username"];
                        }
                        break;
                    case 0:
                        exit("Invalid username input (must use numbers and letters).");
                }
                break;
            case true:
                exit("Empty username input.");
        }

        $password;
        switch (empty($_POST["password"])) {
            case false:
                switch (preg_match("/^[a-z0-9][a-z0-9_]*[a-z0-9]$/", $_POST["password"])) {
                    case 1:
                        if (strlen($_POST["password"]) > 20) {
                            exit("Password is too long (max 20 characters).");
                        } else {
                            $password = $_POST["password"];
                        }
                        break;
                    case 0:
                        exit("Invalid password input (must use numbers and letters).");
                }
                break;
            case true:
                exit("Empty password input.");
        }

        if (isset($_POST['type'])) {
            $type = $_POST['type'];
        } else {
            exit("Choose type.");
        }
        //    print_r($username.$password.$type);
        //    $query = mysqli_query();
        //    $query->bind_param("SELECT type, username, password FROM account WHERE type='$type' AND username = '$username' AND password='$password';");
        $stmt = "SELECT type, username, password FROM account;";
        $query = $connection->prepare($stmt);
        $query->bind_param("sss", $type, $username, $password);
        $query->execute();
        $result = $query->get_result();
        foreach ($result as $row) {
            if (strcmp($row['type'], $type) == 0 && strcmp($row['username'], $username) == 0 && password_verify($password, $row['password'])) {
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['type'] = $type;
                $cookie_name = "type";
                $cookie_value = $type;
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

                echo "  <h2 class='text'></h2>
                        
                        <script src='JS/animation.js'></script>
                        <script>
                            const phrases = ['".$_SESSION['username']. "', 'Welcome', 'Back!'];
                            const el = document.querySelector('.text');
                            const fx = new TextScramble(el);
                            let counter = 0;
                            const next = () => {
                                fx.setText(phrases[counter]).then(() => {
                                setTimeout(next, 1500);
                                });
                            counter = (counter + 1) % phrases.length;
                            };
                            next();
                        </script>";
                echo "<p><a href='index.php'>GO TO INDEX</a></p>";
            }
        }
    }
    ?>
    <?php require_once('template/footer.php') ?>

</body>

</html>

