    </select>
    <br><br>
    <input type="submit" name="remove_from_cart" value="Remove from Cart">
</form>


<?php
session_start();

// --- Cart Logic ---

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity,
    ];
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}


// --- Example Usage (Simulating Cart Interaction) ---

// 1. Add an item to the cart
addToCart(1, "Shirt", 20.00, 2); // product_id, name, price, quantity
addToCart(2, "Pants", 35.00, 1);

// 2. Update the quantity of a product
updateQuantity(1, 3); // Increase quantity of Shirt to 3

// 3. Remove an item from the cart
//removeFromCart(2); // Remove Pants

// --- Display the Cart ---

// Function to display the cart contents
function displayCart() {
  echo "<h2>Your Shopping Cart</h2>";
  echo "<table border='1'>";
  echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";

  $total = 0;

  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    $name = $product_details['name'];
    $price = $product_details['price'];
    $quantity = $product_details['quantity'];
    $total_item = $price * $quantity;

    echo "<tr>";
    echo "<td>" . $name . "</td>";
    echo "<td>$" . number_format($price, 2) . "</td>";
    echo "<td>" . $quantity . "</td>";
    echo "<td>$" . number_format($total_item, 2) . "</td>";
    echo "</tr>";

    $total += $total_item;
  }

  echo "</table>";
  echo "<p><strong>Total: $" . number_format($total, 2) . "</p>";
}

// --- HTML for Displaying the Cart ---

?>
