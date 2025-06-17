    <br><br>

    <button type="submit">Place Order</button>
  </form>

</body>
</html>


<?php

// Database Connection (Replace with your actual credentials)
$dbHost = "localhost";
$dbUser = "your_username";
$dbPass = "your_password";
$dbName = "your_database_name";

// Database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Function to handle adding a cart item
function addCartItem($conn, $userId, $productId, $quantity) {
    // Sanitize input - ALWAYS do this!
    $productId = mysqli_real_escape_string($conn, $productId);
    $quantity = mysqli_real_escape_string($conn, intval($quantity)); // Ensure quantity is an integer

    // Check if the product already exists in the cart for this user
    $query = "SELECT * FROM cart WHERE user_id = '$userId' AND product_id = '$productId'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Product already in cart - Update the quantity
        $query = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$userId' AND product_id = '$productId'";
        if (!$conn->query($query)) {
            echo "Error updating cart: " . $conn->error;
        }
    } else {
        // Product not in cart - Add a new entry
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$userId', '$productId', $quantity)";
        if (!$conn->query($query)) {
            echo "Error adding to cart: " . $conn->error;
        }
    }
}


// Function to handle removing a cart item
function removeCartItem($conn, $userId, $productId) {
    // Sanitize input
    $productId = mysqli_real_escape_string($conn, $productId);

    // Delete the cart entry
    $query = "DELETE FROM cart WHERE user_id = '$userId' AND product_id = '$productId'";

    if (!$conn->query($query)) {
        echo "Error deleting from cart: " . $conn->error;
    }
}


// Function to update cart quantity
function updateCartQuantity($conn, $userId, $productId, $newQuantity) {
    //Sanitize input
    $productId = mysqli_real_escape_string($conn, $productId);
    $newQuantity = mysqli_real_escape_string($conn, intval($newQuantity));

    //Update quantity
    $query = "UPDATE cart SET quantity = $newQuantity WHERE user_id = '$userId' AND product_id = '$productId'";

    if (!$conn->query($query)) {
        echo "Error updating quantity: " . $conn->error;
    }
}

// --- Purchase Handling (Simple Example) ---

// Assume a 'purchases' table exists:  `purchases (purchase_id, user_id, purchase_date)`
// And a 'purchase_items' table: `purchase_items (purchase_id, product_id, quantity)`

function completePurchase($conn, $userId, $productIds, $quantities) {
    // Generate a purchase ID (simplified for demonstration)
    $purchaseId = time();  //Using the timestamp as a unique ID.  In a real application, use a sequence.

    // Generate the purchase items
    $productItemStrings = [];
    foreach($productIds as $productId => $quantity){
        $productItemStrings[] = "($purchaseId, $productId, $quantity)";
    }

    $productItemStrings = implode(',', $productItemStrings);

    // Insert purchase record
    $queryPurchase = "INSERT INTO purchases (user_id, purchase_date) VALUES ('$userId', NOW())";
    if (!$conn->query($queryPurchase)) {
        echo "Error inserting purchase: " . $conn->error;
        return false;
    }

    // Insert purchase items
    $queryItems = "INSERT INTO purchase_items (purchase_id, product_id, quantity) VALUES $productItemStrings";
    if (!$conn->query($queryItems)) {
        echo "Error inserting purchase items: " . $conn->error;
        return false;
    }

    return true; // Purchase completed successfully
}

?>
