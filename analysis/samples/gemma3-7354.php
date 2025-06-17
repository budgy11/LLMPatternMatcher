    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// Database connection (replace with your actual credentials)
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

// **1. Display the Product List**

function displayProducts($conn) {
  $sql = "SELECT id, product_name, price, description, image FROM products";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<h2>Available Products</h2>";
    echo "<table border='1'><tr><th>Image</th><th>Product Name</th><th>Price</th><th>Description</th><th>Action</th></tr>";

    while($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td><img src='images/" . $row['image'] . "' alt='". $row['product_name'] . "' width='100'></td>"; // Adjust image path if needed
      echo "<td>" . $row["product_name"] . "</td>";
      echo "<td>" . $row["price"] . "</td>";
      echo "<td>" . $row["description"] . "</td>";
      echo "<td><button onclick='addToCart(" . $row['id'] . ", '" . $row['product_name'] . "', " . $row['price'] . ")'>Add to Cart</button></td>";
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No products found.</p>";
  }
}


// **2. Add to Cart Functionality**

function addToCart($productId, $productName, $price) {
  //  Implement your cart storage here.  For demonstration purposes,
  //  we'll use a simple session variable.  This is *not* suitable
  //  for a production environment.

  if (isset($_SESSION['cart'])) {
    // Check if the product already exists in the cart
    if (isset($_SESSION['cart'][$productId])) {
      // If it exists, increment the quantity
      $_SESSION['cart'][$productId]['quantity'] += 1;
    } else {
      // If it doesn't exist, add it to the cart
      $_SESSION['cart'][$productId] = array(
        'product_id' => $productId,
        'product_name' => $productName,
        'price' => $price,
        'quantity' => 1
      );
    }
  } else {
    // If the cart is empty, start with a new array
    $_SESSION['cart'] = array($productId => array(
      'product_id' => $productId,
      'product_name' => $productName,
      'price' => $price,
      'quantity' => 1
    ));
  }

  // Optional:  Display a message indicating the item was added
  echo "<p>Added '" . $productName . "' to cart.</p>";
}



// **3. Display Cart Contents**

function displayCart() {
  if (!isset($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Cart</h2>";
  echo "<table border='1'><tr><th>Image</th><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

  $total = 0;

  foreach ($_SESSION['cart'] as $productId => $item) {
    $productName = $item['product_name'];
    $price = $item['price'];
    $quantity = $item['quantity'];
    $total += $price * $quantity;

    echo "<tr>";
    echo "<td><img src='images/" . $productName . "' alt='" . $productName . "' width='100'></td>";
    echo "<td>" . $productName . "</td>";
    echo "<td>" . $price . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "<td>" . $price * $quantity . "</td>";
    echo "<td><button onclick='removeFromCart(" . $productId . ")'>Remove</button></td>";
    echo "</tr>";
  }

  echo "<tr><td colspan='6'><strong>Total: $" . $total . "</strong></td></tr>";
  echo "</table>";
}



// **4. Remove from Cart**

function removeFromCart($productId) {
  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }
  // Optionally, clear the cart if it's empty after removal
  if (empty($_SESSION['cart'])) {
    unset($_SESSION['cart']);
  }
  echo "<p>Removed '" . $_SESSION['cart']['product_name'] . "' from cart.</p>";
}

// **5. Initialize the session**
session_start();

// --- Main Script Execution ---

//Display products
displayProducts($conn);

// Display the cart
displayCart();

?>
