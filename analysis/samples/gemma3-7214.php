

<?php

// --- Database Connection (Replace with your actual credentials) ---
$dbHost = 'localhost';
$dbName = 'ecommerce';
$dbUser = 'root';
$dbPassword = '';

// Create a database connection
$conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Product Data (For demonstration - replace with a database query) ---
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20.00, 'stock' => 50],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50.00, 'stock' => 25],
    3 => ['id' => 3, 'name' => 'Hat', 'price' => 15.00, 'stock' => 30],
];

// --- Session Handling (To track the cart) ---
session_start();

// Initialize the cart in the session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- Helper Functions ---

/**
 * Adds a product to the cart.
 *
 * @param int $product_id The ID of the product to add.
 * @return bool True if successful, false otherwise.
 */
function addToCart(int $product_id) {
    global $products;

    // Find the product in the database (or product data)
    if (isset($products[$product_id])) {
        $product = $products[$product_id];

        // Check if the product is already in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            // Increment quantity
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } else {
            // Add the product to the cart
            $_SESSION['cart'][$product_id] = [
                'id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
            ];
        }
        return true;
    } else {
        return false; // Product not found
    }
}

/**
 * Removes a product from the cart.
 *
 * @param int $product_id The ID of the product to remove.
 * @return bool True if successful, false otherwise.
 */
function removeFromCart(int $product_id) {
    global $products;

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        return true;
    } else {
        return false;
    }
}

/**
 * Updates the quantity of a product in the cart.
 *
 * @param int $product_id The ID of the product to update.
 * @param int $quantity The new quantity.
 * @return bool True if successful, false otherwise.
 */
function updateQuantity(int $product_id, int $quantity) {
    global $products;

    if (isset($_SESSION['cart'][$product_id])) {
        // Validate quantity (ensure it's positive)
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
            return true;
        } else {
            // Optionally, you could clear the item from the cart if the quantity is 0
            removeFromCart($product_id); // Remove if quantity is 0
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Calculates the total cart value.
 *
 * @return float The total cart value.
 */
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// --- Purchase Functionality (Simulated) ---

/**
 * Handles the purchase process (simulated).
 *
 * In a real application, this would:
 *   1. Validate the order (address, payment details, etc.)
 *   2. Process the payment.
 *   3. Update inventory (reduce stock).
 *   4. Send order confirmation email.
 *
 * This is a simplified simulation.
 */
function processPurchase() {
    if (empty($_SESSION['cart'])) {
        // Cart is empty
        return false;
    }

    // Simulate payment processing
    // In a real application, you'd integrate with a payment gateway
    // For now, we just confirm the purchase
    echo "<p>Purchase confirmed!</p>";

    // Simulate inventory update (reduce stock - NOT implemented fully)
    // In a real application, you would update your database
    foreach ($_SESSION['cart'] as $item) {
        // In a real application, you would update your database
        // This is just for demonstration
        echo "<p>Reduced stock for " . $products[$item['id']]['name'] . "</p>";
    }

    // Clear the cart
    $_SESSION['cart'] = [];

    return true;
}


// --- Displaying the Cart and Purchase Button ---

echo "<h1>Shopping Cart</h1>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $item) {
        echo "<li>" . $products[$item['id']]['name'] . " - $" . $item['price'] . " x " . $item['quantity'] . " = $" . $item['price'] * $item['quantity'] . "</li>";
    }
    echo "</ul>";

    echo "<p><button onclick=\"processPurchase()\">Process Purchase</button></p>"; // Using a button for simplicity
}


// ---  Simple JavaScript Function (for button click) ---
// This is just for demo purposes.  A real application would likely use AJAX.
echo "<script>
function processPurchase() {
  // In a real application, AJAX would be used to send the purchase data to the server.
  // For this demo, we're just simulating the process.

  // You would typically do something like:
  //   fetch('/purchase.php', { method: 'POST', body: JSON.stringify({ cart: JSON.stringify($_SESSION['cart']) }) });
  //   (Replace '/purchase.php' with the actual URL)

  alert('Purchase initiated (simulated)!');
}
</script>";


?>
