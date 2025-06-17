

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// --- Function to add an item to the cart ---
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If yes, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If no, add the product to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// --- Example Usage (You'd typically get these from a database or form) ---

// Add a product to the cart
addToCart(1, 'T-Shirt', 20.00, 2); // product_id, name, price, quantity

// Add another product to the cart
addToCart(2, 'Jeans', 50.00, 1);

// --- Function to update the quantity of an item in the cart ---
function updateCartQuantity($product_id, $new_quantity) {
  if (!isset($_SESSION['cart'][$product_id])) {
    // Product not found, you might want to handle this (e.g., log an error or return an error message)
    return false;
  }

  $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  return true;
}

// Example: Update the quantity of product 1 to 3
updateCartQuantity(1, 3);


// --- Function to remove an item from the cart ---
function removeItemFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Example: Remove product 2 from the cart
removeItemFromCart(2);

// --- Function to display the cart contents ---
function displayCart() {
    echo "<h2>Shopping Cart</h2>";

    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($_SESSION['cart'] as $product_id => $product_details) {
            echo "<li>";
            echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
        }
        echo "</ul>";
    }
}

// --- Call the display function to show the cart contents ---
displayCart();

// ---  Session Data (Cleanup - good practice to clear out session data) ---
//  This is just an example, you'd typically do this on logout or when the user leaves the site.
//  Don't do this *every* time the page loads!
//session_unset();
//session_destroy();

?>
