

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The text of the review submitted by the user.
 * @param string $userId (Optional) The ID of the user submitting the review.  Used for tracking and potentially moderation.
 * @param array  $reviews  (Optional) An array of existing reviews to display. Defaults to an empty array.
 *
 * @return array An array containing:
 *   - 'reviews': An array of all reviews (existing and new).
 *   - 'newReview': The new review submitted by the user (if any).
 *
 * Example Usage:
 * $reviews = getReview($productName, $reviewText, $userId);
 * print_r($reviews);
 */
function getReview(string $productName, string $reviewText, string $userId = '', array $reviews = []): array
{
  // Sanitize input (Basic - enhance with more robust validation as needed)
  $productName = trim($productName);
  $reviewText = trim($reviewText);

  //  Check for empty review text.  It's good practice to require at least some content.
  if (empty($reviewText)) {
    return ['reviews' => $reviews, 'newReview' => null];
  }

  // Create a new review object.  This allows you to store additional data later if needed.
  $newReview = [
    'userId' => $userId,
    'text' => $reviewText,
    'date' => date('Y-m-d H:i:s') // Add a timestamp
  ];

  // Add the new review to the array
  $reviews[] = $newReview;

  return ['reviews' => $reviews, 'newReview' => $newReview];
}


//  Example Usage (Demonstration)
$product = "Awesome Gadget X";
$review1 = "This gadget is fantastic!  I love it!";
$review2 = "It's okay, but a bit overpriced.";

// Get the first review
$reviews1 = getReview($product, $review1, "user123");
print_r($reviews1);
echo "<br>";

// Get the second review
$reviews2 = getReview($product, $review2, "user456");
print_r($reviews2);
echo "<br>";

//  Example with empty review (to show handling)
$reviews3 = getReview($product, "", "user789");
print_r($reviews3);
echo "<br>";

?>
