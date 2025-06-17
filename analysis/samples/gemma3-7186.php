    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br><br>

    <label for="price">Price per item:</label>
    <input type="number" id="price" name="price" step="0.01" min="0.01" required><br><br>

    <input type="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// This is a simplified purchase functionality example. 
// For a production environment, you would need:
// - Proper database interaction
// - Input validation and sanitization
// - Error handling
// - Security measures (CSRF protection, authentication, etc.)
// - Session management
// - A real database (MySQL, PostgreSQL, etc.)

// Data (Simulated Database)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15]
];

// Session for storing cart data (simple)
session_start();

// Check if the cart exists, initialize if not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ------------------------------------------------------------------
// Function to add an item to the cart
function addToCart($product_id, $quantity = 1) {
    global $products;

    // Validate product_id
    if (!isset($products[$product_id])) {
        return false; // Product not found
    }

    // Validate quantity
    $quantity = max(1, (int)$quantity); // Ensure quantity is at least 1

    // Check if the item is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add new item to the cart
        $_SESSION['cart'][$product_id] = [
            'id' => $product_id,
            'name' => $products[$product_id]['name'],
            'price' => $products[$product_id]['price'],
            'quantity' => $quantity
        ];
    }
    return true;
}


// ------------------------------------------------------------------
// Function to view the cart
function viewCart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($_SESSION['cart'] as $item_id => $item_data) {
            echo "<li>";
            echo "<strong>" . $item_data['name'] . "</strong> - $" . $item_data['price'] . "  (Quantity: " . $item_data['quantity'] . ") ";
            echo "<form action='' method='post'>"; // Dummy action to prevent form submission
            echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";
            echo "<input type='submit' value='Remove'>";
            echo "</form>";
            echo "</li>";
        }
        echo "</ul>";
    }
}


// ------------------------------------------------------------------
// Function to remove an item from the cart
function removeItemFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// ------------------------------------------------------------------
// Handle form submission (Add to Cart)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['item_id']) && isset($_POST['quantity'])) {
        $product_id = (int)$_POST['item_id'];
        $quantity = (int)$_POST['quantity'];
        addToCart($product_id, $quantity);
    }
}

// ------------------------------------------------------------------
// Handle form submission (Remove from Cart - example)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $product_id_to_remove = (int)$_POST['remove_item'];
    removeItemFromCart($product_id_to_remove);
}


// ------------------------------------------------------------------
// Display the page
?>
