

<?php

// Start a session (if it doesn't exist)
session_start();

//  This is a basic example. For real-world applications, 
//  you'd likely store this in a database instead of an array.

//  Simulated shopping cart
$cart = [];

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($cart[$product_id])) {
        $cart[$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity,
        ];
    } else {
        $cart[$product_id]['quantity'] += $quantity;
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
    }
}

// Function to update quantity of item in cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  }
}

// Example usage (simulated user actions)

// Add some items to the cart
addToCart(1, 'T-Shirt', 20.00, 2);
addToCart(2, 'Jeans', 50.00, 1);
addToCart(1, 'T-Shirt', 20.00, 1); // Adding another t-shirt

// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $product_details) {
        echo "<li>";
        echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . " = $" . ($product_details['price'] * $product_details['quantity']) . "</li>";
        echo "<form action='' method='post'>"; //  Placeholder form
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<input type='submit' name='remove' value='Remove'> ";
        echo "</form>";
    }
    echo "</ul>";

    // Calculate total price
    $total_price = 0;
    foreach ($cart as $product_id => $product_details) {
      $total_price += $product_details['price'] * $product_details['quantity'];
    }

    echo "<p><strong>Total: $" . $total_price . "</strong></p>";
}


// Example of removing an item
// removeFromCart(2);

// Example of updating quantity
// updateQuantity(1, 3);


?>
