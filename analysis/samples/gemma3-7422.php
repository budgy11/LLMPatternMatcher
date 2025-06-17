        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" required><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <input type="submit" value="Place Order">
    </form>

</body>
</html>


<?php

// Configuration (Modify these for your application)
$products = [
  1 => ['name' => 'Laptop', 'price' => 1200.00],
  2 => ['name' => 'Mouse', 'price' => 25.00],
  3 => ['name' => 'Keyboard', 'price' => 75.00],
  4 => ['name' => 'Monitor', 'price' => 300.00]
];

$cart = []; // Initialize an empty cart
$cart_items = []; // Initialize an empty cart items array

// Function to add an item to the cart
function addToCart($product_id, $quantity = 1)
{
  if (isset($products[$product_id])) {
    $product = $products[$product_id];
    $item = [
      'product_id' => $product_id,
      'name' => $product['name'],
      'price' => $product['price'],
      'quantity' => $quantity
    ];

    // Check if the item already exists in the cart
    foreach ($cart_items as $key => $cartItem) {
      if ($cartItem['product_id'] == $cartItem['product_id']) {
        // Update quantity if item exists
        $cart_items[$key]['quantity'] += $quantity;
        return;
      }
    }

    // Add new item to the cart
    $cart_items[] = $item;
    return;
  } else {
    echo "Product ID $product_id not found.";
  }
}

// Function to view the cart
function viewCart()
{
  echo "<h2>Shopping Cart</h2>";
  if (empty($cart_items)) {
    echo "<p>Your cart is empty.</p>";
  } else {
    echo "<ul>";
    foreach ($cart_items as $key => $item) {
      echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";

    // Calculate total price
    $total_price = 0;
    foreach ($cart_items as $item) {
      $total_price += $item['price'] * $item['quantity'];
    }
    echo "<p><strong>Total: $" . number_format($total_price, 2) . "</strong></p>";
  }
}

// Function to remove an item from the cart
function removeItem($product_id)
{
    if (isset($cart_items[$product_id])) {
        unset($cart_items[$product_id]);
    } else {
        echo "Product ID $product_id not found in the cart.";
    }
}

// ---  Example Usage / Handling Request (Simulating a form submission) ---

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Add to cart
  if (isset($_POST["action"]) && $_POST["action"] == "add_to_cart") {
    $product_id = $_POST["product_id"];
    $quantity = isset($_POST["quantity"]) ? (int)$_POST["quantity"] : 1;
    addToCart($product_id, $quantity);
  }

  // Remove from cart
  if (isset($_POST["action"]) && $_POST["action"] == "remove_item") {
    $product_id = $_POST["product_id"];
    removeItem($product_id);
  }

  // Redirect to view cart
  header("Location: cart.php"); //  Assuming you have a 'cart.php' file to display the cart
  exit(); // Important to stop further execution after redirection
}

?>
