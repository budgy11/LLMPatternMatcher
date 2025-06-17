
</body>
</html>


<?php
session_start();

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_pass = "your_password";

// Function to connect to the database
function connect_db() {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to add an item to the cart
function add_to_cart($item_id, $quantity) {
    $conn = connect_db();

    if (!$conn) {
        return false;
    }

    // Check if the item already exists in the cart
    $sql = "SELECT * FROM cart WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Item already in cart, update quantity
        $sql = "UPDATE cart SET quantity = quantity + ? WHERE item_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $quantity, $item_id);
        $stmt->execute();
    } else {
        // Item not in cart, add it
        $sql = "INSERT INTO cart (item_id, quantity) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $item_id, $quantity);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();
    return true;
}

// Function to remove an item from the cart
function remove_from_cart($item_id) {
    $conn = connect_db();

    if (!$conn) {
        return false;
    }

    $sql = "DELETE FROM cart WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    return true;
}

// Function to update the quantity of an item in the cart
function update_quantity($item_id, $quantity) {
    $conn = connect_db();

    if (!$conn) {
        return false;
    }

    if ($quantity <= 0) {
        remove_from_cart($item_id); // If quantity is 0 or negative, remove the item
        return true;
    }

    $sql = "UPDATE cart SET quantity = ? WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $quantity, $item_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    return true;
}

// Function to get the cart contents
function get_cart_contents() {
    $conn = connect_db();

    if (!$conn) {
        return [];
    }

    $sql = "SELECT * FROM cart";
    $result = $conn->query($sql);

    $cart_items = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
    }

    $conn->close();
    return $cart_items;
}

// Function to calculate the total cart value
function calculate_total() {
    $cart_items = get_cart_contents();
    $total = 0;

    foreach ($cart_items as $item) {
        // Assuming you have a 'products' table with 'price' column
        // Adjust this query to fetch the product's price from your product table.
        $sql = "SELECT price FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $item['item_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total += $row['price'] * $item['quantity'];
        $stmt->close();
    }

    return $total;
}

//  --------------------  Example Usage (HTML & PHP)  --------------------

// Initialize the cart session if it doesn't exist.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding to cart (example)
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    add_to_cart($item_id, $quantity);
    // Redirect to the same page to refresh the cart display
    header("Location: cart.php"); // Assuming you have a cart.php page
    exit();
}

// Handle removing from cart (example)
if (isset($_GET['remove_from_cart'])) {
    $item_id = $_GET['remove_from_cart'];
    remove_from_cart($item_id);
    header("Location: cart.php");
    exit();
}

// Handle updating quantity (example)
if (isset($_GET['update_quantity'])) {
    $item_id = $_GET['update_quantity'];
    $quantity = $_GET['quantity'];
    update_quantity($item_id, $quantity);
    header("Location: cart.php");
    exit();
}


// Display the cart contents (cart.php -  Simplified example)
?>
