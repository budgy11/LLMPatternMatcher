
</body>
</html>


<?php
session_start();

// Database connection details (Replace with your actual details)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "your_database_name";

// Function to connect to the database
function connectDB() {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to handle adding to cart
function addToCart($productId, $quantity) {
    $conn = connectDB();
    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null; // Get user ID from session

    if (!$userId) {
        return false; // User not logged in
    }

    // Check if product exists
    $productQuery = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($productQuery);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Product exists - add to cart
        $productName = $row['name'];
        $productPrice = $row['price'];

        // Check if the cart exists for this user
        $cartQuery = "SELECT id FROM carts WHERE userId = ? ";
        $stmt = $conn->prepare($cartQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $cartResult = $stmt->get_result();
        $cartId = null;

        if ($cartResult->num_rows > 0) {
            $cartId = $cartResult->fetch_assoc()['id'];
        } else {
            // Create a new cart for the user
            $newCartQuery = "INSERT INTO carts (userId) VALUES (?)";
            $stmt = $conn->prepare($newCartQuery);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $cartId = $conn->insert_id;
        }

        // Add item to cart
        $cartItemQuery = "INSERT INTO cart_items (cartId, productId, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($cartItemQuery);
        $stmt->bind_param("iii", $cartId, $productId, $quantity);
        $stmt->execute();

        return true;
    } else {
        return false; // Product not found
    }
    $stmt->close();
    $conn->close();
}


// Function to retrieve cart items
function getCartItems() {
    $conn = connectDB();
    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

    if (!$userId) {
        return []; // Empty cart for unauthenticated users
    }

    $cartItemsQuery = "SELECT ci.id AS cartItemId, ci.productId, ci.quantity, p.name, p.price FROM cart_items ci JOIN products p ON ci.productId = p.id WHERE ci.cartId IN (SELECT id FROM carts WHERE userId = ?) ";
    $stmt = $conn->prepare($cartItemsQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }

    $stmt->close();
    $conn->close();
    return $cartItems;
}


// Function to remove an item from cart
function removeFromCart($cartItemId) {
    $conn = connectDB();
    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

    if (!$userId) {
        return false; // User not logged in
    }

    $removeCartItemQuery = "DELETE FROM cart_items WHERE id = ?";
    $stmt = $conn->prepare($removeCartItemQuery);
    $stmt->bind_param("i", $cartItemId);
    $stmt->execute();

    return $stmt->affected_rows > 0; // Return true if item was removed
}

// Function to update quantity in cart
function updateCartQuantity($cartItemId, $newQuantity) {
    $conn = connectDB();
    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

    if (!$userId) {
        return false; // User not logged in
    }

    $updateCartItemQuery = "UPDATE cart_items SET quantity = ? WHERE id = ? ";
    $stmt = $conn->prepare($updateCartItemQuery);
    $stmt->bind_param("is", $newQuantity, $cartItemId);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}



// ---  Example Usage -  Frontend (JavaScript)  ---
//  This code demonstrates how you'd use the PHP functions in a JavaScript environment.
//  You'll need to adapt this to your specific frontend framework (e.g., React, Angular, Vue).
//  This is a simplified illustration.

//  1.  Add to Cart (example)
//  $productId = 1;
//  $quantity = 2;
//  if (addToCart($productId, $quantity)) {
//      console.log("Product added to cart");
//  } else {
//      console.log("Failed to add product to cart");
//  }

//  2. Retrieve Cart Items
//  let cartItems = getCartItems();
//  console.log("Cart Items:", cartItems);

//  3. Remove Item
//  let cartItemIdToRemove = 5;
//  if (removeFromCart(cartItemIdToRemove)) {
//      console.log("Item removed from cart");
//      let cartItems = getCartItems();
//      console.log("Cart Items after removal:", cartItems);
//  } else {
//      console.log("Failed to remove item from cart");
//  }

//  4. Update Quantity
//  let cartItemIdToUpdate = 3;
//  let newQuantity = 5;
//  if (updateCartQuantity(cartItemIdToUpdate, newQuantity)) {
//      console.log("Quantity updated");
//      let cartItems = getCartItems();
//      console.log("Cart Items after update:", cartItems);
//  } else {
//      console.log("Failed to update quantity");
//  }


// ---  Important Notes  ---
// 1.  Database Setup: Create the `products`, `carts`, and `cart_items` tables in your MySQL database.  The specific schema should be appropriate for your needs.

// 2.  Error Handling: This code includes minimal error handling.  In a production environment, you should implement more robust error handling and logging.

// 3.  Security: This code is a basic example and does not include all security measures.  You *must* sanitize user input, protect against SQL injection, and implement proper authentication and authorization.

// 4.  Frontend Integration: Adapt the JavaScript code to your frontend framework for proper rendering and interaction.
// 5.  Sessions: The code uses `session_start()` to maintain user sessions.  Make sure your server is configured to handle PHP sessions.

// Example database schema:

// products table:
//   id (INT, PRIMARY KEY)
//   name (VARCHAR)
//   price (DECIMAL)

// carts table:
//   id (INT, PRIMARY KEY)
//   userId (INT)

// cart_items table:
//   id (INT, PRIMARY KEY)
//   cartId (INT, FOREIGN KEY referencing carts.id)
//   productId (INT, FOREIGN KEY referencing products.id)
//   quantity (INT)
?>
