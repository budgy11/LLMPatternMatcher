

<?php

/**
 * User Review Function
 *
 * This function takes user input and returns a formatted review string.
 * It includes basic sanitization to prevent common vulnerabilities.
 *
 * @param string $user_name The user's name.
 * @param string $review_text The user's review text.
 * @param string $rating  The rating (e.g., 1-5).
 * @return string A formatted review string.  Returns an error message if input is invalid.
 */
function generateUserReview(string $user_name, string $review_text, string $rating) {
  // Input Validation - VERY IMPORTANT!
  if (empty($user_name)) {
    return "Error: User name cannot be empty.";
  }
  if (empty($review_text)) {
    return "Error: Review text cannot be empty.";
  }

  // Basic Sanitization -  More robust sanitization is recommended for production.
  $user_name = trim($user_name);  // Remove leading/trailing whitespace
  $review_text = trim($review_text);
  $rating = filter_var($rating, FILTER_VALIDATE_INT); // Ensure rating is an integer

  if ($rating === false || $rating < 1 || $rating > 5) {
    return "Error: Invalid rating.  Please enter a number between 1 and 5.";
  }
  // Formatting the review
  $formatted_review = "Review by: " . htmlspecialchars($user_name) . "
";
  $formatted_review .= "Rating: " . $rating . " stars
";
  $formatted_review .= "Review: " . $review_text . "
";
  $formatted_review .= "---";

  return $formatted_review;
}

// --- Example Usage ---

// Get user input (e.g., from a form)
$userName = $_POST['user_name'] ?? ''; // Use null coalescing operator for safety
$reviewText = $_POST['review_text'] ?? '';
$rating = $_POST['rating'] ?? '';

// Generate the review
$review = generateUserReview($userName, $reviewText, $rating);

// Display the review
echo $review;


// --- Example Input (for testing) ---
/*
//Simulated form data
$name = "John Doe";
$comment = "This product is amazing!";
$stars = 5;

$review = generateUserReview($name, $comment, $stars);
echo $review;

//Another example with error handling
$review = generateUserReview("", "A review", 3); //Empty name
echo $review;
*/
?>
