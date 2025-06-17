
<!DOCTYPE html>
<html>
<head>
    <title>Simple Purchase Functionality</title>
</head>
<body>

<h1>Products</h1>

<?php
foreach ($products as $id => $product) {
    echo "<div>";
    echo "<strong>" . $product['name'] . "</strong> - $" . $product['price'] . "<br>";
    echo "<form action='' method='post'>"; // Dummy action
    echo "<input type='hidden' name='item_id' value='" . $id . "'>";
    echo "<input type='number' name='quantity' value='1' min='1'>";
    echo "<input type='submit' value='Add to Cart'>";
    echo "</form>";
    echo "</div>";
}
?>
