

<?php
session_start();

// Sample product data (for demonstration)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Function to add an item to the cart
function addToCart($productId, $quantity = 1)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = ['quantity' => $quantity];
    }
}

// Function to display the cart contents
function displayCart()
{
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Your Cart</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $productId => $item) {
        $product = $products[$productId];
        $total = $product['price'] * $item['quantity'];
        echo "<li>" . $product['name'] . " - $" . $product['price'] . " x " . $item['quantity'] . " = $" . $total . "</li>";
    }
    echo "</ul>";
}

// Example Usage:

// Add a product to the cart
if (isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id']; // Ensure product_id is an integer
    addToCart($productId);
}

// Display the cart
displayCart();

?>
