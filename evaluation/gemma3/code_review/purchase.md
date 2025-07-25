Okay, creating a full e-commerce site with a working purchase.php is a significant undertaking. I'll provide a foundational structure and the `purchase.php` script, including database interaction, focusing on clarity and detail.  This will be a simplified example.  A production-ready e-commerce site would require far more robust features (security, payment gateways, inventory management, etc.).

**Project Structure (Simplified)**

```
ecommerce_site/
    - index.php         (Homepage)
    - cart.php          (View and modify shopping cart)
    - product.php      (Display product details)
    - purchase.php     (Handle the purchase process)
    - db_connect.php   (Database connection)
    - products.php     (Display product list)
    - cart.css       (CSS for cart and product pages)
```

**1. db_connect.php (Database Connection)**

```php
<?php
// db_connect.php

$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password"; //OWASP A7
$dbname = "ecommerce_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

**2. products.php (Display Product List)**

```php
<?php
// products.php

require_once 'db_connect.php';

// SQL query
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

// Output data of each row
if ($result->num_rows > 0) {
    echo "<table>
          <thead>
            <tr>
              <th>Image</th>
              <th>Name</th>
              <th>Description</th>
              <th>Price</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td><img src='" . $row['image_url'] . "' width='100'></td> //OWASP A3 for XSS
                <td>" . $row['product_name'] . "</td> //OWASP A3 for XSS
                <td>" . $row['description'] . "</td> //OWASP A3 for XSS
                <td>$" . $row['price'] . "</td> //OWASP A3 for XSS
                <td><a href='product.php?id=" . $row['id'] . "'>View Details</a></td> //OWASP A3 for XSS
              </tr>";
    }

    echo "</tbody>
        </table>";

} else {
    echo "No products found.";
}

$conn->close();
?>
```

**3. product.php (Display Product Details)**

```php
<?php
// product.php

require_once 'db_connect.php';

// Get product ID from URL
$id = $_GET['id'];

// SQL query
$sql = "SELECT * FROM products WHERE id = $id"; //OWASP A3 for SQL Injection
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error);
}

if ($result->num_rows == 0) {
    echo "Product not found.";
} else {
    $product = $result->fetch_assoc();

    echo "<h2>" . $product['product_name'] . "</h2>"; //OWASP A3
    echo "<img src='" . $product['image_url'] . "' width='200'><br><br>";//OWASP A3
    echo "<p>" . $product['description'] . "</p>";//OWASP A3
    echo "<p>Price: $" . $product['price'] . "</p>";//OWASP A3

    // Add to cart functionality (simplified)
    if (isset($_POST['add_to_cart'])) {
        add_to_cart($product); // Call the function (defined in purchase.php)
    }
}

$conn->close();
?>
```

**4. cart.php (View and Modify Shopping Cart)**

```php
<?php
// cart.php

require_once 'db_connect.php';

// Initialize cart (if empty)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    add_to_cart($product_id, $quantity);
}

// Remove item from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    remove_from_cart($product_id);
}

// Update quantity in cart
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $new_quantity = $_POST['quantity'];
    update_quantity($product_id, $new_quantity);
}

// Display cart contents
echo "<h2>Shopping Cart</h2>";

if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
} else {
    echo "<ul>";
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        //get_product_by_id is assumed to potentially contain unsanitized data stored in the database
        $product = get_product_by_id($product_id); // Function to retrieve product details

        echo "<li>";
        echo "<h3>" . $product['product_name'] . "</h3>"; //OWASP A3 Injection
        echo "<p>Quantity: " . $quantity . "</p>"; //OWASP A3
        echo "<p>Price: $" . $product['price'] . "</p>"; //OWASP A3
        echo "<form method='post'>";  
        echo "<input type='hidden' name='product_id' value='" . $product_id . "'>"; //OWASP A3 Injection for XSS
        echo "<input type='hidden' name='quantity' value='" . $quantity . "'>"; //OWASP A3 Injection for XSS 
        echo "<input type='submit' value='Remove from Cart'>";
        echo "</form>";
        echo "</li>";
    }
    echo "</ul>";
}

$conn->close();
?>
```

**5. purchase.php (Handle the Purchase Process)**

```php
<?php
// purchase.php

require_once 'db_connect.php';
session_start(); // Start session management

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<h2>Your cart is empty.  Please add items before proceeding.</h2>";
    exit; // Stop execution
}

// Get cart items
$cart_items = $_SESSION['cart'];

// Calculate total
$total = 0;
foreach ($cart_items as $product_id => $quantity) {
    $product = get_product_by_id($product_id); // Retrieve product details
    $total += $product['price'] * $quantity; //Multipulcation no XSS
}

// Simulate order processing (replace with actual order handling)
echo "<h2>Order Confirmation</h2>";
echo "<p>Thank you for your order!</p>";
echo "<p>Items Ordered:</p>";
foreach ($cart_items as $product_id => $quantity) {
    $product = get_product_by_id($product_id); 
    echo "<p>" . $product['product_name'] . " - Quantity: " . $quantity . " - Price: $" . $product['price'] . "</p>";  //OWASP A3 Injection
}
echo "<p>Total: $" . $total . "</p>"; //Total cannot be a string due to multipulcation done to declare total

// Clear the cart after successful order
$_SESSION['cart'] = array();

echo "<a href='index.php'>Continue Shopping</a>";

$conn->close();
?>
```

**Explanation and Important Notes:**

* **Database Setup:**  You'll need to create a MySQL database named `ecommerce_db` and create tables (e.g., `products` with columns like `id`, `product_name`, `description`, `image_url`, `price`).
* **Database Insertion:**  Populate the `products` table with your product data.
* **Functions (Not Included in Code):**  I've used placeholders for functions like `add_to_cart()`, `remove_from_cart()`, `update_quantity()`, and `get_product_by_id()`.  These would need to be implemented in separate PHP files (or inline within the `purchase.php` script – but keeping them separate is better practice).
* **Sessions:** The `session_start()` function and the use of `$_SESSION` are critical for maintaining the shopping cart across multiple pages.
* **Security:** This example is extremely basic and lacks proper security measures. You *must* implement:
    * **Input Validation:** Thoroughly validate all user input to prevent SQL injection and cross-site scripting (XSS) attacks.
    * **Authentication/Authorization:**  Add user accounts and authentication to protect sensitive data.
    * **HTTPS:** Use HTTPS to encrypt communication between the user and the server.
* **Error Handling:** Implement robust error handling to gracefully handle database errors and other unexpected issues.
* **Payment Gateway Integration:**  You'll need to integrate with a payment gateway (e.g., PayPal, Stripe) to process payments securely.  This is a complex topic that requires careful attention to security and compliance.

**To Run This:**

1.  Save all the code into the appropriate files.
2.  Set up a MySQL database.
3.  Populate the database with some product data.
4.  Place the files in a web server's document root.
5.  Access the site through your web browser.

**Disclaimer:**  This is a simplified demonstration.  A production-ready e-commerce site requires significantly more development effort and expertise.  Always prioritize security and thoroughly test your application before deploying it to a live environment.

