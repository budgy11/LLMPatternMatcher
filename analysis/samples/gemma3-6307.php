

<?php

// Start session management (if not already started)
session_start();

//  ---  Cart Initialization  ---

// If a cart isn't already present, initialize it as an empty array.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


// -------------------  Helper Functions  -------------------

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If it is, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If not, add the product to the cart array
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }
}


// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}


// Function to get the cart contents
function getCartContents() {
    return $_SESSION['cart'];
}


// Function to calculate the total cart value
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $totalItem = $item['price'] * $item['quantity'];
        $total += $totalItem;
    }
    return $total;
}

// -------------------  Cart Operations (Example Usage)  -------------------

// 1. Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = floatval($_POST['price']);  //Ensure price is a number
    $quantity = intval($_POST['quantity']); //Ensure quantity is an integer

    addToCart($product_id, $product_name, $price, $quantity);
    // You might want to redirect the user to the cart page or display a success message.
    echo "<p>Product added to cart.</p>";
}

// 2. Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = intval($_POST['quantity']); //Convert to integer
    updateQuantity($product_id, $new_quantity);
    echo "<p>Quantity updated to " . $new_quantity . "</p>";
}


// 3. Remove from Cart
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];
    removeCartItem($product_id);
    echo "<p>Product removed from cart.</p>";
}

// 4.  Get and Display Cart Contents
$cart_contents = getCartContents();

// Output Cart Items
echo "<h2>Your Shopping Cart</h2>";
if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
}

// Calculate and display total
$total = calculateTotal();
echo "<p><strong>Total: $" . $total . "</strong></p>";
?>
