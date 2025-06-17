

<?php
session_start();

// Array to hold the cart items
$cart = array();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity) {
  global $cart;

  // Check if the item is already in the cart
  if (isset($cart[$product_id])) {
    // Item exists, increment the quantity
    $cart[$product_id]['quantity'] += $quantity;
  } else {
    // Item doesn't exist, create a new entry
    $cart[$product_id] = array(
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    );
  }
}

// Function to update the quantity of an existing item
function updateQuantity($product_id, $new_quantity) {
  global $cart;

  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] = $new_quantity;
  } else {
    // If the product doesn't exist, you might want to handle it,
    // such as adding it with the new quantity.  Alternatively, you could
    // return an error or do nothing.  This example adds it.
    addToCart($product_id, "Product - " . $product_id, 0, $new_quantity); // Use the add to cart function instead
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  global $cart;

  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Function to get the cart contents
function getCartContents() {
  return $cart;
}

// Function to clear the cart
function clearCart() {
  global $cart;
  $cart = array();
}

// --- Example Usage / Controller Part ---

// 1.  Add an item to the cart
$product_id = 1;
$product_name = "T-Shirt";
$price = 20.00;
$quantity = 2;

addToCart($product_id, $product_name, $price, $quantity);

// 2. Update the quantity of an item
updateQuantity($product_id, 5);

// 3.  Remove an item
//removeCartItem($product_id);

// 4.  Get cart contents
$cart_contents = getCartContents();
print_r($cart_contents);

// 5.  Clear the cart
//clearCart();
?>
