

<?php

// Database connection (Replace with your actual database credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to add a product to the cart
function addToCart($conn, $product_id, $quantity) {
    $user_id = $_SESSION['user_id']; // Assuming you have user session

    // Check if the product is already in the cart
    $sql = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Product exists in cart, update quantity
        $sql = "UPDATE cart SET quantity = quantity + '$quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'";
    } else {
        // Product not in cart, add it
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')";
    }

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Function to get the cart contents
function getCartContents($conn) {
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT p.product_name, p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $cart_data = array();
        while($row = $result->fetch_assoc()) {
            $cart_data[] = $row;
        }
        return $cart_data;
    } else {
        return [];
    }
}

// Function to remove a product from the cart
function removeFromCart($conn, $product_id) {
    $user_id = $_SESSION['user_id'];
    $sql = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}


// Function to update the quantity of a product in the cart
function updateQuantity($conn, $product_id, $quantity) {
    $user_id = $_SESSION['user_id'];
    $sql = "UPDATE cart SET quantity = '$quantity' WHERE user_id = '$user_id' AND product_id = '$product_id'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}


// Example Usage (Illustrative - needs to be integrated into your website)

// 1. Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (addToCart($conn, $product_id, $quantity)) {
        echo "Product added to cart!";
    } else {
        echo "Error adding product to cart.";
    }
}

// 2. Get Cart Contents (e.g., for displaying the cart)
$cart_contents = getCartContents($conn);
// Now $cart_contents contains an array of product data from the cart

// 3. Remove Product from Cart (Example)
if (isset($_GET['remove_product'])) {
    $product_id = $_GET['remove_product'];
    if (removeFromCart($conn, $product_id)) {
        echo "Product removed from cart!";
    } else {
        echo "Error removing product from cart.";
    }
}

// 4. Update Quantity
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (updateQuantity($conn, $product_id, $quantity)) {
        echo "Quantity updated in cart!";
    } else {
        echo "Error updating quantity in cart.";
    }
}



// End of Example Usage

// Important Notes and Considerations:

// 1.  Database Design:
//     - `products` table: `product_id` (primary key), `product_name`, `price`, ...
//     - `cart` table: `cart_id` (primary key), `user_id` (foreign key referencing `users` table), `product_id` (foreign key referencing `products` table), `quantity`.
//     -  `users` table: `user_id` (primary key).

// 2. Error Handling: This example has basic error messages.  Robust error handling is *crucial* for production environments (e.g., logging errors, displaying user-friendly messages).

// 3. Security:
//    - **Input Validation and Sanitization:** *Absolutely essential*.  Never directly use user input in SQL queries. Use prepared statements or appropriate escaping functions to prevent SQL injection attacks.  Validate the `quantity` input to ensure it's a valid number.
//    - **CSRF Protection:** Implement CSRF (Cross-Site Request Forgery) protection to prevent malicious websites from triggering actions on your site.

// 4. User Session:  The code assumes you have a user session (`$_SESSION['user_id']`) to identify the user's cart.  You'll need to implement user authentication (login) and store the user ID in the session after a successful login.

// 5. Prepared Statements (Highly Recommended):  While this example uses simple string concatenation for SQL queries, *always* use prepared statements with placeholders to prevent SQL injection.

// 6.  Testing: Thoroughly test the purchase functionality under various scenarios (e.g., adding multiple items, updating quantities, removing items).

// 7.  Scalability: For larger applications, consider using a database connection pooling mechanism to improve performance.

// Example of using Prepared Statements (to illustrate the concept -  replace your database credentials and table names):

//  $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
//  $stmt->bind_param("sii", $user_id, $product_id, $quantity);
//  $stmt->execute();
//  $stmt->close();


<?php

// Database connection details (replace with your actual values)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_user";
$db_password = "your_password";

// --- Database Functions ---

// Connect to the database
function connectDB() {
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Add a product to the cart
function addToCart($product_id, $quantity, $user_id = NULL) {
    $conn = connectDB();
    $sql = "INSERT INTO cart (product_id, quantity, user_id) VALUES ('$product_id', '$quantity', '$user_id')";

    if ($conn->query($sql) === TRUE) {
        return TRUE;
    } else {
        return FALSE;
    }
}


// Get all items in the cart
function getCartItems($user_id = NULL) {
    $conn = connectDB();
    $sql = "SELECT * FROM cart WHERE user_id = '$user_id'"; //Use user_id if user is logged in

    if($user_id) {
      $sql = "SELECT * FROM cart WHERE user_id = '$user_id'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $items = array();
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    } else {
        return array(); // Return an empty array if the cart is empty
    }
}

// Remove a product from the cart
function removeFromCart($product_id, $user_id = NULL) {
    $conn = connectDB();
    $sql = "DELETE FROM cart WHERE product_id = '$product_id'"; // Use user_id if user is logged in
    if ($user_id) {
      $sql = "DELETE FROM cart WHERE product_id = '$product_id'";
    }

    if ($conn->query($sql) === TRUE) {
        return TRUE;
    } else {
        return FALSE;
    }
}

// Update the quantity of a product in the cart
function updateQuantity($product_id, $new_quantity, $user_id = NULL) {
    $conn = connectDB();
    $sql = "UPDATE cart SET quantity = '$new_quantity' WHERE product_id = '$product_id'"; // Use user_id if user is logged in
    if ($user_id) {
      $sql = "UPDATE cart SET quantity = '$new_quantity' WHERE product_id = '$product_id'";
    }

    if ($conn->query($sql) === TRUE) {
        return TRUE;
    } else {
        return FALSE;
    }
}



// --- Purchase Functionality (Simplified) ---

function purchase() {
    // This is a simplified purchase function.  In a real application,
    // you'd handle payment processing, order confirmation emails, etc.

    $cart_items = getCartItems();

    if (empty($cart_items)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<h2>Order Summary:</h2>";
    echo "<ul>";
    foreach ($cart_items as $item) {
        echo "<li>Product ID: " . $item['product_id'] . ", Quantity: " . $item['quantity'] . "</li>";
    }
    echo "</ul>";

    // In a real application, you would:
    // 1. Process payment (integrate with a payment gateway)
    // 2. Create an order record in the 'orders' table.
    // 3. Clear the cart (either by deleting all items or setting quantity to 0)
    // 4. Send confirmation emails to the customer.

    // Example of clearing the cart (you might want to use a more sophisticated approach)
    //  removeFromCart($product_id, $user_id);  //This clears all products in cart.
}



// --- Example Usage (for testing) ---

// You can call these functions from a web form to handle adding to cart,
// retrieving items, removing items, or processing the purchase.
//
// To test:
// 1. Create a database named 'shopping_cart'
// 2. Create tables:
//     - products (product_id, product_name, price)
//     - cart (product_id, quantity, user_id)
// 3. Insert some sample data into the tables.
// 4.  Call purchase() when the user clicks the purchase button.



//  Simulate adding a product to the cart (replace with your actual product_id)
//  addToCart(1, 2);
// purchase();

?>
