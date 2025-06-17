    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" placeholder="Enter product name" required>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" placeholder="Enter quantity" required>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" placeholder="Enter price" required>

    <button type="submit">Add to Purchase</button>
</form>

<a href="index.php">Return to Home</a>

</body>
</html>


<?php

// Sample Data (Replace with your actual database connection and product data)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

$cart = [];  // Initialize an empty cart

// Function to add an item to the cart
function addToCart($product_id, $cart) {
    if (array_key_exists($product_id, $products)) {
        $product = $products[$product_id];
        $cart[] = $product;  // Add the product to the cart array
        return true; // Indicate success
    }
    return false; // Indicate failure (product not found)
}

// Function to view the cart
function viewCart($cart) {
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<h2>Your Cart</h2>";
        echo "<ul>";
        foreach ($cart as $item) {
            echo "<li>" . $item['name'] . " - $" . $item['price'] . "</li>";
        }
        echo "</ul>";
        
        // Calculate total
        $total = array_sum(array_column($cart, 'price'));
        echo "<p><strong>Total: $" . $total . "</strong></p>";
    }
}

// Function to remove an item from the cart (example - needs product_id)
function removeFromCart($product_id, &$cart) {
    // Iterate through the cart and remove the item
    foreach ($cart as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($cart[$key]);
            return true;
        }
    }
    return false; // Item not found
}


// Handle form submission (example)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 'add_to_cart' is present in the form data
    if (isset($_POST['add_to_cart'])) {
        $product_id = (int)$_POST['product_id']; // Ensure product_id is an integer
        addToCart($product_id, $cart);
    }

    if (isset($_POST['remove_item'])) {
        $product_id = (int)$_POST['product_id'];
        removeFromCart($product_id, $cart);
    }
}

?>
