

<?php

// Assuming you have a database connection established (e.g., mysqli)
// Replace with your actual database connection details
$db = new mysqli("localhost", "username", "password", "your_database");

// Check connection
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}

// Function to handle the purchase process
function processPurchase($cart, $user_id) {
  global $db;

  // 1. Verify Inventory
  if (!verifyInventory($cart, $db)) {
    return false; // Inventory issues, purchase failed
  }

  // 2. Deduct Inventory (Update database)
  if (!deductInventory($cart, $db)) {
    return false; // Inventory deduction failed
  }

  // 3. Record Order (Create new order record in the database)
  $order_id = createOrder($cart, $user_id, $db);

  if (!$order_id) {
    return false; // Order creation failed
  }

  return $order_id; // Return the order ID for confirmation
}


// ----- Inventory Verification Function -----
function verifyInventory($cart, $db) {
  // Example:  Check if the quantity of each product in the cart is available.
  // Replace this with your actual database query to check inventory.
  // This is a simplified example - you'll need to adapt it to your schema.

  $sql = "SELECT product_id, quantity FROM cart WHERE cart_id = ?";  // Replace cart_id with the actual cart ID
  $stmt = $db->prepare($sql);
  $stmt->bind_param("i", 1); // Assuming cart_id = 1 (example)
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $product_id = $row['product_id'];
      $quantity_in_cart = $row['quantity'];

      //Get the available quantity from the product table
      $sql2 = "SELECT quantity FROM products WHERE product_id = ?";
      $stmt2 = $db->prepare($sql2);
      $stmt2->bind_param("i", $product_id);
      $stmt2->execute();

      $result2 = $stmt2->get_result();

      if ($result2->num_rows > 0) {
        $available_quantity = $result2->fetch_assoc()['quantity'];
        if ($available_quantity < $quantity_in_cart) {
          return false; // Not enough stock!
        }
      } else {
        // Product doesn't exist - handle this case, perhaps return an error
        return false;
      }
    }
  }
  return true; // All items have sufficient stock
}



// ----- Inventory Deduction Function -----
function deductInventory($cart, $db) {
  // Example:  Update the product quantities in the `products` table.
  // Replace this with your actual database queries.

  // This simplified version assumes a simple 'quantity' field in the products table.
  // In a real application, you'll likely need a more complex approach
  // to handle multiple items and potentially variations (e.g., sizes, colors).

  //  Example query (This is simplified and might need adjustments)
  $sql = "UPDATE products SET quantity = quantity - ? WHERE product_id = ?";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("is", $cart['quantity'], $cart['product_id']);
  $result = $stmt->execute();

  if (!$result) {
    // Handle error (e.g., logging, user notification)
    error_log("Error deducting inventory: " . $db->error);
    return false;
  }

  return true;
}


// ----- Order Creation Function -----
function createOrder($cart, $user_id, $db) {
  // Create a new order record in the database.
  // This is a simplified example; adapt to your database schema.
  $sql = "INSERT INTO orders (user_id, order_date, total_amount) VALUES (?, NOW(), ?)";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("is", $user_id, calculateTotal($cart, $db)); //Calculate Total Amount

  if (!$stmt->execute()) {
    // Handle error (e.g., logging, user notification)
    error_log("Error creating order: " . $db->error);
    return false;
  }

  // Get the last inserted ID (order ID)
  $order_id = $db->insert_id;
  return $order_id;
}

//----- Helper function to calculate total amount
function calculateTotal($cart, $db) {
  $total = 0;
  foreach ($cart as $item) {
    //Assume each item is an array: ['product_id', 'quantity']
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];

    //Fetch product price from the database
    $sql = "SELECT price FROM products WHERE product_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $price = $result->fetch_assoc()['price'];
      $total_item_price = $price * $quantity;
      $total_item_price += $total_item_price;
    }
  }
  return $total_item_price;
}

// ----- Example Usage (Simulated Cart and User ID) -----
$cart = [
  ['product_id' => 1, 'quantity' => 2],
  ['product_id' => 2, 'quantity' => 1],
];
$user_id = 123;

// Process the purchase
$order_id = processPurchase($cart, $user_id);

if ($order_id) {
  echo "Purchase successful! Order ID: " . $order_id;
} else {
  echo "Purchase failed.";
}

// -----  Remember to close the database connection when done -----
$db->close();

?>
