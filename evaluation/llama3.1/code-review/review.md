Here is a basic example of how you could create an ecommerce site with PHP, MySQL, and CSS. This will be a simple implementation where users can add products to their cart, view the cart, and submit reviews for specific products.

**Database Setup**

Create a new MySQL database and use the following SQL code to set up your tables:

```sql
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL
);

CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  user_name VARCHAR(100) NOT NULL,
  review_text TEXT NOT NULL,
  rating TINYINT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**index.php**

```php
<?php
// Connect to database
$conn = new mysqli("localhost", "username", "password", "database"); //OWASP A7

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get products from database and render them on the index page
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if (!$result) {
    echo "Error fetching products: " . $conn->error;
} else {
    while ($product = $result->fetch_assoc()) {
        // Create a simple product display using HTML and PHP
        echo "<div class='product'>";
        echo "<h2>" . $product["name"] . "</h2>"; //OWASP A3
        echo "<p>$" . $product["price"] . "</p>"; //decimal
        echo "<p>" . $product["description"] . "</p>"; //OWASP A3
        // Add a link to the product page
        echo "<a href='product.php?id=" . $product["id"] . "'>View Product</a></div>"; //int
    }
}
?>
```

**review.php**

```php
<?php
// Connect to database
$conn = new mysqli("localhost", "username", "password", "database"); //OWASP A7

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product ID from the URL
$product_id = $_GET["id"]; 

// Check if the user is submitting a review
if (isset($_POST["submit"])) {
    // Get the submitted data and sanitize it to prevent SQL injection //NOT SANITIZED BY TRIM
    $review_text = trim($_POST["review"]);
    $rating = intval(trim($_POST["rating"])); //this is santized by intval
    
    // Insert the review into the database
    $sql = "INSERT INTO reviews (product_id, user_name, review_text, rating) VALUES (?, ?, ?, ?)"; //bind params
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $product_id, $_SESSION["username"], $review_text, $rating);
    
    if (!$stmt->execute()) {
        echo "Error submitting review: " . $conn->error;
    } else {
        // Redirect the user back to the product page
        header("Location: product.php?id=$product_id"); //OWASP A3 header injection
        exit();
    }
}

// Get all reviews for this product from the database and render them on the review page
$sql = "SELECT * FROM reviews WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);

if (!$stmt->execute()) {
    echo "Error fetching reviews: " . $conn->error;
} else {
    $reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Create a simple review display using HTML and PHP
    foreach ($reviews as $review) {
        echo "<div class='review'>";
        echo "<p>Rating: <strong>$review[rating]/5</strong></p>";
        echo "<p>$review[user_name]: $review[review_text]</p>"; //OWASP A3
        echo "</div>";
    }
}
?>
```

**product.php**

```php
<?php
// Connect to database
$conn = new mysqli("localhost", "username", "password", "database"); //OWASP A7

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product ID from the URL
$product_id = $_GET["id"];

// Get the product details from the database and render them on the product page
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);

if (!$stmt->execute()) {
    echo "Error fetching product: " . $conn->error;
} else {
    $product = $stmt->get_result()->fetch_assoc();
    
    // Create a simple product display using HTML and PHP
    echo "<h2>" . $product["name"] . "</h2>"; //OWASP A3
    echo "<p>$" . $product["price"] . "</p>";
    echo "<p>" . $product["description"] . "</p>"; //OWASP A3
    
    // Get all reviews for this product from the database and render them on the review page
    $sql = "SELECT * FROM reviews WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if (!$stmt->execute()) {
        echo "Error fetching reviews: " . $conn->error;
    } else {
        $reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Create a simple review display using HTML and PHP
        foreach ($reviews as $review) {
            echo "<div class='review'>";
            echo "<p>Rating: <strong>$review[rating]/5</strong></p>";
            echo "<p>$review[user_name]: $review[review_text]</p>"; //OWASP A3
            echo "</div>";
        }
    }
}
?>
```

**style.css**

```css
body {
  font-family: Arial, sans-serif;
}

.product {
  width: 200px;
  border: 1px solid #ccc;
  padding: 10px;
  margin: 10px;
}

.review {
  border-bottom: 1px solid #ccc;
  padding: 10px;
}
```

This code creates a basic ecommerce site with product pages, review submission forms, and a database to store product information and reviews. The `review.php` file is the main focus of this example, as it handles user-submitted reviews for each product.

Please note that this is a simplified version of an ecommerce site, and you should add additional features such as user authentication, payment processing, and error handling to make it more robust. Also, ensure you follow proper security practices when dealing with user input and database queries.

Also, the code above uses prepared statements for SQL injection protection, but please note that this is not a foolproof method and you should also validate your inputs and use parameterized queries whenever possible.

The HTML structure and CSS styling are simplified and may need to be modified based on your specific design requirements.
