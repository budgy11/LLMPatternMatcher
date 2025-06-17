

<?php

session_start();

// Assuming you have a database connection established elsewhere (e.g., in a config file or class)
// For simplicity, we'll represent the database connection with a variable 'db'
// In a real application, you'd use mysqli, PDO, or your preferred database library.

// Sample Product Data (replace with your actual data source)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// Function to add an item to the cart
function addToCart($product_id, $quantity = 1)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        // Item already in cart, increment quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Item not in cart, add it
        $_SESSION['cart'][$product_id] = [
            'id' => $product_id,
            'name' => $products[$product_id]['name'],
            'price' => $products[$product_id]['price'],
            'quantity' => $quantity
        ];
    }
}

// Function to view the cart
function displayCart()
{
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Shopping Cart</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item_details) {
        echo "<li>";
        echo "<strong>" . $item_details['name'] . "</strong> - $" . $item_details['price'] . " (Quantity: " . $item_details['quantity'] . ")";
        echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
        echo "<input type='hidden' name='product_id' value='" . $item_id . "'>";
        echo "<input type='submit' name='remove' value='Remove'> ";
        echo "</li>";
    }
    echo "</ul>";

    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
    echo "<input type='submit' name='empty_cart' value='Empty Cart'>";
    echo "</form>";
}

// Handle actions from the cart page

// Remove an item from the cart
if (isset($_POST['remove'])) {
    $product_id = $_POST['remove'];
    unset($_SESSION['cart'][$product_id]);
}

// Empty the entire cart
if (isset($_POST['empty_cart'])) {
    unset($_SESSION['cart']);
}

// Display the cart
displayCart();

?>
