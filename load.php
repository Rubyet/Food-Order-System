<?php
    require_once "connection.php";
    $log_id = 0;
    if(isset($_POST['ID']))
    {
        $log_id = intval($_POST['ID']);
    }
    else
    {
        $log_id = 0;
    }
    
    $date = date('Y-m-d');
    if($log_id !=0)
    {
        $htmls = $conn->query("SELECT `id`,`html` FROM `log_history` WHERE `user`= 1 AND `id` = ".$log_id." ORDER BY id DESC limit 1");
    }
    else
    {
        $htmls = $conn->query("SELECT `id`,`html` FROM `log_history` WHERE `user`= 1 ORDER BY id DESC limit 1");
    }
    if ($htmls->num_rows > 0) {
        // output data of each row
        while($row = $htmls->fetch_object()) {
            echo $row->html;
            $log_id=$row->id;
            echo '<script>id='.$log_id.'</script>';
        }
    } else {
        echo "yo";
        echo "Error: " . $sql . "" . mysqli_error($conn);
    }

    
 
    mysqli_close($conn);
 