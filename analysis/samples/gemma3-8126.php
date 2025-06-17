
<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        .cart-item {
            margin-bottom: 10px;
        }
        .cart-total {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1>Shopping Cart</h1>

<?php
// Display Cart Items
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

    foreach ($_SESSION['cart'] as $productId => $item) {
        $product = $products[$productId]; // Get product details
        $totalItemPrice = $product['price'] * $item['quantity'];
        echo "<tr class='cart-item'>";
        echo "<td>" . $product['name'] . "</td>";
        echo "<td>$" . $product['price'] . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>$" . $totalItemPrice . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo "<div class='cart-total'>Total: $" . calculateTotal() . "</div>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>
