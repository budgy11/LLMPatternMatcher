

<?php

/**
 * User Review Function
 * 
 * This function allows users to submit and display reviews for a given item.
 *
 * @param string $item_id The ID of the item being reviewed.
 * @param string $username The username of the reviewer.
 * @param string $review_text The text of the review.
 * @param array $reviews An array of existing reviews (optional - for storing reviews in a database or array).
 * 
 * @return array  An updated array of reviews if reviews are being stored, 
 *                or the original reviews if not.
 */
function submit_review(string $item_id, string $username, string $review_text, array &$reviews = []) {

  // Basic input validation (you can add more robust validation here)
  if (empty($username) || empty($review_text)) {
    return $reviews; // Return existing reviews if input is invalid
  }

  // Create a new review object (you could use a class here for better organization)
  $review = [
    'item_id' => $item_id,
    'username' => $username,
    'review_text' => $review_text,
    'timestamp' => date('Y-m-d H:i:s') // Add a timestamp for ordering
  ];

  // Add the new review to the array
  $reviews[] = $review;

  return $reviews;
}


/**
 * Display Reviews Function
 * 
 * This function displays a list of reviews for a given item.
 *
 * @param array $reviews An array of reviews.
 * @param string $item_id The ID of the item being reviewed (for display purposes).
 */
function display_reviews(array $reviews, string $item_id) {
  echo "<h2>Reviews for Item ID: " . $item_id . "</h2>";

  if (empty($reviews)) {
    echo "<p>No reviews yet.</p>";
    return;
  }

  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li>";
    echo "<p><strong>Username:</strong> " . htmlspecialchars($review['username']) . "</p>";
    echo "<p><strong>Review:</strong> " . htmlspecialchars($review['review_text']) . "</p>";
    echo "<p><strong>Date:</strong> " . $review['timestamp'] . "</p>";
    echo "</li>";
  }
  echo "</ul>";
}

// Example Usage:
$item_id = '123'; // Example item ID

// Simulate submitting a review
$reviews = []; // Start with an empty array
$reviews = submit_review($item_id, 'John Doe', 'Great product!  Highly recommended.');
$reviews = submit_review($item_id, 'Jane Smith', 'It was okay, but a little expensive.');
$reviews = submit_review($item_id, 'Peter Jones', 'Excellent service and fast delivery.');


// Display the reviews
display_reviews($reviews, $item_id);



//----------------------------------------------------------------------
// Example demonstrating how to save to a database instead of an array.
//----------------------------------------------------------------------
/*
// This requires a database connection.  This is just a placeholder.
// Replace with your database connection details.
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_password = 'your_password';

// Attempt to connect to the database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to save reviews to the database
function save_review_to_db(string $item_id, string $username, string $review_text, mysqli $conn) {
    $sql = "INSERT INTO reviews (item_id, username, review_text, timestamp)
            VALUES ('$item_id', '$username', '$review_text', NOW())";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}


//Example Usage (Assuming a 'reviews' table with columns item_id, username, review_text, timestamp)
//  You'd call this after submitting the review form.
//  $review_result = save_review_to_db($item_id, $username, $review_text, $conn);
//  if ($review_result) {
//     // Review saved successfully
//  } else {
//     // Handle error saving to database
//  }

// Close the database connection
$conn->close();
*/
?>
