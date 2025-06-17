

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and view user reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The text of the review.
 * @param string $username  The username of the user submitting the review.
 * @param array  $reviews (Optional) An array of existing reviews.  Defaults to an empty array.
 *
 * @return array An array containing the updated reviews array with the new review added.
 */
function add_review(string $productName, string $reviewText, string $username, array &$reviews = []): array {
  // Basic input validation - Add more robust validation as needed for production.
  if (empty($productName)) {
    return $reviews; // Return existing reviews if product name is empty.
  }
  if (empty($reviewText)) {
    return $reviews; // Return existing reviews if review text is empty.
  }

  // Create a new review object
  $review = [
    'username' => $username,
    'review'   => $reviewText,
    'date'     => date("Y-m-d H:i:s") // Timestamp for date
  ];

  // Add the review to the array
  $reviews[] = $review;

  return $reviews;
}

// --- Example Usage and Demonstration ---

// Initialize an empty array to store reviews. Note the use of &$reviews
// to modify the array directly, avoiding unnecessary copying.
$product = "Awesome Gadget X";
$reviews = [];

// Add some initial reviews
$reviews = add_review($product, "This gadget is fantastic!  Highly recommended.", "JohnDoe");
$reviews = add_review($product, "Great value for the money.", "JaneSmith");
$reviews = add_review($product, "Could be better, but it works.", "PeterJones");

// Display the reviews
echo "<h2>Reviews for " . $product . "</h2>";
if (empty($reviews)) {
  echo "<p>No reviews yet.</p>";
} else {
  echo "<ul>";
  foreach ($reviews as $review) {
    echo "<li><strong>" . $review['username'] . ":</strong> " . $review['review'] . " - " . $review['date'] . "</li>";
  }
  echo "</ul>";
}

// Example of adding a new review
$reviews = add_review($product, "Amazing features and excellent customer support!", "AliceBrown");


<?php

/**
 * User Review Function
 * 
 * This function allows you to store and display user reviews for a product.
 * It includes basic validation and data sanitization.
 *
 * @param string $product_id The ID of the product the review is for.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param string $rating  The rating given by the user (e.g., 1-5).
 * 
 * @return array  An array containing review data if successful, or an error message if not.
 */
function createReview(string $product_id, string $user_name, string $review_text, string $rating) {
  // Validate inputs
  if (empty($product_id) || empty($user_name) || empty($review_text) || empty($rating)) {
    return ['error' => 'All fields are required.'];
  }

  // Sanitize inputs (basic - more robust sanitization needed for production)
  $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);
  $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
  $review_text = filter_var($review_text, FILTER_SANITIZE_STRING);
  $rating = filter_var($rating, FILTER_SANITIZE_NUMBER_INT, FILTER_VALIDATE_INT); // Ensure rating is an integer

  if ($rating === false) {
    return ['error' => 'Invalid rating. Please enter a number between 1 and 5.'];
  }
  if ($rating < 1 || $rating > 5) {
    return ['error' => 'Invalid rating. Please enter a number between 1 and 5.'];
  }

  // Prepare review data (For database storage - adjust to your database structure)
  $review_data = [
    'product_id' => $product_id,
    'user_name' => $user_name,
    'review_text' => $review_text,
    'rating' => $rating,
    'created_at' => date('Y-m-d H:i:s') // Add timestamp for tracking
  ];

  // In a real application, you would insert $review_data into a database.
  // For this example, we just return the data.

  // Example of how you might save to the database:
  // $db = new DatabaseConnection();
  // $result = $db->insert('reviews', $review_data);

  return $review_data;
}


/**
 * Display User Reviews
 *
 *  Fetches and displays user reviews based on product_id
 *
 * @param string $product_id The product ID to fetch reviews for.
 *
 * @return array  An array of review data, or an error message if no reviews found.
 */
function displayReviews(string $product_id) {
    // In a real application, you would query your database here.
    // This is a placeholder for demonstration.

    // Mock database retrieval
    if ($product_id == "123") {
        $reviews = [
            ['product_id' => '123', 'user_name' => 'John Doe', 'review_text' => 'Great product! Highly recommended.', 'rating' => 5, 'created_at' => '2023-10-27 10:00:00'],
            ['product_id' => '123', 'user_name' => 'Jane Smith', 'review_text' => 'Good value for money.', 'rating' => 4, 'created_at' => '2023-10-26 14:30:00']
        ];
        return $reviews;
    } else {
      return ['error' => 'No reviews found for this product.'];
    }
}


// Example usage:
// Creating a review
$reviewData = createReview('123', 'Alice Brown', 'This is a fantastic product!', '5');

if (isset($reviewData['error'])) {
    echo "Error: " . $reviewData['error'] . "<br>";
} else {
    echo "Review created successfully!<br>";
    print_r($reviewData); // Display the review data
}


// Displaying reviews
$reviews = displayReviews('123');

if (isset($reviews['error'])) {
    echo "Error: " . $reviews['error'] . "<br>";
} else {
    echo "<h2>Reviews for Product 123:</h2>";
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li><strong>User:</strong> " . $review['user_name'] . "<br>";
        echo "<strong>Rating:</strong> " . $review['rating'] . "<br>";
        echo "<strong>Review:</strong> " . $review['review_text'] . "<br>";
        echo "<strong>Date:</strong> " . $review['created_at'] . "</li>";
    }
    echo "</ul>";
}
?>
