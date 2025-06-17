  </ul>

</body>
</html>


<?php

// Database Connection (Replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Session Handling (to track the shopping cart)
session_start();

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        // If the product doesn't exist in the cart, it means it's a new addition.
        // You might want to handle this case differently (e.g., add it to the cart with the specified quantity).
    }
}

// Function to get the cart contents
function getCartContents() {
    return $_SESSION['cart'] ?? []; // Return an empty array if cart is not set.
}


// -----  Example Product Data (for demonstration) -----
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15],
];

// -----  Handle Form Submission (Add to Cart) -----
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $product_id = (int)$_POST['product_id'];
        $quantity = (int)$_POST['quantity'];

        // Validate input (Important for security)
        if ($product_id <= 0 || $quantity <= 0) {
            echo "Invalid product ID or quantity.";
        } else {
            addToCart($product_id, $quantity);
            echo "<p>Item added to cart!</p>";
        }
    }
}


// ----- Display the Cart -----
$cart = getCartContents();

echo "<h2>Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $product_id => $quantity) {
        $product_name = $products[$product_id]['name'];
        $product_price = $products[$product_id]['price'];
        echo "<li>" . $product_name . " - $" . $product_price . " (Quantity: " . $quantity . ")</li>";
    }
    echo "</ul>";

    // Calculate total
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        $total += $products[$product_id]['price'] * $quantity;
    }
    echo "<p><strong>Total: $" . $total . "</strong></p>";

    //Remove Item
    if (isset($_GET['remove'])) {
        $product_id_to_remove = (int)$_GET['remove'];
        removeFromCart($product_id_to_remove);
        echo "<p>Item removed from cart!</p>";
    }
}


?>
