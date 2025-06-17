
</body>
</html>


<?php

// Sample Product Data (Replace with your database connection)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Function to display the product list
function displayProductList($products) {
    echo "<h2>Available Products</h2>";
    echo "<ul>";
    foreach ($products as $id => $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . "</li>";
    }
    echo "</ul>";
}

// Function to handle the purchase process
function handlePurchase($productId, $quantity) {
    // Validate inputs
    if (!isset($products[$productId])) {
        return "Product not found.";
    }

    if ($quantity <= 0) {
        return "Invalid quantity. Please enter a positive number.";
    }

    // Calculate the total price
    $totalPrice = $products[$productId]['price'] * $quantity;

    // Display the purchase details
    echo "<h2>Purchase Confirmation</h2>";
    echo "<p>Product: " . $products[$productId]['name'] . "</p>";
    echo "<p>Quantity: " . $quantity . "</p>";
    echo "<p>Total Price: $" . $totalPrice . "</p>";

    //  In a real application, you'd save this purchase to a database
    //  or another persistent storage. For this example, we just confirm.

    return "Purchase confirmed!  You have purchased " . $quantity . " " . $products[$productId]['name'] . " for $" . $totalPrice;
}


// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    // Validate product_id
    if (!is_numeric($productId)) {
        echo "<p style='color:red;'>Invalid Product ID. Please enter a number.</p>";
    } else {
        $purchaseResult = handlePurchase($productId, $quantity);
        echo $purchaseResult;
    }

} else {
    // Display the purchase form
    ?>
