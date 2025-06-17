        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" value="1" min="1">

        <input type="submit" name="add_to_cart" value="Add to Cart">
    </form>

</body>
</html>


<?php

// Database Connection (Replace with your actual database details)
$db_host = 'localhost';
$db_name = 'ecommerce_db';
$db_user = 'your_username';
$db_password = 'your_password';

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle purchase
function handlePurchase($cart_id, $customer_name, $customer_email, $payment_method) {
    $conn = connectToDatabase();

    // 1.  Get Cart Items
    $query = "SELECT product_id, quantity FROM cart WHERE cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cart_items = [];
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }

    // 2.  Update Order Table
    $order_id = generateOrderID(); // Create a unique order ID
    $order_date = date('Y-m-d H:i:s');

    $query = "INSERT INTO orders (order_id, cart_id, order_date, payment_method) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssis", $order_id, $cart_id, $order_date, $payment_method);
    if ($stmt->execute()) {
        echo "Purchase successful! Order ID: " . $order_id . "<br>";
    } else {
        echo "Purchase failed: " . $stmt->error . "<br>";
    }

    // 3.  Update Inventory (Decrement Quantity)
    foreach ($cart_items as $item) {
        $query = "UPDATE products SET quantity = quantity - ? WHERE product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $item['quantity'], $item['product_id']);
        if ($stmt->execute()) {
            // echo "Product " . $item['product_id'] . " quantity updated successfully.<br>";
        } else {
            echo "Error updating product " . $item['product_id'] . ": " . $stmt->error . "<br>";
        }
    }

    // 4.  Clear Cart (Optional - you could also just set cart_status to 'completed')
    $query = "DELETE FROM cart WHERE cart_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}


// Function to generate a unique order ID (Simple example - improve for production)
function generateOrderID() {
    return bin2hex(random_bytes(16)); // Random hexadecimal string
}


// ----  Example Usage (Form Handling) ----
//  This part shows how you'd typically handle a form submission
//  This is a simplified illustration. In a real application,
//  you'd likely use a framework and a proper form handling system.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cart_id = $_POST["cart_id"];
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];
    $payment_method = $_POST["payment_method"];

    handlePurchase($cart_id, $customer_name, $customer_email, $payment_method);
}
?>
