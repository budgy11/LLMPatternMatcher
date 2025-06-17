        </select><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1"><br><br>

        <input type="submit" value="Place Order">
    </form>

</body>
</html>


<?php

// Database Configuration (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "ecommerce";
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

// Function to handle purchase logic
function handlePurchase($cart_id, $customer_name, $customer_email, $payment_method) {
    $conn = connectToDatabase();

    // 1. Get Cart Details
    $result = $conn->query("SELECT product_id, quantity FROM cart WHERE cart_id = '$cart_id'");
    $cart_items = $result->fetch_assoc();

    if (!$cart_items) {
        echo "<p>No items in the cart.</p>";
        return false;
    }

    // 2. Calculate Total Amount
    $total_amount = 0;
    foreach ($cart_items as $product_id => $quantity) {
        // Fetch product details from the products table
        $product_query = $conn->query("SELECT price FROM products WHERE product_id = '$product_id'");
        $product = $product_query->fetch_assoc();
        $total_amount += $product['price'] * $quantity;
    }

    // 3. Record the Order
    // Assuming you have a 'orders' table with columns: order_id, cart_id, customer_name, customer_email, order_date, total_amount, payment_method
    $conn->query("INSERT INTO orders (cart_id, customer_name, customer_email, order_date, total_amount, payment_method) 
                VALUES ('$cart_id', '$customer_name', '$customer_email', NOW(), '$total_amount', '$payment_method')");

    // 4. Update Cart (Reduce Quantities)
    foreach ($cart_items as $product_id => $quantity) {
        $conn->query("UPDATE cart SET quantity = quantity - '$quantity' WHERE product_id = '$product_id' AND cart_id = '$cart_id'");
    }

    // 5.  Clean up Cart (If Cart is Empty) - Optional
    // Check if the cart is empty after the purchase
    $empty_cart_query = $conn->query("SELECT SUM(quantity) FROM cart WHERE cart_id = '$cart_id'");
    $empty_cart_result = $empty_cart_result->fetch_assoc();

    if ($empty_cart_result['SUM(quantity)'] == 0) {
        $conn->query("DELETE FROM cart WHERE cart_id = '$cart_id'");
    }

    echo "<p>Order placed successfully! Order ID: " . $conn->insert_id . "</p>"; // Show order ID
    return true;
}



// --- Example Usage (Simulated form handling) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Get Form Data
    $cart_id = $_POST["cart_id"];
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];
    $payment_method = $_POST["payment_method"];

    // 2.  Call the purchase function
    if (handlePurchase($cart_id, $customer_name, $customer_email, $payment_method)) {
        // Handle success - Redirect to order confirmation page, etc.
    } else {
        // Handle failure
    }
}
?>
