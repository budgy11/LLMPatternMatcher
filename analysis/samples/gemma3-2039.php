    <label for="itemName">Item Name:</label>
    <input type="text" id="itemName" name="itemName" required><br><br>

    <label for="reviewText">Your Review:</label><br>
    <textarea id="reviewText" name="reviewText" rows="5" cols="50" required></textarea><br><br>

    <label for="rating">Rating (1-5):</label>
    <select id="rating" name="rating" required>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
    </select><br><br>

    <button type="submit">Submit Review</button>
  </form>

</body>
</html>


<?php

/**
 * User Review Function
 *
 * This function allows you to collect and display user reviews for a product or service.
 * It includes input validation, data sanitization, and basic display formatting.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param array $reviews An array of review objects.  Each object should have:
 *                       - `user`:  The username of the reviewer.
 *                       - `rating`:  The rating given (e.g., 1-5).
 *                       - `comment`: The user's comment/review text.
 * @return string HTML representation of the reviews.
 */
function displayUserReviews(string $productName, array $reviews)
{
  // Input Validation (Basic - expand for more robust validation)
  if (empty($productName)) {
    return "<p>Error: Product name cannot be empty.</p>";
  }

  if (!is_array($reviews)) {
    return "<p>Error: Reviews must be an array.</p>";
  }

  // Sanitize input (Important for security - escape HTML)
  $productName = htmlspecialchars($productName);

  $reviewOutput = "<div class='reviews-container'><h2>Reviews for {$productName}</h2>";

  if (empty($reviews)) {
    $reviewOutput .= "<p>No reviews yet.</p>";
  } else {
    foreach ($reviews as $review) {
      //Sanitize data before display
      $user = htmlspecialchars($review['user']);
      $rating = htmlspecialchars($review['rating']);
      $comment = htmlspecialchars($review['comment']);

      $reviewOutput .= "<div class='review'>";
      $reviewOutput .= "<p><strong>User:</strong> {$user}</p>";
      $reviewOutput .= "<p><strong>Rating:</strong> {$rating}</p>";
      $reviewOutput .= "<p>{$comment}</p>";
      $reviewOutput .= "</div>";
    }
  }

  $reviewOutput .= "</div>"; // Close reviews-container

  return $reviewOutput;
}


// Example Usage:
// Sample Reviews Data (replace with your database or other data source)
$product = "Awesome Gadget";
$reviewsData = [
  [
    'user' => 'Alice',
    'rating' => 5,
    'comment' => 'This gadget is amazing!  I love it.'
  ],
  [
    'user' => 'Bob',
    'rating' => 4,
    'comment' => 'Pretty good, but the battery life could be better.'
  ],
  [
    'user' => 'Charlie',
    'rating' => 3,
    'comment' => 'It's okay.  Not as impressive as I hoped.'
  ]
];

// Call the function to generate the HTML output
$reviewHtml = displayUserReviews($product, $reviewsData);

// Output the HTML to the browser or display it in a PHP file
echo $reviewHtml;

?>
