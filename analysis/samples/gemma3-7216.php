

<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Establish database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Function to Add a Product to the Cart ---
function addToCart($conn, $product_id, $quantity) {
    // Prepare the SQL query
    $sql = "INSERT INTO cart (user_id, product_id, quantity) 
            VALUES (1, {$product_id}, {$quantity}) 
            ON DUPLICATE KEY UPDATE quantity = quantity + {$quantity}"; // Handles duplicate product_id (user)

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}


// --- Function to Get Cart Items ---
function getCartItems($conn, $user_id) {
    $sql = "SELECT p.product_name, p.price, c.quantity
            FROM cart c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.user_id = {$user_id}";

    $result = $conn->query($sql);

    $cart_items = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
    }

    return $cart_items;
}


// --- Function to Remove a Product from the Cart ---
function removeFromCart($conn, $product_id, $user_id) {
    $sql = "DELETE FROM cart WHERE product_id = {$product_id} AND user_id = {$user_id}";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}


// --- Function to Update Cart Quantity ---
function updateCartQuantity($conn, $product_id, $user_id, $quantity) {
  // Check if the product exists in the cart for this user
  $sql = "SELECT quantity FROM cart WHERE product_id = {$product_id} AND user_id = {$user_id}";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantity;

    $sql = "UPDATE cart SET quantity = {$new_quantity} WHERE product_id = {$product_id} AND user_id = {$user_id}";
    if ($conn->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  } else {
    return false; // Product not found in cart for this user
  }
}


// --- Example Usage (Simulating a Purchase -  Illustrative) ---

// 1. Add a product to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (addToCart($conn, $product_id, $quantity)) {
        echo "Product added to cart!";
    } else {
        echo "Failed to add product to cart.";
    }
}

// 2. Get Cart Items (e.g., display in a cart page)
$cart_items = getCartItems($conn, 1); // Assuming user_id 1
// Display the cart items here (e.g., using a table)

// 3. Remove a product from the cart (e.g., from a remove button)
if (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    $user_id = 1; // Assuming user_id 1
    if (removeFromCart($conn, $product_id, $user_id)) {
        echo "Product removed from cart!";
    } else {
        echo "Failed to remove product from cart.";
    }
}

// 4. Update Quantity (e.g., when the user changes the quantity)
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $user_id = 1;
    $new_quantity = $_POST['quantity'];

    if (updateCartQuantity($conn, $product_id, $user_id, $new_quantity)) {
        echo "Quantity updated!";
    } else {
        echo "Failed to update quantity.";
    }
}


// --- Close the database connection ---
$conn->close();

?>
