  </select><br><br>
  <label for="quantity">Quantity:</label>
  <input type="number" id="quantity" name="quantity" value="1" min="1"><br><br>
  <input type="submit" value="Add to Cart">
</form>


<?php

// Assuming you have a database connection established
// (Replace with your actual database connection)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to handle the purchase process
function handlePurchase($cart_id, $customer_name, $customer_email, $payment_method) {
    // Validate Inputs (IMPORTANT - Add more robust validation)
    if (empty($cart_id) || empty($customer_name) || empty($customer_email) || empty($payment_method)) {
        return "Error: All fields are required.";
    }

    // Example:  Add the order to the database
    $sql = "INSERT INTO orders (cart_id, customer_name, customer_email, payment_method, order_date) VALUES (:cart_id, :customer_name, :customer_email, :payment_method, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cart_id', $cart_id);
    $stmt->bindParam(':customer_name', $customer_name);
    $stmt->bindParam(':customer_email', $customer_email);
    $stmt->bindParam(':payment_method', $payment_method);

    if ($stmt->execute()) {
        // Update the cart status to 'processed'
        $update_cart_sql = "UPDATE carts SET status = 'processed' WHERE id = :cart_id";
        $update_stmt = $conn->prepare($update_cart_sql);
        $update_stmt->bindParam(':cart_id', $cart_id);
        if ($update_stmt->execute()) {
            return "Purchase successful! Order ID: " . $conn->lastInsertId();  // Returns the order ID
        } else {
            return "Error updating cart status.";
        }
    } else {
        return "Error creating order.";
    }
}



// --- Example Usage (This would typically be handled by a form submission) ---

// Assuming $cart_id and $payment_method are received from a form
//  (e.g., $_POST['cart_id'])
$cart_id = $_POST['cart_id'];
$payment_method = $_POST['payment_method'];
$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];


$result = handlePurchase($cart_id, $customer_name, $customer_email, $payment_method);
echo $result;

// --- Database Setup (Example Tables - Adjust to your needs) ---
/*
CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,  -- Link to product table
    quantity INT,
    status ENUM('active', 'processed', 'cancelled') DEFAULT 'active',
    total_price DECIMAL(10,2)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT,
    customer_name VARCHAR(255),
    customer_email VARCHAR(255),
    payment_method VARCHAR(50),
    order_date DATETIME,
    FOREIGN KEY (cart_id) REFERENCES carts(id)
);
*/

?>
