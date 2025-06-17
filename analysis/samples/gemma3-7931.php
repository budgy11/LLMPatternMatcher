
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Form</title>
</head>
<body>

<h1>Purchase Products</h1>

<form method="post" action="purchase.php">
    <label for="product_ids">Product IDs:</label>
    <input type="text" id="product_ids" name="product_ids" value="<?php echo isset($_GET['product_ids']) ? $_GET['product_ids'] : ''; ?>"  placeholder="Enter product IDs (comma-separated)">
