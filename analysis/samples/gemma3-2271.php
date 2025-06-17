

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $product_name The name of the product or service being reviewed.
 * @param string $review_text The text of the review.
 * @param string $reviewer_name The name of the reviewer.
 * @param int $rating (optional) A rating from 1 to 5. Defaults to 0.
 *
 * @return array An array containing the review data, or an error message if the review submission fails.
 */
function submit_review(string $product_name, string $review_text, string $reviewer_name, int $rating = 0) {
    // Validate inputs (basic example - you'd likely want more robust validation)
    if (empty($product_name)) {
        return ['error' => 'Product name cannot be empty.'];
    }
    if (empty($review_text)) {
        return ['error' => 'Review text cannot be empty.'];
    }
    if (empty($reviewer_name)) {
        return ['error' => 'Reviewer name cannot be empty.'];
    }
    if ($rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be between 1 and 5.'];
    }

    // Sanitize inputs (essential for security - prevent XSS)
    $product_name = htmlspecialchars($product_name);
    $review_text = htmlspecialchars($review_text);
    $reviewer_name = htmlspecialchars($reviewer_name);


    // Store the review (in a real application, you'd use a database)
    // For this example, we'll just echo the review data.
    echo "<h2>Review for: " . $product_name . "</h2>";
    echo "<p><strong>Reviewer:</strong> " . $reviewer_name . "</p>";
    echo "<p><strong>Rating:</strong> " . $rating . "</p>";
    echo "<p><strong>Review:</strong> " . $review_text . "</p>";


    return ['product_name' => $product_name, 'review_text' => $review_text, 'reviewer_name' => $reviewer_name, 'rating' => $rating];
}


// Example Usage:
// Simulate a user submitting a review
$review_data = submit_review("Awesome Widget", "This widget is amazing!  I love it!", "John Doe", 5);

if (isset($review_data['error'])) {
    echo "<p style='color: red;'>Error: " . $review_data['error'] . "</p>";
}

// Another example with a different rating:
$review_data2 = submit_review("Mediocre Gadget", "It's okay, but nothing special.", "Jane Smith", 3);
if (isset($review_data2['error'])) {
  echo "<p style='color: red;'>Error: " . $review_data2['error'] . "</p>";
}

?>
