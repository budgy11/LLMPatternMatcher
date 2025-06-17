    <br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php

// --- Database Configuration ---
$db_host = "localhost";
$db_name = "shop";
$db_user = "your_username";
$db_pass = "your_password";
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// --- Data Validation and Sanitization ---
function validateData($data, $type) {
    switch ($type) {
        case "string":
            return htmlspecialchars(trim($data)); //Escape HTML and remove whitespace
        case "int":
            return filter_var($data, FILTER_VALIDATE_INT);
        case "email":
            return filter_var($data, FILTER_VALIDATE_EMAIL);
        default:
            return $data; // Return original value if type is unknown
    }
}

// --- Purchase Functionality ---

// 1. Add to Cart (Simple implementation -  stores cart data in a session)
function addToCart($product_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    $product_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            $product_exists = true;
            break;
        }
    }

    // If not in cart, add it
    if (!$product_exists) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'quantity' => $quantity
        ];
    }

    // You could also update the product count directly in a database for persistence.
}

// 2.  Checkout Functionality (Simplified - just prints a summary)
function checkout() {
    $cart = $_SESSION['cart'];

    if (empty($cart)) {
        echo "<p>Your cart is empty.  Add some products!</p>";
        return;
    }

    echo "<h2>Checkout Summary</h2>";
    echo "<ul>";
    $total = 0;
    foreach ($cart as $item) {
        $product_id = $item['id'];
        $quantity = $item['quantity'];

        //  (In a real application, you would fetch product details from a database here)
        //  For simplicity, we'll just display the product ID and quantity
        echo "<li>Product ID: " . $product_id . ", Quantity: " . $quantity . "</li>";
        $total += $product_id * $quantity; //  Simple calculation - replace with real price
    }
    echo "</ul>";
    echo "<p>Total: $" . $total . "</p>";

    // Clear the cart after checkout
    $_SESSION['cart'] = [];
    echo "<p>Your cart has been cleared.</p>";
}


// --- Example Product Data (Simulated - Replace with Database Queries in a real app) ---
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15],
];


// --- Handle Requests ---
// Example:  If you have a form submission:
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
    $product_id = validateData($_POST['product_id'], "int");
    $quantity = validateData($_POST['quantity'], "int");

    if ($product_id && $quantity) {
        addToCart($product_id, $quantity);
        echo "<p>Product added to cart!</p>";
    } else {
        echo "<p>Invalid product ID or quantity.</p>";
    }
}


// If you want to trigger the checkout:
if (isset($_POST['action']) && $_POST['action'] == 'checkout') {
    checkout();
}


// --- Display Products (for the main page) ---
echo "<h2>Available Products</h2>";
echo "<ul>";
foreach ($products as $id => $product) {
    echo "<li>" . $product['name'] . " - $" . $product['price'] . " <button onclick='addToCart(" . $id . ", 1)'>Add to Cart</button></li>";
}
echo "</ul>";
?>
