

<?php
session_start();

// Define session variables for the cart
$_SESSION['cart'] = array(); // Initialize the cart as an empty array
$_SESSION['cart_total'] = 0;
$_SESSION['cart_count'] = 0;

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  global $session; // Access the session variables

  // Check if the item is already in the cart
  $item_found = false;
  foreach ($session['cart'] as $key => $cart_item) {
    if ($cart_item['product_id'] == $product_id) {
      // Increment the quantity if the item is found
      $session['cart'][$key]['quantity'] += $quantity;
      $session['cart_total'] += $quantity * $price;
      $session['cart_count'] += $quantity;
      $item_found = true;
      break;
    }
  }

  // If the item is not in the cart, add it as a new entry
  if (!$item_found) {
    $session['cart'][] = array(
      'product_id' => $product_id,
      'product_name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
    $session['cart_total'] += $quantity * $price;
    $session['cart_count'] += $quantity;
  }
}


// Function to remove an item from the cart
function removeCartItem($product_id) {
  global $session;

  // Iterate through the cart array and remove the item
  foreach ($session['cart'] as $key => $cart_item) {
    if ($cart_item['product_id'] == $product_id) {
      unset($session['cart'][$key]);
      $session['cart_total'] -= $cart_item['price'] * $cart_item['quantity'];
      $session['cart_count'] -= $cart_item['quantity'];
      break;
    }
  }
}


// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    global $session;

    // Find the item in the cart
    foreach ($session['cart'] as $key => $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            $session['cart'][$key]['quantity'] = $quantity;
            $session['cart_total'] -= $cart_item['price'] * $cart_item['quantity'];
            $session['cart_total'] += $quantity * $price;
            break;
        }
    }
}


// Example Usage (Simulating adding to cart)
// You would typically get this data from a database or other source

// Add a product to the cart
addToCart(1, "Laptop", 1200, 1);
addToCart(2, "Mouse", 25, 2);
addToCart(1, "Laptop", 1200, 1); // Add another laptop


// Display the cart contents
echo "<h2>Shopping Cart</h2>";
if (empty($session['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($session['cart'] as $key => $cart_item) {
        echo "<li>";
        echo "Product: " . $cart_item['product_name'] . "<br>";
        echo "Price: $" . $cart_item['price'] . "<br>";
        echo "Quantity: " . $cart_item['quantity'] . "<br>";
        echo "Total for this item: $" . ($cart_item['price'] * $cart_item['quantity']) . "<br>";
        echo "</li>";
    }
    echo "</ul>";
    echo "<p>Total Cart Value: $" . $session['cart_total'] . "</p>";
    echo "<p>Cart Count: " . $session['cart_count'] . "</p>";
}

// Example: Remove an item
// removeCartItem(2);

// Example: Update quantity
// updateCartQuantity(1, 3);  // Update laptop quantity to 3
?>
