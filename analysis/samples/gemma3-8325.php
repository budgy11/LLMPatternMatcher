

</body>
</html>


<?php
session_start();

// Database connection (replace with your actual credentials)
$dbHost = 'localhost';
$dbUser = 'your_username';
$dbPass = 'your_password';
$dbName = 'your_database_name';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to check if an item is already in the cart
function isInCart($cart_id, $product_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM cart_items WHERE cart_id = ? AND product_id = ?");
    $stmt->execute([$cart_id, $product_id]);
    return (int)$stmt->fetchColumn() > 0;
}

// Function to update the cart
function updateCart($cart_id, $product_id, $quantity) {
    $stmt = $pdo->prepare("
        UPDATE cart_items
        SET quantity = :quantity
        WHERE cart_id = :cart_id AND product_id = :product_id
    ");
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':cart_id', $cart_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
}

// Function to add an item to the cart
function addItemToCart($cart_id, $product_id, $quantity) {
    if (isInCart($cart_id, $product_id)) {
        updateCart($cart_id, $product_id, $quantity);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$cart_id, $product_id, $quantity]);
    }
}

// Cart ID based on session
$cart_id = isset($_SESSION['cart_id']) ? $_SESSION['cart_id'] : null;

if ($cart_id === null) {
    // Create a new cart if one doesn't exist
    $cart_id = bin2hex(random_bytes(16));
    $_SESSION['cart_id'] = $cart_id;
}

// Get products (replace with your product retrieval logic)
$products = [
    ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    ['id' => 3, 'name' => 'Hat', 'price' => 15],
];

// Handle add to cart request
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Validate quantity (optional, but recommended)
    if (isset($quantity) && is_numeric($quantity) && $quantity > 0) {
        addItemToCart($cart_id, $product_id, $quantity);
    }
}

// Display the cart contents
$cartItems = [];
if ($cart_id !== null) {
    $stmt = $pdo->prepare("SELECT product_id, quantity FROM cart_items WHERE cart_id = ?");
    $stmt->execute([$cart_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Calculate total price
$total_price = 0;
if ($cart_id !== null) {
    foreach ($cartItems as $item) {
        $product = null;
        foreach ($products as $p) {
            if ($p['id'] == $item['product_id']) {
                $product = $p;
                break;
            }
        }
        if ($product) {
            $total_price += $product['price'] * $item['quantity'];
        }
    }
}

?>
