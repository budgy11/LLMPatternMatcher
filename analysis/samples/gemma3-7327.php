

<?php

// Simulated Product Data (Replace with a database query in a real application)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00],
    2 => ['id' => 2, 'name' => 'Mug', 'price' => 10.00],
    3 => ['id' => 3, 'name' => 'Book', 'price' => 15.00],
];

// Initialize the cart
$cart = [];

// Function to add an item to the cart
function addToCart($productId, $cart) {
    if (array_key_exists($productId, $products)) {
        $product = $products[$productId];
        $cart[] = $product;
        return true;
    }
    return false;
}

// Function to calculate the cart total
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'];
    }
    return $total;
}

// Handle form submission (adding items to the cart)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $productId = $_POST['product_id'];
        addToCart($productId, $cart);
    }
}
?>
