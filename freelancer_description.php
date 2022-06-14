<?php
require "db_connection.php";
$name = $_GET['name'];
$stmt = "select * from freelancer_form_data";
$query = $connection->prepare($stmt);
$query->execute();
$result = $query->get_result();
foreach ($result as $row) {
    if (strcmp($row['name_text'], $name) == 0) {
        $name = $row['name_text'];
        $title = $row['title_text'];
        $description = $row['description_text'];
        $wage = $row['rate_text'];
        $wage_type = $row['money_type'];
        $location = $row['location_type'];
        $job = $row['job_type'];
        $username = $row['username'];
        break;
    }
}
$stmt2 = "select * from feedback where type = 'freelancer' and username = ?";
$query2 = $connection->prepare($stmt2);
$query2->bind_param("s", $username);
$query2->execute();
$result2 = $query2->get_result();
$rating = 0;
$counter = 0;
$feedback_array = [];
foreach ($result2 as $row2) {
    $feedback = $row2['feedback'];
    $feedback .= " - ";
    $feedback .= $row2['company'];
    $rating += $row2['rating'];
    $counter++;
    array_push($feedback_array, $feedback);
}

$rating /= $counter;
$rating = number_format($rating, $decimals = 2)
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Oxygen&family=PT+Sans&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="styles/main.css">
        <title>Freelancer Description</title>
    </head>

    <body>
        <?php require_once('template/menu.php');
        if (!isset($_SESSION['username'])) exit("You must be logged in to view this page"); ?>
        <div class="content">
            <div class="left">
                <h1>Name</h1>
                <?php echo $name; ?>
                <h1>Title</h1>
                <?php echo $title; ?>
                <h1>Description</h1>
                <?php echo $description; ?>
                <h3>Wage</h3>
                <?php echo $wage; ?>
                <?php echo $wage_type; ?>
                <h3>Location</h3>
                <?php echo $location; ?>
                <h3>Job</h3>
                <?php echo $job; ?>
                <?php if (isset($_SESSION['username'])){
                echo '<br><form name="apply" action="form_handling.php" method="post">';
                echo '<input type="hidden" name="jobid" value="', $id ,'">';
                echo '<input type="hidden" name="name" value="', $name ,'">';
                echo '<input type="submit" name="hire_button" value="Hire me!"></form>';
                }?>
                <h3>Feedback about this freelancer</h3>
                <ul>
                    <?php
                    foreach ($feedback_array as $string) {
                        echo "<li>", $string, "</li>";
                    }
                    echo "<li>Rating:", $rating, "</li>";
                    ?>
                </ul>
            </div>
        </div>
        <?php require_once('template/footer.php') ?>
    </body>
</html>