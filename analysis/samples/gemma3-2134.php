

<?php

/**
 * User Review Function
 * 
 * This function allows you to store and display user reviews for a given item.
 * It includes basic input validation and error handling.
 * 
 * @param string $item_id The unique identifier for the item being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * @param string $comment  The user's review comment.
 * @param array $db_connection A database connection object (e.g., mysqli).
 * 
 * @return bool True if the review was successfully added, false otherwise.
 */
function add_user_review(string $item_id, string $username, string $rating, string $comment, array $db_connection) {
  // Input Validation - Basic example, expand as needed
  if (empty($item_id) || empty($username) || empty($rating) || empty($comment)) {
    error_log("Missing required fields in user review."); // Log for debugging
    return false;
  }

  if (!preg_match('/^[0-5][0-9]*$/', $rating)) {
    error_log("Invalid rating format. Rating must be between 1 and 5.");
    return false;
  }

  // Sanitize Input - Important for security
  $item_id = filter_var($item_id, FILTER_SANITIZE_STRING);
  $username = filter_var($username, FILTER_SANITIZE_STRING);
  $rating = (int) filter_var($rating, FILTER_SANITIZE_NUMBER_INT);  // Convert to integer
  $comment = filter_var($comment, FILTER_SANITIZE_STRING);

  // Prepare the SQL query - Use prepared statements to prevent SQL injection
  $sql = "INSERT INTO reviews (item_id, username, rating, comment) 
          VALUES (?, ?, ?, ?)";

  $stmt = $db_connection->prepare($sql);

  if ($stmt === false) {
    error_log("Error preparing SQL statement: " . $db_connection->error); // Log the error
    return false;
  }

  // Bind parameters
  $stmt->bind_param("ssii", $item_id, $username, $rating, $comment);

  // Execute the query
  if (!$stmt->execute()) {
    error_log("Error executing SQL query: " . $stmt->error); // Log the error
    $stmt->close();
    return false;
  }

  // Close the statement
  $stmt->close();

  return true;
}

/**
 *  Example function to display reviews for a given item ID
 *  This is just a conceptual example, you'll need to adapt it to your specific database schema and presentation layer.
 *
 * @param string $item_id The ID of the item to retrieve reviews for
 * @param array $db_connection  Your database connection object
 * @return array An array of review objects, or an empty array if no reviews are found.
 */
function get_reviews_for_item(string $item_id, array $db_connection) {
    $sql = "SELECT * FROM reviews WHERE item_id = ?";
    $stmt = $db_connection->prepare($sql);

    if ($stmt === false) {
        error_log("Error preparing SQL statement: " . $db_connection->error);
        return [];
    }

    $stmt->bind_param("s", $item_id);

    $stmt->execute();

    if ($stmt === false) {
        error_log("Error executing SQL query: " . $stmt->error);
        return [];
    }

    $result = $stmt->get_result();

    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    $stmt->close();
    return $reviews;
}


// Example Usage (Illustrative - requires a database connection setup)
//  Remember to replace with your actual database connection details!

// Sample database connection (Replace with your actual connection)
//$db_connection = new mysqli("localhost", "your_username", "your_password", "your_database");

//if ($db_connection->connect_error) {
//  die("Connection failed: " . $db_connection->connect_error);
//}


// Add a review
//$item_id = "product123";
//$username = "john.doe";
//$rating = 3;
//$comment = "Great product, would recommend!";

//if (add_user_review($item_id, $username, $rating, $comment, $db_connection)) {
//    echo "Review added successfully!
";
//} else {
//    echo "Failed to add review.
";
//}

// Get and display reviews for the item
//  Assuming a table named 'reviews' with columns: item_id, username, rating, comment
//  and that the item_id is 'product123'
//  This is just conceptual - you'll need to adapt the output to display in your interface.
//  $reviews = get_reviews_for_item("product123", $db_connection);

//if (!empty($reviews)) {
//    echo "<h2>Reviews for product123:</h2>
";
//    foreach ($reviews as $review) {
//        echo "<div>
";
//        echo "  <b>Username:</b> " . $review['username'] . "<br>
";
//        echo "  <b>Rating:</b> " . $review['rating'] . "<br>
";
//        echo "  <b>Comment:</b> " . $review['comment'] . "
";
//        echo "  </div>
";
//    }
//} else {
//    echo "No reviews found for this item.
";
//}

// Close the database connection
//$db_connection->close();

?>
