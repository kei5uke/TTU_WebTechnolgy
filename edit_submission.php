<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen&family=PT+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <title>Sign Up</title>
</head>

<body>
<?php require_once('template/menu.php') ?>

<div class="content">
    <?php if (!isset($_SESSION['username']))exit("You must be logged in to view this page"); ?>

    <?php
    require "db_connection.php";
    if (isset($_SESSION['username']) && strcmp($_SESSION['type'],"freelancer")==0) {
        $stmt = "select * from freelancer_form_data";
        $query = $connection->prepare($stmt);
        $query->execute();
        $options = $query->get_result();
        $username = $_SESSION['username'];
        foreach ($options as $row) {
            if (strcmp($row['username'], $username) == 0) {
                $username = $row['username'];
                $name = $row['name_text'];
                $title = $row['title_text'];
                $description = $row['description_text'];
                $wage = $row['rate_text'];
                $wage_type = $row['money_type'];
                $location = $row['location_type'];
                $job = $row['job_type'];
                break;
            }
        }

        $currentDate = date("Y-m-d");
        $intervalDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currentDate)) . " + 1 year"));
        $stmt = "select job_type, job_location from job_freelancer_data";
        $query = $connection->prepare($stmt);
        $query->execute();
        $options = $query->get_result();
    }
    ?>

    <div class="description">
        <h1>EDIT FREELANCER SUBMISSION</h1>
        <?php
        require "db_connection.php";
        if (isset($_SESSION['username']) && strcmp($_SESSION['type'],"freelancer")==0) {
            $stmt = "select * from freelancer_form_data;";
            $query = $connection->prepare($stmt);
            $query->execute();
            $options = $query->get_result();
            $username = $_SESSION['username'];
            foreach ($options as $row) {
                if (strcmp($row['username'], $username) == 0) {
                    $id = $row['ID'];
                    $url = "'freelancer_description_edit.php?id=".$id."'";
                    echo "<h2><a href=".$url.">".$row['title_text']."</a></h2><br>";
                }
            }
        }else{
            echo "NO FREELANCE ENTRY<br>";
        }
        ?>
    </div>
    <div class="description">
        <h1>EDIT COMPANY SUBMISSION</h1>
    <?php
    require "db_connection.php";
    if (isset($_SESSION['username']) && strcmp($_SESSION['type'],"company")==0) {
        $stmt = "select * from job_form_data;";
        $query = $connection->prepare($stmt);
        $query->execute();
        $options = $query->get_result();
        $username = $_SESSION['username'];
        foreach ($options as $row) {
            if (strcmp($row['username'], $username) == 0) {
                $id = $row['ID'];
                $url = "'job_description_edit.php?id=".$id."'";
                echo "<h2><a href=".$url.">".$row['title_text']."</a></h2><br>";
            }
        }
    }else{
        echo "NO COMPANY SUBMISSION ENTRY<br>";
    }
    ?>
    </div>
</div>
<?php require_once('template/footer.php') ?>

</body>
</html>