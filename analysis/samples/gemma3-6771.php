

<?php

// Database connection details (Replace with your actual credentials)
$db_host = "localhost";
$db_user = "your_db_user";
$db_pass = "your_db_password";
$db_name = "your_db_name";

// Function to connect to the database
function connectToDatabase() {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to add a new order to the database
function addOrder($userId, $items) {
    $conn = connectToDatabase();

    // Prepare the SQL query
    $sql = "INSERT INTO orders (user_id, order_date, items) VALUES (?, NOW(), ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("is", $userId, json_encode($items));

    // Execute the statement
    if ($stmt->execute()) {
        return $stmt->insert_id; // Return the ID of the newly created order
    } else {
        return false;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Function to display products (for demonstration purposes)
function displayProducts() {
    // Replace this with your database query to fetch products
    $products = [
        ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
        ['id' => 2, 'name' => 'Mouse', 'price' => 25],
        ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
    ];

    echo "<h2>Available Products:</h2>";
    echo "<ul>";
    foreach ($products as $product) {
        echo "<li>" . $product['name'] . " - $" . $product['price'] . "</li>";
    }
    echo "</ul>";
}


// Handle the purchase request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user ID (replace with your actual user authentication)
    $userId = $_POST["user_id"];

    // Get the items to purchase (assuming a JSON format)
    $itemsString = $_POST["items"];
    $items = json_decode($itemsString, true);  // Decode JSON into an associative array

    // Validate user ID (add more robust validation as needed)
    if (!is_numeric($userId)) {
        echo "<p style='color:red;'>Invalid User ID.</p>";
    } else {
        // Add the order to the database
        $orderId = addOrder($userId, $items);

        if ($orderId) {
            echo "<p style='color:green;'>Order placed successfully! Order ID: " . $orderId . "</p>";
        } else {
            echo "<p style='color:red;'>Failed to place order.</p>";
        }
    }
}
?>
