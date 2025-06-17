        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Your Name"><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Your Email"><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" max="100" value="1"><br><br>

        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" min="1" max="100"><br><br>


        <button type="submit">Add to Cart</button>
    </form>

    <a href="index.php">Back to Home</a>

</body>
</html>


<?php
session_start();

// Database connection (Replace with your database details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Define product data (for demonstration - replace with your database)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Cart function
function get_cart() {
    if (empty($_SESSION['cart'])) {
        return [];
    }
    return $_SESSION['cart'];
}

function add_to_cart($product_id, $quantity = 1) {
    $cart = get_cart();

    // Check if product exists
    if (!isset($products[$product_id])) {
        return false;
    }

    // Check if product is already in cart
    foreach ($cart as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            return true;
        }
    }

    // If not in cart, add it
    $cart[] = $products[$product_id];
    return true;
}

function remove_from_cart($product_id) {
    $cart = get_cart();
    foreach ($cart as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            return true;
        }
    }
    return false;
}


function calculate_total() {
    $cart = get_cart();
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// Handle adding to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id']; // Ensure product_id is an integer
    add_to_cart($product_id, (int)$_POST['quantity']); // Ensure quantity is an integer
    // Optionally, you could redirect to a success page or refresh the cart view
}

// Handle removing from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = (int)$_POST['product_id'];
    remove_from_cart($product_id);
}

// Display Cart
$cart = get_cart();

?>
