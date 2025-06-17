

<?php
session_start();

// --- Session Variables ---
$cart = []; // Initialize an empty cart array

// Helper function to add an item to the cart
function addToCart($item_id, $item_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  // Check if the item already exists in the cart
  if (isset($_SESSION['cart'][$item_id])) {
    // If it exists, increment the quantity
    $_SESSION['cart'][$item_id]['quantity'] += $quantity;
  } else {
    // If it doesn't exist, add it to the cart
    $_SESSION['cart'][$item_id] = [
      'name' => $item_name,
      'price' => $price,
      'quantity' => $quantity
    ];
  }
}

// Helper function to remove an item from the cart
function removeCartItem($item_id) {
    if (isset($_SESSION['cart'][$item_id])) {
        unset($_SESSION['cart'][$item_id]);
    }
}

// Helper function to update quantity of an item in the cart
function updateQuantity($item_id, $quantity) {
    if (isset($_SESSION['cart'][$item_id])) {
        $_SESSION['cart'][$item_id]['quantity'] = $quantity;
    }
}

// Helper function to get the cart contents
function getCartContents() {
    return $_SESSION['cart'];
}

// Helper function to clear the cart
function clearCart() {
    unset($_SESSION['cart']);
}

// --- Example Usage (Simulating a Product Add to Cart Process) ---

// If a product was added (e.g., from a form submission)
if (isset($_POST['add_to_cart'])) {
  $item_id = $_POST['item_id'];
  $item_name = $_POST['item_name'];
  $price = floatval($_POST['price']); // Ensure price is a float
  $quantity = intval($_POST['quantity']); // Ensure quantity is an integer

  addToCart($item_id, $item_name, $price, $quantity);
  echo "<p>Item added to cart.</p>";
}

// If a product was removed
if (isset($_POST['remove_from_cart'])) {
  $item_id = $_POST['item_id'];
  removeCartItem($item_id);
  echo "<p>Item removed from cart.</p>";
}

// If a product quantity was updated
if (isset($_POST['update_quantity'])) {
  $item_id = $_POST['item_id'];
  $quantity = intval($_POST['quantity']);
  updateQuantity($item_id, $quantity);
  echo "<p>Quantity updated in cart.</p>";
}

// If the cart was cleared
if (isset($_POST['clear_cart'])) {
  clearCart();
  echo "<p>Cart cleared.</p>";
}


// --- Display the Cart Contents ---
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
  echo "<h2>Your Cart:</h2>";
  echo "<ul>";
  foreach ($_SESSION['cart'] as $item_id => $item_data) {
    echo "<li>";
    echo "<strong>Name:</strong> " . $item_data['name'] . "<br>";
    echo "<strong>Price:</strong> $" . number_format($item_data['price'], 2) . "<br>";
    echo "<strong>Quantity:</strong> " . $item_data['quantity'] . "<br>";
    echo "<strong>Total Item Cost:</strong> $" . number_format($item_data['price'] * $item_data['quantity'], 2) . "<br>";

    // Add a remove link for each item
    echo "<a href='?action=remove&item_id=" . $item_id . "'>Remove</a><br>";
    echo "</li>";
  }
  echo "</ul>";
  echo "<a href='?action=clear_cart'>Clear Cart</a>"; // Link to clear cart
} else {
  echo "<p>Your cart is empty.</p>";
}


?>
