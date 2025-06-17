
<!DOCTYPE html>
<html>
<head>
  <title>Purchase Functionality</title>
</head>
<body>

  <h1>Purchase Items</h1>

  <form method="post" action="">
    <label for="product1">Product 1 (ID: 1):</label>
    <input type="number" id="product1" name="cart_items[0][product_id]" value="1">
    <label for="quantity1">Quantity:</label>
    <input type="number" id="quantity1" name="cart_items[0][quantity]" value="1">
    <br>

    <label for="product2">Product 2 (ID: 2):</label>
    <input type="number" id="product2" name="cart_items[1][product_id]" value="2">
    <label for="quantity2">Quantity:</label>
    <input type="number" id="quantity2" name="cart_items[1][quantity]" value="2">
    <br>

    <button type="submit">Place Order</button>
  </form>

  <br>
  <p>You can add more products by adding more input fields (product_id and quantity) to the form.</p>

</body>
</html>


<?php
session_start();

// Configuration
$cart_file = 'cart.php';
$item_id_key = 'item_id';
$item_name_key = 'item_name';
$quantity_key = 'quantity';
$price_key = 'price';

// Function to initialize the cart
function initializeCart() {
    if (!file_exists($cart_file)) {
        file_put_contents($cart_file, '{}'); // Create an empty cart file
    }
}

// Function to add an item to the cart
function addItemToCart($item_id, $item_name, $quantity, $price) {
    $cart = getCartData();

    $item_id = $item_id;
    $item_name = $item_name;
    $quantity = $quantity;
    $price = $price;

    if (empty($cart)) {
        $cart = array($item_id_key => $item_id, $item_name_key => $item_name, $quantity_key => $quantity, $price_key => $price);
    } else {
        // Check if item already in cart
        if (isset($cart[$item_id])) {
            $cart[$item_id][$quantity_key] += $quantity;
        } else {
            $cart[$item_id] = array($item_name_key => $item_name, $quantity_key => $quantity, $price_key => $price);
        }
    }

    // Save the cart to the file
    file_put_contents($cart_file, json_encode($cart));
}

// Function to update an item's quantity in the cart
function updateCartItemQuantity($item_id, $quantity) {
    $cart = getCartData();

    if (isset($cart[$item_id])) {
        $cart[$item_id][$quantity_key] = $quantity;
    }

    file_put_contents($cart_file, json_encode($cart));
}

// Function to remove an item from the cart
function removeItemFromCart($item_id) {
    $cart = getCartData();

    if (isset($cart[$item_id])) {
        unset($cart[$item_id]);
    }

    file_put_contents($cart_file, json_encode($cart));
}

// Function to get the cart data from the file
function getCartData() {
    if (file_exists($cart_file)) {
        $cart = json_decode(file_get_contents($cart_file), true);
        return $cart;
    } else {
        return array(); // Return an empty array if the cart file doesn't exist
    }
}

// Function to get the total cart value
function calculateCartTotal() {
    $cart = getCartData();
    $total = 0;
    foreach ($cart as $item_id => $item) {
        $quantity = $item[$quantity_key];
        $price = $item[$price_key];
        $total_item_price = $quantity * $price;
        $total += $total_item_price;
    }
    return $total;
}

//  Example Usage (Illustrative - Replace with your actual logic)
// This is just to demonstrate how the functions would be used.
// You'd integrate this into your website's purchase flow.

// 1. Add to Cart (Example)
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    addItemToCart($item_id, $item_name, $quantity, $price);
    // Redirect or display a success message
    echo "<p>Item added to cart!</p>";
}

// 2. Update Quantity (Example)
if (isset($_POST['update_quantity'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['quantity'];
    updateCartItemQuantity($item_id, $new_quantity);
    echo "<p>Quantity updated in cart!</p>";
}

// 3. Remove Item (Example)
if (isset($_POST['remove_item'])) {
    $item_id = $_POST['item_id'];
    removeItemFromCart($item_id);
    echo "<p>Item removed from cart!</p>";
}

// 4. Get Cart Data for Display (Example - Display Cart Contents)
$cart = getCartData();

$total = calculateCartTotal();
?>
