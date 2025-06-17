

<?php
session_start();

// Database Connection (Replace with your actual database details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add item to cart
function addItemToCart($product_id, $quantity) {
    $conn = connectToDatabase();

    // Check if the product exists
    $sql = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $name = $row['name'];
        $price = $row['price'];

        // Check if the product is already in the cart
        $cart_key = "cart_" . session_id(); // Unique cart identifier

        if (isset($_SESSION[$cart_key]['items'][$product_id])) {
            // Product already in cart, update the quantity
            $_SESSION[$cart_key]['items'][$product_id]['quantity'] += $quantity;
            $_SESSION[$cart_key]['total_price'] += $price * $quantity;
        } else {
            // Product not in cart, add it
            $_SESSION[$cart_key]['items'][$product_id] = array(
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity
            );
            $_SESSION[$cart_key]['total_price'] = $price * $quantity;
        }
    } else {
        // Product not found, you might want to handle this error differently
        echo "Product with ID " . $product_id . " not found.";
    }

    $stmt->close();
    $conn->close();
}

// Function to get cart contents
function getCartContents() {
    $cart_key = "cart_" . session_id();

    if (isset($_SESSION[$cart_key])) {
        return $_SESSION[$cart_key];
    } else {
        return array(); // Return an empty array if cart is empty
    }
}

// Function to remove item from cart
function removeItemFromCart($product_id) {
    $cart_key = "cart_" . session_id();

    if (isset($_SESSION[$cart_key]['items'][$product_id])) {
        unset($_SESSION[$cart_key]['items'][$product_id]);
        $_SESSION[$cart_key]['total_price'] -= $_SESSION[$cart_key]['items'][$product_id]['price'] * $_SESSION[$cart_key]['items'][$product_id]['quantity'];
    }
}

// Function to update quantity of item in cart
function updateQuantity($product_id, $quantity) {
  $cart_key = "cart_" . session_id();

  if (isset($_SESSION[$cart_key]['items'][$product_id])) {
    if ($quantity > 0) {
      $_SESSION[$cart_key]['items'][$product_id]['quantity'] = $quantity;
      $_SESSION[$cart_key]['total_price'] = $_SESSION[$cart_key]['items'][$product_id]['price'] * $quantity;
    } else {
      removeItemFromCart($product_id); // If quantity is 0, remove the item
    }
  }
}

// --- Example Usage (handle form submissions) ---

// Add item to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    addItemToCart($product_id, $quantity);
}

// Remove item from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_from_cart"])) {
    $product_id = $_POST["product_id"];
    removeItemFromCart($product_id);
}

// Update quantity
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_quantity"])) {
    $product_id = $_POST["product_id"];
    $new_quantity = $_POST["quantity"];
    updateQuantity($product_id, $new_quantity);
}

// --- Display Cart Contents (Example) ---

$cart = getCartContents();

if (!empty($cart)) {
    echo "<h2>Shopping Cart</h2>";
    echo "<ul>";
    foreach ($cart['items'] as $id => $item) {
        echo "<li>";
        echo "<strong>" . $item['name'] . "</strong> - $" . $item['price'] . " ";
        echo "Quantity: " . $item['quantity'];
        echo "</li>";
    }
    echo "</ul>";
    echo "<p>Total: $" . number_format($cart['total_price'], 2) . "</p>";
} else {
    echo "<p>Your cart is empty.</p>";
}
?>
