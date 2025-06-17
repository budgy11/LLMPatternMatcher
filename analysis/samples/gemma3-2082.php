

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product.
 * It includes basic validation and handles database interactions.
 *
 * @param string $product_id The ID of the product to review.
 * @param string $username The username of the user submitting the review.
 * @param string $rating  The rating (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @param string $db_host The database host.
 * @param string $db_name The database name.
 * @param string $db_user The database username.
 * @param string $db_password The database password.
 *
 * @return bool True if the review was successfully submitted, false otherwise.
 */
function submitUserReview(
    $product_id,
    $username,
    $rating,
    $comment,
    $db_host,
    $db_name,
    $db_user,
    $db_password
) {
    // Validate inputs (Add more sophisticated validation as needed)
    if (empty($product_id) || empty($username) || empty($rating) || empty($comment)) {
        error_log("Missing required fields for review submission."); // Log for debugging
        return false;
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        error_log("Invalid rating value.");
        return false;
    }

    // Prepare the SQL query
    $sql = "INSERT INTO reviews (product_id, username, rating, comment)
            VALUES ('$product_id', '$username', '$rating', '$comment')";

    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error); // Log the error
        return false;
    }

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        error_log("Query failed: " . $conn->error); // Log the error
        return false;
    }

    // Close the connection
    $conn->close();
}



/**
 * Display User Reviews
 *
 * This function retrieves and displays user reviews for a given product.
 *
 * @param string $product_id The ID of the product.
 * @param string $db_host The database host.
 * @param string $db_name The database name.
 * @param string $db_user The database username.
 * @param string $db_password The database password.
 */
function displayUserReviews(
    $product_id,
    $db_host,
    $db_name,
    $db_user,
    $db_password
) {
    // Prepare the SQL query
    $sql = "SELECT * FROM reviews WHERE product_id = '$product_id'";

    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        echo "<p>Error: Unable to connect to the database.</p>";
        return;
    }

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>User Reviews for Product ID: " . $product_id . "</h2>";
        while ($row = $result->fetch_assoc()) {
            echo "<div class='review'>";
            echo "<strong>Username:</strong> " . $row["username"] . "<br>";
            echo "<strong>Rating:</strong> " . $row["rating"] . "<br>";
            echo "<strong>Comment:</strong> " . $row["comment"] . "<br>";
            echo "</div>";
        }
    } else {
        echo "<p>No reviews found for this product.</p>";
    }

    // Close the connection
    $conn->close();
}


// Example Usage (Simulate a database setup and some reviews)

// Create a dummy database and table if it doesn't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS my_reviews_db";
$conn = new mysqli("localhost", "root", "", "my_reviews_db");
if ($conn) {
    $conn->query($sql_create_db);
    $conn->close();
}


$sql_create_table = "CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    rating INT NOT NULL,
    comment TEXT
)";

$conn = new mysqli("localhost", "root", "", "my_reviews_db");
if ($conn) {
    $conn->query($sql_create_table);
    $conn->close();
}



// Example Review Submission
$product_id = "123";
$username = "JohnDoe";
$rating = 4;
$comment = "Great product!  Easy to use.";
$success = submitUserReview($product_id, $username, $rating, $comment, "localhost", "my_reviews_db", "root", "");

if ($success) {
    echo "<p>Review submitted successfully!</p>";
} else {
    echo "<p>Error submitting review.</p>";
}


// Example Review Display (After submitting a review)
displayUserReviews($product_id, "localhost", "my_reviews_db", "root", "");


?>
