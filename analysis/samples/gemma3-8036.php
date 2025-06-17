

<?php

// Database connection (replace with your actual database credentials)
$dbHost = "localhost";
$dbName = "shop";
$dbUser = "username";
$dbPass = "password";

// Create a database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Function to add a new product (Example)
function addProduct($productName, $price, $quantity) {
    global $conn; // Access the database connection

    $sql = "INSERT INTO products (name, price, quantity) VALUES ('$productName', '$price', '$quantity')";

    if ($conn->query($sql) === TRUE) {
        return true; // Success
    } else {
        return false; // Failure
    }
}

// Function to process the purchase
function processPurchase($cart) {
    global $conn;

    // 1.  Validate Cart (check for valid product IDs and sufficient stock)
    $validCart = [];
    foreach ($cart as $productId => $quantity) {
        $product = getProductById($productId);

        if ($product) {
            if ($product['quantity'] >= $quantity) {
                $validCart[] = $productId => $quantity;
            } else {
                echo "<p style='color:red;'>Error: Not enough stock for product ID: " . $productId . "</p>";
                return false; // Cannot proceed if stock is insufficient
            }
        } else {
            echo "<p style='color:red;'>Error: Product ID " . $productId . " not found.</p>";
            return false; // Cannot proceed if product doesn't exist
        }
    }

    // 2. Update Stock (reduce quantity in the database)
    foreach ($validCart as $productId => $quantity) {
        $product = getProductById($productId);
        if ($product) {
            $newQuantity = $product['quantity'] - $quantity;
            updateProductQuantity($productId, $newQuantity);
        }
    }

    // 3.  Generate Order Details (example - can be expanded)
    $orderId = generateOrderId();
    $orderDate = date("Y-m-d H:i:s");
    $totalAmount = calculateTotal($validCart);

    // 4.  Create Order Table Entry
    $sql = "INSERT INTO orders (order_id, order_date, total_amount) VALUES ('$orderId', '$orderDate', '$totalAmount')";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Purchase successful! Order ID: " . $orderId . "</p>";
        return true;
    } else {
        echo "<p style='color:red;'>Error creating order: " . $conn->error . "</p>";
        return false;
    }
}


//  Helper Functions (Example Implementations)

// Function to get a product by ID
function getProductById($productId) {
    global $conn;

    $sql = "SELECT * FROM products WHERE id = '$productId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        return $product;
    } else {
        return null;
    }
}

// Function to update product quantity
function updateProductQuantity($productId, $newQuantity) {
    global $conn;

    $sql = "UPDATE products SET quantity = '$newQuantity' WHERE id = '$productId'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to generate a unique order ID (Simple Example - improve for production)
function generateOrderId() {
    global $conn;
    $orderId = bin2hex(random_bytes(5)); // Generate a random hex string
    return $orderId;
}

// Function to calculate the total order amount
function calculateTotal($cart) {
  $total = 0;
  foreach ($cart as $productId => $quantity) {
    $product = getProductById($productId);
    if ($product) {
      $total += $product['price'] * $quantity;
    }
  }
  return $total;
}


// --- Example Usage (This would typically be handled via a form submission)
// Assume $cart is a variable containing the items the user wants to purchase.
// For example:
// $cart = array(
//    '1' => 2,  // Product ID 1, quantity 2
//    '2' => 1   // Product ID 2, quantity 1
// );

// Example cart (replace with your actual cart data)
$cart = array(
    '1' => 2,  // Product ID 1, quantity 2
    '2' => 1   // Product ID 2, quantity 1
);

// Process the purchase
if (processPurchase($cart)) {
    // Order created successfully - redirect or display confirmation
    echo "<p>Order placed successfully!</p>";
} else {
    // Handle errors - display error messages
    echo "<p>There was an error processing your order.</p>";
}


?>
