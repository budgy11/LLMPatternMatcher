

<?php
session_start();

// Sample products (for demonstration purposes)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15],
];

// Function to add an item to the cart
function addToCart($product_id, $quantity = 1)
{
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] = $_SESSION['cart'][$product_id] + $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to display the cart
function displayCart()
{
    if (isset($_SESSION['cart'])) {
        echo "<h2>Your Cart</h2>";
        echo "<ul>";
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $product_name = $products[$product_id]['name'];
            $product_price = $products[$product_id]['price'];
            $total_price = $product_price * $quantity;

            echo "<li>$product_name - $" . number_format($product_price, 2) . " x " . $quantity . " = $" . number_format($total_price, 2) . "</li>";
        }
        echo "</ul>";

        // Calculate total cart value
        $total = 0;
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $total += $products[$product_id]['price'] * $quantity;
        }
        echo "<p><strong>Total Cart Value: $" . number_format($total, 2) . "</strong></p>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }
}


// Handle adding to cart requests (e.g., from a button click)
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);  // Ensure product_id is an integer
    $quantity = intval($_POST['quantity']) ?? 1;   // Default quantity to 1
    addToCart($product_id, $quantity);
}

// Display the cart
displayCart();

?>
