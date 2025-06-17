        <label for="product_id">Product ID to Remove:</label>
        <input type="number" id="product_id" name="product_id" required><br><br>
        <input type="submit" name="remove_from_cart" value="Remove from Cart">
    </form>



</body>
</html>


<?php

// Sample Products (In a real app, this would come from a database)
$products = [
    [
        'id' => 1,
        'name' => 'T-Shirt',
        'price' => 20.00
    ],
    [
        'id' => 2,
        'name' => 'Mug',
        'price' => 10.00
    ],
    [
        'id' => 3,
        'name' => 'Notebook',
        'price' => 5.00
    ]
];

// Initialize Orders
$orders = [];

// Function to display available products
function displayProducts() {
    echo "<h2>Available Products:</h2>";
    echo "<ul>";
    foreach ($products as $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . "</li>";
    }
    echo "</ul>";
}

// Function to handle order creation
function createOrder($productId, $quantity) {
    // Validate product ID and quantity
    $product = null;
    foreach ($products as $p) {
        if ($p['id'] == $productId) {
            $product = $p;
            break;
        }
    }

    if ($product === null) {
        echo "<p style='color:red;'>Product with ID " . $productId . " not found.</p>";
        return;
    }

    if ($quantity <= 0) {
        echo "<p style='color:red;'>Quantity must be greater than 0.</p>";
        return;
    }

    // Create the order object
    $order = [
        'id' => uniqid(), // Generate a unique order ID (for demonstration)
        'productId' => $productId,
        'product' => $product,
        'quantity' => $quantity,
        'total' => $product['price'] * $quantity
    ];

    // Add the order to the orders array
    $orders[] = $order;

    echo "<p style='color:green;'>Order created successfully! Order ID: " . $order['id'] . "</p>";
}

// Function to display the order list
function displayOrders() {
    echo "<h2>Orders:</h2>";
    if (empty($orders)) {
        echo "<p>No orders placed yet.</p>";
        return;
    }

    echo "<ul>";
    foreach ($orders as $order) {
        echo "<li>Order ID: " . $order['id'] . "<br>";
        echo "Product: " . $order['product']['name'] . " - $" . $order['product']['price'] . "<br>";
        echo "Quantity: " . $order['quantity'] . "<br>";
        echo "Total: $" . $order['total'] . "</li>";
    }
    echo "</ul>";
}


// Example Usage (This would typically be handled through a web form)
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = (int)$_POST['product_id']; // Ensure it's an integer
    $quantity = (int)$_POST['quantity'];

    createOrder($productId, $quantity);
}

//Display the products
displayProducts();

//Display the orders
displayOrders();

?>
