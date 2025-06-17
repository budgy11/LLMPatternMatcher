
    <br><br>

    <button type="submit">Place Order</button>
  </form>

  <br><br>
  <a href="cart.php">View Cart</a>  <!-- Link to your cart page -->

</body>
</html>


function generate_order_id() {
  // This is a very basic example.  In a real application, you'd want a more robust
  // method to generate unique IDs, such as using a UUID (Universally Unique Identifier).
  $timestamp = time();
  return 'ORDER-' . $timestamp;
}


<?php

// --- Database Configuration ---
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_db_user";
$db_password = "your_db_password";

// --- Database Connection ---
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Helper Functions ---

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity The quantity to add.
 *
 * @return bool True on success, false on failure.
 */
function add_to_cart(int $product_id, int $quantity) {
  global $conn;

  // Check if product exists (basic validation - expand for more robust validation)
  $product_query = "SELECT id, name, price FROM products WHERE id = ?";
  $stmt = $conn->prepare($product_query);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $name = $row['name'];
    $price = $row['price'];
  } else {
    return false; // Product doesn't exist
  }

  // Get current cart contents (if any)
  $cart_query = "SELECT id, product_id, quantity FROM cart WHERE product_id = ?";
  $stmt = $conn->prepare($cart_query);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $cart_data = $result->fetch_assoc();

  // If the product is already in the cart, update the quantity
  if ($cart_data) {
    $quantity = $cart_data['quantity'] + $quantity;
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE product_id = ?");
    $stmt->bind_param("is", $quantity, $product_id);
    $stmt->execute();
  } else {
    // Otherwise, add the product to the cart
    $stmt = $conn->prepare("INSERT INTO cart (product_id, quantity) VALUES (?, ?)");
    $stmt->bind_param("is", $product_id, $quantity);
    $stmt->execute();
  }

  return true;
}


/**
 * Retrieves the contents of the cart.
 *
 * @return array An array of cart items, each with 'id', 'product_id', 'name', 'price', and 'quantity' keys.
 */
function get_cart() {
  $query = "SELECT c.id, c.product_id, p.name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id";
  $result = $conn->query($query);

  $cart_items = array();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $cart_items[] = $row;
    }
  }

  return $cart_items;
}

/**
 * Removes an item from the cart by product ID.
 *
 * @param int $product_id The ID of the product to remove.
 * @return bool True on success, false on failure.
 */
function remove_from_cart(int $product_id) {
  $query = "DELETE FROM cart WHERE product_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();

  return true;
}

/**
 * Calculates the total price of the cart.
 *
 * @return float The total price.
 */
function calculate_total() {
  $cart_items = get_cart();
  $total = 0;
  foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
  }
  return $total;
}

// --- Product Data (Example - replace with your product database) ---
$products = array(
    array("id" => 1, "name" => "Laptop", "price" => 1200),
    array("id" => 2, "name" => "Mouse", "price" => 25),
    array("id" => 3, "name" => "Keyboard", "price" => 75),
);


// --- Purchase Functionality ---

function purchase() {
  // 1. Get Cart Items
  $cart_items = get_cart();

  // 2. Calculate Total
  $total = calculate_total();

  // 3. Display Cart Summary
  echo "<h2>Cart Summary</h2>";
  echo "<ul>";
  foreach ($cart_items as $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
  }
  echo "</ul>";
  echo "<p><strong>Total: $" . $total . "</strong></p>";

  // 4.  (Simulated Payment - Replace with actual payment processing)
  echo "<p>Processing Payment... (Simulated)</p>";
  echo "<p>Thank you for your purchase!</p>";

  // 5. Clear the Cart (Optional - depending on your requirements)
  // remove_from_cart(1); // Remove all items
  // remove_from_cart(2);
}


// ---  Example Usage (for demonstration - you'll integrate this into your form) ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["add_to_cart"])) {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    add_to_cart($product_id, $quantity);
  } elseif (isset($_POST["remove_from_cart"])) {
    $product_id = $_POST["product_id"];
    remove_from_cart($product_id);
  } elseif (isset($_POST["purchase"])) {
    purchase();
  }
}

?>
