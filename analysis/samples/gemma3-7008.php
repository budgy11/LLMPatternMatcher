    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Initialize an empty shopping cart
session_start();

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function add_to_cart($product_id, $product_name, $price, $quantity) {
  global $servername, $username, $password, $dbname;

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Add the item to the cart
  $_SESSION['cart'][] = [
    'product_id' => $product_id,
    'product_name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  ];

  // You might want to update the product quantity in the database here
  // For example:
  // $sql = "UPDATE products SET quantity = quantity - $quantity WHERE id = $product_id";
  // if ($conn->query($sql) === TRUE) {
  //   echo "Product quantity updated successfully";
  // } else {
  //   echo "Error updating product quantity: " . $conn->error;
  // }
}

// Function to display the shopping cart
function display_cart() {
  if (empty($_SESSION['cart'])) {
    echo "<p>Your shopping cart is empty.</p>";
    return;
  }

  echo "<h2>Shopping Cart</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $item['product_name'] . "<br>";
    echo "<strong>Price:</strong> $" . number_format($item['price'], 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
    echo "<form action='update_cart.php' method='post'>";
    echo "<input type='number' name='quantity' value='" . $item['quantity'] . "'><br>";
    echo "<input type='hidden' name='product_id' value='" . $item['product_id'] . "'>";
    echo "<button type='submit'>Update</button>";
    echo "</form>";
    echo "</li>";
  }
  echo "</ul>";
}


// Function to update the cart (this will be handled by a separate page)
function update_cart($product_id, $new_quantity) {
    global $servername, $username, $password, $dbname;

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the quantity in the database
    $sql = "UPDATE products SET quantity = '$new_quantity' WHERE id = $product_id";

    if ($conn->query($sql) === TRUE) {
        // Update the quantity in the session
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $_SESSION['cart'][$key]['quantity'] = $new_quantity;
                break;
            }
        }
        echo "<p>Cart updated successfully!</p>";
    } else {
        echo "<p>Error updating cart: " . $conn->error . "</p>";
    }
}


// Example Usage - Handling the purchase

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['submit'])) {
    // Process the order (e.g., update inventory, send confirmation email)
    echo "<h2>Order Summary</h2>";
    echo "<p>Total items in cart: " . count($_SESSION['cart']) . "</p>";
    echo "<p><strong>Total Price:</strong> $" . number_format(calculate_total(), 2) . "</p>";
    // Clear the cart after the order is placed
    $_SESSION['cart'] = [];
    echo "<p>Cart cleared successfully!</p>";
  }
}

// Function to calculate the total price of the cart
function calculate_total() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

?>
