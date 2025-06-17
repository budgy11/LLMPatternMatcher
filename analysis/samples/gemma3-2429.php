

<?php

/**
 *  User Review Function
 *
 *  This function allows you to store and display user reviews for a given item.
 *
 *  @param string $item_id - The unique identifier for the item being reviewed.
 *  @param string $user_name - The name of the user submitting the review.
 *  @param string $review_text - The text of the review.
 *  @param int $rating - The rating given by the user (e.g., 1-5).
 *  @param string $db_connection -  A database connection object (e.g., mysqli or PDO).
 *
 *  @return void
 */
function storeUserReview(string $item_id, string $user_name, string $review_text, int $rating, $db_connection)
{
    // Sanitize and validate inputs (VERY IMPORTANT)
    $item_id = mysqli_real_escape_string($db_connection, $item_id); // or your appropriate escape function
    $user_name = mysqli_real_escape_string($db_connection, $user_name);
    $review_text = mysqli_real_escape_string($db_connection, $review_text);
    $rating = (int)$rating;  // Cast to integer for database storage

    // Ensure rating is within valid range (1-5 in this example)
    if ($rating < 1 || $rating > 5) {
        // Handle invalid rating (e.g., display error, log error, default to 1)
        error_log("Invalid rating provided: " . $rating);
        $rating = 1; // Default to 1 if invalid
    }


    // SQL Query - Use parameterized queries to prevent SQL injection
    $sql = "INSERT INTO reviews (item_id, user_name, review_text, rating)
            VALUES ('$item_id', '$user_name', '$review_text', $rating)";

    if (mysqli_query($db_connection, $sql)) {
        // Review successfully added
        echo "Review submitted successfully!
";
    } else {
        // Handle error (e.g., log the error, display an error message)
        error_log("Error adding review: " . mysqli_error($db_connection));
        echo "Error adding review. Please try again later.
";
    }
}



// Example Usage (assuming you have a database connection)
// In a real application, you'd get these values from a form
// $item_id = 'product123';
// $user_name = 'John Doe';
// $review_text = 'This product is amazing!';
// $rating = 5;

// //  Establish database connection (replace with your database credentials)
// $db_connection = mysqli_connect("localhost", "your_username", "your_password", "your_database");

// // Check connection
// if (mysqli_connect_errno($db_connection)) {
//     echo "Failed to connect to MySQL: " . mysqli_connect_error($db_connection);
// } else {
//     storeUserReview('product123', 'John Doe', 'This product is amazing!', 5, $db_connection);
// }
?>
