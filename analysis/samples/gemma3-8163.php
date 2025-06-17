    <?php endif; ?>

    <hr>
    <p>
        <a href="index.php">Continue Shopping</a>
    </p>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$dbHost = 'localhost';
$dbUser = 'your_db_user';
$dbPass = 'your_db_password';
$dbName = 'your_db_name';

// Function to connect to the database
function connectDB() {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function addToCart($conn, $userId, $productId, $quantity) {
    // Check if the user has a cart yet
    if (!isset($_SESSION['cart'][$productId])) {
        // If not, create a new entry in the cart
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($userId, $productId, $quantity)";
        if ($conn->query($query) === TRUE) {
            $_SESSION['cart'][$productId] = $quantity;
        } else {
            echo "Error adding to cart: " . $conn->error;
        }
    } else {
        // If the item already exists, update the quantity
        $_SESSION['cart'][$productId] += $quantity;
    }
}

// Function to remove an item from the cart
function removeFromCart($conn, $userId, $productId) {
    // Delete the item from the cart
    $query = "DELETE FROM cart WHERE user_id = $userId AND product_id = $productId";
    if ($conn->query($query) === TRUE) {
        unset($_SESSION['cart'][$productId]);
    } else {
        echo "Error removing from cart: " . $conn->error;
    }
}

// Function to get the cart contents
function getCartContents($conn, $userId) {
    $cartContents = [];

    // Retrieve all cart items for the user
    $query = "SELECT product_id, quantity FROM cart WHERE user_id = $userId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];

            // Get product details (you'll need a products table)
            $product_query = "SELECT product_id, product_name, price FROM products WHERE product_id = $product_id";
            $product_result = $conn->query($product_result);

            if ($product_result->num_rows > 0) {
                $product = $product_result->fetch_assoc();
                $cartContents[] = [
                    'product_id' => $product['product_id'],
                    'product_name' => $product['product_name'],
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'total' => $product['price'] * $quantity
                ];
            } else {
                // Handle the case where the product is not found
                echo "Product ID $product_id not found.";
            }
        }
    }

    return $cartContents;
}

// ------  Shopping Cart Logic  ------

// 1. Add to Cart (Handle form submission)
if (isset($_POST['add_to_cart'])) {
    $userId = $_SESSION['user_id']; // Assuming user is logged in
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    addToCart($conn, $userId, $productId, $quantity);
    header("Location: cart.php"); // Redirect to cart page
    exit();
}

// 2. Remove from Cart
if (isset($_GET['remove_from_cart'])) {
    $userId = $_SESSION['user_id'];
    $productId = $_GET['remove_from_cart'];
    removeFromCart($conn, $userId, $productId);
    header("Location: cart.php");
    exit();
}

// 3. Get Cart Contents (Load cart data)
$cartContents = getCartContents($conn, $_SESSION['user_id']);

?>
