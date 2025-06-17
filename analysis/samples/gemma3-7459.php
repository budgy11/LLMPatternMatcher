    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <button type="submit">Add to Cart</button>
</form>


</body>
</html>


<?php

// --- Database Configuration (Replace with your actual credentials) ---
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// ---  Database Connection ---
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// --- Product Data (Simulated for demonstration) ---
$products = [
    1 => ["id" => 1, "name" => "Laptop", "price" => 1200.00],
    2 => ["id" => 2, "name" => "Mouse", "price" => 25.00],
    3 => ["id" => 3, "name" => "Keyboard", "price" => 75.00],
];


// --- Purchase Functionality ---

// 1. Add to Cart (Simulated -  In a real application, you'd add to a session or database)
function addToCart($product_id, $quantity) {
    // In a real application, you'd update a shopping cart session or database
    // This is a simple demonstration
    echo "Added " . $quantity . " " . $product['name'] . " to the cart.<br>";
}

// 2. Process Purchase
function processPurchase($product_id, $quantity) {
    // Validate Input (Crucial for security!)
    if (!is_numeric($product_id) || !is_numeric($quantity) || $quantity <= 0) {
        return "Invalid input. Please enter valid product ID and quantity.";
    }

    $product = $products[$product_id];

    if (!isset($product)) {
        return "Product not found.";
    }

    $total_price = $product['price'] * $quantity;

    // Add purchase record to database (Replace with your database logic)
    try {
        $stmt = $pdo->prepare("INSERT INTO purchases (product_id, quantity, total_price) VALUES (:product_id, :quantity, :total_price)");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':total_price', $total_price);
        $stmt->execute();

        return "Purchase successful! Total: $" . number_format($total_price, 2);

    } catch (PDOException $e) {
        return "Error processing purchase: " . $e->getMessage();
    }
}

// ---  Example Usage (HTML Form for Purchase) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    $purchase_result = processPurchase($product_id, $quantity);

    echo $purchase_result . "<br>";
}

?>
