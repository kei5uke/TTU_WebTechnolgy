<?php
require "db_connection.php";
$id = $_GET['id'];
$stmt = "select * from job_form_data";
$query = $connection->prepare($stmt);
$query->execute();
$result = $query->get_result();
foreach ($result as $row) {
    if ($row['ID'] == $id) {
        $title = $row['title_text'];
        $description = $row['description_text'];
        $companyname = $row['companyname_text'];
        $wage = $row['rate_text'];
        $wage_type = $row['money_type'];
        $date = $row['daterange'];
        $location = $row['location_type'];
        $job = $row['job_type'];
        break;
    }
}

// remove all spaces and convert to lowercase
$companyname2 = preg_replace('/\s*/', '', $companyname);
// convert the string to all lowercase
$companyname2 = strtolower($companyname2);

$stmt2 = "select * from feedback where type = 'company' and company = ?";
$query2 = $connection->prepare($stmt2);
$query2->bind_param("s", $companyname2);
$query2->execute();
$result2 = $query2->get_result();
$rating = 0;
$counter = 0;
$feedback_array = [];
foreach ($result2 as $row2) {
    $feedback = $row2['feedback'];
    $feedback .= " - ";
    $feedback .= $row2['username'];
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
        <link href="https://fonts.googleapis.com/css2?family=Oxygen&family=PT+Sans&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/job_list.css">
        <title>Job Description</title>
    </head>

    <body>
        <?php require_once('template/menu.php');
        if (!isset($_SESSION['username'])) exit("You must be logged in to view this page"); ?>
        <div class="content">
            <div class="left">
                <h1>Title</h1>
                <?php echo $title; ?>
                <h1>Company name</h1>
                <?php echo $companyname; ?>
                <h1>Description</h1>
                <?php echo $description; ?>
                <h3>Wage</h3>
                <?php echo $wage; ?>
                <?php echo $wage_type; ?>
                <h3>Location</h3>
                <?php echo $location; ?>
                <h3>Job</h3>
                <?php echo $job; ?>
                <h3>Offer is valid until</h3>
                <?php echo $date; ?><br>
                <?php if (isset($_SESSION['username'])){
                echo '<form name="apply" action="form_handling.php" method="post">';
                echo '<input type="hidden" name="jobid" value="', $id ,'">';
                echo '<input type="hidden" name="companyname" value="', $companyname ,'">';
                echo '<input type="submit" name="apply_button" value="Apply for this job!"></form>';
            }?>
                <h3>Feedback about this company</h3>
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