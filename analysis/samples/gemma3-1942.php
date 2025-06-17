

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or service.
 *
 * @param string $productId  The ID of the product or service being reviewed.
 * @param string $username   The username of the reviewer.
 * @param string $rating     The rating given by the reviewer (e.g., 1-5).
 * @param string $comment    The reviewer's comment/feedback.
 * @param array $dbConnection  A database connection object (e.g., mysqli).
 *
 * @return array|string  Returns an array on success, or an error message string on failure.
 */
function create_user_review(
    string $productId,
    string $username,
    string $rating,
    string $comment,
    $dbConnection
) {
    // Validate input (important for security and data integrity)
    if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
        return "Error: All fields are required.";
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return "Error: Rating must be a number between 1 and 5.";
    }

    // Sanitize input to prevent SQL injection
    $productId = mysqli_real_escape_string($dbConnection, $productId);
    $username = mysqli_real_escape_string($dbConnection, $username);
    $rating = mysqli_real_escape_string($dbConnection, $rating);
    $comment = mysqli_real_escape_string($dbConnection, $comment);

    // SQL query -  IMPORTANT: Use prepared statements for real applications!
    $sql = "INSERT INTO reviews (product_id, username, rating, comment) 
            VALUES ('$productId', '$username', '$rating', '$comment')";

    if (mysqli_query($dbConnection, $sql)) {
        return "Review created successfully!";
    } else {
        return "Error creating review: " . mysqli_error($dbConnection);
    }
}

/**
 * Function to display all reviews for a product.
 *
 * @param array $dbConnection  A database connection object.
 * @param string $productId  The ID of the product.
 *
 * @return array|string  Returns an array of reviews, or an error message.
 */
function display_reviews(string $productId, $dbConnection) {
    $sql = "SELECT * FROM reviews WHERE product_id = '$productId'";

    $result = mysqli_query($dbConnection, $sql);

    if ($result) {
        $reviews = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }
        return $reviews;
    } else {
        return "Error: Unable to retrieve reviews: " . mysqli_error($dbConnection);
    }
}



// Example Usage (This part is just for demonstration - you'll need to adapt to your database setup)

// Assuming you have a database connection object named $conn

// Create a Review
//$review_result = create_user_review("123", "JohnDoe", "5", "Excellent product!", $conn);
//echo $review_result; // Output: Review created successfully!

// Get all reviews for product 123
//$reviews = display_reviews("123", $conn);

//if (is_array($reviews)) {
//   echo "<h2>Reviews for Product 123:</h2>";
//   foreach ($reviews as $review) {
//       echo "<p><strong>Username:</strong> " . $review['username'] . "</p>";
//       echo "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
//       echo "<p><strong>Comment:</strong> " . $review['comment'] . "</p>";
//       echo "<hr>";
//   }
//} else {
//   echo $reviews; // Output the error message if retrieval failed
//}


// IMPORTANT NOTES:

// 1. Database Setup: This code assumes you have a database set up with a table named "reviews" 
//    with columns: product_id (INT), username (VARCHAR), rating (INT), comment (TEXT).

// 2. Prepared Statements:  The `create_user_review` function uses simple string concatenation to build the SQL query.  **THIS IS VULNERABLE TO SQL INJECTION ATTACKS.**  For production code, ALWAYS use prepared statements to prevent this. Prepared statements separate the SQL code from the data, preventing malicious data from being interpreted as SQL commands.

// Example of using prepared statements (using mysqli):

//    $stmt = mysqli_prepare($dbConnection, "INSERT INTO reviews (product_id, username, rating, comment) VALUES (?, ?, ?, ?)");
//    mysqli_stmt_bind_param($stmt, "s", $productId, $username, $rating, $comment);  // 's' indicates a string parameter
//    mysqli_stmt_execute($stmt);
//    mysqli_stmt_close($stmt);

// 3. Error Handling: This code includes basic error handling, but you should implement more robust error logging and handling in a production environment.

// 4. Input Validation: The code validates the rating, but you should add more comprehensive input validation to prevent invalid data from being stored.  Consider using a validation library.

// 5. Security:  Always sanitize and validate user input to prevent security vulnerabilities.  Prepared statements are essential.

// 6.  Data Types: Adjust data types in your SQL table schema to match your requirements.  For example, you might use `INT` for `product_id`, `VARCHAR` for `username`, `FLOAT` or `DECIMAL` for `rating` (if you need decimal precision), and `TEXT` for `comment`.

?>
