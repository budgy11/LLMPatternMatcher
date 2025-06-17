
</body>
</html>


<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

// Check if the cart is empty or not
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// -------------------- Product Handling --------------------

// Function to get product details by ID
function getProductDetails($productId) {
    $conn = connectToDatabase();
    $result = $conn->query("SELECT id, name, description, price, image FROM products WHERE id = '$productId'");

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        return $product;
    } else {
        return null;
    }
}


// -------------------- Cart Functions --------------------

// Add item to cart
function addToCart($productId, $quantity) {
    $conn = connectToDatabase();

    // Check if the product exists
    $product = getProductDetails($productId);
    if (!$product) {
        return false; // Product not found
    }

    $itemName = $product['name'];
    $itemPrice = $product['price'];

    if (isset($_SESSION['cart'][$productId])) {
        // Product already in cart, update quantity
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    } else {
        // Add new product to cart
        $_SESSION['cart'][$productId] = array(
            'quantity' => $quantity,
            'name' => $itemName,
            'price' => $itemPrice
        );
    }

    return true;
}

// Remove item from cart
function removeItemFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
    return true;
}

// Update quantity of item in cart
function updateQuantity($productId, $quantity) {
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
    return true;
}

// Get cart contents
function getCartContents() {
    return $_SESSION['cart'];
}

// Calculate total cart value
function calculateTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}


// -------------------- Displaying the Cart --------------------

// Display the cart contents
function displayCart() {
    $cartContents = getCartContents();

    echo "<div class='cart-container'>";
    echo "<h2>Shopping Cart</h2>";

    if (empty($cartContents)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cartContents as $productId => $item) {
            $product = getProductDetails($productId);
            echo "<li>";
            echo "<img src='" . $product['image'] . "' alt='" . $product['name'] . "' width='100'>";
            echo "<h3>" . $product['name'] . "</h3>";
            echo "<p>Price: $" . $product['price'] . "</p>";
            echo "<form method='post'>";
            echo "<label for='quantity'>" . $product['name'] . " Quantity:</label>";
            echo "<input type='number' id='quantity' name='quantity' value='" . $item['quantity'] . "' min='1'>";
            echo "<button type='submit' name='update_cart'>Update Cart</button>";
            echo "</form>";

            echo "<form method='post'>";
            echo "<button type='submit' name='remove_item_" . $productId . "'>Remove from Cart</button>";
            echo "</form>";
            echo "<br>";
        }
        echo "</ul>";
        echo "<p>Total: $" . calculateTotal() . "</p>";
    }

    echo "</div>";
}


// -------------------- Handling Cart Updates --------------------

// Update cart when the "Update Cart" button is clicked
if (isset($_POST['update_cart'])) {
    $productId = isset($_POST['update_cart']) ? $_POST['update_cart'] : ""; // get product id from form
    if (!empty($productId)) {
        addToCart($productId, 1); // Update quantity by 1 for simplicity
    }
}

// Remove item from cart when "Remove from Cart" button is clicked
if (isset($_POST['remove_item_' . $productId])) {
    removeItemFromCart($productId);
}



// -------------------- Displaying the Cart (Simplified for demonstration) --------------------
displayCart();


// -------------------- Example Product Data (for demonstration) --------------------
$products = array(
    1 => array('id' => 1, 'name' => 'T-Shirt', 'description' => 'A comfortable cotton t-shirt', 'price' => 20, 'image' => 'tshirt.jpg'),
    2 => array('id' => 2, 'name' => 'Jeans', 'description' => 'Classic blue jeans', 'price' => 50, 'image' => 'jeans.jpg'),
    3 => array('id' => 3, 'name' => 'Hat', 'description' => 'Warm wool hat', 'price' => 30, 'image' => 'hat.jpg')
);

?>
