

   // Example using prepared statements (simplified)
   $stmt = $conn->prepare("INSERT INTO orders (product_id, customer_name, customer_email, quantity, total_price) VALUES (?, ?, ?, ?, ?)");
   $stmt->bind_param("ssisd", $product_id, $customer_name, $customer_email, $quantity);
   $stmt->execute();
   // Don't forget to close the statement when you're done!
   $stmt->close();
   

* **Error Logging:**  In a production environment, instead of just displaying errors in the browser, log them to a file or a database for monitoring.

* **Form Handling:**  You'll need an HTML form to collect the purchase data.  Handle form submissions using `$_POST`.

* **Session Management:**  Use PHP sessions to store user information (if required) to maintain the purchase context.

* **Payment Integration:** Integrate with a payment gateway (e.g., PayPal, Stripe) to securely process payments. *Never* store credit card information directly on your server.

* **Database Transactions:**  For critical operations (like processing a payment), use database transactions to ensure that either all operations succeed or none do, maintaining data integrity.

* **HTML Form:** Add the appropriate HTML form to take user input.


<?php
session_start();

// Configuration
$cart_file = 'cart.json'; // File to store the cart data
$item_name_key = 'item_name';
$item_price_key = 'item_price';
$quantity_key = 'quantity';

// Function to load cart from JSON file
function loadCart() {
  if (file_exists($cart_file)) {
    $cart = json_decode(file_get_contents($cart_file), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      return array(); // Return an empty array in case of JSON error
    }
    return $cart;
  } else {
    return array(); // Return an empty array if the file doesn't exist
  }
}

// Function to save cart to JSON file
function saveCart($cart) {
  $json_data = json_encode($cart, JSON_PRETTY_PRINT); // Use JSON_PRETTY_PRINT for readability
  if ($json_data = file_put_contents($cart_file, $json_data)) {
    return $json_data;
  } else {
    return false;
  }
}


// ----------------------- Cart Management Functions -----------------------

// Add an item to the cart
function addToCart($item_name, $item_price, $quantity = 1) {
  $cart = loadCart();

  $item_name = trim($item_name); // Clean the item name
  $item_price = (float) $item_price; // Ensure price is a float
  $quantity = (int) $quantity; // Ensure quantity is an integer

  if ($quantity <= 0) {
    return false; // Invalid quantity
  }

  $item_name = $item_name;
  if (isset($cart[$item_name])) {
    $cart[$item_name]['quantity'] += $quantity;
  } else {
    $cart[$item_name] = [
      $item_name_key => $item_name,
      $item_price_key => $item_price,
      $quantity_key => $quantity
    ];
  }
  return saveCart($cart);
}

// Remove an item from the cart
function removeFromCart($item_name) {
  $cart = loadCart();
  $item_name = trim($item_name);

  if (isset($cart[$item_name])) {
    unset($cart[$item_name]);
    return saveCart($cart);
  } else {
    return false; // Item not found in cart
  }
}

// Update quantity of an item in the cart
function updateQuantity($item_name, $new_quantity) {
  $cart = loadCart();
  $item_name = trim($item_name);
  $new_quantity = (int) $new_quantity;

  if (isset($cart[$item_name])) {
    $cart[$item_name][$quantity_key] = $new_quantity;
    return saveCart($cart);
  } else {
    return false; // Item not found in cart
  }
}


// Get the cart contents
function getCartContents() {
  $cart = loadCart();
  return $cart;
}

// -----------------------  Cart Display Functions -----------------------

// Display the cart contents
function displayCart() {
  $cart = getCartContents();

  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Shopping Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Action</th></tr>";

  foreach ($cart as $item_name => $details) {
    $price = $details[$item_price_key];
    $quantity = $details[$quantity_key];
    $total = $price * $quantity;

    echo "<tr>";
    echo "<td>" . $details[$item_name_key] . "</td>";
    echo "<td>$" . number_format($price, 2) . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "<td>$" . number_format($total, 2) . "</td>";
    echo "<td><a href='cart.php?action=remove&item=$item_name'>Remove</a></td>";
    echo "</tr>";
  }

  echo "</table>";
  echo "<p><a href='checkout.php'>Checkout</a></p>";
}


// -----------------------  Example Usage (for demonstration only - use within a PHP page) -----------------------

// Example usage for adding items.  This is just to show how you'd call the functions
/*
if (isset($_POST['add_to_cart'])) {
  $item_name = $_POST['item_name'];
  $item_price = (float)$_POST['item_price'];
  $quantity = (int)$_POST['quantity'];

  addToCart($item_name, $item_price, $quantity);
  echo "<p>Item added to cart!</p>";
}
*/

?>
