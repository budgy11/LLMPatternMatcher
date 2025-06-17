

<?php
session_start();

// Database Connection (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_username";
$db_password = "your_password";
$db_name = "your_database_name";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}


// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to add to cart
function addToCart($product_id, $quantity) {
    global $db;

    // Get product details
    $product_query = "SELECT id, name, price FROM products WHERE id = ?";
    $stmt = $db->prepare($product_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $product_name = $product['name'];
        $product_price = $product['price'];

        // Check if the product is already in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            // Update the quantity if the product is already in the cart
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            // Add the product to the cart
            $_SESSION['cart'][$product_id] = [
                'id' => $product_id,
                'name' => $product_name,
                'quantity' => $quantity,
                'price' => $product_price
            ];
        }
    } else {
        // Product not found
        return false;
    }
    return true;
}

// Function to remove from cart
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    return true;
}

// Function to update quantity in cart
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
  return true;
}

// Function to get cart total
function calculateCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}

// --- Example Usage (Product Listing -  Replace with your product data source) ---
$db = connectToDatabase();


// Dummy Product Data (Replace with your database query)
$products = [
    [ 'id' => 1, 'name' => 'Laptop', 'price' => 1200 ],
    [ 'id' => 2, 'name' => 'Mouse', 'price' => 25 ],
    [ 'id' => 3, 'name' => 'Keyboard', 'price' => 75 ]
];

// Function to display product listing (for demonstration)
function displayProductList($products) {
    echo "<h2>Available Products</h2>";
    echo "<ul>";
    foreach ($products as $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . "</li>";
    }
    echo "</ul>";
}

// Display product list
displayProductList($products);

// --- Cart Display and Form ---
echo "<h2>Shopping Cart</h2>";

// Display Cart Items
echo "<ul>";
$cart_total = calculateCartTotal();
foreach ($_SESSION['cart'] as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['quantity'] * $item['price'] . "</li>";
}
echo "</ul>";
echo "<p><strong>Total: $" . $cart_total . "</strong></p>";

// Add to Cart Form
echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
echo "<h3>Add to Cart</h3>";
displayProductList($products); // Display the same product list

echo "<ul>";
foreach ($products as $product) {
    echo "<li>";
    echo "<label for='product_" . $product['id'] . "'>" . $product['name'] . ":</label>";
    echo "<input type='number' id='product_" . $product['id'] . "' name='quantity_" . $product['id'] . "' value='1' min='1'>";
    echo "<button type='submit' name='add_" . $product['id'] . "'><img src='add_to_cart.png' width='20'></button>";
    echo "</li>";
}
echo "</ul>";

echo "</form>";


// Handle Add to Cart Submission
if (isset($_POST['add_'])) {
    $product_id = $_POST['add_'];
    $quantity = 1; // Default quantity
    if (isset($_POST['quantity_' . $product_id])) {
        $quantity = intval($_POST['quantity_' . $product_id]); // Ensure it's an integer
    }

    if (addToCart($product_id, $quantity)) {
        echo "<p>Product added to cart.</p>";
    } else {
        echo "<p>Error adding product to cart.</p>";
    }
}

// Handle Remove from Cart (Example - Implement Remove buttons)
// This is just a placeholder. In a real application, you'd have a button to remove.

// Handle Update Quantity
if (isset($_POST['update_'])) {
    $product_id = $_POST['update_'];
    $quantity = intval($_POST['quantity_' . $product_id]);  // Ensure integer
    updateQuantity($product_id, $quantity);
    echo "<p>Quantity updated.</p>";
}


?>
