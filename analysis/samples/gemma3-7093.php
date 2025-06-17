    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1"><br><br>

    <button type="submit">Add to Cart</button>
</form>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "root";
$db_pass = "";

// Function to connect to the database
function connectToDatabase() {
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}

// Function to add item to cart
function addToCart($product_id, $quantity) {
  $conn = connectToDatabase();

  // Check if the product exists
  $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $product_name = $row["name"];
    $product_price = $row["price"];

    // Check if the product is already in the cart
    $cart_key = "cart_" . session_id();

    if (isset($_SESSION[$cart_key][$product_id])) {
      $_SESSION[$cart_key][$product_id]["quantity"] += $quantity;
    } else {
      $_SESSION[$cart_key][$product_id] = [
        "name" => $product_name,
        "price" => $product_price,
        "quantity" => $quantity,
      ];
    }
  } else {
    // Product not found
    echo "Product with ID " . $product_id . " not found.";
  }

  $stmt->close();
}

// Function to get cart contents
function getCartContents() {
  $cart_key = "cart_" . session_id();

  if (isset($_SESSION[$cart_key])) {
    return $_SESSION[$cart_key];
  } else {
    return []; // Return an empty array if the cart is empty
  }
}

// Function to remove item from cart
function removeItemFromCart($product_id) {
    $cart_key = "cart_" . session_id();

    if (isset($_SESSION[$cart_key][$product_id])) {
        unset($_SESSION[$cart_key][$product_id]);
    }
}


// ---  Handlers for adding to cart and handling the cart  ---

// Check for POST requests to add to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_to_cart"])) {
  $product_id = $_POST["product_id"];
  $quantity = $_POST["quantity"];
  addToCart($product_id, $quantity);
}

// Check for POST requests to remove item from cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_item"])) {
    $product_id = $_POST["product_id"];
    removeItemFromCart($product_id);
}

// Display the cart contents
$cart_contents = getCartContents();

$total_price = 0;
if (!empty($cart_contents)) {
  foreach ($cart_contents as $item) {
    $total_price += $item["price"] * $item["quantity"];
  }
}
?>
