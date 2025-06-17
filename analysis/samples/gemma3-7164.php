    <br><br>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method">
        <option value="credit_card">Credit Card</option>
        <option value="paypal">PayPal</option>
        <option value="other">Other</option>
    </select>
    <br><br>

    <button type="submit">Purchase Now</button>
</form>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = 'localhost';
$db_name = 'shopping_cart';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to connect to the database
function connect_db() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function add_to_cart($product_id, $quantity) {
    $conn = connect_db();

    if (!$conn) {
        return false;
    }

    $product_id = intval($product_id); // Ensure product ID is an integer
    $quantity = intval($quantity);      // Ensure quantity is an integer

    // Check if the product exists
    $sql = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return false; // Product doesn't exist
    }

    $product = $result->fetch_assoc();

    // Check if the item is already in the cart
    $cart_item_id = isset($_SESSION['cart']) ? array_keys($_SESSION['cart']) : [];
    if (in_array($product['id'], $cart_item_id)) {
        // Item already in cart, update quantity
        $cart_item_id = array_keys($_SESSION['cart']);
        $index = array_search($product['id'], $cart_item_id);
        $_SESSION['cart'][$index]['quantity'] += $quantity;
    } else {
        // Add new item to cart
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }
    $stmt->close();
    return true;
}


// Function to get the cart items
function get_cart_items() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return [];
    }
    return $_SESSION['cart'];
}

// Function to update quantity in cart
function update_cart_quantity($product_id, $quantity) {
    $conn = connect_db();

    if (!$conn) {
        return false;
    }

    $product_id = intval($product_id); // Ensure product ID is an integer
    $quantity = intval($quantity);      // Ensure quantity is an integer

    // Check if the product exists
    $sql = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt->close();
        return false; // Product doesn't exist
    }

    $product = $result->fetch_assoc();

    // Check if the product exists in the cart
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return false;
    }
    
    $cart_items = get_cart_items();

    foreach ($cart_items as $key => $item) {
        if ($item['id'] == $product['id']) {
            $_SESSION['cart'][$key]['quantity'] = $quantity;
            break;
        }
    }

    $stmt->close();
    return true;
}


// Function to remove item from cart
function remove_from_cart($product_id) {
    $conn = connect_db();

    if (!$conn) {
        return false;
    }

    $product_id = intval($product_id); // Ensure product ID is an integer

    // Check if the product exists in the cart
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return false;
    }

    $cart_items = get_cart_items();

    foreach ($cart_items as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            $cart_items = get_cart_items();
            break;
        }
    }
    return true;
}

// Function to get the total cart value
function calculate_cart_total() {
    $cart_items = get_cart_items();
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


//--------------------  Example usage and handling the request (e.g., from a form)  --------------------

// If the request is to add an item to the cart:
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    add_to_cart($product_id, $quantity);
}

// If the request is to update quantity in cart:
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    update_cart_quantity($product_id, $quantity);
}

//If the request is to remove an item from cart:
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
}

// Display the cart contents:
$cart_items = get_cart_items();
$total = calculate_cart_total();

?>
