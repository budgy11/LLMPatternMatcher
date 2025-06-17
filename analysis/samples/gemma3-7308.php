    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <label for="customer_name">Customer Name:</label>
    <input type="text" id="customer_name" name="customer_name" required><br><br>

    <label for="customer_email">Customer Email:</label>
    <input type="email" id="customer_email" name="customer_email" required><br><br>

    <input type="submit" name="submit" value="Place Order">
  </form>

</body>
</html>


<?php

// Configuration
$products = [
    1 => ['name' => 'Laptop', 'price' => 1200],
    2 => ['name' => 'Mouse', 'price' => 25],
    3 => ['name' => 'Keyboard', 'price' => 75],
    4 => ['name' => 'Monitor', 'price' => 300],
];

$cart = []; // Initialize an empty cart

// Function to display the product catalog
function displayProductCatalog() {
    echo "<h1>Product Catalog</h1>";
    foreach ($products as $id => $product) {
        echo "<p><strong>" . $product['name'] . "</strong> - $" . $product['price'] . "</p>";
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='product_id' value='" . $id . "'>";
        echo "<input type='submit' value='Add to Cart'>";
        echo "</form>";
    }
}

// Function to add a product to the cart
function addToCart($product_id) {
    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $cart[] = $product;
        echo "<p>Added '" . $product['name'] . "' to cart.</p>";
    } else {
        echo "<p>Product ID not found.</p>";
    }
}

// Function to display the cart
function displayCart() {
    echo "<h1>Shopping Cart</h1>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($cart as $item) {
            echo "<li>" . $item['name'] . " - $" . $item['price'] . "</li>";
        }
        echo "</ul>";

        // Calculate total price
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'];
        }
        echo "<p><strong>Total: $" . $total . "</strong></p>";
    }
}

// Handle form submission to add to cart
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id'])) {
        addToCart($_POST['product_id']);
    }
}

// Display the page content
?>
