

<?php

/**
 * Function to handle user reviews.
 *
 * This function allows users to submit reviews, displays them, and handles
 * basic data validation.
 *
 * @param string $username The username of the reviewer.
 * @param string $reviewText The text of the review.
 * @param string $rating  The rating (e.g., 1-5).  Should be validated.
 *
 * @return array  An array containing success/failure status and any error messages.
 */
function handleUserReview(string $username, string $reviewText, string $rating) {

  // Validate input - VERY IMPORTANT!
  if (empty($username)) {
    return ['success' => false, 'message' => 'Username cannot be empty.'];
  }

  if (empty($reviewText)) {
    return ['success' => false, 'message' => 'Review text cannot be empty.'];
  }

  if (!preg_match('/^[0-5][0-9]*$/', $rating)) {
    return ['success' => false, 'message' => 'Invalid rating.  Please enter a number between 0 and 5.'];
  }


  // In a real application, you would save this data to a database.
  // This is just a simplified demonstration.

  // Simulate saving the review (replace with database interaction)
  $review = [
    'username' => $username,
    'review' => $reviewText,
    'rating' => $rating,
    'timestamp' => time()  // Add a timestamp for ordering
  ];

  // Add review to a simple array (replace with database)
  $reviews = isset($_SESSION['reviews']) ? $_SESSION['reviews'] : []; //use session for simplicity

  $reviews[] = $review;
  $_SESSION['reviews'] = $reviews;



  return ['success' => true, 'message' => 'Review submitted successfully!'];
}



// Example usage (for demonstration) -  This won't run when you visit the page
// You'd likely use this in a controller function within a web framework
if (isset($_POST['username']) && isset($_POST['review']) && isset($_POST['rating'])) {
    $username = $_POST['username'];
    $reviewText = $_POST['review'];
    $rating = $_POST['rating'];

    $result = handleUserReview($username, $reviewText, $rating);

    if ($result['success']) {
        echo '<p style="color: green;">' . $result['message'] . '</p>';
    } else {
        echo '<p style="color: red;">' . $result['message'] . '</p>';
    }
}


//  Example HTML form for submitting the review
?>
