
<!DOCTYPE html>
<html>
<head>
    <title>Product Purchase</title>
</head>
<body>

<h1>Product Purchase</h1>

<form method="post" action="">
    <label for="product_id">Product ID:</label>
    <input type="number" id="product_id" name="product_id" required><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required><br><br>

    <button type="submit">Purchase</button>
</form>

</body>
</html>


<?php

// Database connection (replace with your database credentials)
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

// Function to add a product to the cart
function addToCart($conn, $product_id, $quantity) {
    // Sanitize input (important for security)
    $product_id = mysqli_real_escape_string($conn, $product_id);
    $quantity = mysqli_real_escape_string($conn, $quantity);


    // Check if the product is already in the cart
    $query = "SELECT * FROM cart WHERE product_id = '$product_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Product already exists in cart, update the quantity
        $query = "UPDATE cart SET quantity = quantity + $quantity WHERE product_id = '$product_id'";
        if (!$conn->query($query)) {
            echo "Update error: " . $conn->error;
        }
    } else {
        // Product not in cart, add it
        $query = "INSERT INTO cart (product_id, quantity) VALUES ('$product_id', $quantity)";
        if (!$conn->query($query)) {
            echo "Insert error: " . $conn->error;
        }
    }
}

// Function to get the cart contents
function getCartContents($conn) {
    $query = "SELECT * FROM cart";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "Product ID: " . $row["product_id"]. " - Quantity: " . $row["quantity"]. "<br>";
        }
    } else {
        echo "Cart is empty";
    }
}

// Function to remove a product from the cart
function removeFromCart($conn, $product_id) {
    // Sanitize input
    $product_id = mysqli_real_escape_string($conn, $product_id);

    $query = "DELETE FROM cart WHERE product_id = '$product_id'";
    if (!$conn->query($query)) {
        echo "Delete error: " . $conn->error;
    }
}



// --- Example Usage (Simulated Form Handling) ---

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 'add_to_cart' is submitted
    if (isset($_POST["add_to_cart"])) {
        $product_id = $_POST["product_id"];
        $quantity = $_POST["quantity"];
        addToCart($conn, $product_id, $quantity);
    }

    // Check if 'remove_from_cart' is submitted
    if (isset($_POST["remove_from_cart"])) {
        $product_id = $_POST["product_id"];
        removeFromCart($conn, $product_id);
    }

    // Always refresh the cart contents
    getCartContents($conn);
}



// --- Display the Cart Form ---
?>
