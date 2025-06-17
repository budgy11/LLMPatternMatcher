

<?php

session_start(); // Start the session

// ------------------ Cart Functions ------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @param int $quantity  The quantity of the product to add.
 * @return void
 */
function addToCart($product_id, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the product is already in the cart
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}


/**
 * Removes a specific item from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return void
 */
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


/**
 * Updates the quantity of an item in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity for the product.
 * @return void
 */
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = $quantity;
  }
}

/**
 * Gets the items in the cart.
 *
 * @return array An array representing the cart items.
 */
function getCartItems() {
  return $_SESSION['cart'];
}

/**
 * Returns the total number of items in the cart
 * @return int
 */
function cartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $product_id => $quantity) {
            $total += $quantity;
        }
    }
    return $total;
}

// ------------------ Example Usage (Demonstration) ------------------

// Add a product to the cart
addToCart(123);  // Add product with ID 123

// Add a second item to the cart
addToCart(456, 3); // Add product with ID 456 and quantity 3

// Update the quantity of the first item
updateCartQuantity(123, 5);

// Remove the second item
removeFromCart(456);

// Get the items in the cart
$cart = getCartItems();
print_r($cart); // Output: Array ( [123] => 5 )

echo "Cart Total: " . cartTotal();

// ------------------  Example HTML (for adding products to cart) ------------------

//  This is a simplified example.  In a real application, you'd likely
//  get product information (name, price, image, etc.) from a database.

// <form method="post" action="">
//   <input type="hidden" name="product_id" value="123">
//   <input type="number" name="quantity" value="1" min="1" max="10">
//   <input type="submit" value="Add to Cart">
// </form>

//  You'd need to handle the form submission to call the addToCart() function.
//  For example:

//  if ($_SERVER["REQUEST_METHOD"] == "POST") {
//      $product_id = $_POST['product_id'];
//      $quantity = $_POST['quantity'];
//      addToCart($product_id, $quantity);
//  }

?>
