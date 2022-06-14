<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen&family=PT+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <title>Freelancer List</title>
</head>

<body>
    <!--header menu-->
    <?php require_once('template/menu.php') ?>

    <div class="content">
        <div class="list">
            <!--Search bar-->
            <form method="post" action="freelancer_list.php">
                <input type="search" name="keywords" placeholder="Add some keywords">
                <input type="submit" name="search" value="Search">
            </form>


        <!--pagination algorithm-->
        <?php
        //SQL <=> PHP Part
        require "db_connection.php";
    
        if(isset($_POST['search'])){
            $search = $_POST['keywords'];
            $search = "%$search%";
            $data ="SELECT * FROM freelancer_form_data where title_text like ? or name_text like ?";
            $query = $connection->prepare($data);
            $query->bind_param("ss", $search, $search);
        } else {
            $data ="SELECT * FROM freelancer_form_data;";
            $query = $connection->prepare($data);
        }
    
        $query->execute();
        $result = $query->get_result();

        $max = 4;
        $tableContent = array();

        foreach ($result as $row){
            $new = str_replace(' ', '%20', $row['name_text']);
            $url = "'freelancer_description.php?name=".$new."'";
            $content = "<li><a href=".$url.">";
            $content .= "<img src='img/freelancer.jpeg' alt='img'>";
            $content .= sprintf("<p><b>%s</b><br>%s</p>",$row['name_text'],$row['title_text']);
            $content .= "</a></li>";
            $tableContent[] = $content;
        }

        $tableContent_sum = count($tableContent);
        $max_page = ceil($tableContent_sum / $max);

        if (!isset($_GET['page'])) {
            $page = 1;
        }else{
            $page = $_GET['page'];
        }

        $start = $max * ($page - 1);
        $view_page = array_slice($tableContent, $start, $max, true);
        ?>

        <!--list of jobs-->
        <ul class="jobs">
                <?php
                foreach ($view_page as $value) {
                    echo $value;
                }
                ?>
        </ul>

        <!--pagination html-->
            <ul class="pagination" id="pagination">
                <?php  if ($page > 1): ?>
                    <li><a href="freelancer_list.php?page=<?php echo ($page-1); ?>"><?php echo $page-1 ?></a></li>
                    <li><?php echo $page ?></li>
                    <li><a href="freelancer_list.php?page=<?php echo ($page+1); ?>"><?php echo $page+1 ?></a></li>
                <?php endif; ?>
                <?php  if ($page < $max_page): ?>
                    <li><?php echo $page ?></li>
                    <li><a href="freelancer_list.php?page=<?php echo ($page+1); ?>"><?php echo $page+1 ?></a></li>
                    <li><a href="freelancer_list.php?page=<?php echo ($page+2); ?>"><?php echo $page+2 ?></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <!--footer-->
    <?php require_once('template/footer.php') ?>



</body>

</html>