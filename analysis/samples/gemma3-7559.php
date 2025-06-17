

<?php
session_start();

// Database connection details (replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_pass = "your_password";

//  Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and validate input
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    // Validate input (Example - you should add more robust validation)
    if (!is_numeric($product_id) || !is_numeric($quantity) || $quantity <= 0) {
        $error = "Invalid product ID or quantity.";
    } else {
        // Add item to cart
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if the product already exists in the cart
        $product_exists = false;
        foreach($_SESSION['cart'] as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity'] += $quantity;
                $product_exists = true;
                break;
            }
        }

        if (!$product_exists) {
            // Add new product to cart
            $_SESSION['cart'][] = [
                'product_id' => $product_id,
                'quantity' => $quantity,
                'name' => "Product " . $product_id, // Add product name (improve this)
                'price' => 10.00 // Placeholder price - get this from your database
            ];
        }

        // Redirect to cart page
        header("Location: cart.php");
        exit();
    }
}
?>
