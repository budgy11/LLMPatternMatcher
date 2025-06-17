

<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database";

// Function to connect to the database
function connectDB() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function addToCart($conn, $product_id, $quantity) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
    } else {
        $cart = [];
    }

    // Check if the product already exists in the cart
    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $cart[$key]['quantity'] += $quantity;
            return;
        }
    }

    // If product not found, add it to the cart
    $cart[] = [
        'product_id' => $product_id,
        'quantity' => $quantity
    ];
}

// Function to get the cart total
function getCartTotal($conn) {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        foreach ($cart as $item) {
            $product_id = $item['product_id'];
            $product_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, price FROM products WHERE id = $product_id"));
            $total_price = $product_name['price'] * $item['quantity'];
            $total = $total + $total_price;
        }
    }
    return $total;
}

// Function to clear the cart
function clearCart() {
    unset($_SESSION['cart']);
}

// Function to get product details by ID
function getProductDetails($conn, $product_id) {
    $result = mysqli_query($conn, "SELECT id, name, price, description FROM products WHERE id = $product_id");
    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart button
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart(connectDB(), $product_id, $quantity);
}

// Handle Remove from Cart button (basic implementation)
if (isset($_GET['remove_from_cart'])) {
    $product_id = $_GET['remove_from_cart'];
    removeFromCart(connectDB(), $product_id);
}

//Remove from cart function
function removeFromCart($conn, $product_id) {
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($cart[$key]);
                //remove all keys greater than the current key
                $keys = array_keys($cart);
                $new_cart = array();
                foreach ($keys as $key) {
                    $new_cart[] = $cart[$key];
                }
                $_SESSION['cart'] = $new_cart;
                break;
            }
        }
    }
}

// Display the cart
echo "<h2>Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['product_id'];
        $product_name = mysqli_fetch_assoc(mysqli_query(connectDB(), "SELECT name, price FROM products WHERE id = $product_id"));
        $total_price = $product_name['price'] * $item['quantity'];
        echo "<li>" . $product_name['name'] . " - $" . $product_name['price'] . " x " . $item['quantity'] . " = $" . $total_price . "</li>";
        $total = $total + $total_price;
    }
    echo "</ul>";
    echo "<p><strong>Total: $" . $total . "</strong></p>";
    echo "<a href='checkout.php'>Proceed to Checkout</a>";
}
?>
