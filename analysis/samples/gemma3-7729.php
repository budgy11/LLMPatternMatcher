</ul>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$dbHost = "localhost";
$dbUsername = "your_username";
$dbPassword = "your_password";
$dbName = "your_database";

// Session Management
session_start();

// Database Connection
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Helper function to sanitize input (basic example)
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


// 1. Add to Cart Function
function addToCart($productId, $quantity) {
    global $pdo;

    $productId = sanitizeInput($productId);
    $quantity = (int)$quantity; // Ensure quantity is an integer

    // Check if the product exists
    $stmt = $pdo->prepare("SELECT id, product_name, price FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        return false; // Product not found
    }

    // Check if the product is already in the cart
    $cartItemId = isset($_SESSION['cart']) ? array_keys($_SESSION['cart']) : [];

    if (in_array($productId, $cartItemId)) {
        // Update quantity
        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + :quantity WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->execute();
        return true;
    } else {
        // Add to cart
        $stmt = $pdo->prepare("INSERT INTO cart_items (product_id, quantity) VALUES (?, ?)");
        $stmt->execute([$productId, $quantity]);
        return true;
    }
}


// 2. View Cart Function
function viewCart() {
    $cartItems = [];

    // Fetch cart items from the cart_items table
    $stmt = $pdo->prepare("SELECT p.product_name, p.price, ci.quantity FROM cart_items ci JOIN products p ON ci.product_id = p.id");
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $cartItems[] = $row;
    }

    return $cartItems;
}

// 3. Remove Item from Cart Function
function removeItemFromCart($productId) {
    global $pdo;
    $productId = sanitizeInput($productId);

    // Delete the item from the cart_items table
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE product_id = ?");
    $stmt->execute([$productId]);

    // Update the cart session (remove the item from the array)
    if (isset($_SESSION['cart'])) {
        $cartItemId = array_keys($_SESSION['cart']);
        foreach ($cartItemId as $key => $item) {
            if ($item['product_id'] == $productId) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
    }

    return true;
}



// 4. Update Quantity Function
function updateQuantity($productId, $quantity) {
    global $pdo;
    $productId = sanitizeInput($productId);
    $quantity = (int)$quantity; // Ensure quantity is an integer

    // Check if the product exists
    $stmt = $pdo->prepare("SELECT id, price FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        return false; // Product not found
    }

    // Update the quantity in the cart_items table
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = :quantity WHERE product_id = :product_id");
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();

    return true;
}


// Example usage (Illustrative - this would typically be in your product page or cart page)

// Add to cart example
// if (isset($_POST['add_to_cart'])) {
//     $productId = sanitizeInput($_POST['product_id']);
//     $quantity = (int)$_POST['quantity']; // or $_POST['qty']
//     addToCart($productId, $quantity);
// }

// View cart (example - this is just to demonstrate)
// $cartItems = viewCart();
// if ($cartItems) {
//     echo "<h2>Your Cart</h2>";
//     echo "<ul>";
//     foreach ($cartItems as $item) {
//         echo "<li>" . $item['product_name'] . " - $" . $item['price'] . " (Quantity: " . $item['quantity'] . ")</li>";
//     }
//     echo "</ul>";
// } else {
//     echo "<p>Your cart is empty.</p>";
// }

// Remove Item Example
// if(isset($_GET['remove_item'])) {
//     $productId = sanitizeInput($_GET['remove_item']);
//     removeItemFromCart($productId);
// }

//Update Quantity example
//if(isset($_POST['update_quantity'])) {
//    $productId = sanitizeInput($_POST['product_id']);
//    $newQuantity = (int)$_POST['quantity'];
//    updateQuantity($productId, $newQuantity);
//}
?>
