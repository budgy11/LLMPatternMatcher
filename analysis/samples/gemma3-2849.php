

<?php

/**
 * User Review Class
 */
class UserReview {

    private $review_id;
    private $user_id;
    private $product_id;
    private $rating;
    private $comment;
    private $review_date;

    /**
     * Constructor for the UserReview class
     *
     * @param int $review_id (Optional) - Unique ID for the review.  If not provided, one will be generated.
     * @param int $user_id The ID of the user who submitted the review.
     * @param int $product_id The ID of the product being reviewed.
     * @param int $rating The rating given by the user (e.g., 1-5).
     * @param string $comment The user's comment on the product.
     * @param string $review_date The date the review was submitted (e.g., "Y-m-d H:i:s").
     */
    public function __construct($review_id = null, $user_id, $product_id, $rating, $comment, $review_date) {
        $this->review_id = $review_id;
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->review_date = $review_date;
    }

    /**
     * Getters and Setters (for encapsulation)
     */

    public function getReviewId() {
        return $this->review_id;
    }

    public function setReviewId($review_id) {
        $this->review_id = $review_id;
    }


    public function getUserId() {
        return $this->user_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function getProductId() {
        return $this->product_id;
    }

    public function setProductId($product_id) {
        $this->product_id = $product_id;
    }

    public function getRating() {
        return $this->rating;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function getReviewDate() {
        return $this->review_date;
    }

    public function setReviewDate($review_date) {
        $this->review_date = $review_date;
    }

    /**
     * Display the review information in a readable format.
     *
     * @return string  A formatted string representing the review.
     */
    public function displayReview() {
        return "Review ID: " . $this->getReviewId() .
               "
User ID: " . $this->getUserId() .
               "
Product ID: " . $this->getProductId() .
               "
Rating: " . $this->getRating() .
               "
Comment: " . $this->getComment() .
               "
Date: " . $this->getReviewDate();
    }

}


/**
 * User Review Function (Illustrative Example -  A Basic Review "Function")
 *
 * This is a simplified example demonstrating how you might *use* a UserReview object.
 *  In a real-world scenario, you'd likely integrate this with a database.
 */
function processUserReview($review_id, $user_id, $product_id, $rating, $comment, $review_date) {
    // Create a UserReview object
    $review = new UserReview($review_id, $user_id, $product_id, $rating, $comment, $review_date);

    // Basic validation (you'd want more robust validation in a real application)
    if ($review->getRating() < 1 || $review->getRating() > 5) {
        echo "Invalid rating. Rating must be between 1 and 5.
";
        return false;
    }

    // Display the review
    echo "Review Submitted:
" . $review->displayReview() . "

";

    // In a real application, you'd save this review to a database.

    return true; // Indicate success
}


// Example Usage
processUserReview(123, 45, 67, 4, "Great product!", "2023-10-27 10:00:00");
processUserReview(456, 78, 90, 5, "Excellent value!", "2023-10-27 11:30:00");
processUserReview(789, 10, 12, 3, "Okay", "2023-10-27 13:00:00"); //  Demonstrating a 3-star review


<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It includes basic validation and error handling.  This is a simplified
 * example and can be expanded upon significantly for a production environment.
 *
 * @param string $productId The ID of the product the review is for.
 * @param string $username  The username of the reviewer.
 * @param string $rating   The rating given (e.g., 1-5).
 * @param string $comment  The review text.
 *
 * @return array An array containing the response data:
 *               - 'success': True if the operation was successful, false otherwise.
 *               - 'message': A message describing the result.
 *               - 'data':  The review data if successful, or an empty array.
 */
function create_user_review(string $productId, string $username, string $rating, string $comment) {
  // Input Validation (Basic)
  if (empty($productId) || empty($username) || empty($rating) || empty($comment)) {
    return [
      'success' => false,
      'message' => "All fields are required.",
      'data' => []
    ];
  }

  if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
    return [
      'success' => false,
      'message' => "Rating must be a number between 1 and 5.",
      'data' => []
    ];
  }

  // *** In a real application, you would: ***
  // 1. Connect to a database to store the review.
  // 2. Sanitize and validate the input thoroughly.
  // 3.  Handle database errors properly.
  // 4.  Potentially check for existing reviews for the same user and product.
  // 5.  Implement a more robust security mechanism (e.g., escaping user input).

  // For demonstration purposes, we'll just simulate a successful review creation.
  $review_id = time(); // Generate a simple unique ID
  $review = [
    'review_id' => $review_id,
    'product_id' => $productId,
    'username' => $username,
    'rating' => $rating,
    'comment' => $comment,
    'date_created' => date('Y-m-d H:i:s')
  ];

  return [
    'success' => true,
    'message' => "Review created successfully.",
    'data' => $review
  ];
}


/**
 *  Example Usage
 */

// Create a review
$response = create_user_review('product123', 'JohnDoe', 4, 'Great product! Highly recommended.');

echo "<pre>";
print_r($response);
echo "</pre>";

// Example of an invalid response
$response = create_user_review('', 'JaneSmith', 3, 'Terrible.');
echo "<pre>";
print_r($response);
echo "</pre>";
?>
