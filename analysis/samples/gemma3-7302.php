

<?php

// Database connection details (replace with your actual credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Function to add a product to the cart
function addToCart($conn, $product_id, $quantity) {
  // Check if the product already exists in the cart
  $query = "SELECT * FROM cart WHERE product_id = '$product_id'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    // Product exists, update the quantity
    $query = "UPDATE cart SET quantity = quantity + $quantity WHERE product_id = '$product_id'";
  } else {
    // Product doesn't exist, add a new entry
    $query = "INSERT INTO cart (product_id, quantity) VALUES ('$product_id', $quantity)";
  }

  if ($conn->query($query) === TRUE) {
    return true;
  } else {
    return false;
  }
}


// Function to get the cart contents
function getCartContents($conn) {
    $query = "SELECT * FROM cart";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $cart_contents = array();
        while($row = $result->fetch_assoc()) {
            $cart_contents[] = $row;
        }
        return $cart_contents;
    } else {
        return array();
    }
}


// Function to remove a product from the cart
function removeFromCart($conn, $product_id) {
  $query = "DELETE FROM cart WHERE product_id = '$product_id'";

  if ($conn->query($query) === TRUE) {
    return true;
  } else {
    return false;
  }
}



// ======================  Purchase Logic (Simplified)  =========================

//  This is a simplified example. A real-world purchase process would involve:
//  - User Authentication
//  - Payment Processing (e.g., PayPal, Stripe)
//  - Order Confirmation Emails
//  - Inventory Management

// Example:  Simulating a Purchase (Empty cart is assumed)
if (isset($_POST['purchase_button'])) {
    // Check if the cart is empty
    $cart_contents = getCartContents($conn);
    if (empty($cart_contents)) {
        echo "<p>Your cart is empty.  Nothing to purchase.</p>";
    } else {
        // Simulate purchase processing
        echo "<p><b>Purchase Initiated!</b></p>";
        echo "<p>Items in your cart:</p>";

        foreach ($cart_contents as $item) {
            echo "Product ID: " . $item['product_id'] . "<br>";
            echo "Quantity: " . $item['quantity'] . "<br>";
            echo "--------------------<br>";
        }

        // Clear the cart after purchase (optional)
        // This would typically be handled on the server-side after successful processing
        // For demonstration purposes, we'll do it here.  This is not ideal for production.
        $conn->query("DELETE FROM cart");
        echo "<p>Cart cleared.</p>";
    }
}

// ======================  Display Cart and Purchase Button ====================
$cart_contents = getCartContents($conn);

?>
