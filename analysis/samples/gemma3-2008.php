

<?php

/**
 * User Review Function - PHP
 * 
 * This function allows you to store and retrieve user reviews for a given product.
 * It uses a simple file-based storage for demonstration purposes.  
 * 
 * **Important:** In a real-world application, you would likely use a 
 * database (MySQL, PostgreSQL, etc.) for storing data, 
 * rather than files.  This is purely for illustrative purposes.
 */

class ReviewManager {

  private $reviewsFile;

  public function __construct($reviewsFile) {
    $this->reviewsFile = $reviewsFile;
  }

  /**
   * Adds a new review to the database.
   *
   * @param int $productId The ID of the product the review is for.
   * @param string $username The username of the reviewer.
   * @param string $comment The review comment.
   * @return bool True if the review was added successfully, false otherwise.
   */
  public function addReview(int $productId, string $username, string $comment) {
    // Sanitize input (important!) -  Validate for security.
    $productId = (int)$productId;  //Cast to integer
    $username = trim($username);
    $comment = trim($comment);

    // Check if username and comment are empty.
    if (empty($username) || empty($comment)) {
      return false;
    }

    // Format the review data
    $reviewData = "{$productId}: {$username}: {$comment}
";

    // Check if the file exists. If not, create it.
    if (!file_exists($this->reviewsFile)) {
      if (!touch($this->reviewsFile)) {
        error_log("Failed to create reviews file: $this->reviewsFile"); //Log an error.
        return false;
      }
    }

    // Append the review to the file
    $result = file_put_contents($this->reviewsFile, $reviewData, FILE_APPEND);

    if ($result === false) {
      error_log("Failed to add review to file: $this->reviewsFile");
      return false;
    }

    return true;
  }


  /**
   * Retrieves all reviews for a product.
   *
   * @param int $productId The ID of the product to retrieve reviews for.
   * @return array An array of review strings, or an empty array if no reviews are found.
   */
  public function getReviews(int $productId) {
    $reviews = [];
    $productId = (int)$productId; //Cast to integer

    if (!file_exists($this->reviewsFile)) {
      return $reviews; // Return empty array if file doesn't exist
    }

    $contents = file($this->reviewsFile, FILE_IGNORE_NEW_LINES);

    if ($contents === false) {
      error_log("Failed to read reviews file: $this->reviewsFile");
      return $reviews;
    }

    foreach ($contents as $review) {
      if (strpos($review, ": {$productId}:") === 0) {
        $reviews[] = $review;
      }
    }

    return $reviews;
  }

  /**
   * Deletes all reviews for a product.
   *
   * @param int $productId The ID of the product to delete reviews for.
   */
  public function deleteReviews(int $productId) {
      $productId = (int)$productId;

      if (!file_exists($this->reviewsFile)) {
          return; // Nothing to delete
      }

      $reviews = $this->getReviews($productId);
      if ($reviews) {
          $deletedCount = 0;
          foreach ($reviews as $review) {
            $deleted = unlink($this->reviewsFile);  //Unlink each review.
            if ($deleted) {
                $deletedCount++;
            }
          }
          if($deletedCount > 0){
            // Re-create file after deleting
            touch($this->reviewsFile);
          }
      }
  }
}


// Example Usage:
$reviewsManager = new ReviewManager('reviews.txt');

// Add some reviews
$reviewsManager->addReview(123, 'John Doe', 'Great product! Highly recommended.');
$reviewsManager->addReview(123, 'Jane Smith', 'Excellent value for money.');
$reviewsManager->addReview(456, 'Peter Jones', 'Could be better.');


// Get reviews for product ID 123
$reviews = $reviewsManager->getReviews(123);

if (!empty($reviews)) {
  echo "Reviews for Product 123:
";
  foreach ($reviews as $review) {
    echo $review . "
";
  }
} else {
  echo "No reviews found for product 123.
";
}


// Delete reviews for product 123
$reviewsManager->deleteReviews(123);

//Try getting reviews for the product again.
$reviews = $reviewsManager->getReviews(123);
if (!empty($reviews)) {
  echo "Reviews for Product 123:
";
  foreach ($reviews as $review) {
    echo $review . "
";
  }
} else {
  echo "No reviews found for product 123.
";
}
?>
