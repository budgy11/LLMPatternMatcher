


Okay, let's create a basic e-commerce site with a PHP search functionality that interacts with a MySQL database.  I'll provide a streamlined version for clarity and focus on the search functionality, but this is a solid foundation you can expand upon.

**Website Structure (Conceptual)**

*   **`index.php`**:  Main homepage with product listings and search bar.
*   **`search.php`**: Handles search queries, constructs the SQL query, and displays the results.
*   **`products.php`**: (Optional, but recommended)  Displays products. Can be called from `index.php`.
*   **`details.php`**: (Optional) Displays a single product's details.
*   **`database.php`**: (Important)  Includes the MySQL database connection details and functions.

**1. `database.php` (Database Connection)**

```php
<?php

$servername = "localhost";
$username = "your_username";
$password = [0;31m"your_password"[0m; //OWASP A7
$dbname = "your_ecommerce_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to execute queries
function executeQuery($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) {
        echo "Error executing query: " . $conn->error;
        return false;
    }
    return $result;
}

?>
```
#####
Alerts:
Variable may contain secrets that should not be stored in code
#####

**2. `products.php` (Product Listing - Example)**

```php
<?php
require_once 'database.php'; // Include the database connection

$sql = "SELECT * FROM products";
$result = executeQuery($conn, $sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        [0;31mecho "<td>" . $row["product_id"] . "</td>";[0m //OWASP A3
        [0;31mecho "<td>" . $row["product_name"] . "</td>";[0m //OWASP A3
        [0;31mecho "<td>" . $row["category"] . "</td>";[0m //OWASP A3
        [0;31mecho "<td>" . $row["price"] . "</td>";[0m //OWASP A3
        echo "</tr>";
    }
} else {
    echo "No products found.";
}

$conn->close();
?>
```
#####
Alerts:
Echo may lead to XSS if passed unsanitized input
#####

**3. `index.php` (Homepage with Search)**

```php
<?php
require_once 'database.php';
require_once 'products.php'; //Include products.php

// Handle Search Query
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];

    $sql = "SELECT * FROM products [0;31mWHERE product_name LIKE '%" . $searchTerm . "%' OR category LIKE '%" . $searchTerm .[0m "%'"; //OWASP A3
    $result = executeQuery($conn, $sql);

    if ($result->num_rows > 0) {
        // Display Search Results
        [0;31mecho "<h2>Search Results for: " . $searchTerm . "</h2>";[0m //OWASP A3
        //  Display search results here - similar logic as in products.php
    } else {
        echo "<p>No products found matching your search.</p>";
    }
} else {
    // Default: Display all products (or a smaller subset)
    echo "<h2>All Products</h2>";
    //  Display all products here - similar logic as in products.php
}

$conn->close();
?>

<form action="index.php" method="get">
    <input type="text" name="search" placeholder="Search products...">
    <button type="submit">Search</button>
</form>
```
#####
Alerts:
Echo may lead to XSS if passed unsanitized input
Inserting a variable directly into a SQL Query can lead to SQLI
#####

**4. `search.php` (Complete Search Functionality - No Changes Needed)**

The `search.php` file is actually integrated into `index.php` in the example above.  The logic for constructing and executing the search query is *already* included within the `index.php` file when the `search` parameter is present in the URL.  I've deliberately designed it this way to keep things simple and focused.  If you wanted to create a separate `search.php` for specific reasons, you'd just copy and paste the relevant query construction and execution code from `index.php` into it.

**Important Considerations and Explanations:**

*   **Security:**  This is a *very* basic example.  **Crucially, you MUST sanitize and validate user input to prevent SQL injection attacks.** The `LIKE '%...'` operator can be vulnerable.  Use prepared statements (using `mysqli_stmt`) to prevent this.
*   **Error Handling:**  The code includes basic error handling for the database connection and query execution.  Expand this for a production environment.
*   **Prepared Statements (Essential for Security):**
```php
    // Example using prepared statements (stronger security)
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_name LIKE ? OR category LIKE ?");
    $searchTerm = "%" . $searchTerm . "%"; // Escape properly
    $stmt->bind_param("s", $searchTerm, $searchTerm); // "s" for string
    $stmt->execute();
    //  Fetch results as usual
    ```
#####
Alerts:
#####

