        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" value="1" min="1">
        <input type="submit" name="add_to_cart" value="Add to Cart">
    </form>

</body>
</html>


<?php

// Database Connection (Replace with your actual database credentials)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

try {
    $db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input (Implement more robust validation here)
    $name = $_POST["name"];
    $email = $_POST["email"];
    $quantity = $_POST["quantity"];
    $item_id = $_POST["item_id"]; // Assuming you have an item_id field

    // Check if the item exists (Example - Adapt to your table structure)
    $stmt = $db->prepare("SELECT id, name, price FROM items WHERE id = ?");
    $item_result = $stmt->execute([$item_id]);
    $item = $item_result->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        $error = "Invalid item ID.";
    } elseif (empty($name) || empty($email) || empty($quantity)) {
        $error = "All fields are required.";
    } else {
        // Prepare the data for insertion
        $stmt = $db->prepare("INSERT INTO purchases (name, email, quantity, item_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $quantity, $item_id]);
        $last_insert_id = $db->lastInsertId();

        // Display success message
        $success = "Purchase successful! Order ID: " . $last_insert_id;

    }
}

?>
