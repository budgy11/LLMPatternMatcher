

<?php

// Start the session if it's not already started
session_start();

// **Helper Functions (for clarity and organization)**

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  // Check if the cart exists in the session
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart as an empty array
  }

  // Add the item to the cart
  if (isset($_SESSION['cart'][$product_id])) {
    // Item already exists in the cart - increment quantity
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  } else {
    // Item not in cart - add a new entry
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}


// **Example Usage (Simulating a Product)**
// In a real application, you'd fetch product information from a database.
$products = [
  1 => ['name' => 'Laptop', 'price' => 1200],
  2 => ['name' => 'Mouse', 'price' => 25],
  3 => ['name' => 'Keyboard', 'price' => 75]
];

// **Handling Cart Actions (Example)**

// 1. Add to Cart
if (isset($_POST['add_to_cart'])) {
  $product_id = (int)$_POST['product_id']; // Cast to integer for safety
  $quantity = (int)$_POST['quantity'];

  if (isset($products[$product_id])) {
    addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
  } else {
    echo "Product ID $product_id not found.";
  }
}

// 2. Update Quantity (Example)
if (isset($_POST['update_quantity'])) {
  $product_id = (int)$_POST['product_id'];
  $new_quantity = (int)$_POST['quantity'];
  updateCartQuantity($product_id, $new_quantity);
}

// 3. Remove Item (Example)
if (isset($_POST['remove_from_cart'])) {
  $product_id = (int)$_POST['product_id'];
  removeCartItem($product_id);
}



// **Displaying the Cart (Example)**

if (isset($_SESSION['cart'])) {
  echo "<h2>Your Shopping Cart</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>" . $product_details['name'] . "</strong> - $" . $product_details['price'] . " x " . $product_details['quantity'] . "<button onclick=\"removeCartItem(" . $product_id . ")\" style=\"margin-right: 10px;\">Remove</button>";
    echo "</li>";
  }
  echo "</ul>";
} else {
  echo "<p>Your cart is empty.</p>";
}


?>
