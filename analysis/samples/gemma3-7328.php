
<!DOCTYPE html>
<html>
<head>
    <title>Simple Purchase</title>
</head>
<body>

<h1>Shopping Cart</h1>

<?php if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<h2>Cart Contents:</h2>";
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . "</li>";
    }
    echo "</ul>";

    $total = calculateTotal($cart);
    echo "<p><strong>Total: $" . $total . "</strong></p>";
}
?>
