

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display user reviews for a product or item.
 * It includes validation and basic sanitization.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $reviewText The text of the review submitted by the user.
 * @param array $allowedTags  (Optional) An array of allowed HTML tags.  Defaults to an empty array,
 *                            preventing most HTML tags from being allowed.  
 * @return array An array containing:
 *   - 'success' (bool): True if the review was successfully submitted, false otherwise.
 *   - 'message' (string):  A message indicating the outcome (success or error).
 *   - 'review' (string): The submitted review if successful, or an empty string.
 */
function submitReview($productName, $reviewText, $allowedTags = []) {
    // Input Validation and Sanitization
    if (empty($productName)) {
        return array('success' => false, 'message' => 'Product name cannot be empty.', 'review' => '');
    }
    if (empty($reviewText)) {
        return array('success' => false, 'message' => 'Review text cannot be empty.', 'review' => '');
    }
    $reviewText = trim($reviewText); // Remove leading/trailing whitespace

    // Sanitize HTML tags -  Important for security
    $reviewText = strip_tags($reviewText, $allowedTags);

    // You could add more robust sanitization here, such as:
    // - Escaping HTML entities
    // - Filtering out unsafe characters

    // Store the review (This is a simplified example -  in a real application,
    // you'd use a database or other persistent storage)
    $storedReview = $reviewText;

    return array('success' => true, 'message' => 'Review submitted successfully.', 'review' => $storedReview);
}


/**
 * Display User Reviews
 *
 * This function takes an array of reviews and displays them in an unordered list.
 *
 * @param array $reviews An array of reviews, each review being an associative array
 *                      with keys 'success', 'message', and 'review'.
 */
function displayReviews(array $reviews) {
    echo "<h2>User Reviews for " . htmlspecialchars($reviews[0]['message']) . "</h2>"; // Display the product name.  htmlspecialchars escapes characters for HTML output.

    if (count($reviews) === 0) {
        echo "<p>No reviews yet.</p>";
        return;
    }

    echo "<ul>";
    foreach ($reviews as $review) {
        if ($review['success']) {
            echo "<li>";
            echo htmlspecialchars($review['review']); // Escape the review text for HTML output.
            echo " - " . htmlspecialchars(getReviewAuthorName()); // Add the author name.
            echo "</li>";
        } else {
            echo "<li>Error: " . htmlspecialchars($review['message']) . "</li>";
        }
    }
    echo "</ul>";
}


// Example Usage (Illustrative -  You'd integrate this into a form and display)

// Simulate a review submission
$reviewResult = submitReview("Awesome Widget", "This widget is fantastic!  It does everything I need.");

// Display the result
displayReviews($reviewResult);


// Example with allowed tags
$allowedTags = ['b', 'i', 'u']; // Allow bold, italics, and underline
$reviewResult2 = submitReview("Another Product", "This is a <b style='color:red;'>bold</b> and *italic* review.");
displayReviews($reviewResult2);

?>
