

<?php
session_start();

// --- Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id  The ID of the product being added.
 * @param int    $quantity   The quantity of the product to add.
 * @return void
 */
function add_to_cart(string $product_id, int $quantity) {
  // Check if the cart already exists.  If not, create it.
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart.
  if (isset($_SESSION['cart'][$product_id])) {
    // Increment the quantity if the product exists
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Add the product to the cart with a quantity of 1.
    $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
  }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int    $quantity The new quantity.
 * @return void
 */
function update_cart_quantity(string $product_id, int $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart(string $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Clears the entire cart.
 *
 * @return void
 */
function clear_cart() {
    unset($_SESSION['cart']);
}


// --- Example Usage (Demonstration) ---

// Add some items to the cart
add_to_cart('product1', 2);
add_to_cart('product2', 1);
add_to_cart('product1', 3); // Add more of product1


// Display the contents of the cart
echo "<h2>Your Cart:</h2>";
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>" . $item['quantity'] . " x " . "<a href='details.php?product_id=$product_id'>$product_id</a> (Price: $5)</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}

// Update the quantity of product1 to 5
update_cart_quantity('product1', 5);

// Display the updated cart
echo "<h2>Your Cart (Updated):</h2>";
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>" . $item['quantity'] . " x " . "<a href='details.php?product_id=$product_id'>$product_id</a> (Price: $5)</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}

// Remove product2 from the cart
remove_from_cart('product2');

// Display the updated cart (after removing an item)
echo "<h2>Your Cart (After Removal):</h2>";
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>" . $item['quantity'] . " x " . "<a href='details.php?product_id=$product_id'>$product_id</a> (Price: $5)</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Your cart is empty.</p>";
}


// Clear the cart
// clear_cart();

// Display the final empty cart
// echo "<h2>Your Cart (Empty):</h2>";
// if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
//     echo "<ul>";
//     foreach ($_SESSION['cart'] as $product_id => $item) {
//         echo "<li>" . $item['quantity'] . " x " . "<a href='details.php?product_id=$product_id'>$product_id</a> (Price: $5)</li>";
//     }
//     echo "</ul>";
// } else {
//     echo "<p>Your cart is empty.</p>";
// }

?>
