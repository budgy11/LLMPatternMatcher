

<?php
session_start();

// Assuming you have a database connection established (e.g., $db)

// Example Product Information (replace with your actual product data)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// ----------------------- Functions -----------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity of the product to add (default is 1).
 */
function addToCart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = ['quantity' => $quantity];
    }
}

/**
 * Retrieves the items in the cart.
 *
 * @return array The array of items in the cart.
 */
function getCart() {
    if (isset($_SESSION['cart'])) {
        return $_SESSION['cart'];
    }
    return [];
}

/**
 * Calculates the total price of the items in the cart.
 *
 * @return float The total price.
 */
function calculateTotal() {
    $total = 0;
    $cart = getCart();
    foreach ($cart as $item_id => $item_data) {
        $product = $products[$item_id];
        $total_item_price = $product['price'] * $item_data['quantity'];
        $total += $total_item_price;
    }
    return $total;
}

/**
 * Removes an item from the cart by product ID.
 *
 * @param int $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}



// ----------------------- Handling Cart Actions -----------------------

// Example: Adding a product to the cart when a button is clicked
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; // Ensure product_id is an integer
    addToCart($product_id, (int)$_POST['quantity']);  // Ensure quantity is an integer
}

// Example: Removing an item from the cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id'];
    removeFromCart($product_id);
}


// ----------------------- Displaying the Cart -----------------------

// Get the current cart contents
$cart = getCart();

// Calculate the total price
$total = calculateTotal();


echo "<h1>Your Shopping Cart</h1>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item_id => $item_data) {
        $product = $products[$item_id];
        echo "<li>";
        echo "<strong>" . $product['name'] . "</strong> - $" . $product['price'] . "</li>";
        echo "<form method='post'>";
        echo "<label for='quantity_" . $item_id . "'>Quantity:</label>";
        echo "<input type='number' id='quantity_" . $item_id . "' value='" . $item_data['quantity'] . "' min='1' max='10'>";
        echo "<input type='hidden' name='product_id' value='" . $item_id . "'>";
        echo "<button type='submit' name='update_cart'>Update Cart</button>";
        echo "</form>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $total . "</strong></p>";
}


?>
