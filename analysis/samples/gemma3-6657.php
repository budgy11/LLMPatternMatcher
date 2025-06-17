    </ul>

    <script>
        // This is a basic JavaScript function to simulate adding to cart.
        // In a real application, you would use AJAX to send the product ID
        // to the server.

        function addToCart(product_id) {
            // In a real application, you would use AJAX to send this product_id
            // to the server.
            // For this example, we'll just display a message.

            alert("Added product " + product_id + " to the cart!");
        }

        function clearCart() {
           // In a real application, you would clear the cart data from the server.
           alert("Cart cleared!");
        }
    </script>
</body>
</html>


<?php
session_start();

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to add an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If it exists, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If it doesn't exist, add the product to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// Function to remove an item from the cart
function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($product_id, $quantity) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Function to get the cart contents
function getCartContents() {
    return $_SESSION['cart'];
}


// --- Example Usage (Simulated Product Data) ---

// Dummy product data (replace with your actual database/API calls)
$products = array(
    1 => array('name' => 'Laptop', 'price' => 1200),
    2 => array('name' => 'Mouse', 'price' => 25),
    3 => array('name' => 'Keyboard', 'price' => 75)
);


// --- Handling Add to Cart Request (Simulated) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default to 1 if not provided

    if (isset($products[$product_id])) {
        addToCart($product_id, $products[$product_id]['name'], $products[$product_id]['price'], $quantity);
    } else {
        // Handle the case where the product ID is not found
        echo "Product ID '$product_id' not found.";
    }
}

// --- Handling Remove from Cart Request (Simulated) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    removeCartItem($product_id);
}

// --- Handling Update Quantity Request (Simulated) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    updateCartQuantity($product_id, $new_quantity);
}

// --- Display the Cart Contents ---

$cart_contents = getCartContents();

echo "<h2>Your Shopping Cart</h2>";

if (empty($cart_contents)) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart_contents as $product_id => $item) {
        echo "<li>" . $item['name'] . " - $" . $item['price'] . " (Quantity: " . $item['quantity'] . ")</li>";
    }
    echo "</ul>";
}


?>
