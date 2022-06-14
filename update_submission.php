<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen&family=PT+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <title>Job Submitted</title>
</head>

<body>
<?php require_once('template/menu.php') ?>

<?php require_once('template/footer.php') ?>
</body>
</html>
<?php
if(isset($_POST['update_freeform']) && strcmp($_SESSION['type'],"freelancer")==0){
    //SQL <=> PHP Part
    require "db_connection.php";

    $username = $_SESSION['username'];
    $name = $_POST['name_text'];
    $title = $_POST['title_text'];
    $description = $_POST['description_text'];
    $wage = $_POST['rate_text'];
    $wage_type = $_POST['money_type'];
    $location = $_POST['location_type'];
    $job = $_POST['job_type'];

    $datastuff = "update freelancer_form_data set ";
    $datastuff .= "name_text='".$name."', " ;
    $datastuff .= "title_text='".$title."', " ;
    $datastuff .= "description_text='".$description."', " ;
    $datastuff .= "rate_text='".$wage."', " ;
    $datastuff .= "money_type='".$wage_type."', " ;
    $datastuff .= "location_type='".$location."', " ;
    $datastuff .= "job_type='".$job."' where username='".$username."';";

    echo $datastuff."\n";
    $query = $connection->prepare($datastuff);
    $query->execute();
    echo ("The FREELANCER UPDATE has been successful.");
}
?>

<?php
if(isset($_POST['update_compform'])&&strcmp($_SESSION['type'],"company")==0){
    //SQL <=> PHP Part
    require "db_connection.php";

    $username = $_SESSION['username'];
    $name = $_POST['name_text'];
    $title = $_POST['title_text'];
    $description = $_POST['description_text'];
    $wage = $_POST['rate_text'];
    $wage_type = $_POST['money_type'];
    $location = $_POST['location_type'];
    $job = $_POST['job_type'];
    $date = $_POST['daterange'];

    $datastuff = "update job_form_data set ";
    $datastuff .= "companyname_text='".$name."', " ;
    $datastuff .= "title_text='".$title."', " ;
    $datastuff .= "description_text='".$description."', " ;
    $datastuff .= "rate_text='".$wage."', " ;
    $datastuff .= "money_type='".$wage_type."', " ;
    $datastuff .= "location_type='".$location."', " ;
    $datastuff .= "daterange='".$date."', " ;
    $datastuff .= "job_type='".$job."' where username='".$username."';";

    echo $datastuff."\n";
    $query = $connection->prepare($datastuff);
    $query->execute();
    echo ("The JOB UPDATE has been successful.");
}
?>
