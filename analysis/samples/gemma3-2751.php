

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product.
 *
 * @param string $productId The ID of the product being reviewed.
 * @param string $userName The name of the user submitting the review.
 * @param string $reviewText The text of the review.
 * @param int $rating (Optional) The rating given by the user (1-5). Defaults to 0.
 * @return array An array containing:
 *   - 'success': True if the review was submitted successfully, false otherwise.
 *   - 'review': The submitted review (if successful).
 *   - 'error': An error message (if applicable).
 */
function submitReview(string $productId, string $userName, string $reviewText, int $rating = 0) {
  // **Input Validation (Crucial!)**
  if (empty($productId)) {
    return ['success' => false, 'review' => null, 'error' => 'Product ID cannot be empty.'];
  }
  if (empty($userName)) {
    return ['success' => false, 'review' => null, 'error' => 'User name cannot be empty.'];
  }
  if (empty($reviewText)) {
    return ['success' => false, 'review' => null, 'error' => 'Review text cannot be empty.'];
  }
  if ($rating < 1 || $rating > 5) {
    return ['success' => false, 'review' => null, 'error' => 'Rating must be between 1 and 5.'];
  }

  // **Simulating Database Insertion (Replace with your actual database logic)**
  $review = [
    'productId' => $productId,
    'userName' => $userName,
    'reviewText' => $reviewText,
    'rating' => $rating,
    'submissionDate' => date('Y-m-d H:i:s') // Add a timestamp for tracking
  ];

  // **In a real application, you would insert this $review array into your database.**
  // For example:
  // $db = new PDO('mysql:host=localhost;dbname=your_database_name', 'username', 'password');
  // $stmt = $db->prepare("INSERT INTO reviews (productId, userName, reviewText, rating, submissionDate) VALUES (:productId, :userName, :reviewText, :rating, :submissionDate)");
  // $stmt->execute($review);

  // **Simulated Success**
  return ['success' => true, 'review' => $review, 'error' => null];
}

/**
 * Function to Retrieve Reviews for a Product
 *
 * This function retrieves all reviews for a given product.
 *
 * @param string $productId The ID of the product.
 * @return array An array containing:
 *   - 'reviews': An array of review objects (each object has 'userName', 'reviewText', 'rating', 'submissionDate').
 *   - 'error': An error message (if applicable).
 */
function getReviews(string $productId) {
    // **Simulate retrieving reviews from a database**
    // In a real application, you would query your database.

    //Simulated Data
    $reviews = [
        ['productId' => '123', 'userName' => 'Alice', 'reviewText' => 'Great product!', 'rating' => 5, 'submissionDate' => date('Y-m-d H:i:s')],
        ['productId' => '123', 'userName' => 'Bob', 'reviewText' => 'It was okay.', 'rating' => 3, 'submissionDate' => date('Y-m-d H:i:s')],
        ['productId' => '456', 'userName' => 'Charlie', 'reviewText' => 'Amazing!', 'rating' => 5, 'submissionDate' => date('Y-m-d H:i:s')]
    ];

    //Filter Reviews by Product ID (Important for security)
    $filteredReviews = [];
    foreach ($reviews as $review) {
        if ($review['productId'] == $productId) {
            $filteredReviews[] = $review;
        }
    }

    return ['reviews' => $filteredReviews, 'error' => null];
}


// **Example Usage**

// Submit a review
$reviewResult = submitReview('123', 'David', 'Excellent value for money!', 4);

if ($reviewResult['success']) {
  echo "Review submitted successfully:<br>";
  echo "Reviewer: " . $reviewResult['review']['userName'] . "<br>";
  echo "Review: " . $reviewResult['review']['reviewText'] . "<br>";
  echo "Rating: " . $reviewResult['review']['rating'] . "<br>";
} else {
  echo "Error submitting review: " . $reviewResult['error'] . "<br>";
}


// Retrieve reviews for product '123'
$reviewsResult = getReviews('123');

if ($reviewsResult['error'] == null) {
    echo "<h3>Reviews for product 123:</h3>";
    foreach ($reviewsResult['reviews'] as $review) {
        echo "<strong>Reviewer:</strong> " . $review['userName'] . "<br>";
        echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
        echo "<strong>Review:</strong> " . $review['reviewText'] . "<br><br>";
    }
} else {
    echo "Error retrieving reviews: " . $reviewsResult['error'] . "<br>";
}
?>
