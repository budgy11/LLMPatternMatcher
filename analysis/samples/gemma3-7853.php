
</body>
</html>


<?php

// --- Configuration ---
$cart = array(); // Array to store items in the cart
$product_data = array(
    1 => array('name' => 'T-Shirt', 'price' => 20.00, 'description' => 'Comfortable cotton t-shirt.'),
    2 => array('name' => 'Jeans', 'price' => 50.00, 'description' => 'Classic blue denim jeans.'),
    3 => array('name' => 'Hat', 'price' => 15.00, 'description' => 'Stylish baseball cap.')
);

// --- Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.  Defaults to 1.
 */
function addToCart($product_id, $quantity = 1) {
    if (isset($product_data[$product_id])) {
        $product = $product_data[$product_id];

        //Check if the item is already in the cart
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = array(
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'description' => $product['description']
            );
        }
    } else {
        echo "Product ID $product_id not found.";
    }
}

/**
 * Displays the shopping cart.
 */
function displayCart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $item_id => $item) {
        echo "<li>";
        echo "<strong>" . $item['name'] . "</strong> - $" . number_format($item['price'], 2) . "<br>";
        echo "Description: " . $item['description'] . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Total Item Price: $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
    echo "<p><a href='checkout.php'>Checkout</a></p>"; //Link to checkout page
}

/**
 * Simulates the checkout process.  In a real application, this would involve
 * database interaction, payment processing, and order confirmation.
 */
function checkout() {
    echo "<h2>Checkout</h2>";
    echo "<p>Thank you for your order!</p>";
    echo "<p>Your order will be processed shortly.</p>";
    // In a real application, this is where you would:
    // 1.  Validate the order (ensure quantities are valid, etc.)
    // 2.  Process payment through a payment gateway.
    // 3.  Store the order in a database.
    // 4.  Send an order confirmation email to the customer.

    //Clear the cart after checkout (optional)
    $cart = array();
}


// --- Example Usage and Handling ---

//Add items to the cart
addToCart(1, 2); // Add 2 T-Shirts
addToCart(2, 1); // Add 1 pair of Jeans
addToCart(3, 3); // Add 3 Hats


//Display the cart
displayCart();

//Simulate checkout
//checkout();
?>
