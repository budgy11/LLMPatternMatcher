
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order</title>
</head>
<body>

<h1>Purchase Order</h1>

<form method="post" action="purchase.php">
    <h2>Select Items:</h2>
    <label for="item1">Item 1 (Quantity):</label>
    <input type="number" id="item1" name="items[]" value="0" > <br><br>

    <label for="item2">Item 2 (Quantity):</label>
    <input type="number" id="item2" name="items[]" value="0" > <br><br>

    <label for="item3">Item 3 (Quantity):</label>
    <input type="number" id="item3" name="items[]" value="0" > <br><br>

    <input type="submit" value="Place Order">
</form>

<br>

<h2>Order Summary:</h2>
<?php if (empty($order)): ?>
