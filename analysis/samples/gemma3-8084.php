

<?php
session_start();

// --- Database Connection (Replace with your actual credentials) ---
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Functions ---

// Add to Cart
function addToCart($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = array(
            'quantity' => $quantity,
            'price' => getProductPrice($product_id) // Get price from product table
        );
    }
}

// Get Cart Contents
function getCartContents() {
    return $_SESSION['cart'];
}

// Calculate Total Cart Value
function calculateTotal() {
    $total = 0;
    $cart = getCartContents();
    foreach ($cart as $item_id => $item_data) {
        $total_item_price = $item_data['price'] * $item_data['quantity'];
        $total += $total_item_price;
    }
    return $total;
}


// Get Product Price (Fetch from product table - adapt to your schema)
function getProductPrice($product_id) {
    $sql = "SELECT price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $product_id = intval($product_id); // Ensure integer
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        return $row['price'];
    } else {
        return 0; // Or handle the error appropriately
    }
}


// --- Cart Management ---

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<h1>Your shopping cart is empty.</h1>";
    echo "<a href='index.php'>Browse Products</a>";
} else {

    // Display Cart Contents
    echo "<h2>Shopping Cart</h2>";
    echo "<ul>";
    $cart = getCartContents();
    foreach ($cart as $product_id => $item_data) {
        echo "<li>";
        echo "Product ID: " . $product_id . "<br>";
        echo "Quantity: " . $item_data['quantity'] . "<br>";
        echo "Price per item: " . $item_data['price'] . "<br>";
        echo "Total for this item: " . $item_data['price'] * $item_data['quantity'] . "<br>";
        echo "<form method='post' action='update_cart.php'>";
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<input type='number' name='quantity' value='" . $item_data['quantity'] . "' min='1'>";
        echo "<button type='submit'>Update</button>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";

    // Display Total
    echo "<p><strong>Total Cart Value: $" . calculateTotal() . "</strong></p>";

    // Checkout Link (Example -  Replace with your actual checkout process)
    echo "<a href='checkout.php'>Proceed to Checkout</a>";
}

// ---  Update Cart (update_cart.php -  Separated for clarity)
// This would handle the update of quantity in the cart.  It receives
// the product_id and the new quantity from the form submission.
//  It then calls the addToCart function to update the cart.
//  (Implementation details are intentionally omitted for brevity.)

?>
