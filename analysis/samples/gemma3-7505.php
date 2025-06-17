

<?php

// Define product data (replace with your database or data source)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00],
];

// Function to add a product to the cart
function addToCart($productId, $cart) {
    if (isset($products[$productId])) {
        $product = $products[$productId];
        $cart[] = $product;
    } else {
        echo "Product ID $productId not found.";
    }
}

// Function to display the cart
function displayCart($cart) {
    echo "<h2>Your Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $product) {
        echo "<li>" . $product['name'] . " - $" . number_format($product['price'], 2) . "</li>";
    }
    echo "</ul>";

    // Calculate the total
    $total = array_sum(array_column($cart, 'price'));
    echo "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";
}

// Handle purchase request (simulated)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];

        //  Add the product to the cart (simulated)
        addToCart($productId, $cart); 

        //  Update the cart (simulated - in a real app, you'd likely update a database)
        displayCart($cart);  // Display the cart after the "purchase"
    }
}

// Initialize the cart (empty array)
$cart = [];

?>
