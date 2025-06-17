

<?php
session_start();

// Configuration
$items = []; // Array to store cart items
$cart_file = 'cart.json'; // File to store the cart data

// Helper function to load cart from file
function loadCartFromFile($file) {
    if (file_exists($file)) {
        $json = file_get_contents($file);
        if ($json !== false) {
            return json_decode($json, true); // Returns associative array
        } else {
            return [];
        }
    }
    return [];
}

// Helper function to save cart to file
function saveCartToFile($items, $file) {
    $json = json_encode($items);
    if ($json !== false) {
        file_put_contents($file, $json);
    } else {
        // Handle JSON encoding error
        error_log("Error encoding cart data to JSON: " . json_last_error_msg());
    }
}


// ================== Cart Functions ==================

// Add an item to the cart
function add_to_cart($product_id, $quantity) {
    global $items;

    // Check if the item is already in the cart
    $item_exists = false;
    foreach ($items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $items[$key]['quantity'] += $quantity;
            $item_exists = true;
            break;
        }
    }

    // If not in the cart, add a new item
    if (!$item_exists) {
        $items[] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
        ];
    }
}

// Remove an item from the cart
function remove_from_cart($product_id) {
    global $items;

    // Iterate and remove the item
    foreach ($items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($items[$key]);
            // Re-index the array to avoid gaps
            $items = array_values($items);
            break;
        }
    }
}

// Update the quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    global $items;

    foreach ($items as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $items[$key]['quantity'] = $quantity;
            break;
        }
    }
}


// Get the contents of the cart
function get_cart_contents() {
    return $items;
}


// ==================  Cart Handling ==================

// Initialize the cart if it doesn't exist
if (!file_exists($cart_file)) {
    $items = [];
    saveCartToFile($items, $cart_file);
}

$items = loadCartFromFile($cart_file);

// ==================  Cart Actions ==================

// Handle adding to cart (e.g., from a form submission)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    add_to_cart($product_id, $quantity);
    // Redirect to the cart page
    header("Location: cart.php");
    exit();
}

// Handle removing an item
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    remove_from_cart($product_id);
    header("Location: cart.php");
    exit();
}


// Handle updating the quantity
if (isset($_GET['update'])) {
    $product_id = $_GET['update'];
    $quantity = $_POST['quantity'];
    update_quantity($product_id, $quantity);
    header("Location: cart.php");
    exit();
}


// ==================  Display Cart ==================
// You would typically display the cart contents on a separate page (cart.php)
// This is just a placeholder to demonstrate the functionality.
if (empty($items)) {
    echo "<h1>Your Cart is Empty</h1>";
} else {
    echo "<h1>Your Shopping Cart</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Product ID</th><th>Quantity</th><th>Price</th></tr>"; // Assuming price is stored in the product data
    foreach ($items as $key => $item) {
        echo "<tr>";
        echo "<td>" . $item['product_id'] . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        //  You'll need to fetch the product's price from a database or other source
        //  Example (replace with your actual price retrieval method):
        //  $product = get_product_by_id($item['product_id']);
        //  echo "<td>" . $product['price'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

?>
