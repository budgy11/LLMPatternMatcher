    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required>
    <button type="submit">Add to Cart</button>
</form>


<?php
session_start();

// Configuration
$items = array(); // Array to store items in the cart
$cart_key = 'shopping_cart'; // Key to store the cart in session

// Helper Functions

// Function to add an item to the cart
function addItemToCart($product_id, $product_name, $price, $quantity) {
  global $items, $cart_key;

  $item = array(
    'id' => $product_id,
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );

  // Check if the item is already in the cart
  foreach ($items as $key => $existing_item) {
    if ($existing_item['id'] == $item['id']) {
      // Update the existing item's quantity
      $items[$key]['quantity'] += $quantity;
      return;
    }
  }

  // If item not found, add it to the cart
  $items[] = $item;
}


// Function to get the cart items
function getCartItems() {
  global $items, $cart_key;
  return $items;
}

// Function to update the quantity of an item
function updateCartItemQuantity($product_id, $quantity) {
  global $items, $cart_key;

  // Find the item in the cart
  foreach ($items as $key => $item) {
    if ($item['id'] == $product_id) {
      $items[$key]['quantity'] = $quantity;
      return true;
    }
  }
  return false; // Item not found
}


// Function to remove an item from the cart
function removeItemFromCart($product_id) {
  global $items, $cart_key;

  // Iterate through the items and remove the item with the matching product_id
  for ($i = 0; $i < count($items); $i++) {
    if ($items[$i]['id'] == $product_id) {
      unset($items[$i]);
      // Re-index the array to avoid gaps
      $items = array_values($items);
      return true;
    }
  }
  return false;
}

// Function to calculate the cart total
function calculateCartTotal() {
    global $items;
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// ----  Handle incoming requests  ----

// Check if the request is adding an item to the cart
if (isset($_POST['action']) && $_POST['action'] == 'add_to_cart') {
  $product_id = $_POST['product_id'];
  $product_name = $_POST['product_name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  addItemToCart($product_id, $product_name, $price, $quantity);
  // Redirect to the cart page
  header("Location: cart.php");
  exit();
}


// Check if the request is updating the quantity of an item
if (isset($_POST['action']) && $_POST['action'] == 'update_quantity') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    updateCartItemQuantity($product_id, $quantity);
    header("Location: cart.php");
    exit();
}

// Check if the request is removing an item from the cart
if (isset($_POST['action']) && $_POST['action'] == 'remove_item') {
    $product_id = $_POST['product_id'];
    removeItemFromCart($product_id);
    header("Location: cart.php");
    exit();
}



// ----  Display the cart contents  ----

// Get the cart items
$cart_items = getCartItems();

// Calculate the total
$total = calculateCartTotal();

?>
