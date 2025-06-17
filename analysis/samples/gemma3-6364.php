

<?php

session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = [
      'name' => $product_name,
      'price' => $price,
      'quantity' => $quantity,
    ];
  } else {
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
  }
}

// Function to remove an item from the cart
function removeFromCart($product_id) {
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantity($product_id, $new_quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $new_quantity;
  }
}


// Example Usage (Demonstration - this part will likely be handled by your frontend)
//  You'll likely be getting this data from your product listing page
//  or your shopping cart page.

// Add some items to the cart
addToCart(1, 'T-Shirt', 20.00, 2);
addToCart(2, 'Jeans', 50.00, 1);
addToCart(1, 'T-Shirt', 20.00, 1); // Add more of the T-shirt


// Display the cart contents
echo "<h2>Your Shopping Cart</h2>";
if (empty($_SESSION['cart'])) {
  echo "<p>Your cart is empty.</p>";
} else {
  echo "<ul>";
  foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Total for item:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
    echo "</li>";
  }
  echo "</ul>";
}

// Example of updating quantity
updateQuantity(1, 3);

// Display the updated cart
echo "<h2>Updated Cart</h2>";
echo "<ul>";
foreach ($_SESSION['cart'] as $product_id => $product_details) {
    echo "<li>";
    echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
    echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
    echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
    echo "<strong>Total for item:</strong> $" . ($product_details['price'] * $product_details['quantity']) . "<br>";
    echo "</li>";
}


// Example of removing an item
//removeFromCart(2);


// Important Considerations & Next Steps:

// 1. Frontend Integration:
//    -  You'll need to add HTML forms to your web page to allow users to add items to the cart.
//    -  When a user clicks an "Add to Cart" button, you'll use JavaScript to send a request to the server (typically a POST request) with the product ID and quantity.
//    -  The PHP code (this example) will receive that request, update the session, and return a response (e.g., a confirmation message to display on the page).

// 2. Security:
//    -  **Input Validation:**  Always validate and sanitize user inputs (product IDs, quantities) to prevent vulnerabilities like SQL injection or cross-site scripting (XSS).
//    -  **Session Management:**  Implement proper session management practices, including setting appropriate session expiration times, using secure session IDs, and protecting session cookies.
//    -  **CSRF Protection:** Implement Cross-Site Request Forgery (CSRF) protection.

// 3.  Database Integration:
//    - In a real application, you'll typically store product information (name, price, etc.) in a database.
//    -  The PHP code would need to query the database to retrieve product details before adding them to the cart.

// 4.  Cart Persistence:
//    - Sessions are stored on the server.  However, the session data is still associated with the user's browser.
//    -  If the user closes their browser and reopens it, the session will be lost unless you implement a mechanism to persist the cart data (e.g., using cookies or localStorage, or storing it on the server).

// 5.  Error Handling:
//    -  Include error handling to gracefully handle situations like invalid product IDs, database connection errors, or other unexpected issues.

?>
