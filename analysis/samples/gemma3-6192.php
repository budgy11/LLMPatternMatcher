

<?php

// Start a session if it's not already started
session_start();

// Initialize the cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Increment quantity if already in cart
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add the product to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
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


// --- Example Usage (Handling Form Submission -  Illustrative) ---

// If the form is submitted (e.g., adding to cart)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for adding to cart
    if (isset($_POST['action']) && $_POST['action'] == 'addToCart') {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];

        addToCart($product_id, $product_name, $price, $quantity);
    }

    // Check for removing an item
    if (isset($_POST['action']) && $_POST['action'] == 'removeFromCart') {
        $product_id = $_POST['product_id'];
        removeFromCart($product_id);
    }

     // Check for updating quantity
    if (isset($_POST['action']) && $_POST['action'] == 'updateQuantity') {
        $product_id = $_POST['product_id'];
        $new_quantity = $_POST['quantity'];
        updateQuantity($product_id, $new_quantity);
    }
}

// --- Displaying the Cart (Example) ---

// Output the cart contents
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

?>
