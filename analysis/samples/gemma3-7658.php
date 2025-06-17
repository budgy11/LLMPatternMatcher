
</body>
</html>


<?php

// Database Connection (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// --------------------- Function Definitions ---------------------

// Function to add a product to the cart
function add_to_cart($conn, $product_id, $quantity) {
  // Check if product exists (basic check - improve for production)
  $sql = "SELECT id, name, price FROM products WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    $name = $product['name'];
    $price = $product['price'];

    // Construct the cart item string
    $cart_item = "('" . $product_id . "', " . $quantity . ", '" . $name . "', " . $price . ")";

    //  SQL injection prevention - Prepare statement
    $sql = "INSERT INTO cart (product_id, quantity, name, price) VALUES " . $cart_item;

    if ($conn->query($sql) === TRUE) {
      return true;
    } else {
      error_log("Error adding to cart: " . $conn->error);
      return false;
    }
  } else {
    error_log("Product ID " . $product_id . " not found.");
    return false;
  }

  $stmt->close();
}


// Function to update the quantity of a product in the cart
function update_cart_quantity($conn, $product_id, $new_quantity) {
  // Check if product exists in the cart (basic check - improve for production)
  $sql = "SELECT id FROM cart WHERE product_id = ? AND id > 0";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Update the quantity
    $sql = "UPDATE cart SET quantity = ? WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $new_quantity, $product_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
      return true;
    } else {
      error_log("Error updating cart quantity.");
      return false;
    }
  } else {
    error_log("Product ID " . $product_id . " not found in cart.");
    return false;
  }

  $stmt->close();
}



// Function to remove a product from the cart
function remove_from_cart($conn, $product_id) {
    $sql = "DELETE FROM cart WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}



// Function to get the cart contents
function get_cart_contents($conn) {
  $sql = "SELECT id, product_id, quantity, name, price FROM cart";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $cart_items = array();
    while($row = $result->fetch_assoc()) {
      $cart_items[] = $row;
    }
    return $cart_items;
  } else {
    return array(); // Return an empty array if the cart is empty
  }
}


// --------------------- Example Usage ---------------------

// ---  Simulate a purchase (This is just an example) ---
$product_id = 1;  // Example product ID
$quantity = 2;

if (add_to_cart($conn, $product_id, $quantity)) {
  echo "Product added to cart successfully!";
} else {
  echo "Error adding product to cart.";
}

// Get and display the cart contents
$cart = get_cart_contents($conn);

if (count($cart) > 0) {
  echo "<br><b>Your Cart:</b><br>";
  foreach ($cart as $item) {
    echo "ID: " . $item['id'] . "<br>";
    echo "Product ID: " . $item['product_id'] . "<br>";
    echo "Quantity: " . $item['quantity'] . "<br>";
    echo "Name: " . $item['name'] . "<br>";
    echo "Price: $" . $item['price'] . "<br>";
    echo "---<br>";
  }
} else {
  echo "<br>Your cart is empty.";
}



// --- Update quantity example ---
// $update_id = 1;
// if (update_cart_quantity($conn, $product_id, 3)) {
//     echo "<br>Quantity updated successfully";
// } else {
//     echo "<br>Error updating quantity";
// }


// --- Remove from cart example ---
// if(remove_from_cart($conn, $product_id)) {
//   echo "<br>Product removed from cart";
// }



// ---------------------  Database Setup (Create Tables) ---------------------
//  This code is to create the database and tables.  Run this *once*
//  to set up your database.  It's not part of the purchase functionality.

// $sql = "CREATE TABLE products (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(255) NOT NULL,
//     price DECIMAL(10,2) NOT NULL
// )";

// if (!$conn->query($sql)) {
//   die("Error creating products table: " . $conn->error);
// }

// $sql = "CREATE TABLE cart (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     product_id INT NOT NULL,
//     quantity INT NOT NULL,
//     name VARCHAR(255) NOT NULL,
//     price DECIMAL(10,2) NOT NULL
// )";

// if (!$conn->query($sql)) {
//   die("Error creating cart table: " . $conn->error);
// }


?>
