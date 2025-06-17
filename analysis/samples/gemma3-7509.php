
<!-- Example HTML Form (For testing) -->
<form method="POST" action="">
  <label for="user_id">User ID:</label>
  <input type="number" id="user_id" name="user_id" value="123"> <br><br>

  <label for="cart_items">Cart Items (JSON):</label>
  <input type="text" id="cart_items" name="cart_items" value='[{"product_id":1,"quantity":2},{"product_id":2,"quantity":1}]'> <br><br>

  <button type="submit">Create Purchase</button>
</form>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Initialize variables
$cart = [];  // Array to store items in the cart
$total_amount = 0.00; // Total amount of the cart

// Function to add an item to the cart
function addItemToCart($conn, $product_id, $quantity) {
    global $cart, $total_amount;

    // Check if the item is already in the cart
    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += $quantity;
            $item['price'] = $conn->query("SELECT price FROM products WHERE id = $product_id")->fetch_assoc()['price']; // Get current price
            $total_amount = 0.00;
            foreach ($cart as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }
            return;
        }
    }

    // Item not in cart, add it
    $result = $conn->query("SELECT id, name, price FROM products WHERE id = $product_id");
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $cart[] = [
            'product_id' => $product_id,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
        $total_amount = 0.00;
        foreach ($cart as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }
    } else {
        echo "Product not found.";
    }
}

// Function to remove an item from the cart
function removeItemFromCart($conn, $product_id) {
    global $cart;

    foreach ($cart as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($cart[$key]);
            $total_amount = 0.00;
            foreach ($cart as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }
            return;
        }
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($conn, $product_id, $quantity) {
    global $cart;

    foreach ($cart as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] = $quantity;
            $item['price'] = $conn->query("SELECT price FROM products WHERE id = $product_id")->fetch_assoc()['price']; // Get current price
            $total_amount = 0.00;
            foreach ($cart as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }
            return;
        }
    }
}


// Handle add to cart request
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (isset($conn)) { // Ensure the database connection is established
        addItemToCart($conn, $product_id, $quantity);
    } else {
        echo "Database connection failed.";
    }
}


// Handle remove from cart request
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    if (isset($conn)) {
        removeItemFromCart($conn, $product_id);
    }
}

// Handle update quantity request
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    if (isset($conn)) {
        updateQuantity($conn, $product_id, $new_quantity);
    }
}

// Display the cart
echo "<h2>Shopping Cart</h2>";

if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>" . $item['name'] . " - $" . number_format($item['price'], 2) . " x " . $item['quantity'] . " = $" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total Amount: $" . number_format($total_amount, 2) . "</strong></p>";
}

// Example of a button to clear the cart
echo "<br>";
echo "<a href='cart_clear.php'>Clear Cart</a>";
?>
