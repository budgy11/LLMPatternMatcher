

<?php
session_start();

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();
}

// ---  Add to Cart Function ---
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Add the product to the cart array
  $_SESSION['cart'][] = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );

  // Optional:  Refresh the cart array to make it easier to manage
  // and to ensure the latest updates are reflected in the session.
  //  This is generally a good practice.  If you don't do this, 
  //  you might get unexpected results if multiple users are 
  //  simultaneously accessing the same session.
  $_SESSION['cart'] = $_SESSION['cart']; 
}


// ---  Remove from Cart Function ---
function removeFromCart($product_id) {
  // Check if the product is in the cart
  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $product_id) {
        unset($_SESSION['cart'][$key]);
        // Remove from end of array to avoid shifting indices
        //  This is slightly more efficient than re-indexing
        //  and avoids the issue of re-indexing and potential lost data.
        if (!empty($_SESSION['cart'])) {
          $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
        return true;
      }
    }
    return false; // Product not found
  }
  return false; // Cart is empty
}


// ---  Update Quantity Function ---
function updateQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $product_id) {
        $item['quantity'] = $quantity;
        $_SESSION['cart'] = $_SESSION['cart']; // Update the session
        return true;
      }
    }
    return false; // Product not found
  }
  return false; // Cart is empty
}



// ---  View Cart Function ---
function viewCart() {
  if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
      echo "<li>" . $item['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . ($item['price'] * $item['quantity']) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total:</strong> $" . array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $_SESSION['cart'])) . "</p>";
  } else {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<p>Your cart is empty.</p>";
  }
}



// --- Example Usage (in your HTML, after the session start) ---
// <form method="post" action="">
//   <button type="button" onclick="addToCart(1, 'Product A', 20.00, 2)">Add to Cart</button>
//   <button type="button" onclick="addToCart(2, 'Product B', 30.00, 1)">Add to Cart</button>
// </form>

// <br>

// <form method="post" action="">
//  <button type="button" onclick="removeFromCart(1)">Remove from Cart</button>
// </form>

// <br>

// <form method="post" action="">
//   <label for="quantity">Quantity:</label>
//   <input type="number" id="quantity" name="quantity" value="1">
//   <button type="button" onclick="updateQuantity(1, parseInt(document.getElementById('quantity').value))">Update Quantity</button>
// </form>

// <br>

// <button type="button" onclick="viewCart()">View Cart</button>

?>
