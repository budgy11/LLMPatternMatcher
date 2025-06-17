
        <button type="submit">Add to Cart</button>
    </form>
</body>
</html>


<?php

// Data storage (replace with a database in a real application)
$cart = [];

// Function to add an item to the cart
function addToCart($productId, $quantity, $productName, $productPrice) {
  global $cart;
  
  // Check if the product already exists in the cart
  if (isset($cart[$productId])) {
    $cart[$productId]['quantity'] += $quantity;
  } else {
    $cart[$productId] = [
      'quantity' => $quantity,
      'name' => $productName,
      'price' => $productPrice
    ];
  }
}

// Function to view the cart
function viewCart() {
  global $cart;

  if (empty($cart)) {
    echo "<p>Your cart is empty.</p>";
    return;
  }

  echo "<h2>Your Cart</h2>";
  echo "<ul>";
  foreach ($cart as $id => $item) {
    echo "<li>";
    echo "<strong>Product Name:</strong> " . $item['name'] . "<br>";
    echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
    echo "<strong>Price:</strong> $" . $item['price'] . " per item<br>";
    echo "<strong>Total for item:</strong> $" . $item['quantity'] * $item['price'] . "<br>";
    echo "</li>";
  }
  echo "</ul>";

  // Calculate total cost
  $totalCost = 0;
  foreach ($cart as $id => $item) {
      $totalCost += $item['quantity'] * $item['price'];
  }
  echo "<p><strong>Total Cost:</strong> $" . $totalCost . "</p>";
}

// Function to remove an item from the cart
function removeItemFromCart($productId) {
  global $cart;

  if (isset($cart[$productId])) {
    unset($cart[$productId]);
  } else {
    echo "<p>Product with ID " . $productId . " not found in the cart.</p>";
  }
}

// Function to update the quantity of an item
function updateQuantity($productId, $newQuantity) {
  global $cart;

  if (isset($cart[$productId])) {
    if ($newQuantity > 0) {
      $cart[$productId]['quantity'] = $newQuantity;
    } else {
      // Handle invalid quantity (e.g., set quantity to 0 or remove item)
      removeItemFromCart($productId); // Or $cart[$productId]['quantity'] = 0;
    }
  } else {
    echo "<p>Product with ID " . $productId . " not found in the cart.</p>";
  }
}


// --- Example Usage (Simulated product data) ---
$products = [
  1 => ['name' => 'T-Shirt', 'price' => 20],
  2 => ['name' => 'Jeans', 'price' => 50],
  3 => ['name' => 'Shoes', 'price' => 80],
];

// --- Simulated Purchase Request ---
//  Let's simulate a user adding items to the cart
addToCart(1, 2, $products[1]['name'], $products[1]['price']); // Add 2 T-Shirts
addToCart(2, 1, $products[2]['name'], $products[2]['price']); // Add 1 Jeans
addToCart(3, 1, $products[3]['name'], $products[3]['price']); // Add 1 Shoes

// View the cart
viewCart();

// Simulate removing an item
removeItemFromCart(1);

// View the cart again
viewCart();

// Simulate updating quantity
updateQuantity(2, 3); //Change quantity of Jeans to 3
viewCart();

//--------------------------------------------------------------------
//  THIS IS A VERY BASIC EXAMPLE.  IN A REAL APPLICATION, YOU WOULD:
// 1. Use a database to store product information and cart data.
// 2. Implement input validation to handle user input.
// 3.  Add error handling.
// 4.  Secure the application to prevent vulnerabilities.
// 5.  Consider using a session or cookie to maintain cart state.
//--------------------------------------------------------------------
?>
