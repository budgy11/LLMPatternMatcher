

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or item.
 *
 * @param string $productName The name of the product or item being reviewed.
 * @param string $reviewText The review text submitted by the user.
 * @param array  $userOptions (Optional) An array of options for the review form.
 *                            e.g., ['rating' => 5, 'comment' => 'Great product!']
 *
 * @return string HTML representation of the review form and the displayed review.
 */
function createReviewFunction(string $productName, string $reviewText, array $userOptions = []) {
  // Validate input (basic example - expand for production)
  if (empty($reviewText)) {
    return "<p>Please enter a review.</p>";
  }

  // Build the review form HTML
  $formHTML = '<form method="post" action="process_review.php">';
  $formHTML .= '<h3>Review for ' . $productName . '</h3>';
  $formHTML .= '<label for="review_text">Review:</label><br>';
  $formHTML .= '<textarea id="review_text" name="review_text" rows="4" cols="50">' . $reviewText . '</textarea><br><br>';

  // Add user options (if provided)
  if (!empty($userOptions)) {
    $formHTML .= '<label for="rating">Rating:</label><br>';
    $formHTML .= '<select id="rating" name="rating">';
    foreach ($userOptions as $key => $value) {
      $formHTML .= '<option value="' . $key . '">' . $value . '</option>';
    }
    $formHTML .= '</select><br><br>';
  }

  $formHTML .= '<input type="submit" value="Submit Review">';
  $formHTML .= '</form>';

  // Display the submitted review (if no review exists yet)
  $reviewExists = false; // Assume no review exists until proven otherwise

  // Check if there's a saved review
  if (isset($_SESSION['reviews'][$productName])) {
    $reviewExists = true;
    $reviewHTML = '<p><strong>Review:</strong> ' . htmlspecialchars($_SESSION['reviews'][$productName]) . '</p>';
    $reviewHTML = $reviewHTML; // Use the saved review
  } else {
    // Add the new review to the session
    $_SESSION['reviews'][$productName] = htmlspecialchars($reviewText);  // Sanitize the input
  }

  return $formHTML . $reviewHTML;
}

// Example usage (simulating a session)
session_start();

//  Demonstrates a single review being created
$review = createReviewFunction("Awesome Widget", "This widget is fantastic!");

//Demonstrates multiple reviews being created
$review2 = createReviewFunction("Amazing Gadget", "Highly recommend this gadget!");
$review3 = createReviewFunction("Fantastic Item", "Great value for money.");


//  Illustrates retrieving and displaying a stored review:
if (isset($_SESSION['reviews']['Awesome Widget'])) {
    echo "<h2>Existing Review for Awesome Widget:</h2>";
    echo "<p><strong>Review:</strong> " . htmlspecialchars($_SESSION['reviews']['Awesome Widget']) . "</p>";
}

?>
