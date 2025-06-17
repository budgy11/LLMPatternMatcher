

<?php
// Start the session
session_start();

// Check if the cart is empty - prevent displaying cart if empty
if (empty($_SESSION['cart'])) {
    header("Location: index.php"); // Redirect if cart is empty
    exit();
}

// Display the cart contents
echo "<h1>Your Shopping Cart</h1>";

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

// Buttons to remove items from the cart (you'll need to add form elements for this)
// ... (Implementation for removing items)

// Clear the cart on checkout (example)
// if (isset($_POST['checkout'])) {
//     unset($_SESSION['cart']);
//     header("Location: index.php"); // Redirect to the home page
//     exit();
// }
?>
