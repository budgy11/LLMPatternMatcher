

<?php
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the item already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If it exists, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If it doesn't exist, add a new item to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}


// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (!isset($_SESSION['cart'][$product_id])) {
    return false; // Item not found in cart
  }

  if ($new_quantity <= 0) {
    // Remove the item if quantity is zero or negative
    unset($_SESSION['cart'][$product_id]);
    return true;
  }

  $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  return true;
}


// Function to remove an item from the cart
function removeItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    return true;
}


// Function to display the cart contents
function displayCart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $product_info) {
        echo "<li>";
        echo "<strong>" . $product_info['name'] . "</strong> - $" . $product_info['price'] . "</li>";
        echo "<form method='post' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>"; // Use htmlspecialchars for security
        echo "<label for='quantity_" . $product_id . "'>Quantity:</label>";
        echo "<input type='number' id='quantity_" . $product_id . "' value='" . $product_info['quantity'] . "' min='1' max='100' name='quantity_" . $product_id . "'/>"; // Added min/max attributes for validation
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<button type='submit'>Update</button>";
        echo "</form>";
    }
    echo "</ul>";
}


// Example Usage - You would typically get this data from a form or database
// For demonstration purposes, we'll add a few items to the cart.
addToCart(1, "Shirt", 20, 2);
addToCart(2, "Pants", 30, 1);
addToCart(1, "Shirt", 20, 3); //Add more of the shirt
addToCart(3, "Shoes", 50, 1);

// Display the cart contents
displayCart();

// Example of updating quantity
if (isset($_POST['quantity_1'])) {
    $newQuantity = intval($_POST['quantity_1']);
    if (updateQuantity(1, $newQuantity)) {
        echo "<p>Quantity of Shirt updated to " . $newQuantity . "</p>";
    } else {
        echo "<p>Error updating quantity for Shirt.</p>";
    }
}

//Example of removing an item
if (isset($_POST['remove_1'])) {
    removeItem(1);
    echo "<p>Shirt removed from cart.</p>";
}

// Display the cart contents after updates or removals
displayCart();

?>
