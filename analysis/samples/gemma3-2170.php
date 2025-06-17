

<?php

/**
 * User Review Function
 *
 * This function allows you to create and store user reviews.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating The rating (e.g., 1-5).
 * @param string $comment The user's review comment.
 * @return bool True if the review was created successfully, false otherwise.
 */
function createReview(string $productId, string $username, string $rating, string $comment)
{
  // Validate inputs - important for security and data integrity
  if (empty($productId)) {
    error_log("Error: Product ID cannot be empty."); // Log for debugging
    return false;
  }
  if (empty($username)) {
    error_log("Error: Username cannot be empty.");
    return false;
  }
  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    error_log("Error: Rating must be a number between 1 and 5.");
    return false;
  }
  if (empty($comment)) {
    error_log("Error: Comment cannot be empty.");
    return false;
  }

  // Database connection (replace with your actual database details)
  $servername = "localhost";
  $username = "your_db_username";
  $password = "your_db_password";
  $dbname = "your_db_name";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    return false;
  }

  // Prepare SQL statement
  $sql = "INSERT INTO reviews (product_id, username, rating, comment) 
          VALUES ('$productId', '$username', '$rating', '$comment')";

  // Execute the SQL statement
  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    error_log("Error inserting review: " . $conn->error); // Log the specific error
    return false;
  }

  // Close the connection
  $conn->close();
}


// Example Usage:
// Assuming you have a product_id of 123 and a user 'JohnDoe'
// $success = createReview("123", "JohnDoe", 4, "Great product, highly recommended!");

// if ($success) {
//   echo "Review created successfully!";
// } else {
//   echo "Failed to create review.";
// }



//  --- Example Table Structure (SQL) ---
/*
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  username VARCHAR(255) NOT NULL,
  rating INT NOT NULL,
  comment TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/

?>
