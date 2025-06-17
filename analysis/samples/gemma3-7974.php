

<?php

// Simulate a product database (replace with your actual database connection)
$products = [
    1 => ['id' => 1, 'name' => 'T-Shirt', 'price' => 20],
    2 => ['id' => 2, 'name' => 'Jeans', 'price' => 50],
    3 => ['id' => 3, 'name' => 'Sneakers', 'price' => 80],
];

// Function to handle the purchase process
function handlePurchase($cart) {
    // Validate cart items
    foreach ($cart as $itemId => $quantity) {
        if (!isset($products[$itemId]) || $quantity <= 0) {
            return ['error' => 'Invalid item or quantity'];
        }
    }

    // Calculate total price
    $totalPrice = 0;
    foreach ($cart as $itemId => $quantity) {
        $totalPrice += $products[$itemId]['price'] * $quantity;
    }

    // Display purchase confirmation
    echo "<h2>Purchase Confirmation</h2>";
    echo "<p>Items purchased: </p>";
    echo "<ul>";
    foreach ($cart as $itemId => $quantity) {
        echo "<li>" . $products[$itemId]['name'] . " x " . $quantity . " = $" . $products[$itemId]['price'] * $quantity . "</li>";
    }
    echo "</ul>";
    echo "<p>Total Price: $" . $totalPrice . "</p>";

    // Simulate payment processing (replace with actual payment gateway integration)
    // echo "<p>Processing payment...</p>";
    // echo "<p>Payment successful!</p>";
    // return ['success' => true];
    
    return ['success' => true, 'total' => $totalPrice];
}

// ----------------------------------------------------
// Example Usage & Handling the Purchase
// ----------------------------------------------------

// 1. Get Cart Items from User Input (e.g., form submission)
$cart = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['item1'])) {
        $cart[] = ['item1' => 'item1', 'quantity' => $_POST['item1']];
    }
    if (isset($_POST['item2'])) {
        $cart[] = ['item2' => 'item2', 'quantity' => $_POST['item2']];
    }
    if (isset($_POST['item3'])) {
        $cart[] = ['item3' => 'item3', 'quantity' => $_POST['item3']];
    }
}


// 2.  Handle the Purchase
$purchaseResult = handlePurchase($cart);

// 3. Display the Result
?>
