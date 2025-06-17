

<?php

// Configuration
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20.00],
    2 => ['name' => 'Jeans', 'price' => 50.00],
    3 => ['name' => 'Hat', 'price' => 15.00],
];

$cart = [];

// Function to add a product to the cart
function addToCart($productId, $quantity = 1)
{
    if (isset($products[$productId])) {
        $product = $products[$productId];
        $cart[] = ['product_id' => $productId, 'name' => $product['name'], 'price' => $product['price'], 'quantity' => $quantity];
        echo "<p>Added " . $quantity . " x " . $product['name'] . " to your cart.</p>";
    } else {
        echo "<p>Product with ID " . $productId . " not found.</p>";
    }
}

// Function to display the cart
function displayCart()
{
    echo "<h2>Your Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $item) {
        $product = $products[$item['product_id']];
        $total_price = $product['price'] * $item['quantity'];
        echo "<li>" . $item['name'] . " - $" . number_format($item['quantity'] * $product['price'], 2) . "</li>";
    }
    echo "</ul>";

    // Calculate total cart price
    $totalPrice = 0;
    foreach ($cart as $item) {
        $totalPrice += $item['quantity'] * $products[$item['product_id']]['price'];
    }

    echo "<p><strong>Total: $" . number_format($totalPrice, 2) . "</strong></p>";
}


// Handle form submission (Adding to Cart)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a product ID is submitted
    if (isset($_POST["product_id"]) && isset($_POST["quantity"])) {
        $productId = (int)$_POST["product_id"]; // Cast to integer
        $quantity = (int)$_POST["quantity"]; // Cast to integer

        addToCart($productId, $quantity);
    }
}


?>
