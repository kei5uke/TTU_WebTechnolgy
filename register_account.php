<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen&family=PT+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <title>Registration</title>
</head>

<body>
    <?php require_once('template/menu.php') ?>

    <?php require_once('template/footer.php') ?>

</body>

</html>

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

    if(isset($_POST['type'])){
        $type = $_POST['type'];
    }else{
        exit("Choose type.");
    }

    $column_array = array();
    $value_array = array();

    array_push($column_array, "type");
    array_push($value_array, $type);
    array_push($column_array, "username");
    array_push($value_array, $username);
    array_push($column_array, "password");
    $options = [
        'cost' => 12,
    ];
    $hashedpassword = password_hash($password, PASSWORD_BCRYPT, $options); 
    array_push($value_array, $hashedpassword);

    // add commas
    $comma_seperated_columns = implode(", ", $column_array);
    $comma_seperated_values = implode("','", $value_array);
    // add parentheses
    $comma_seperated_columns = "($comma_seperated_columns)";
    $comma_seperated_values = "('$comma_seperated_values')";

    $datastuff = "insert into account ".$comma_seperated_columns." VALUES ".$comma_seperated_values;
    echo $datastuff;
    $query = $connection->prepare($datastuff);
    $query->execute();
    echo ("The registration has been successful.");
}
?>