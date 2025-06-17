
<!DOCTYPE html>
<html>
<head>
    <title>Purchase Functionality</title>
</head>
<body>

    <h1>Purchase an Item</h1>

    <form method="post" action="">
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" required><br><br>

        <label for="customer_email">Customer Email:</label>
        <input type="email" id="customer_email" name="customer_email" required><br><br>

        <label for="payment_method">Payment Method:</label>
        <select id="payment_method" name="payment_method">
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="cash">Cash</option>
        </select><br><br>

        <label for="cart">Cart (JSON):</label>
        <textarea id="cart" name="cart" rows="10" cols="50" required>
        {
            "1": 2,
            "2": 1,
            "3": 3
        }
        </textarea><br><br>

        <input type="submit" value="Place Order">
    </form>

</body>
</html>


<?php
session_start();

// Database connection details
$db_host = "localhost";
$db_name = "shop";
$db_user = "root";
$db_password = "";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}


// ------------------ Product Functions ------------------

// Function to fetch products from the database
function getProducts($conn) {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $products = array();
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    } else {
        return array();
    }
}

// ------------------ Purchase Functionality ------------------

// Function to add to cart
function addToCart($conn, $product_id, $quantity) {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "<p>You must be logged in to add to cart.</p>";
        return;
    }

    // Check if the product exists
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo "<p>Product not found.</p>";
        return;
    }
    $product = $result->fetch_assoc();

    // Check if the product is already in the cart
    $cart_query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param("is", $_SESSION['user_id'], $product_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    if ($cart_result->num_rows > 0) {
        // Update the quantity in the cart
        $cart_row = $cart_result->fetch_assoc();
        $new_quantity = $cart_row['quantity'] + $quantity;
        $update_query = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("iis", $_SESSION['user_id'], $product_id, $new_quantity);
        $update_stmt->execute();
    } else {
        // Add the product to the cart
        $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("iis", $_SESSION['user_id'], $product_id, $quantity);
        $insert_stmt->execute();
    }
}

// Function to view cart
function viewCart($conn) {
    if (!isset($_SESSION['user_id'])) {
        echo "<p>You must be logged in to view your cart.</p>";
        return;
    }

    $cart_query = "SELECT p.name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ? ";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param("i", $_SESSION['user_id']);
    $cart_stmt->execute();
    $result = $cart_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Your Cart</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";
        while ($row = $result->fetch_assoc()) {
            $total = $row['price'] * $row['quantity'];
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . $total . "</td>";
            echo "<td><a href='cart.php?action=remove&id=" . $row['product_id'] . "'>Remove</a></td>";
            echo "</tr>";
        }
        echo "</table>";

        // Calculate total cart value
        $total_cart_value = 0;
        $cart_stmt = $conn->prepare($cart_query);
        $cart_stmt->bind_param("i", $_SESSION['user_id']);
        $cart_stmt->execute();
        $result = $cart_stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $total = $row['price'] * $row['quantity'];
            $total_cart_value += $total;
        }
        echo "<p><strong>Total Cart Value: $" . $total_cart_value . "</strong></p>";
    } else {
        echo "<p>Your cart is empty.</p>";
    }
}

// Function to remove item from cart
function removeCartItem($conn, $product_id) {
    if (!isset($_SESSION['user_id'])) {
        echo "<p>You must be logged in to remove items from your cart.</p>";
        return;
    }

    $delete_query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("is", $_SESSION['user_id'], $product_id);
    $delete_stmt->execute();
}


// ------------------  Example Usage (in a web page) ------------------

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["product_id"]) && isset($_POST["quantity"])) {
        $product_id = $_POST["product_id"];
        $quantity = $_POST["quantity"];
        addToCart($conn, $product_id, $quantity);
    }
}

// Cart View
viewCart($conn);

// Remove Cart Item
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $product_id = $_GET['id'];
    removeCartItem($conn, $product_id);
}

?>
