

<?php
session_start(); // Start the session

// Sample product data (replace with your actual product data)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Function to add a product to the cart
function addToCart($productId, $quantity = 1)
{
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] ?? 0;
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] + $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}


// Example usage - Handling adding items to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        addToCart($productId, $quantity);
        // Redirect to the same page to update the cart display
        header("Location: cart.php"); // Redirect to the cart page
        exit();
    }
}



// Display the cart contents (cart.php would handle this)
echo "<h1>Your Shopping Cart</h1>";

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $products[$productId];
        echo "<li>";
        echo "Product: " . $product['name'] . "<br>";
        echo "Quantity: " . $quantity . "<br>";
        echo "Price per item: $" . $product['price'] . "<br>";
        echo "Total: $" . ($product['price'] * $quantity) . "<br>";
        echo "</li>";
    }
    echo "</ul>";

    // Calculate total cost
    $totalCost = 0;
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $products[$productId];
        $totalCost += ($product['price'] * $quantity);
    }

    echo "<p><b>Total Cost: $" . $totalCost . "</b></p>";

} else {
    echo "<p>Your cart is empty.</p>";
}

?>
