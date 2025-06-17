<?php } ?>

<br>
<a href="checkout.php">Checkout</a>

</body>
</html>


<?php
session_start();

// Database Connection (Replace with your actual database details)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function addToCart($product_id, $quantity) {
    $conn = connectToDatabase();

    // Check if the product exists
    $product_query = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($product_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $product_name = $row['name'];
        $product_price = $row['price'];

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'quantity' => $quantity,
                'product_id' => $product_id,
                'product_name' => $product_name,
                'product_price' => $product_price
            ];
        }
    } else {
        // Product not found - you might want to log this
        echo "Product with ID " . $product_id . " not found.";
    }

    $stmt->close();
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
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to get the cart contents
function getCartContents() {
    return $_SESSION['cart'];
}

// Function to calculate the total cart value
function calculateTotal() {
    $total = 0;
    $cart = getCartContents();
    foreach ($cart as $item) {
        $total += $item['product_price'] * $item['quantity'];
    }
    return $total;
}

// ---------------------  Handle Cart Actions  ---------------------

// Check if the action is "add"
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addToCart($product_id, $quantity);
}

// Check if the action is "remove"
if (isset($_POST['action']) && $_POST['action'] == 'remove') {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
}

// Check if the action is "update"
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    updateQuantity($product_id, $quantity);
}


// --------------------- Display the Cart  ---------------------

// Get the cart contents
$cart = getCartContents();

// Calculate the total
$total = calculateTotal();


?>
