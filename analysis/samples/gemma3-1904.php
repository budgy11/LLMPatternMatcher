

// Example (using PDO - conceptual):
try {
    $pdo = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
    $stmt = $pdo->prepare("INSERT INTO reviews (product_name, review_text, user_email, rating, user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$review['product_name'], $review['review_text'], $review['user_email'], $review['rating'], $review['user_id']]);
    // ... handle success or error
} catch (PDOException $e) {
    // Handle database errors
}


<?php

/**
 * User Review Function
 *
 * This function allows you to create, retrieve, update, and delete user reviews.
 * It uses a simple database (simulated for demonstration) and provides basic
 * functionality.  For production environments, consider a more robust database
 * and security measures.
 */

class Review {
  private $id;
  private $product_id;
  private $user_id;
  private $rating;
  private $comment;
  private $created_at;
  private $updated_at;

  public function __construct($productId, $userId, $rating, $comment = '') {
    $this->productId = $productId;
    $this->userId = $userId;
    $this->rating = $rating;
    $this->comment = $comment;
    $this->created_at = date('Y-m-d H:i:s');
    $this->updated_at = $this->created_at;
  }

  public function toArray() {
    return [
      'id' => $this->id,
      'productId' => $this->productId,
      'userId' => $this->userId,
      'rating' => $this->rating,
      'comment' => $this->comment,
      'createdAt' => $this->created_at,
      'updatedAt' => $this->updated_at,
    ];
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }
}



class ReviewManager {
  private $reviews = [];

  public function createReview($productId, $userId, $rating, $comment = '') {
    $review = new Review($productId, $userId, $rating, $comment);
    $this->reviews[] = $review;
    return $review;
  }

  public function getReviewById($id) {
    foreach ($this->reviews as $review) {
      if ($review = $this->reviews[$id]) {
        return $review;
      }
    }
    return null;
  }

  public function getAllReviewsForProduct($productId) {
    $reviews = [];
    foreach ($this->reviews as $review) {
      if ($review->getProductId() == $productId) {
        $reviews[] = $review;
      }
    }
    return $reviews;
  }

  // Implement update, delete methods if needed
}


// --- Example Usage ---

// Initialize the review manager
$reviewManager = new ReviewManager();

// Create some reviews
$review1 = $reviewManager->createReview(1, 1, 5, 'Excellent product!');
$review2 = $reviewManager->createReview(2, 2, 4, 'Good value for money.');
$review3 = $reviewManager->createReview(1, 3, 3, 'It\'s okay.');

// Get a review by ID
$review = $reviewManager->getReviewById(1);
if ($review) {
  echo "Review ID 1: ";
  print_r($review->toArray());
} else {
  echo "Review not found.";
}

// Get all reviews for product ID 1
$reviewsForProduct1 = $reviewManager->getAllReviewsForProduct(1);
echo "
Reviews for Product 1:
";
print_r($reviewsForProduct1);


//  --- Further Development Ideas ---
//  * **Database Integration:**  Use a database (MySQL, PostgreSQL, etc.) to store reviews.
//  * **Input Validation:**  Validate user input (e.g., rating must be between 1 and 5).
//  * **Error Handling:**  Implement proper error handling (e.g., handling database errors).
//  * **Authentication/Authorization:**  Add security measures to control who can create, update, or delete reviews.
//  * **Pagination:**  If you have a large number of reviews, implement pagination to improve performance.
//  * **Object-Relational Mapper (ORM):**  Consider using an ORM like Doctrine or Eloquent to simplify database interactions.
?>
