

<?php

// Sample Product Data (Replace with your database or other data source)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Initialize variables
$cart = [];
$total = 0;

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    global $cart, $total;

    if (array_key_exists($product_id, $products)) {
        $product = $products[$product_id];
        $item_price = $product['price'] * $quantity;

        // Check if the item is already in the cart
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = ['quantity' => $quantity, 'price' => $product['price']];
        }

        $total += $item_price;
    } else {
        echo "<p>Product with ID: $product_id not found.</p>";
    }
}


// Handle Form Submission (Add to Cart Logic)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = (int)$_POST['product_id']; //Cast to integer for safety
        $quantity = (int)$_POST['quantity']; //Cast to integer for safety

        if ($quantity > 0) {
            add_to_cart($product_id, $quantity);
        } else {
            echo "<p>Quantity must be greater than 0.</p>";
        }
    }
}

// Display Cart Contents
echo "<h2>Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $item) {
        echo "<li>" . $products[$product_id]['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . $total . "</p>";
}

?>
