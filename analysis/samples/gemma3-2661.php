

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $reviewText The review text submitted by the user.
 * @param array $userDetails (optional) An array containing user details (e.g., 'username', 'rating').
 * 
 * @return array An array containing review data (id, product, review, user, rating) 
 *               or null on error.
 */
function createAndDisplayReview(string $productName, string $reviewText, array $userDetails = []) {

  // Sanitize Input (VERY IMPORTANT - Prevent SQL Injection and Cross-Site Scripting)
  $productName = mysqli_real_escape_string($GLOBALS['db'], $productName); // Use your database connection here
  $reviewText = mysqli_real_escape_string($GLOBALS['db'], $reviewText);
  if (isset($userDetails['username'])) {
    $username = mysqli_real_escape_string($GLOBALS['db'], $userDetails['username']);
  } else {
    $username = 'Anonymous';
  }

  // Example Rating Handling (Assume a rating scale of 1-5)
  $rating = isset($userDetails['rating']) ? (int) $userDetails['rating'] : 0; // Convert to integer

  // Validation (Add more as needed)
  if (empty($reviewText)) {
    return ['error' => 'Review cannot be empty.'];
  }

  // Database Interaction (Replace with your actual database query)
  $sql = "INSERT INTO reviews (product, review, username, rating) VALUES ('$productName', '$reviewText', '$username', $rating)";
  $result = mysqli_query($GLOBALS['db'], $sql);

  if (!$result) {
    return ['error' => 'Database error: ' . mysqli_error($GLOBALS['db'])];
  }

  // Get the newly inserted review ID (important for display)
  $reviewId = mysqli_insert_id($GLOBALS['db']);


  // Return Review Data
  return [
    'id' => $reviewId,
    'product' => $productName,
    'review' => $reviewText,
    'user' => $username,
    'rating' => $rating
  ];
}


// Example Usage (Replace with your database connection)
// Simulate a database connection
$GLOBALS['db'] = new mysqli('localhost', 'username', 'password', 'database');

if ($GLOBALS['db']->connect_error) {
  die('Connection failed: ' . $GLOBALS['db']->connect_error);
}


// Create a review
$reviewData = createAndDisplayReview('Awesome Gadget', 'This gadget is fantastic! I love it.', ['username' => 'JohnDoe', 'rating' => 5]);

if (isset($reviewData['error'])) {
  echo "Error: " . $reviewData['error'] . "<br>";
} else {
  echo "Review ID: " . $reviewData['id'] . "<br>";
  echo "Product: " . $reviewData['product'] . "<br>";
  echo "Review: " . $reviewData['review'] . "<br>";
  echo "User: " . $reviewData['user'] . "<br>";
  echo "Rating: " . $reviewData['rating'] . "<br>";
}



// Display Existing Reviews (Example - Replace with your query)
$select_sql = "SELECT * FROM reviews WHERE product = 'Awesome Gadget'";
$result = $GLOBALS['db']->query($select_sql);

if ($result->num_rows > 0) {
  echo "<br><h2>Existing Reviews:</h2>";
  while($row = $result->fetch_assoc()) {
    echo "<br><b>Review ID:</b> " . $row['id'] . "<br>";
    echo "<b>Product:</b> " . $row['product'] . "<br>";
    echo "<b>Review:</b> " . $row['review'] . "<br>";
    echo "<b>User:</b> " . $row['username'] . "<br>";
    echo "<b>Rating:</b> " . $row['rating'] . "<br>";
    echo "<hr>";
  }
} else {
  echo "<br>No reviews found for 'Awesome Gadget'.";
}


?>
