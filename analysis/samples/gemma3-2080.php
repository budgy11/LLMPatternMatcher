

<?php

/**
 * User Review Function
 *
 * This function processes user reviews, saves them to a database,
 * and optionally displays a summary of the reviews.
 *
 * @param string $name The name of the user submitting the review.
 * @param string $rating A rating (e.g., 1-5).
 * @param string $comment The user's review text.
 * @param string $db_connection A database connection object (e.g., mysqli or PDO).
 * @param string $table_name The name of the table to store reviews in.
 *
 * @return bool True if the review was saved successfully, false otherwise.
 */
function saveUserReview(string $name, string $rating, string $comment, $db_connection, string $table_name) {
  // Validate input (basic example - you should add more robust validation)
  if (empty($name) || empty($rating) || empty($comment)) {
    error_log("Missing required data in review submission."); // Log the error
    return false;
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    error_log("Invalid rating provided: " . $rating);
    return false;
  }

  // Prepare the SQL query
  $sql = "INSERT INTO `$table_name` (name, rating, comment) 
          VALUES ('$name', '$rating', '$comment')";

  // Execute the query
  try {
    $stmt = $db_connection->prepare($sql);
    $stmt->execute();
    return true;
  } catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    return false;
  }
}

// Example Usage (This is just for demonstration)
// Assuming you have a database connection object $conn and a table named 'reviews'
// with columns: id (int, auto-increment), name (varchar), rating (int), comment (text)


// Example Database Connection (Replace with your actual connection)
//$conn = new mysqli("localhost", "username", "password", "database_name");
//$conn->query("SET NAMES utf8mb4"); // Important for proper character encoding

// $review_success = saveUserReview("John Doe", 4, "Great product!", $conn, "reviews");

// if ($review_success) {
//   echo "Review saved successfully!";
// } else {
//   echo "Failed to save review.";
// }


// Example:  Displaying reviews (This part is optional and depends on your needs)
/**
 *  Example function to fetch and display reviews from a database.
 *
 * @param string $db_connection A database connection object.
 * @param string $table_name The name of the table to fetch reviews from.
 * @param int $limit The number of reviews to display (optional).
 */
function displayReviews( $db_connection, string $table_name, $limit = 10) {
    $sql = "SELECT id, name, rating, comment FROM `$table_name` ORDER BY id DESC LIMIT $limit";

    try {
        $stmt = $db_connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            echo "<h2>User Reviews</h2>";
            echo "<ul>";
            foreach ($result as $review) {
                echo "<li><strong>Name:</strong> " . htmlspecialchars($review['name']) . "<br>";
                echo "   <strong>Rating:</strong> " . $review['rating'] . "<br>";
                echo "   <strong>Comment:</strong> " . htmlspecialchars($review['comment']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "No reviews found.";
        }
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
    }
}

?>
