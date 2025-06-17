

<?php

// Database connection (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ------------------- Functions -------------------

// Function to add a product to the cart
function addToCart($product_id, $quantity, $user_id = null) {
    global $pdo;

    // Check if user is logged in
    if ($user_id) {
        // If user is logged in, store the cart in the database
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);

    } else {
        // If not logged in, store the cart in the session
        session_start();
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][] = ['product_id' => $product_id, 'quantity' => $quantity];
    }
}

// Function to remove a product from the cart
function removeFromCart($product_id) {
    global $pdo;

    // Remove from database
    $stmt = $pdo->prepare("DELETE FROM cart WHERE product_id = ?");
    $stmt->execute([$product_id]);

    // Remove from session
    session_start();
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break; // Important: stop after removing
        }
    }
}


// Function to update the quantity of a product in the cart
function updateCartQuantity($product_id, $quantity) {
    global $pdo;
    session_start();

    // First, find the cart entry to update.  This is more efficient than looping.
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        // Update the quantity in the database
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE product_id = ?");
        $stmt->execute([$quantity, $product_id]);
    } else {
        // If not found, add to cart
        addToCart($product_id, $quantity);
    }
}

// Function to get the cart contents
function getCartContents() {
    global $pdo;

    // Get cart contents from database
    $stmt = $pdo->prepare("SELECT * FROM cart");
    $stmt->execute();
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Combine with session cart items (if any)
    $cart_contents = [];
    if (isset($_SESSION['cart'])) {
        $cart_contents += $_SESSION['cart'];
    }

    // Return the combined cart items
    return $cart_contents;
}


// -------------------  Example Usage  -------------------

// Assuming you have a product ID of 123

// 1. Add to Cart
addToCart(123, 2);

// 2. Remove from Cart
//removeFromCart(123);

// 3. Update Quantity
// updateCartQuantity(123, 5);

// 4. Get Cart Contents
$cart = getCartContents();
echo "<pre>";
print_r($cart);
echo "</pre>";


?>
