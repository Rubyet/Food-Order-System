<?php
session_start();
require_once("connection.php");
$conn = new DBController();
$user_id = isset($_SESSION['user_id']) ? trim($_SESSION['user_id']) : '';
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = $conn->runQuery("SELECT * FROM products WHERE code='" . $_GET["code"] . "'");
			$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode[0]["code"] == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			} else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["code"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
	break;	
    case "store":
        if(!empty($_SESSION["cart_item"])) {

            $userId = $_SESSION["user_id"];
            $amount = $_SESSION["total_price"];
            $query = "INSERT INTO order_table (user_id, total_amount) VALUES('$userId', '$amount')";
            $query1 = "SELECT LAST_INSERT_ID()";
            
            $conn->runQuery($query);
            // echo "<br>";
            $order_id = $conn->runQuery($query1);
            $order_id = $order_id[0]['LAST_INSERT_ID()'];
            // print_r($order_id);

            foreach($_SESSION["cart_item"] as $k => $v) {
                // print_r($v);
                // echo "<br>";
                

                //die();
                $query = "INSERT INTO order_details (code, order_id, quantity) VALUES('$v[code]', '$order_id', '$v[quantity]')";
                print_r($query);
                echo "<br>";
                $conn->runQuery($query);
                

                
            }
            unset($_SESSION['cart_item']);
            unset($_SESSION['total_price']);
            header('location: index.php');
        }
	break;
}
}
?>
<HTML>
<HEAD>
<TITLE>Simple PHP Shopping Cart</TITLE>
<link href="style.css" type="text/css" rel="stylesheet" />
</HEAD>
<BODY>
<h1>
        <?
            if($user_id !=''){
                echo "Welcome, " . $user_id ;
            }
        ?>
    </h1>
    <br>
    <?php
        if($user_id !=''){
            echo "<a href='logout.php'>Logout</a>";
        }
        else
        {
            echo "
            <a href='login.php'>Login</a>
            <br>
            <a href='register.php'>Register</a>
            ";
        }
    ?>
    <div id="shopping-cart">
    <div class="txt-heading">Shopping Cart</div>

    <a id="btnEmpty" href="/menu.php?action=empty">Empty Cart</a>
    <?php
    if(isset($_SESSION["cart_item"])){
        $total_quantity = 0;
        $total_price = 0;
    ?>	
    <table class="tbl-cart" cellpadding="10" cellspacing="1">
    <tbody>
        <tr>
            <th style="text-align:left;">Name</th>
            <th style="text-align:left;">Code</th>
            <th style="text-align:right;" width="5%">Quantity</th>
            <th style="text-align:right;" width="10%">Unit Price</th>
            <th style="text-align:right;" width="10%">Price</th>
            <th style="text-align:center;" width="5%">Remove</th>
        </tr>	
    <?php		
        foreach ($_SESSION["cart_item"] as $item){
            $item_price = $item["quantity"]*$item["price"];
            ?>
                    <tr>
                    <td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
                    <td><?php echo $item["code"]; ?></td>
                    <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
                    <td  style="text-align:right;"><?php echo "$ ".$item["price"]; ?></td>
                    <td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
                    <td style="text-align:center;"><a href="/menu.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
                    </tr>
                    <?php
                    $total_quantity += $item["quantity"];
                    $total_price += ($item["price"]*$item["quantity"]);
            }
            ?>

    <tr>
    <td colspan="2" align="right">Total:</td>
    <td align="right"><?php echo $total_quantity; ?></td>
    <td align="right" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2);$_SESSION["total_price"]=$total_price; ?></strong></td>
    <td></td>
    </tr>
    </tbody>
    </table>
    <a id="btnEmpty" href="/menu.php?action=store">Confirm Order</a>
    <?php
    } else {
    ?>
    <div class="no-records">Your Cart is Empty</div>
    <?php 
    }
    ?>
    </div>

    <?php 
        for($i=1; $i<=4; $i++){
    ?>
    <div>
        <div id="product-grid">
            <div class="txt-heading">
                <?php
            switch ($i) {
                case 1:
                    echo "Breakfast";
                    break;
                case 2:
                    echo "Lunch";
                    break;
                case 3:
                    echo "Dinner";
                    break;
                case 4:
                    echo "Drinks";
                    break;
                default:
                    echo "No product found";
                }
                ?>
            </div>
            <?php
            $product_array = $conn->runQuery("SELECT * FROM products WHERE category = $i ORDER BY id ASC");
            if (!empty($product_array)) { 
                foreach($product_array as $key=>$value){
            ?>
                <div class="product-item">
                    <form method="post" action="/menu.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
                    <div class="product-image"><img src="<?php echo $product_array[$key]["image"]; ?>"></div>
                    <div class="product-tile-footer">
                    <div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
                    <div class="product-price"><?php echo "$".$product_array[$key]["price"]; ?></div>
                    <?php
                        isset($_SESSION['user_id']) ? print_r('<div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>') : '';
                    ?>
                    
                    </div>
                    </form>
                </div>
            <?php
                }
            }
            ?>
        </div>
    </div> 
    <?php 
        }
    ?> 
    
    
</BODY>
</HTML>