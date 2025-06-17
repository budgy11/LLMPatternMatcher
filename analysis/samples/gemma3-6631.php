

<?php
session_start();

// --- Initialize the Cart (if it doesn't exist) ---
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add.
 * @return void
 */
function add_to_cart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = 0; // Initialize quantity if not already present
  }
  $_SESSION['cart'][$product_id] += $quantity;
}

/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function remove_from_cart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity of the product.
 * @return void
 */
function update_cart_quantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Gets all items in the cart.
 *
 * @return array An array containing the items in the cart.
 */
function get_cart_items() {
  return $_SESSION['cart'];
}

/**
 * Calculates the total price of the cart.
 *
 * @param array $cart_items  An array of cart items (product_id => price).
 * @return float The total price.
 */
function calculate_total_price($cart_items) {
    $total = 0;
    foreach ($cart_items as $product_id => $quantity) {
        //  Assume you have a database query or function to fetch the product price
        $price = get_product_price($product_id); //Replace with your actual function
        $total_for_item = $price * $quantity;
        $total += $total_for_item;
    }
    return $total;
}

//  Dummy function to simulate fetching product prices from a database
function get_product_price($product_id) {
    // Replace this with your actual database query or API call
    switch ($product_id) {
        case 1: return 10.00;
        case 2: return 25.00;
        case 3: return 5.50;
        default: return 0; //Handle unknown product ID
    }
}


// --- Example Usage (Handle Add to Cart Button) ---
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default to 1 if no quantity provided

    add_to_cart($product_id, $quantity);

    // Optionally, redirect to the cart page or display a success message
    header("Location: cart.php"); //Redirect to cart.php
    exit(); //Important to prevent further processing on this page
}

// --- Example Usage (Handle Update Quantity Button) ---
if (isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0; // Allow setting to 0 to remove

    if ($quantity > 0) {
        update_cart_quantity($product_id, $quantity);
    } else {
        remove_from_cart($product_id);
    }

    header("Location: cart.php");
    exit();
}



//--- Display Cart Contents (for demonstration) ---
echo "<h2>Your Cart</h2>";
if (empty(get_cart_items())) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach (get_cart_items() as $product_id => $quantity) {
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
    }
    echo "</ul>";

    echo "<p>Total Price: " . number_format(calculate_total_price(get_cart_items())) . "</p>";
}
?>
