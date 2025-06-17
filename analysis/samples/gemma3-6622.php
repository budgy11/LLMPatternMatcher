

<?php

// Start the session
session_start();

// --- Cart Variables ---

// Initialize an empty cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Helper Functions ---

// Function to add an item to the cart
function addToCart($item_id, $item_name, $item_price, $quantity = 1) {
    // Check if the item already exists in the cart
    if (isset($_SESSION['cart'][$item_id])) {
        // Item exists, increase the quantity
        $_SESSION['cart'][$item_id]['quantity'] += $quantity;
    } else {
        // Item doesn't exist, add a new entry
        $_SESSION['cart'][$item_id] = [
            'name' => $item_name,
            'price' => $item_price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function removeCartItem($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($item_id, $new_quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $new_quantity;
    }
}

// Function to get the cart total
function getCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// --- Example Usage / Actions (Simulated) ---

// 1. Adding an item to the cart
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = floatval($_POST['item_price']);  // Convert to float
    $quantity = intval($_POST['quantity']);  // Convert to integer

    addToCart($item_id, $item_name, $item_price, $quantity);
    $message = "Item added to cart!";
}

// 2. Removing an item from the cart
if (isset($_GET['remove_item'])) {
    $item_id = $_GET['remove_item'];
    removeCartItem($item_id);
    $message = "Item removed from cart!";
}

// 3. Updating the quantity of an item
if (isset($_GET['update_quantity'])) {
    $item_id = $_GET['update_quantity'];
    $new_quantity = intval($_GET['quantity']); // Convert to integer
    updateCartQuantity($item_id, $new_quantity);
    $message = "Quantity updated!";
}



// --- Displaying the Cart ---

// Output the cart contents
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item_id => $item) {
        echo "<li>";
        echo "<strong>" . $item['name'] . "</strong> - $" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
        // Add a remove link for each item
        echo "<a href='?remove_item=" . $item_id . "'>Remove</a>";
        echo "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . number_format(getCartTotal(), 2) . "</strong></p>";
}

?>
