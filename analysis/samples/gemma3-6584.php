    </select><br><br>

    <label for="new_quantity">New Quantity:</label>
    <input type="number" id="new_quantity" name="quantity" value="1"><br><br>

    <input type="submit" name="update_quantity" value="Update Quantity">
</form>


<?php
session_start();

// This is the shopping cart array.  You'll want to replace this with
// a database or more robust storage solution in a real application.
$cart = [];

// --- Helper Functions ---

// Function to add an item to the cart
function addItemToCart($productId, $productName, $price, $quantity = 1) {
  if (!isset($cart[$productId])) {
    $cart[$productId] = [
      'name' => $productName,
      'price' => $price,
      'quantity' => $quantity
    ];
  } else {
    $cart[$productId]['quantity'] += $quantity;
  }
}

// Function to remove an item from the cart by product ID
function removeItemFromCart($productId) {
  if (isset($cart[$productId])) {
    unset($cart[$productId]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $newQuantity) {
    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] = $newQuantity;
    }
}


// ---  Example Usage / Cart Interaction (Illustrative) ---

// 1. Add an item to the cart
addItemToCart(1, "Awesome T-Shirt", 20.00, 2);
addItemToCart(2, "Cool Hat", 15.00, 1);


// 2. Display the Cart Contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $productId => $item) {
    echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
  }
  echo "</ul>";

  // Calculate the total price
  $totalPrice = 0;
  foreach ($cart as $productId => $item) {
    $totalPrice += $item['price'] * $item['quantity'];
  }
  echo "<p><strong>Total: $" . $totalPrice . "</strong></p>";
}

// ---  Example: Removing an item from the cart ---
// Remove the "Cool Hat" (productId 2)
// removeItemFromCart(2);

// ---  Example: Updating the quantity of an item ---
//  Update the quantity of "Awesome T-Shirt" (productId 1) to 3
//  updateQuantity(1, 3);

// --- End of Example Usage ---
?>
