

<?php

// Initialize the session if it doesn't exist
session_start();

// **1. Define Cart Variables**
// These variables will store the items in the cart.
// You can expand this to include quantity, price, etc.
// For simplicity, we'll just use an array of product IDs.
$_SESSION['cart'] = [];

// **2. Cart Operations (Functions)**

// Add an item to the cart
function add_to_cart($product_id, $quantity = 1) {
  global $cart; // Access the global cart array

  // Check if the product is already in the cart
  if (isset($cart[$product_id])) {
    $cart[$product_id]['quantity'] += $quantity; // Increment quantity if exists
  } else {
    $cart[$product_id] = ['quantity' => $quantity];
  }
}


// Remove an item from the cart (by product ID)
function remove_from_cart($product_id) {
  global $cart;
  if (isset($cart[$product_id])) {
    unset($cart[$product_id]);
  }
}

// Update the quantity of an item in the cart
function update_cart_quantity($product_id, $new_quantity) {
    global $cart;

    if (isset($cart[$product_id])) {
        $cart[$product_id]['quantity'] = $new_quantity;
    }
}

// Get the cart contents (for display or processing)
function get_cart_contents() {
  global $cart;
  return $cart;
}

// **3. Example Usage (Illustrative - You'll integrate this into your application)**

//  Simulate adding items to the cart
add_to_cart(123, 2); // Add product 123 to the cart with quantity 2
add_to_cart(456, 1); // Add product 456 to the cart with quantity 1
add_to_cart(123, 3); //  Increase the quantity of product 123

// Display the contents of the cart (for demonstration)
echo "<h2>Cart Contents:</h2>";
echo "<ul>";
$cart_contents = get_cart_contents();
if (empty($cart_contents)) {
  echo "<li>Cart is empty.</li>";
} else {
  foreach ($cart_contents as $product_id => $item) {
    echo "<li>Product ID: " . $product_id . ", Quantity: " . $item['quantity'] . "</li>";
  }
}
echo "</ul>";



// **4.  Interaction (Demonstrates how you'd use these functions in a website)**

//  Simulate a user selecting an item (This is just a simplified example)
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']); // Sanitize input

    add_to_cart($product_id, $quantity);
}


// Example of removing an item (for demonstration)
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
}


// **Important Considerations and Next Steps:**

// * **Database Integration:** In a real application, you'd retrieve product information (name, price, etc.) from a database based on the product ID.
// * **Session Management:** The session needs to be persistent.  This means the user needs to remain on the site (or have a cookie set) to maintain the cart.
// * **Input Validation and Sanitization:**  Always sanitize and validate user input (e.g., `intval()` to convert input to integers).  This prevents security vulnerabilities.
// * **Quantity Handling:** This example uses a simple quantity.  You might want to add error handling (e.g., prevent negative quantities).
// * **Error Handling:** Add error handling (e.g., if a product ID is invalid).
// * **Real-World Cart Implementations:** For larger e-commerce sites, you'd likely use a more robust cart library or framework component.  Consider using a session management service other than the native PHP session for scalability and security.
// * **Security:** Use appropriate security measures to protect user data and prevent unauthorized access to the cart.



?>
