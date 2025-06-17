

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view reviews for a product or service.
 * It includes basic validation and saving of the review to a file (for simplicity).
 *
 * @param string $productId The ID of the product or service being reviewed.
 * @param string $reviewText The review text submitted by the user.
 * @param string $reviewerName The name of the reviewer (optional).
 *
 * @return bool True if the review was successfully saved, false otherwise.
 */
function submitReview(string $productId, string $reviewText, string $reviewerName = '') {
  // Basic validation
  if (empty($reviewText)) {
    error_log("Empty review text submitted for product ID: $productId");
    return false;
  }

  // Sanitize and escape the review text to prevent XSS vulnerabilities
  $sanitizedReview = htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8');

  // Create the review data
  $reviewData = [
    'productId' => $productId,
    'reviewText' => $sanitizedReview,
    'reviewerName' => $reviewerName,
    'timestamp' => date('Y-m-d H:i:s') // Add a timestamp for reference
  ];

  // Save the review to a file (for demonstration purposes only - use a database for production)
  $filename = 'reviews_' . $productId . '.txt';
  $reviewString = json_encode($reviewData) . PHP_EOL; // Add a newline for clarity

  if (file_put_contents($filename, $reviewString, FILE_APPEND) !== false) {
    // Log success
    error_log("Review submitted for product ID: $productId by $reviewerName: $sanitizedReview");
    return true;
  } else {
    // Log error
    error_log("Failed to save review for product ID: $productId");
    return false;
  }
}


/**
 * Display Reviews for a Product
 *
 * This function reads reviews from a file and displays them.
 *
 * @param string $productId The ID of the product or service.
 *
 * @return void Displays the reviews on the screen.
 */
function displayReviews(string $productId) {
    $filename = 'reviews_' . $productId . '.txt';

    if (file_exists($filename)) {
        echo "<h2>Reviews for Product ID: $productId</h2>";
        echo "<ol>";

        // Read and display the reviews
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            echo "<li>$line</li>";
        }

        echo "</ol>";
    } else {
        echo "<p>No reviews found for product ID: $productId</p>";
    }
}


// Example Usage (Demonstration)
//  This section demonstrates how to use the functions.  You would typically
//  integrate these into a web application's routing and form handling.

// Form submission (Simulated)
if (isset($_POST['submitReview'])) {
    $productId = $_POST['productId'];
    $reviewText = $_POST['reviewText'];
    $reviewerName = isset($_POST['reviewerName']) ? $_POST['reviewerName'] : '';

    if (submitReview($productId, $reviewText, $reviewerName)) {
        echo "<p>Review submitted successfully!</p>";
    } else {
        echo "<p>Error submitting review.</p>";
    }
}

// Displaying Reviews (Example)
// You'd typically call this function after a review has been submitted.
//displayReviews('product123'); // Replace 'product123' with an actual product ID
?>
