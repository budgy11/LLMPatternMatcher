  <br><br>
  <button type="submit">Process Purchase</button>
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Function to connect to the database
function connectToDatabase() {
  try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit; // Stop execution if the connection fails
  }
}

// Function to handle the purchase process
function handlePurchase($cart_id, $user_id) {
  $pdo = connectToDatabase();

  // Update the cart status to 'completed'
  try {
    $stmt = $pdo->prepare("UPDATE carts SET status = 'completed', completed_at = NOW() WHERE id = :cart_id");
    $stmt->bindParam(':cart_id', $cart_id);
    $stmt->execute();

    //  (Optional) Add order details to a separate orders table
    //  This allows you to track order history.
    //  You'll likely need to create an `orders` table with columns like `cart_id`, `user_id`, `order_date`, etc.

    // Example:  Inserting an order record
    // $order_stmt = $pdo->prepare("INSERT INTO orders (cart_id, user_id, order_date) VALUES (:cart_id, :user_id, NOW())");
    // $order_stmt->bindParam(':cart_id', $cart_id);
    // $order_stmt->bindParam(':user_id', $user_id);
    // $order_stmt->execute();


    echo "Purchase successful! Cart ID: " . $cart_id;

  } catch (PDOException $e) {
    echo "Purchase failed: " . $e->getMessage();
  }
}

// --- Example Usage (Simulated form submission) ---
//  This is how you would process a purchase request.
//  In a real application, this would come from an HTML form.

// Assuming the form submitted with cart_id = 3 and user_id = 1
$cart_id = 3;
$user_id = 1;

handlePurchase($cart_id, $user_id);

?>
