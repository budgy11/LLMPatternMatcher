

<?php

// 1. Initialize the Session (if not already done)
session_start();

// 2. Product Data (Replace with your actual product information)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Shoes', 'price' => 80],
];

// 3. Handle Adding to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    if (isset($products[$product_id])) {
        $product = $products[$product_id];

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = []; // Initialize the cart array
        }

        // Check if the product is already in the cart
        $product_exists = false;
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                // Product already in cart, increment quantity
                $_SESSION['cart'][$key]['quantity']++;
                $product_exists = true;
                break;
            }
        }

        if (!$product_exists) {
            // Product not in cart, add it with quantity 1
            $_SESSION['cart'][] = $product;
        }
    }
}

// 4. Handle Removing from Cart
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];

    if (isset($_SESSION['cart']) && isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        // Optionally, remove any lingering keys from the array if needed
        while (keyexists($_SESSION['cart'], key($session['cart']))) {
           unset($_SESSION['cart'][key($session['cart'])]);
        }
    }
}


// 5. Display Cart Contents
if (isset($_SESSION['cart'])) {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>";
        echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . "</li>";
        $total += $item['price'];
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $total . "</strong></p>";
} else {
    echo "<p>Your cart is empty.</p>";
}

?>
