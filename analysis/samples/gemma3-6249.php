

// Replace this with your database query to get the product price
$product_price = get_product_price($product_id); // Assuming you have a function to fetch the price


<?php

session_start(); // Start the session

// This is a very basic cart implementation.  For production, you'll
// want to use a database and handle quantities, images, etc. properly.

// -------------------------------------------------------------------
// Utility Functions (for example)
// -------------------------------------------------------------------

/**
 * Adds an item to the cart.
 *
 * @param int $product_id  The ID of the product.
 * @param int $quantity    The quantity to add.
 */
function addToCart($product_id, $quantity) {
    if (empty($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Initialize the cart array
    }

    $_SESSION['cart'][] = $product_id; // Add the product ID to the cart
    $_SESSION['quantity'][$product_id] = $quantity; // Store the quantity
}


// -------------------------------------------------------------------
// Example Usage - Simulate User Interactions
// -------------------------------------------------------------------

// Simulate user adding products to the cart:
addToCart(1, 2); // User adds product with ID 1, quantity 2
addToCart(2, 1); // User adds product with ID 2, quantity 1
addToCart(1, 3); // User adds product with ID 1, quantity 3

// -------------------------------------------------------------------
// Display the Cart
// -------------------------------------------------------------------

echo "<h2>Your Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    // Loop through the cart and display the items
    foreach ($_SESSION['cart'] as $product_id) {
        // Simulate fetching product details (replace with actual database query)
        $product_name = "Product " . $product_id;
        $product_price = rand(10, 50); // Random price for demonstration

        echo "<li>$product_name - $" . $product_price . " (Quantity: " . $_SESSION['quantity'][$product_id] . ")</li>";
    }
    echo "</ul>";
}


// -------------------------------------------------------------------
// Example:  Simulating Removing an Item (Advanced - requires a method to identify the item to remove)
// -------------------------------------------------------------------
/*
//  This requires a way to identify the product to remove (e.g., a hidden field)
//  For simplicity, this example assumes you know the product_id
//  For production, you'd use a more robust mechanism.

$product_id_to_remove = 1; // Example: User selected to remove product 1

if (isset($_POST['remove_item'])) {
    if (isset($_SESSION['cart']) && isset($_SESSION['quantity'][$product_id_to_remove])) {
        unset($_SESSION['cart'][$product_id_to_remove]);
        unset($_SESSION['quantity'][$product_id_to_remove]);
    }
}

//  After the removal code, you would likely need to refresh the cart display.

// -------------------------------------------------------------------
// Important Considerations and Next Steps:

// 1. Database Integration:  This is a *very* basic example. In a real application,
//    you would store product details (name, price, image, etc.) in a database.
//    You would then use SQL queries to fetch product information based on the
//    product ID.

// 2. Quantity Handling:  This example simply adds the product to the cart.
//    You'll need to allow users to update the quantity of items in their cart.
//    This usually involves adding a quantity field to the cart display and
//    handling form submissions to update the quantity in the session.

// 3.  Session Management:  Make sure your session is properly set up and
//     handled.  Set the session cookie with the appropriate flags (e.g., `session.cookie_secure = true` if you are using HTTPS).

// 4.  Error Handling:  Add error handling for situations like invalid product IDs,
//    empty carts, etc.

// 5.  User Interface (UI):  You'll need to design and implement a user-friendly
//     interface for adding items to the cart, viewing the cart, and
//     updating quantities.

// 6.  Security:  Always sanitize and validate user input to prevent
//     security vulnerabilities (e.g., SQL injection).  Use prepared statements
//     when interacting with the database.

// 7.  Data Storage: For larger carts or multiple users, consider using
//     a caching system (like Redis or Memcached) to improve performance.
// -------------------------------------------------------------------


<?php

session_start();

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
  }

  $_SESSION['cart'][$product_id] = array(
    'name' => $product_name,
    'price' => $price,
    'quantity' => $quantity
  );
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

// Function to get the cart contents
function getCartContents() {
  return $_SESSION['cart'];
}


// Example Usage:  (Simulate user actions)

// 1. Add an item to the cart
addToCart(1, 'Laptop', 1200, 1);
addToCart(2, 'Mouse', 25, 2);


// 2.  Update the quantity of a product
updateCartQuantity(2, 5); // Now user has 5 mice in the cart

// 3. Remove an item from the cart
// removeCartItem(1); // Uncomment to remove the laptop.

// 4. Display the cart contents
$cart = getCartContents();
echo "<h2>Your Cart</h2>";

if (empty($cart)) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($cart as $product_id => $product_data) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_data['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_data['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_data['quantity'] . "<br>";
    echo "<strong>Total Price for this item:</strong> $" . ($product_data['price'] * $product_data['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

?>
