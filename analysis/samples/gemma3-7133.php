    <br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required>
    <br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required>
    <br><br>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method">
      <option value="credit_card">Credit Card</option>
      <option value="paypal">PayPal</option>
    </select>
    <br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual details)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

//  Helper function to sanitize input (prevent SQL injection)
function sanitizeInput($data) {
    global $conn; // Access the database connection
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



// Start the shopping cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Function to add an item to the cart
function addToCart($product_id, $name, $price, $quantity) {
    global $conn, $_SESSION['cart'];

    $name = sanitizeInput($name);
    $price = sanitizeInput($price);
    $quantity = sanitizeInput($quantity);

    if (!is_numeric($quantity) || $quantity <= 0) {
        return false; // Invalid quantity
    }

    $item = array(
        'id' => $product_id,
        'name' => $name,
        'price' => $price,
        'quantity' => $quantity
    );

    $_SESSION['cart'][] = $item;
    return true;
}


// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
    global $conn, $_SESSION['cart'];

    $new_quantity = sanitizeInput($new_quantity);

    if (!is_numeric($new_quantity) || $new_quantity <= 0) {
        return false; // Invalid quantity
    }


    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['id'] == $product_id) {
            $_SESSION['cart'][$i]['quantity'] = $new_quantity;
            return true;
        }
    }
    return false;
}



// Function to remove an item from the cart
function removeFromCart($product_id) {
    global $conn, $_SESSION['cart'];

    $product_id = sanitizeInput($product_id);

    $keys_to_remove = array();

    foreach($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            $keys_to_remove[] = $key;
        }
    }

    foreach ($keys_to_remove as $key) {
        unset($_SESSION['cart'][$key]);
    }

    return true;
}



// Function to calculate the total cart value
function calculateTotal() {
    global $conn, $_SESSION['cart'];
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// Handling different actions

// Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    if (addToCart($product_id, $name, $price, $quantity)) {
        echo "<p>Item added to cart.</p>";
    } else {
        echo "<p>Failed to add item to cart.</p>";
    }
}


// Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    if (updateQuantity($product_id, $new_quantity)) {
        echo "<p>Quantity updated in cart.</p>";
    } else {
        echo "<p>Failed to update quantity.</p>";
    }
}

// Remove Item
if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    removeFromCart($product_id);
    echo "<p>Item removed from cart.</p>";
}



// Display Cart
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>";
        echo "Product: " . $item['name'] . "<br>";
        echo "Price: $" . number_format($item['price'], 2) . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Subtotal: $" . number_format($item['price'] * $item['quantity'], 2) . "<br>";

        // Optional: Add a button to update quantity or remove item
        echo "<form method='post'>";
        echo "<label for='quantity_" . $item['id'] . "'>Quantity:</label>";
        echo "<input type='number' id='quantity_" . $item['id'] . "' value='" . $item['quantity'] . "' min='1' max='99' name='quantity_" . $item['id'] . "'>";
        echo "<input type='hidden' name='product_id' value='" . $item['id'] . "'>";
        echo "<input type='submit' value='Update'>";
        echo "</form>";


        echo "<br>";
    }
    echo "</ul>";

    echo "<p><strong>Total: $" . number_format(calculateTotal(), 2) . "</strong></p>";
}


?>
