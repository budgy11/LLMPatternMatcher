</ul>


</body>
</html>


<?php
session_start();

// Configuration
$cart_file = 'cart.php';

// Function to get the cart data
function get_cart_data() {
    if (file_exists($cart_file)) {
        $cart = unserialize(file_get_contents($cart_file));
        if (!$cart) {
            return []; // Return an empty array if the file is empty or corrupted
        }
        return $cart;
    } else {
        return [];
    }
}

// Function to save the cart data
function save_cart_data($cart) {
    file_put_contents($cart_file, serialize($cart));
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity = 1) {
    $cart = get_cart_data();

    // Check if the product is already in the cart
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            break;
        }
    }

    // If the product is not in the cart, add it
    if (!isset($item['product_id'])) {
        $cart[] = [
            'product_id' => $product_id,
            'product_name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }

    save_cart_data($cart);
}

// Function to remove an item from the cart
function remove_from_cart($product_id) {
    $cart = get_cart_data();

    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($cart[$key]);
            break;
        }
    }

    save_cart_data($cart);
}

// Function to update the quantity of an item in the cart
function update_quantity($product_id, $quantity) {
    $cart = get_cart_data();

    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $quantity;
            break;
        }
    }

    save_cart_data($cart);
}

// Example Usage (For demonstration purposes - Replace with your product/database logic)
// Simulate product information
$products = [
    1 => ['product_id' => 1, 'product_name' => 'Laptop', 'price' => 1200],
    2 => ['product_id' => 2, 'product_name' => 'Mouse', 'price' => 25],
    3 => ['product_id' => 3, 'product_name' => 'Keyboard', 'price' => 75],
];


// Handle adding to cart (e.g., from a form submission)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    add_to_cart($product_id, $product_name, $price, $quantity);
    echo "<p>Item added to cart.</p>";
}

// Handle removing an item from the cart
if (isset($_GET['remove_item'])) {
    $product_id = $_GET['remove_item'];
    remove_from_cart($product_id);
    echo "<p>Item removed from cart.</p>";
}

// Handle updating quantity
if (isset($_GET['update_quantity'])) {
    $product_id = $_GET['update_quantity'];
    $new_quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; //Default to 1 if not provided
    update_quantity($product_id, $new_quantity);
    echo "<p>Quantity updated.</p>";
}


// Display the cart
$cart = get_cart_data();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['product_name'] . " - $" . $item['price'] . " (Quantity: " . $item['quantity'] . ")</li>";
    }
    echo "</ul>";
    echo "<a href='cart.php?action=empty'>Empty Cart</a>"; //Link to empty the cart
}

?>
