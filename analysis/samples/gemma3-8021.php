

<?php

// Sample product data (replace with your database connection)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 0; // Use intval for safety
    if ($quantity <= 0) {
        $errorMessage = "Please enter a valid quantity.";
    }

    // Process the order
    if (empty($errorMessage)) {
        $cart = []; // Initialize an empty cart
        $total = 0;

        foreach ($products as $product_id => $product) {
            if (isset($_POST["add_product_" . $product_id])) {
                $product_name = $product["name"];
                $product_price = $product["price"];
                $quantity_to_add = $quantity;

                $cart[$product_name] = [
                    "quantity" => $quantity_to_add,
                    "price" => $product_price,
                ];

                $total += $product_price * $quantity_to_add;
            }
        }

        // Display the order summary
        echo "<h2>Order Summary</h2>";
        echo "<ul>";
        foreach ($cart as $product_name => $item) {
            echo "<li>" . $product_name . ": " . $item["quantity"] . " x $" . $item["price"] . " = $" . $item["quantity"] * $item["price"] . "</li>";
        }
        echo "</ul>";
        echo "<p><strong>Total: $" . $total . "</strong></p>";

        // You would typically add code here to save the order to a database.
        // For example:
        // $order_data = [
        //     "user_id" => 1, // Replace with the user's ID
        //     "items" => $cart,
        //     "total" => $total,
        // ];
        // $db = new DatabaseConnection(); // Assuming you have a DatabaseConnection class
        // $db->insertOrder($order_data);
    }
}
?>
