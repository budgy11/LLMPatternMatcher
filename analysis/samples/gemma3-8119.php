
</body>
</html>


<?php
session_start();

// Configuration
$items = []; // Array to store items in the cart
$cart_file = 'cart.json'; // File to store cart data

// --- Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product being added.
 * @param int $quantity The quantity of the product being added.
 * @return void
 */
function add_to_cart(int $product_id, int $quantity) {
    // Check if the item is already in the cart
    if (isset($items[$product_id])) {
        $items[$product_id]['quantity'] += $quantity;
    } else {
        // If not, add it to the cart
        $items[$product_id] = ['quantity' => $quantity];
    }

    // Save the cart to the file
    save_cart_to_file();
}


/**
 * Removes an item from the cart.
 *
 * @param int $product_id The ID of the product being removed.
 * @return void
 */
function remove_from_cart(int $product_id) {
    if (isset($items[$product_id])) {
        unset($items[$product_id]);
    }

    // Save the cart to the file
    save_cart_to_file();
}

/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product being updated.
 * @param int $new_quantity The new quantity of the product.
 * @return void
 */
function update_cart_quantity(int $product_id, int $new_quantity) {
    if (isset($items[$product_id])) {
        $items[$product_id]['quantity'] = $new_quantity;
    }
    save_cart_to_file();
}



/**
 * Loads the cart from the JSON file.
 *
 * @return void
 */
function load_cart_from_file() {
    if (file_exists($cart_file)) {
        $json_data = file_get_contents($cart_file);
        $data = json_decode($json_data, true);

        if ($data) {
            $items = $data; // Directly assign the decoded array
        }
    }
}

/**
 * Saves the cart to the JSON file.
 *
 * @return void
 */
function save_cart_to_file() {
    file_put_contents($cart_file, json_encode($items));
}

/**
 * Displays the cart contents.
 *
 * @return void
 */
function display_cart() {
    if (empty($items)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    foreach ($items as $product_id => $item) {
        $product_name = get_product_name($product_id); // Assuming you have a function to get product name
        echo "<li>" . $product_name . " - Quantity: " . $item['quantity'] . " - Price: $" . get_product_price($product_id) . "</li>";
    }
    echo "</ul>";
}


/**
 * Retrieves the product name based on its ID. (Placeholder - Implement your logic here)
 * @param int $product_id
 * @return string
*/
function get_product_name(int $product_id) {
    // Replace this with your actual product database lookup logic.
    // This is just a placeholder.
    switch ($product_id) {
        case 1: return "Awesome T-Shirt";
        case 2: return "Cool Mug";
        case 3: return "Fancy Hat";
        default: return "Unknown Product";
    }
}

/**
 * Retrieves the product price based on its ID. (Placeholder - Implement your logic here)
 * @param int $product_id
 * @return float
*/
function get_product_price(int $product_id) {
    // Replace this with your actual product database lookup logic.
    // This is just a placeholder.
    switch ($product_id) {
        case 1: return 19.99;
        case 2: return 9.99;
        case 3: return 24.99;
        default: return 0.00;
    }
}



// --- Main Execution ---

// Load the cart data
load_cart_from_file();

// --- Example Usage (for demonstration) ---
// Add some items to the cart
//add_to_cart(1, 2); // Add 2 T-shirts
//add_to_cart(2, 1); // Add 1 Mug
//add_to_cart(1, 1); //Add 1 T-shirt
//
// //Remove item
//remove_from_cart(2);
//
// //Update quantity
// update_cart_quantity(1, 3);
//
// //Display the cart
// display_cart();
?>
