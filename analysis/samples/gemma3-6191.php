

<?php
session_start();

// --- Cart Logic ---

// Initialize an empty cart in the session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product exists, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}

// Function to get the cart contents
function get_cart() {
    return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculate_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total_item = $item['price'] * $item['quantity'];
        $total += $total_item;
    }
    return $total;
}

// --- Example Usage (Simulated Product Data) ---

$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
];


// ---  Handle Cart Actions (Simulated Input) ---

// Add a product to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; // Ensure product_id is an integer
    $quantity = (int)$_POST['quantity']; // Ensure quantity is an integer

    if (array_key_exists($product_id, $products)) {
        add_to_cart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
    }
}

// Remove an item from the cart
if (isset($_POST['remove_item'])) {
    $product_id = (int)$_POST['product_id'];
    remove_from_cart($product_id);
}

// Update quantity
if (isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $new_quantity = (int)$_POST['quantity'];
    update_quantity($product_id, $new_quantity);
}


// --- Display the Cart ---

echo '<div class="cart">';
echo '<h2>Shopping Cart</h2>';

// Display items in the cart
$cart_items = get_cart();
if (!empty($cart_items)) {
    echo '<ul>';
    foreach ($cart_items as $product_id => $item) {
        echo '<li>';
        echo "Product: " . $item['name'] . "<br>";
        echo "Price: $" . number_format($item['price'], 2) . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Total for item: $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";
        echo "<form method="post" action=\"#\">"; // Use a form for removal
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<button type='submit' name='remove_item'>Remove</button>";
        echo "</form>";
        echo '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>Your cart is empty.</p>';
}

echo '<h2>Total Cart Value: $"' . number_format(calculate_total(), 2) . '</h2>';
echo '</div>';

?>
