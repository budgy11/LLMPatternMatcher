    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required>
    <br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1" required>
    <br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required>
    <br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required>
    <br><br>

    <button type="submit">Place Order</button>
</form>

</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$dbHost = 'localhost';
$dbUser = 'your_db_user';
$dbPass = 'your_db_password';
$dbName = 'your_db_name';

//  Basic product data - replace with your product data source (e.g., database query)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
    4 => ['id' => 4, 'name' => 'Monitor', 'price' => 300],
];


// Function to add an item to the cart
function addItemToCart($productId, $cart) {
    if (isset($cart['items'][$productId])) {
        $cart['items'][$productId]['quantity']++;
    } else {
        $cart['items'][$productId] = ['quantity' => 1];
    }
}

// Function to update the quantity of an item in the cart
function updateQuantity($productId, $quantity, $cart) {
    if (isset($cart['items'][$productId])) {
        $cart['items'][$productId]['quantity'] = $quantity;
    }
}


// Function to remove an item from the cart
function removeItemFromCart($productId, $cart) {
    unset($cart['items'][$productId]);
}

// Function to get the cart contents
function getCartContents($cart) {
    return $cart['items'];
}

// Cart initialization
$cart = ['items' => []];

// Handle form submissions (add to cart)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $productId = (int)$_POST['product_id']; // Validate and convert to integer

    if (isset($products[$productId])) {
        addItemToCart($productId, $cart);
    } else {
        echo "Product ID $productId not found.";
    }
}


// Handle updating quantities
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_quantity'])) {
    $productId = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    updateQuantity($productId, $quantity, $cart);
}

// Handle removing items
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_item'])) {
    $productId = (int)$_POST['product_id'];
    removeItemFromCart($productId, $cart);
}


// Display the cart
echo "<h2>Shopping Cart</h2>";

if (empty($cart['items'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($cart['items'] as $id => $item) {
        echo "<li>";
        echo "<strong>Product:</strong> " . $products[$id]['name'] . "<br>";
        echo "<strong>Price:</strong> $" . $products[$id]['price'] . "<br>";
        echo "<strong>Quantity:</strong> " . $item['quantity'] . "<br>";
        echo "<strong>Total:</strong> $" . $products[$id]['price'] * $item['quantity'] . "<br>";
        echo "<form action=\"update_quantity.php\" method=\"POST\">";
        echo "<input type=\"hidden\" name=\"product_id\" value=\"".$id."\">";
        echo "<input type='number' name='quantity' value='".$item['quantity']."'>";
        echo "<button type=\"submit\">Update</button>";
        echo "</form>";
        echo "<form action=\"remove_item.php\" method=\"POST\">";
        echo "<input type=\"hidden\" name=\"product_id\" value=\"".$id."\">";
        echo "<button type=\"submit\">Remove</button>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";

    // Calculate total cart value
    $total = 0;
    foreach ($cart['items'] as $id => $item) {
        $total += $products[$id]['price'] * $item['quantity'];
    }

    echo "<p><strong>Total Cart Value:</strong> $" . $total . "</p>";
}


?>
