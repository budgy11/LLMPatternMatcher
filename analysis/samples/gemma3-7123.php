    <br><br>

    <button type="submit" name="submit">Place Order</button>
</form>

</body>
</html>


<?php

// Assuming you have a database connection established (e.g., mysqli)
// $conn = new mysqli("localhost", "username", "password", "database_name");

// Check connection
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }

//  Example using a dummy product array for simplicity (replace with database query)
$products = [
    ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    ['id' => 3, 'name' => 'Hat', 'price' => 15]
];


// Function to handle the purchase process
function handlePurchase($cart, $conn) {
  $total = 0;
  $order_details = [];

  foreach ($cart as $item_id => $quantity) {
    // Find the product details based on the item_id
    $product = null;
    foreach ($products as $p) {
      if ($p['id'] == $item_id) {
        $product = $p;
        break;
      }
    }

    if ($product) {
      $price = $product['price'] * $quantity;
      $total += $price;
      $order_details[] = [
        'product_id' => $item_id,
        'product_name' => $product['name'],
        'quantity' => $quantity,
        'price' => $price
      ];
    } else {
      // Handle the case where the product ID is not found (e.g., display an error)
      echo "Product with ID " . $item_id . " not found.";
      return false;
    }
  }

  // Insert order details into a database table (replace with your database insertion)
  if ($conn) {
    $sql = "INSERT INTO orders (total_amount, order_details) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sd", $total, json_encode($order_details));  // json_encode for compatibility
    $stmt->execute();
    echo "Order placed successfully! Order ID: " . $conn->insert_id;
  } else {
      echo "Database connection failed.  Could not place order.";
  }
  return true;
}


// Example usage:
$cart = [
    1 => 2, // 2 T-Shirts
    2 => 1 // 1 Jeans
];


if (handlePurchase($cart, $conn)) {
  // Display success message
} else {
  // Display error message
}

//  ---  Database Table Setup (for demonstration) ---
//  Create the following table (adjust data types as needed)
/*
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_amount DECIMAL(10,2) NOT NULL,
    order_details TEXT NOT NULL
);
*/

?>
