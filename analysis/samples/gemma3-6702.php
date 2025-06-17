

<?php
session_start(); // Start the session

// ---  Example Cart Logic ---

$cart = []; // Initialize an empty cart (array)

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    if (empty($_SESSION['cart'])) {
        $cart = [['id' => $product_id, 'name' => $product_name, 'price' => $price, 'quantity' => $quantity]];
    } else {
        // Check if the item already exists in the cart
        $item_exists = false;
        foreach ($cart as $index => $item) {
            if ($item['id'] == $product_id) {
                // Update quantity if item exists
                $cart[$index]['quantity'] += $quantity;
                $item_exists = true;
                break;
            }
        }
        // If the item doesn't exist, add it
        if (!$item_exists) {
            $cart[] = ['id' => $product_id, 'name' => $product_name, 'price' => $price, 'quantity' => $quantity];
        }
    }
}

// Function to remove an item from the cart by ID
function remove_from_cart($product_id) {
    global $cart; // Access the global $cart array

    foreach ($cart as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            $cart = array_values($cart); // Re-index the array after deleting
            return true;
        }
    }
    return false;
}

// Function to get the cart contents
function get_cart_contents() {
    return $cart;
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    global $cart;

    foreach ($cart as $key => $item) {
        if ($item['id'] == $product_id) {
            $cart[$key]['quantity'] = $quantity;
            return true;
        }
    }
    return false;
}



// ---  Example Usage (Adding items to the cart )---

// Example: Add a product to the cart
add_to_cart(1, "T-Shirt", 20.00, 2);
add_to_cart(2, "Jeans", 50.00, 1);

// Example: Remove an item from the cart
// remove_from_cart(2);

// Example: Update the quantity of a product
// update_quantity(1, 3); // Increase the quantity of T-Shirt to 3

// ---  Display the Cart Contents (for demonstration) ---

// Get the cart contents
$cart_contents = get_cart_contents();

// Display the cart
echo "<h2>Your Shopping Cart</h2>";

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}


?>
