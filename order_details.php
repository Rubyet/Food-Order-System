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
    $ordersById = isset($_GET['id']) ? trim($_GET['id']) : '';
    $total_quantity = 0;
    $total_price = 0;
    $ordersByOrderId = $conn->runQuery("SELECT * FROM order_details WHERE 	order_id = ".$ordersById."");
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
    
<h1>Details</h1>

<div id="shopping-cart">
    <table class="tbl-cart" cellpadding="10" cellspacing="1">
        <tbody>
            <tr>
                <th style="text-align:left;">Name</th>
                <th style="text-align:left;">Code</th>
                <th style="text-align:right;" width="5%">Quantity</th>
                <th style="text-align:right;" width="10%">Unit Price</th>
                <th style="text-align:right;" width="10%">Price</th>
            </tr>	
            <?php		
                foreach ($ordersByOrderId as $item){
                    $productByCode = $conn->runQuery("SELECT * FROM products WHERE code='" . $item["code"] . "'");
			        $itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$item["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
                    
                    $item_price = $item["quantity"]*$productByCode[0]["price"];
                    ?>
                            <tr>
                            <td><img src="<?php echo $productByCode[0]["image"]; ?>" class="cart-item-image" /><?php echo $productByCode[0]["name"]; ?></td>
                            <td><?php echo $item["code"]; ?></td>
                            <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
                            <td  style="text-align:right;"><?php echo "$ ".$productByCode[0]["price"]; ?></td>
                            <td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
                            </tr>
                            <?php
                            $total_quantity += $item["quantity"];
                            $total_price += ($productByCode[0]["price"]*$item["quantity"]);
                    }
                    ?>

            <tr>
                <td colspan="2" align="right">Total:</td>
                <td align="right"><?php echo $total_quantity; ?></td>
                <td align="right" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2);$_SESSION["total_price"]=$total_price; ?></strong></td>
            </tr>
        </tbody>
    </table>
</div>
<a id="btnEmpty" href="/account.php">Back to Orders</a>

</body>
</html>