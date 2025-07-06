<?php
// db_connect.php

$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "ecommerce_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
// products.php

require_once 'db_connect.php';

// SQL query
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

// Output data of each row
if ($result->num_rows > 0) {
    echo "<table>
          <thead>
            <tr>
              <th>Image</th>
              <th>Name</th>
              <th>Description</th>
              <th>Price</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td><img src='" . $row['image_url'] . "' width='100'></td>
                <td>" . $row['product_name'] . "</td>
                <td>" . $row['description'] . "</td>
                <td>$" . $row['price'] . "</td>
                <td><a href='product.php?id=" . $row['id'] . "'>View Details</a></td>
              </tr>";
    }

    echo "</tbody>
        </table>";

} else {
    echo "No products found.";
}

$conn->close();
?>
<?php
// product.php

require_once 'db_connect.php';

// Get product ID from URL
$id = $_GET['id'];

// SQL query
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

if ($result->num_rows == 0) {
    echo "Product not found.";
} else {
    $product = $result->fetch_assoc();

    echo "<h2>" . $product['product_name'] . "</h2>";
    echo "<img src='" . $product['image_url'] . "' width='200'><br><br>";
    echo "<p>" . $product['description'] . "</p>";
    echo "<p>Price: $" . $product['price'] . "</p>";

    // Add to cart functionality (simplified)
    if (isset($_POST['add_to_cart'])) {
        add_to_cart($product); // Call the function (defined in purchase.php)
    }
}

$conn->close();
?>
<?php
// cart.php

require_once 'db_connect.php';

// Initialize cart (if empty)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    add_to_cart($product_id, $quantity);
}

// Remove item from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
}

// Update quantity in cart
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    update_quantity($product_id, $new_quantity);
}

// Display cart contents
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product = get_product_by_id($product_id); // Function to retrieve product details

        echo "<li>";
        echo "<h3>" . $product['product_name'] . "</h3>";
        echo "<p>Quantity: " . $quantity . "</p>";
        echo "<p>Price: $" . $product['price'] . "</p>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
        echo "<input type='hidden' name='quantity' value='" . $quantity . "'>";
        echo "<input type='submit' value='Remove from Cart'>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";
}

$conn->close();
?>
<?php
// purchase.php

require_once 'db_connect.php';
session_start(); // Start session management

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<h2>Your cart is empty.  Please add items before proceeding.</h2>";
    exit; // Stop execution
}

// Get cart items
$cart_items = $_SESSION['cart'];

// Calculate total
$total = 0;
foreach ($cart_items as $product_id => $quantity) {
    $product = get_product_by_id($product_id); // Retrieve product details
    $total += $product['price'] * $quantity;
}

// Simulate order processing (replace with actual order handling)
echo "<h2>Order Confirmation</h2>";
echo "<p>Thank you for your order!</p>";
echo "<p>Items Ordered:</p>";
foreach ($cart_items as $product_id => $quantity) {
    $product = get_product_by_id($product_id);
    echo "<p>" . $product['product_name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</p>";
}
echo "<p>Total: $" . $total . "</p>";

// Clear the cart after successful order
$_SESSION['cart'] = array();

echo "<a href='index.php'>Continue Shopping</a>";

$conn->close();
?>
