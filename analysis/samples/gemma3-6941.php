
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Form</title>
</head>
<body>

<h1>Make a Purchase</h1>

<form method="post" action="purchase.php">
    <label for="productName">Product Name:</label>
    <input type="text" id="productName" name="productName" value="<?php echo htmlspecialchars($productName); ?>">
