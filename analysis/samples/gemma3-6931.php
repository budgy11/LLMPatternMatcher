

<?php

// Database Connection (Replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Product Data (Example) - Replace with your product data source
$products = [
    ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];


// Function to handle the purchase process
function handlePurchase($cart, $conn) {
    $total = 0;
    $items = [];

    foreach ($cart as $product_id => $quantity) {
        // Find the product by ID
        $product = findProductById($product_id, $conn);

        if ($product) {
            $price = $product['price'];
            $total += $price * $quantity;
            $items[] = [
                'product_id' => $product_id,
                'name' => $product['name'],
                'quantity' => $quantity,
                'price' => $price,
            ];
        } else {
            // Handle product not found (e.g., display an error)
            return ['error' => "Product with ID '$product_id' not found."];
        }
    }

    // Insert order into database (Replace with your database insertion query)
    $sql = "INSERT INTO orders (items, total_amount) VALUES (:items, :total_amount)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $items, $total); // 's' indicates a string
    if ($stmt->execute()) {
        $order_id = $conn->insert_id; // Get the ID of the newly inserted order
        return ['success' => true, 'order_id' => $order_id];
    } else {
        return ['error' => "Failed to insert order into database: " . $conn->error];
    }

    $stmt->close(); // Close the statement
}


// Purchase Request Handler
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get cart data from POST request (Assuming a JSON array is sent)
    $cart = json_decode($_POST["cart"], true);

    if (json_decode($_POST["cart"]) === null) {
        $cart = []; // Handle invalid JSON
    }

    // Process the purchase
    $result = handlePurchase($cart, $conn);

    if ($result['success']) {
        echo "<p style='color:green;'>Purchase successful! Order ID: " . $result['order_id'] . "</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $result['error'] . "</p>";
    }
} else {
    // Display the cart form
    echo "<h2>Shopping Cart</h2>";
    echo "<form method='post' action=''>";
    echo "<ul>";
    foreach ($products as $product) {
        echo "<li>";
        echo "<label for='product_" . $product['id'] . "'>" . $product['name'] . " ($" . $product['price'] . ")</label><br>";
        echo "<input type='number' id='product_" . $product['id'] . "' name='cart[' . $product['id'] . ']' value='0' min='0'>";
        echo "<button type='submit' name='add_to_cart' value='" . $product['id'] . "'>Add to Cart</button>";
        echo "</li>";
    }
    echo "</ul>";
    echo "<input type='hidden' name='action' value='purchase'>"; // Indicate purchase action
    echo "<button type='submit' name='purchase'>Complete Purchase</button>";
    echo "</form>";
}

// Close connection
$conn->close();
?>
