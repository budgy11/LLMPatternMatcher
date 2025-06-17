

<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// --- Function to Add Items to Cart ---
function addToCart($product_id, $quantity) {
  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add the product to the cart
    $_SESSION['cart'][$product_id] = [
      'quantity' => $quantity,
      'price'    => 0 // You'll likely want to fetch this from a database
    ];
  }
}

// --- Function to Remove Items from Cart ---
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// --- Function to Update Quantity of an Item in the Cart ---
function updateQuantity($product_id, $new_quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
    }
}


// --- Example Usage (Handling Cart Actions) ---

// 1.  Adding to cart (Example: When a user clicks "Add to Cart")
if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity']; // Get the quantity from the form
  addToCart($product_id, $quantity);
}

// 2.  Removing from cart (Example: When a user clicks "Remove from Cart")
if (isset($_GET['remove_from_cart'])) {
  $product_id = $_GET['remove_from_cart'];
  removeFromCart($product_id);
}

// 3. Updating Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['new_quantity'];
    updateQuantity($product_id, $new_quantity);
}




// --- Displaying the Cart (For Demonstration Purposes) ---
echo "<h2>Your Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . ", Price: " . $item['price'] . "</li>";
  }
  echo "</ul>";
}


// ---  Example Form for Adding Items (Put this in your HTML) ---
// <form method="post" action="">
//   <label for="product_id">Product ID:</label>
//   <input type="number" id="product_id" name="product_id" value="">
//   <br>
//   <label for="quantity">Quantity:</label>
//   <input type="number" id="quantity" name="quantity" value="1">
//   <br>
//   <button type="submit" name="add_to_cart">Add to Cart</button>
// </form>

?>
