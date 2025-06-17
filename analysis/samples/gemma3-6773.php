
    <form method="post">
        <label for="user_id">User ID:</label>
        <input type="text" id="user_id" name="user_id" required><br><br>

        <label for="items">Items to Purchase (JSON format - e.g., [{"id": 1, "quantity": 2}, {"id": 2, "quantity": 1}])</label><br>
        <textarea id="items" name="items" rows="5" cols="50" required></textarea><br><br>

        <input type="submit" value="Place Order">
    </form>

</body>
</html>


<?php

// Database connection details (Replace with your actual details)
$db_host = "localhost";
$db_name = "ecommerce";
$db_user = "your_username";
$db_password = "your_password";

// Ensure you have a database named 'ecommerce' created.
//  It should have at least two tables:
//  - products (id, name, price, description)
//  - orders (id, customer_name, order_date, total_amount)
//  - order_items (order_id, product_id, quantity)


// 1. Purchase Functionality (add_to_cart.php - Example)

// Assuming this is called from a form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    // Validate input (VERY IMPORTANT - Add more robust validation here)
    if (!is_numeric($product_id) || !is_numeric($quantity) || $quantity <= 0) {
        echo "Invalid input. Please check the product ID and quantity.";
        exit;
    }


    // --------------------
    //  Example:  Add to Cart Logic (Simplified -  Needs more robust implementation)
    //  This is just a placeholder.  You'll need to store the cart information
    //  somewhere (e.g., in a session, a cookie, or a database table).
    // --------------------
    //  Here, we'll just echo the info for demonstration purposes.
    echo "Product ID: " . $product_id . "<br>";
    echo "Quantity: " . $quantity . "<br>";

    // In a real application, you would:
    // 1.  Retrieve the existing cart data (if any).
    // 2.  Add the new item to the cart.
    // 3.  Store the updated cart data.

    // For demonstration, let's just add a placeholder record to the order_items table.
    // WARNING: This is NOT a production-ready solution.  It's just for illustration.

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (NULL, " . $product_id . ", " . $quantity . ")";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Product added to cart.</p>";
    } else {
        echo "<p>Error adding product to cart: " . $conn->error . "</p>";
    }

    $conn->close();
}

// 2.  Checkout Functionality (checkout.php - Example)

// (This is a simplified example -  A real checkout needs secure payment processing)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form (product_ids, quantities, customer_name)
    $product_ids = $_POST["product_ids"];
    $quantities = $_POST["quantities"];
    $customer_name = $_POST["customer_name"];

    // Validate input
    if (!is_array($product_ids) || !is_array($quantities) || !is_numeric($customer_name)) {
        echo "Invalid input.  Please check the form data.";
        exit;
    }


    // Calculate the total amount
    $total_amount = 0;
    foreach ($product_ids as $index => $product_id) {
        // Get product price from the products table
        $sql = "SELECT price FROM products WHERE id = " . $product_id;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $price = $row["price"];
            $total_amount += $price * $quantities[$index];
        } else {
            echo "Product ID " . $product_id . " not found.";
            exit; // Or handle the error appropriately
        }
    }

    // Insert into the orders table
    // Generate a unique order ID (e.g., using a timestamp or auto-increment)
    $order_id = date("YmdHis"); // Simple timestamp

    $sql = "INSERT INTO orders (customer_name, order_date, total_amount) VALUES ('" . $customer_name . "', '" . date("YmdHis") . "', " . $total_amount . ")";

    if ($conn->query($sql) === TRUE) {
        echo "Order placed successfully! Order ID: " . $order_id . "<br>";
    } else {
        echo "Error placing order: " . $conn->error . "<br>";
    }

    // (Important)  Clear the cart after placing the order.
    //  You can do this in a session, cookie, or database.
    //  For this example, we'll just print a message.
    echo "Your cart is now cleared.";

    $conn->close();
}

?>
