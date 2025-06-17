

<?php

/**
 *  User Review Function
 *
 *  This function allows you to store and display user reviews for a product or item.
 *
 *  @param string $product_id  The unique identifier for the product.
 *  @param string $user_name   The name of the user submitting the review.
 *  @param string $review_text The text content of the review.
 *  @param int   $rating      The rating given by the user (e.g., 1-5).
 *  @param string $connection  (Optional) Database connection object. If not provided, 
 *                           the function will attempt to create one.
 *
 *  @return bool True on successful insertion, false on failure.
 */
function create_user_review(string $product_id, string $user_name, string $review_text, int $rating, $connection = null) {
    // Create database connection if none is provided
    if ($connection === null) {
        //  Replace with your database connection details
        $db_host = 'localhost';
        $db_name = 'your_database_name';
        $db_user = 'your_database_user';
        $db_password = 'your_database_password';

        $connection = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
    }

    // Sanitize inputs to prevent SQL injection
    $product_id = $connection->real_escape_string($product_id);
    $user_name = $connection->real_escape_string($user_name);
    $review_text = $connection->real_escape_string($review_text);
    $rating = $connection->real_escape_string($rating);


    // SQL query
    $query = "INSERT INTO reviews (product_id, user_name, review_text, rating) VALUES 
              ('$product_id', '$user_name', '$review_text', '$rating')";

    if ($connection->query($query) === TRUE) {
        return true;
    } else {
        echo "Error: " . $query . "<br>" . $connection->error;  // Log or display the error
        return false;
    }
}


/**
 * Retrieves reviews for a product.
 *
 * @param string $product_id The product ID.
 * @param $connection (Optional) Database connection object.
 *
 * @return array An array of review objects, or an empty array if no reviews are found.
 */
function get_product_reviews(string $product_id, $connection = null) {
    if ($connection === null) {
        //  Replace with your database connection details
        $db_host = 'localhost';
        $db_name = 'your_database_name';
        $db_user = 'your_database_user';
        $db_password = 'your_database_password';

        $connection = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
    }

    $query = "SELECT * FROM reviews WHERE product_id = '$product_id'";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        $reviews = [];
        while($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        return $reviews;
    } else {
        return []; // Return an empty array if no reviews are found
    }
}


/**
 * Displays a single review
 * @param array $review
 */
function display_review(array $review) {
    echo "<b>User:</b> " . $review['user_name'] . "<br>";
    echo "<b>Rating:</b> " . $review['rating'] . " / 5<br>";
    echo "<b>Review:</b> " . $review['review_text'] . "<br><br>";
}


// --- Example Usage (Simulated Data for demonstration) ---
// In a real application, these reviews would come from a form submission.

// Simulate a database connection (for demonstration only)
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_database_user';
$db_password = 'your_database_password';
$connection = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}



// Create some sample reviews
$review1 = create_user_review('product_123', 'John Doe', 'Great product! Highly recommended.', 5, $connection);
$review2 = create_user_review('product_123', 'Jane Smith', 'Could be better, but overall good.', 4, $connection);
$review3 = create_user_review('product_456', 'Peter Jones', 'This is amazing!', 5, $connection);



// Get reviews for product_123
$reviews = get_product_reviews('product_123', $connection);

// Display the reviews
echo "<h2>Reviews for Product 123:</h2>";
if (!empty($reviews)) {
    foreach ($reviews as $review) {
        display_review($review);
    }
} else {
    echo "<p>No reviews found for this product.</p>";
}


// Close the database connection
$connection->close();

?>
