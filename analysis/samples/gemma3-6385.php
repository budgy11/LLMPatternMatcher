

<?php

// Start the session if it doesn't already exist
session_start();

// --- Cart Initialization ---

// If the 'cart' session variable doesn't exist, initialize it as an empty array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Cart Functions ---

/**
 * Adds an item to the cart.
 *
 * @param string $product_id The ID of the product to add.
 * @param string $name The name of the product.
 * @param int    $price  The price of the product.
 * @param int    $quantity The quantity of the product to add (default: 1)
 */
function addToCart(string $product_id, string $name, float $price, int $quantity = 1) {
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = [
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
        ];
    } else {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    }
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param string $product_id The ID of the product to update.
 * @param int    $quantity The new quantity of the product.
 */
function updateCartItem(string $product_id, int $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } else {
        // Product doesn't exist in the cart, so you might want to handle this.
        //  - You could throw an error.
        //  - You could add the product with the given quantity.
        //  - You could log the error.

        // Example:  Adding it with a quantity of 1 if not found.
        addToCart($product_id, $nameFromId($product_id), $priceFromId($product_id), $quantity);
    }
}


/**
 * Removes an item from the cart.
 *
 * @param string $product_id The ID of the product to remove.
 */
function removeCartItem(string $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


/**
 * Gets the name of a product by its ID.  (Example function - would likely be pulled from a database)
 *
 * @param string $product_id
 * @return string|null
 */
function nameFromId(string $product_id) {
    // In a real application, this would fetch the product name from a database.
    // This is just an example using a simple associative array.
    $products = [
        'product1' => 'Awesome T-Shirt',
        'product2' => 'Cool Mug',
        'product3' => 'Fancy Pen'
    ];
    return $products[$product_id] ?? null;  // Returns null if not found.
}


/**
 * Gets the price of a product by its ID. (Example function - would likely be pulled from a database)
 *
 * @param string $product_id
 * @return float|null
 */
function priceFromId(string $product_id) {
    // In a real application, this would fetch the product price from a database.
    // This is just an example using a simple associative array.
    $products = [
        'product1' => 25.00,
        'product2' => 12.50,
        'product3' => 5.00
    ];
    return $products[$product_id] ?? null;
}



// --- Cart Operations (Example Usage - Replace with your form processing) ---

// 1. Add to Cart (Simulated form submission)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $price = floatval($_POST['product_price']);
    $quantity = intval($_POST['product_quantity']);
    addToCart($product_id, $name, $price, $quantity);
    echo "<p>Item added to cart.</p>";
}

// 2. Update Cart Item
if (isset($_POST['update_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);
    updateCartItem($product_id, $quantity);
    echo "<p>Cart updated.</p>";
}

// 3. Remove Item
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeCartItem($product_id);
    echo "<p>Item removed from cart.</p>";
}

// --- Display Cart Contents ---

// Output the cart contents
echo "<h2>Your Shopping Cart</h2>";
echo "<ul>";

if (empty($_SESSION['cart'])) {
    echo "<li>Your cart is empty.</li>";
} else {
    foreach ($_SESSION['cart'] as $product_id => $product_details) {
        $name = $product_details['name'];
        $price = $product_details['price'];
        $quantity = $product_details['quantity'];

        echo "<li>Product: " . $name . " - Price: $" . $price . " - Quantity: " . $quantity . "<br>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<input type='hidden' name='quantity' value='" . $quantity . "'>";
        echo "<input type='submit' value='Update Quantity'>";
        echo "</form>";
        echo "</li>";
    }
}

echo "</ul>";


?>
