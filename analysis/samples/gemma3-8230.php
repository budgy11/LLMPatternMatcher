<?php endif; ?>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_user";
$db_password = "your_password";
$db_name = "your_database";

// Function to connect to the database
function connectDB() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}


// Cart functions

function initializeCart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function addItemToCart($productId, $quantity) {
    initializeCart();

    // Check if the product is already in the cart
    $product_id = $productId;
    $item = array_search($product_id, $_SESSION['cart'], true);

    if ($item !== false) {
        // Product exists, increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = array(
            'id' => $product_id,
            'quantity' => $quantity,
            'price' => getProductPrice($product_id)  // Get the price
        );
    }
}


function getCartTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return $total;
}

function removeItemFromCart($productId) {
    initializeCart();
    unset($_SESSION['cart'][$productId]);
}

function updateQuantity($productId, $quantity) {
    initializeCart();
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}


function getCartContents() {
    return $_SESSION['cart'];
}

function clearCart() {
    unset($_SESSION['cart']);
}

// Helper function to get product details from the database (replace with your database query)
function getProductPrice($productId) {
  // Example using a simple array for demonstration
  $products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
  ];

  if (isset($products[$productId])) {
    return $products[$productId]['price'];
  } else {
    return 0; // Or handle the error appropriately
  }
}

// Example usage (demonstration)
// You would typically get this information from a form submission.
// For example:
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    addItemToCart($productId, $quantity);
}


if (isset($_POST['remove_from_cart'])) {
    $productId = $_POST['product_id'];
    removeItemFromCart($productId);
}

if (isset($_POST['update_quantity'])) {
  $productId = $_POST['product_id'];
  $quantity = $_POST['quantity'];
  updateQuantity($productId, $quantity);
}


// Display the cart contents
$cartContents = getCartContents();

if (!empty($cartContents)) {
    echo "<h2>Your Shopping Cart</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product ID</th><th>Name</th><th>Quantity</th><th>Price</th><th>Total</th></tr>";

    $cartTotal = getCartTotal($cartContents);

    foreach ($cartContents as $item) {
        $product_id = $item['id'];
        $product_name = getProductName($product_id);
        $price = $item['price'];
        $total = $item['quantity'] * $price;
        echo "<tr>";
        echo "<td>$product_id</td>";
        echo "<td>$product_name</td>";
        echo "<td>$item['quantity']</td>";
        echo "<td>$price</td>";
        echo "<td>$total</td>";
        echo "</tr>";
    }

    echo "<tr><td colspan='4'><strong>Total: $" . $cartTotal . "</strong></td></tr>";

    echo "</table>";

    echo "<br>";
    echo "<a href='checkout.php'>Proceed to Checkout</a>"; // Link to your checkout page
} else {
    echo "<p>Your cart is empty.</p>";
}

// Example: Clear Cart Button
echo "<br>";
echo "<form method='post'>
        <button type='submit' name='clear_cart'>Clear Cart</button>
      </form>";

?>
