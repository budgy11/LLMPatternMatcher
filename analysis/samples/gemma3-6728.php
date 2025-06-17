

<?php
session_start();

// Initialize the cart (empty array if it doesn't exist)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = ['name' => $product_name, 'price' => $price, 'quantity' => $quantity];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}

// Example Usage (simulated product data - replace with your database or other source)
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
];

// Simulated form processing (e.g., from a form submission)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
        $product_id = $_POST['product_id'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Default quantity is 1

        if (isset($products[$product_id])) {
            addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
        } else {
            // Handle invalid product ID (e.g., display an error message)
            echo "Invalid product ID: " . $product_id;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'remove_from_cart') {
        $product_id = $_POST['product_id'];
        removeFromCart($product_id);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
        $product_id = $_POST['product_id'];
        $new_quantity = (int)$_POST['quantity'];
        updateQuantity($product_id, $new_quantity);
    }
}

// Display the cart content
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<input type='submit' name='action' value='Remove from Cart'>";
        echo "</form>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<input type='number' name='quantity' value='" . $product_details['quantity'] . "' min='1' max='10'>";
        echo "<input type='submit' name='action' value='Update Quantity'>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";
}

?>
