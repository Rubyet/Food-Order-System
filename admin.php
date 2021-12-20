<?php 
	session_start();
    require_once("connection.php");
    $conn = new DBController();
    $user_id = isset($_SESSION['user_id']) ? trim($_SESSION['user_id']) : '';
    $user_name = isset($_SESSION['number']) ? trim($_SESSION['number']) : '';
    if($user_name!='admin')
    {
        header("Location: /index.php");
        die();
    }

	if (isset($_POST['save'])) {
        
		$name = $_POST['name'];
		$price = $_POST['price'];
        $category = $_POST['category'];
        $code = '';
        if($category == 1)
        {
            $code = "BR".rand(100000,999999);
        }
        elseif($category == 2)
        {
            $code = "LN".rand(100000,999999);
        }
        elseif($category == 3)
        {
            $code = "DN".rand(100000,999999);
        }
        elseif($category == 4)
        {
            $code = "DR".rand(100000,999999);
        }
        $date = new DateTime();
        $date = $date->getTimestamp();

        $filename = $date.$_FILES["uploadfile"]["name"];
        $tempname = $_FILES["uploadfile"]["tmp_name"];    
        $folder = "product-images/".$filename;
        
        try {
            move_uploaded_file($tempname, $folder);
            $query = "INSERT INTO products (name, code, image, price, category) VALUES ('$name', '$code', '$folder', '$price', '$category')";
            //die($query);
            $conn->runQuery($query); 
        } catch (Exception $e) {
            echo 'and the error is: ',  $e->getMessage(), "\n";
        }
        
		$_SESSION['message'] = "Product Saved To Menu"; 
		header('location: admin.php');
	}
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $file= $conn->runQuery("SELECT * FROM products WHERE id=$id")[0]['image'];
        if($file!='') {
            unlink($file);
        } 
        $conn->runQuery("DELETE FROM products WHERE id=$id");
        
        $_SESSION['message'] = "Products deleted!"; 
        header('location: admin.php');
    }

// ...
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
</head>
<body>
    <style>
        body {
            font-size: 19px;
        }
        table{
            width: 70%;
            margin: 30px auto;
            border-collapse: collapse;
            text-align: left;
        }
        tr {
            border-bottom: 1px solid #cbcbcb;
        }
        th, td{
            border: none;
            height: 30px;
            padding: 2px;
        }
        tr:hover {
            background: #F5F5F5;
        }

        form {
            width: 45%;
            margin: 50px auto;
            text-align: left;
            padding: 20px; 
            border: 1px solid #bbbbbb; 
            border-radius: 5px;
        }

        .input-group {
            margin: 10px 0px 10px 0px;
        }
        .input-group label {
            display: block;
            text-align: left;
            margin: 3px;
        }
        .input-group input{
            height: 30px;
            width: 93%;
            padding: 5px 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid gray;
        }
        .input-group select {
            height: 30px;
            width: 97%;
            padding: 5px 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid gray;
        }
        .btn {
            padding: 10px;
            font-size: 15px;
            color: white;
            background: #5F9EA0;
            border: none;
            border-radius: 5px;
        }
        .edit_btn {
            text-decoration: none;
            padding: 2px 5px;
            background: #2E8B57;
            color: white;
            border-radius: 3px;
        }

        .del_btn {
            text-decoration: none;
            padding: 2px 5px;
            color: white;
            border-radius: 3px;
            background: #800000;
        }
        .msg {
            margin: 30px auto; 
            padding: 10px; 
            border-radius: 5px; 
            color: #3c763d; 
            background: #dff0d8; 
            border: 1px solid #3c763d;
            width: 50%;
            text-align: center;
        }
        img.item-image {
            width: 125px;
            height: 125px;
            object-fit: cover;
        }
        .img-grid{
            display: inline-grid;
            min-height: 150px;
        }
    </style>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="msg">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
            ?>
        </div>
    <?php endif ?>
    <h1>Add Product</h1>
	<form method="post" action="admin.php" enctype="multipart/form-data">
		<div class="input-group">
			<label>Product Name</label>
			<input type="text" name="name" value="" required>
		</div>
		<div class="input-group">
			<label>Price</label>
			<input type="text" name="price" value="" required>
		</div>
		<div class="input-group">
			<label>Image</label>
			<input type="file" name="uploadfile" id="fileToUpload" required>
		</div>
        <div class="input-group">
            <label>Category</label>
            <select class="form-control" name="category" required>
                <option value="1">Breakfast</option>
                <option value="2">Lunch</option>
                <option value="3">Dinner</option>
                <option value="4">Drinks</option>
            </select>
		</div>
		<div class="input-group">
			<button class="btn" type="submit" name="save" >Save</button>
		</div>
	</form>


    <?php $results = $conn->runQuery("SELECT * FROM products"); ?>
    <?php
        if(empty($results))
        {
            echo "No Product Found";
        }
        else{
    ?>
    <table>
        <thead>
            <tr>
                <th style="text-align:left;">Name</th>
                <th style="text-align:left;">Code</th>
                <th style="text-align:right;" width="">Unit Price</th>
                <!-- <th style="text-align:center;" width="10%"> Edit </th> -->
                <th style="text-align:center;" width="10%">Remove</th>
            </tr>
        </thead>
        
        <?php		
        foreach ($results as $item){
        ?>
            <tr>
                <td class="img-grid"><img src="<?php echo $item["image"]; ?>" class="item-image" /><?php echo $item["name"]; ?></td>
                <td><?php echo $item["code"]; ?></td>
                <td  style="text-align:right;"><?php echo "$ ".$item["price"]; ?></td>
                <!-- <td style="text-align:center;"><a href="/admin.php?edit=<?php //echo $item['id']; ?>" class="btnRemoveAction"><img src="details.png" alt="Remove Item" /></a></td> -->
                <td style="text-align:center;"><a href="/admin.php?del=<?php echo $item['id']; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
        }
    ?>
    
</body>
</html>