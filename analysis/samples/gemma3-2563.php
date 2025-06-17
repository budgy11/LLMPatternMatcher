

<?php

/**
 * User Review Function - A simplified example
 *
 * This function allows you to add a new user review to a product.
 * It includes basic validation and saves the review to a file.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $userName The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param string $filename  The name of the file to save the review in.  Default is 'reviews.txt'
 *
 * @return bool True if the review was saved successfully, false otherwise.
 */
function addReview(string $productId, string $userName, string $reviewText, string $filename = 'reviews.txt'): bool
{
  // **Input Validation** - VERY IMPORTANT!
  if (empty($productId)) {
    error_log("Error: Product ID cannot be empty."); //Log the error - useful for debugging
    return false;
  }

  if (empty($userName)) {
    error_log("Error: User name cannot be empty.");
    return false;
  }

  if (empty($reviewText)) {
    error_log("Error: Review text cannot be empty.");
    return false;
  }

  // Sanitize input to prevent basic attacks.  This is a basic example,
  // more robust sanitization might be needed for production.
  $reviewText = trim($reviewText);
  $reviewText = htmlspecialchars($reviewText); // Protects against XSS attacks

  // **Save the Review**
  $reviewData = "Product ID: " . $productId . "
";
  $reviewData .= "User: " . $userName . "
";
  $reviewData .= "Review: " . $reviewData . "
";

  $file = fopen($filename, 'a'); // 'a' for append mode

  if ($file) {
    fwrite($file, $reviewData);
    fclose($file);
    return true;
  } else {
    error_log("Error: Could not open file for writing.");
    return false;
  }
}


// **Example Usage**

// Add a review
$productID = "123";
$userName = "John Doe";
$reviewText = "This product is amazing! I highly recommend it.";

if (addReview($productID, $userName, $reviewText)) {
  echo "Review added successfully!
";
} else {
  echo "Failed to add review.
";
}


// Another example:
$productID = "456";
$userName = "Jane Smith";
$reviewText = "Good product, but could be improved.";

if (addReview($productID, $userName, $reviewText, 'product_reviews.txt')) {
  echo "Review added successfully to product_reviews.txt!
";
} else {
  echo "Failed to add review.
";
}

?>
