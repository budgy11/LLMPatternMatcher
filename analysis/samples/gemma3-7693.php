    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="price">Price per item:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// Database Connection (Replace with your actual database details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

try {
  $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

// Function to check if an item is already in the cart
function itemExistsInCart($cart_id, $item_id) {
  $stmt = $conn->prepare("SELECT id FROM cart_items WHERE cart_id = ? AND item_id = ?");
  $stmt->execute([$cart_id, $item_id]);
  return $stmt->fetchColumn() !== false; // Returns true if the item exists, false otherwise
}

// Function to add an item to the cart
function addItemToCart($cart_id, $item_id, $quantity) {
  if (itemExistsInCart($cart_id, $item_id)) {
    // Item already in cart, update the quantity
    $stmt = $conn->prepare("UPDATE cart_items SET quantity = quantity + ? WHERE cart_id = ? AND item_id = ?");
    $stmt->execute([$quantity, $cart_id, $item_id]);
  } else {
    // Item not in cart, add a new row
    $stmt = $conn->prepare("INSERT INTO cart_items (cart_id, item_id, quantity) VALUES (?, ?, ?)");
    $stmt->execute([$cart_id, $item_id, $quantity]);
  }
}

// Function to update the quantity of an item in the cart
function updateQuantityInCart($cart_id, $item_id, $quantity) {
  $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND item_id = ?");
  $stmt->execute([$quantity, $cart_id, $item_id]);
}

// Function to remove an item from the cart
function removeItemFromCart($cart_id, $item_id) {
  $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_id = ? AND item_id = ?");
  $stmt->execute([$cart_id, $item_id]);
}

// ---  Purchase Functionality (Simplified Example) ---

// Simulate a purchase
function completePurchase($cart_id) {
    // 1. Get Cart Details
    $cartQuery = $conn->prepare("SELECT * FROM cart_items WHERE cart_id = ?");
    $cartQuery->execute([$cart_id]);
    $cartItems = $cartQuery->fetchAll(PDO::FETCH_ASSOC);

    // 2.  Process Order (Placeholder - Replace with actual order processing logic)
    //    - Send email notification
    //    - Update inventory
    //    - Create a new order record in your orders table

    // Example: Print the cart items for demonstration
    echo "<h2>Items in your Cart:</h2>";
    echo "<ul>";
    foreach ($cartItems as $item) {
      echo "<li>" . $item['item_id'] . " - " . $item['quantity'] . "</li>";
    }
    echo "</ul>";

    // 3. Clear the Cart (You might want to offer the customer to save the cart)
    removeItemFromCart($cart_id, $cart_id); // Assuming cart_id is the item_id
}


// ---  Example Usage (Simulating Interactions) ---

//  1.  Simulate adding items to the cart
addItemToCart(123, 1, 2); // Cart ID 123, Item ID 1, Quantity 2
addItemToCart(123, 2, 1); // Cart ID 123, Item ID 2, Quantity 1
addItemToCart(456, 1, 3); // Cart ID 456, Item ID 1, Quantity 3

//  2.  Simulate updating the quantity
updateQuantityInCart(123, 1, 5); // Increase quantity of item 1 in cart 123 to 5

//  3. Simulate completing the purchase
completePurchase(123); //  Complete the purchase of items in cart 123

//  4.  Cleanup (Remove all items from the cart -  Consider user feedback)
// removeItemFromCart(123, 1);  //  Remove all items from cart 123

?>
