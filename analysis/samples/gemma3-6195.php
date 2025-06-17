

<?php

// Start a session if it doesn't exist
if (!session_id()) {
    session_start();
}

// Cart data -  This can be stored in a database or a more sophisticated system
// For simplicity, we'll store it in the session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Helper functions
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity,
            'total' => $price * $quantity
        ];
    } else {
        // If the product already exists, update the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
    }
}

function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

function update_quantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
    }
}


// Example Usage:
// Add a product to the cart
add_to_cart(1, "T-Shirt", 20, 2);
add_to_cart(2, "Jeans", 50, 1);

// Display the cart contents
echo "<h2>Your Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item) {
        echo "<li>";
        echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['total'];
        echo " <a href='update_cart.php?product_id=" . $product_id . "&quantity='>Update Quantity</a> | <a href='remove_from_cart.php?product_id=" . $item_id . "'>Remove</a></li>";
    }
    echo "</ul>";
}



// Example functions for updating/removing:

// Function to update quantity (update_cart.php would handle this)
//  $product_id = the ID of the product to update
//  $quantity = the new quantity to set
// function update_quantity($product_id, $quantity) {
//     if (isset($_SESSION['cart'][$product_id])) {
//         $_SESSION['cart'][$product_id]['quantity'] = $quantity;
//         $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
//     }
// }


// Function to remove from cart (remove_from_cart.php would handle this)
// function remove_from_cart($product_id) {
//     if (isset($_SESSION['cart'][$product_id])) {
//         unset($_SESSION['cart'][$product_id]);
//     }
// }


?>
