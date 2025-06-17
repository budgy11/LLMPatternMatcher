

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// -------------------------------------------------------------------
// Example Functions for Adding, Removing, and Updating Items
// -------------------------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product being added.
 * @param string $name The name of the product.
 * @param int $price The price of the product.
 * @param int $quantity The quantity to add.
 */
function addToCart($product_id, $name, $price, $quantity) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = array(
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        );
    } else {
        // If the product already exists in the cart, update the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 */
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 */
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// -------------------------------------------------------------------
// Example Usage (Simulated Product Information - Replace with actual data)
// -------------------------------------------------------------------

$products = array(
    'product1' => array('name' => 'Laptop', 'price' => 1200),
    'product2' => array('name' => 'Mouse', 'price' => 25),
    'product3' => array('name' => 'Keyboard', 'price' => 75)
);

// Simulate adding items to the cart
addToCart('product1', $products['product1']['name'], $products['product1']['price'], 1);
addToCart('product2', $products['product2']['name'], $products['product2']['price'], 2);
addToCart('product3', $products['product3']['name'], $products['product3']['price'], 1);


// -------------------------------------------------------------------
// Displaying the Cart Contents
// -------------------------------------------------------------------

echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        echo "<li>";
        echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}

// -------------------------------------------------------------------
// Example: Removing an item (Illustrative)
// -------------------------------------------------------------------

// echo "<br><br><h2>Remove Item</h2>";
// if(isset($_POST['remove_product'])) {
//   removeFromCart($_POST['product_id']);
//   echo "<p>Item removed from cart.</p>";
// }
?>
