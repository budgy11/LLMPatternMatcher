    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database Connection (Replace with your actual database credentials)
$db_host = "localhost";
$db_name = "e_commerce";
$db_user = "your_username";
$db_password = "your_password";

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

// Function to handle the purchase process
function processPurchase($cart_id, $user_id) {
    // Validate inputs (add more robust validation as needed)
    if (!is_numeric($cart_id) || !is_numeric($user_id)) {
        return "Invalid input.  Cart ID and User ID must be numbers.";
    }

    // 1.  Retrieve Cart Details
    $stmt = $pdo->prepare("SELECT product_id, quantity, price FROM cart WHERE cart_id = :cart_id");
    $stmt->bindParam(':cart_id', $cart_id);
    $stmt->execute();
    $cart_details = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cart_details)) {
        return "Cart not found.";
    }

    // 2. Calculate Total Amount
    $total_amount = 0;
    foreach ($cart_details as $row) {
        $total_amount += $row['price'] * $row['quantity'];
    }

    // 3.  Update Order Details (Create a new order record)
    $order_id =  (int)($pdo->lastInsertId()); // Get the auto-incremented order ID
    $order_date = date("Y-m-d H:i:s"); // Current timestamp
    $order_status = "pending";

    $stmt = $pdo->prepare("INSERT INTO orders (user_id, order_date, order_status, total_amount, cart_id) 
                             VALUES (:user_id, :order_date, :order_status, :total_amount, :cart_id)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':order_date', $order_date);
    $stmt->bindParam(':order_status', $order_status);
    $stmt->bindParam(':total_amount', $total_amount);
    $stmt->bindParam(':cart_id', $cart_id);
    $stmt->execute();


    // 4.  Update Cart (Mark items as purchased -  e.g., change quantity to 0 or remove entirely)
    //  Option 1:  Reduce quantity
    //$stmt = $pdo->prepare("UPDATE cart SET quantity = quantity - 1 WHERE cart_id = :cart_id");
    // $stmt->bindParam(':cart_id', $cart_id);
    // $stmt->execute();

    // Option 2:  Delete cart items
    $stmt = $pdo->prepare("DELETE FROM cart WHERE cart_id = :cart_id");
    $stmt->bindParam(':cart_id', $cart_id);
    $stmt->execute();



    return "Purchase successful! Order ID: $order_id";
}


// ---  Example Usage (This part would be in a form submission) ---
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get cart_id and user_id from the form
    $cart_id = $_POST['cart_id'];
    $user_id = $_POST['user_id'];

    // Process the purchase
    $result = processPurchase($cart_id, $user_id);

    // Display the result
    echo "<br>" . $result;
}
?>
