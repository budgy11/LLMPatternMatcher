        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Your Name"><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Your Email"><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" value="1"><br><br>

        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" min="1" value="1"><br><br>

        <input type="submit" value="Place Order">
    </form>

</body>
</html>


<?php

// Database connection (Replace with your actual credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_pass = "your_password";

// Establish database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate input (Add more validation as needed)
    $product_name = trim($_POST["product_name"]);
    $quantity = intval($_POST["quantity"]);
    $price = floatval($_POST["price"]);

    if (empty($product_name) || $quantity <= 0 || $price <= 0) {
        echo "<p style='color: red;'>Invalid input. Please fill in all fields correctly.</p>";
    } else {
        // Sanitize input (Important for security - prevent SQL injection)
        $product_name = $conn->real_escape_string($product_name);
        $quantity = $conn->real_escape_string($quantity);
        $price = $conn->real_escape_string($price);


        // Add item to the cart (This part needs to be adapted based on how you're storing cart data)
        // Example: Store in a session variable
        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        //Add new item to the cart
        $_SESSION['cart'][] = array(
            'name' => $product_name,
            'quantity' => $quantity,
            'price' => $price
        );
        echo "<p style='color: green;'>Item added to cart!</p>";
        // Display the cart details.  This is for demonstration purposes.
        displayCart();

    }
}

// Function to display the cart contents (for demonstration)
function displayCart() {
    global $conn;
    if(isset($_SESSION['cart'])) {
        echo "<h2>Your Shopping Cart</h2>";
        echo "<ul>";
        foreach($_SESSION['cart'] as $item) {
            echo "<li>" . $item['name'] . " - Quantity: " . $item['quantity'] . " - Price: $" . $item['price'] . "</li>";
        }
        echo "</ul>";
    }
}

//  Start the session
session_start();

?>
