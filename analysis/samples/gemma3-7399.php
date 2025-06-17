        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>

        <input type="submit" value="Place Order">
    </form>

</body>
</html>


<?php
session_start();

// Configuration
$items = []; // Array to store shopping cart items
$database_file = "cart_data.txt"; // File to store cart data (for simplicity - use a database in a real application)

// --- Helper Functions ---

// Add an item to the cart
function addToCart($product_id, $quantity) {
  global $items;

  // Check if the item is already in the cart
  foreach ($items as $key => $item) {
    if ($item['product_id'] == $product_id) {
      $items[$key]['quantity'] += $quantity;
      return;
    }
  }

  // If item not in cart, add it
  $items[] = ['product_id' => $product_id, 'quantity' => $quantity];
}

// Calculate the total price
function calculateTotal() {
  $total = 0;
  foreach ($items as $item) {
    // Assume you have a function to get product price by ID
    $price = getProductPrice($item['product_id']);
    $total_for_item = $price * $item['quantity'];
    $total += $total_for_item;
  }
  return $total;
}

// Save the cart to a file (for persistence)
function saveCartToFile() {
  file_put_contents($database_file, serialize($items));
}

// Load the cart from a file
function loadCartFromFile() {
  global $items;
  if (file_exists($database_file)) {
    $cartData = file_get_contents($database_file);
    if ($cartData = @unserialize($cartData)) { //Use @ to suppress errors
        $items = $cartData;
    }
  }
}

// --- Mock Product Price Function (Replace with your actual database query) ---
function getProductPrice($product_id) {
  // This is a mock function. In a real application, you'd query your database.
  // For demonstration purposes, it returns a hardcoded price.
  $product_prices = [
    1 => 10.00,
    2 => 20.00,
    3 => 15.00
  ];
  return $product_prices[$product_id] ?? 0; // Return 0 if product_id not found
}


// --- Cart Handling Functions ---

// Add to cart
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  addToCart($product_id, $quantity);
  saveCartToFile();
  header("Location: cart.php"); // Redirect to cart.php
  exit();
}

// Remove item from cart
if (isset($_GET['remove_from_cart'])) {
  $product_id = $_GET['remove_from_cart'];
  removeItemFromCart($product_id);
  saveCartToFile();
  header("Location: cart.php"); // Redirect to cart.php
  exit();
}

// Remove Item function (helper function for remove from cart)
function removeItemFromCart($product_id) {
  global $items;
  foreach ($items as $key => $item) {
    if ($item['product_id'] == $product_id) {
      unset($items[$key]);
      return;
    }
  }
}

// --- Display Cart (cart.php) ---

// Load cart data on page load
loadCartFromFile();

// Calculate total
$total = calculateTotal();

?>
