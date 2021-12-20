<?php
    session_start();
    require_once("connection.php");
    $conn = new DBController();
    $user_id = isset($_SESSION['user_id']) ? trim($_SESSION['user_id']) : '';
    if($user_id =='')
    {
        header("Location: /index.php");
        die();
    }
    $ordersById = $conn->runQuery("SELECT * FROM order_table WHERE 	user_id = ".$user_id." ORDER BY created_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <link href="style.css" type="text/css" rel="stylesheet" />
</head>
<body>
    
<h1>My Orders</h1>

<div id="shopping-cart">
    <?php
        if(empty($ordersById))
        {
            echo "No Orders Found";
        }
        else{
    ?>
    <table class="tbl-cart" cellpadding="10" cellspacing="1">
        <tbody>
            <tr>
                <th style="text-align:left;">Date & time</th>
                <th style="text-align:right;" width="10%">Amount</th>
                <th style="text-align:center;">Details</th>
            </tr>	
            <?php		
                foreach ($ordersById as $item){
                    ?>
                    <tr>
                        <td><?php echo $item["created_date"]; ?></td>
                        <td style="text-align:right;"><?php echo $item["total_amount"]; ?></td>
                        <td style="text-align:center;"><a href="/order_details.php?id=<?php echo $item["id"]; ?>" class="btnRemoveAction"><img src="details.png" alt="Remove Item" /></a></td>
                    </tr>
                    <?php
                    }
                    ?>
        </tbody>
    </table>
    <?php
    }
    ?>
</div>
<a id="btnEmpty" href="/index.php">Back to Index</a>
</body>
</html>