

<?php
session_start();

// Configuration
$items = []; // Array to store cart items
$item_id = 1; // Unique item ID
$db_file = 'cart.txt'; // File to store cart data

// Function to load cart data from file
function loadCart() {
    if (file_exists($db_file)) {
        $cartData = file_get_contents($db_file);
        if ($cartData = json_decode($cartData, true)) {
            return $cartData;
        } else {
            return [];
        }
    }
    return [];
}

// Function to save cart data to file
function saveCart($cartData) {
    file_put_contents($db_file, json_encode($cartData, JSON_PRETTY_PRINT));
}

// Load cart data
$cartData = loadCart();

// ------------------- Cart Management Functions -------------------

// Add an item to the cart
function add_to_cart($product_id, $quantity = 1) {
    global $items, $item_id;

    // Check if the item is already in the cart
    $item_found = false;
    foreach ($items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $items[$key]['quantity'] += $quantity;
            $item_found = true;
            break;
        }
    }

    // If the item isn't in the cart, add it
    if (!$item_found) {
        $items[$item_id] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
        ];
        $item_id++;
    }

    saveCart($items);
}

// Remove an item from the cart
function remove_from_cart($product_id) {
    global $items;

    foreach ($items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($items[$key]);
            // Re-index array to prevent gaps
            $i = 0;
            foreach ($items as $k => $v) {
                $items[$i] = $v;
                $i++;
            }
            saveCart($items);
            return true;
        }
    }
    return false;
}

// Update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    global $items;

    foreach ($items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $items[$key]['quantity'] = $new_quantity;
            saveCart($items);
            return true;
        }
    }
    return false;
}


// Get the cart contents
function get_cart_contents() {
    return $items;
}


// ------------------- Display Cart -------------------

// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($items)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($items as $key => $item) {
        echo "<li>Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";

    // Calculate total price (assuming a simple price per item)
    $total_price = 0;
    foreach ($items as $key => $item) {
        // Replace this with your actual price retrieval logic
        $price = 10; // Placeholder price
        $total_price += $price * $item['quantity'];
    }
    echo "<p><strong>Total: $" . $total_price . "</strong></p>";
}

// ------------------- Example Usage (Simulating Actions) -------------------

// Example: Add a product to the cart
// add_to_cart(1, 2);

// Example: Remove a product
// remove_from_cart(1);

// Example: Update quantity
// update_quantity(1, 5);

?>
