

<?php

/**
 * User Review Function - Allows storing and retrieving user reviews.
 */
class UserReview {

  private $db_host = 'localhost';
  private $db_name = 'your_database_name'; // Replace with your database name
  private $db_user = 'your_username'; // Replace with your username
  private $db_password = 'your_password'; // Replace with your password

  private $conn = null;

  /**
   * Constructor: Establishes the database connection.
   */
  public function __construct() {
    try {
      $this->conn = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_user, $this->db_password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo "Database connection failed: " . $e->getMessage();
      die(); // Stop execution if the connection fails
    }
  }

  /**
   * Adds a new review to the database.
   *
   * @param string $user_id The ID of the user submitting the review.
   * @param string $review_text The text of the review.
   * @return bool True on success, false on failure.
   */
  public function addReview(string $user_id, string $review_text) {
    try {
      $stmt = $this->conn->prepare("INSERT INTO reviews (user_id, review_text) VALUES (:user_id, :review_text)");
      $stmt->bindParam(':user_id', $user_id);
      $stmt->bindParam(':review_text', $review_text);
      $result = $stmt->execute();
      return $result;
    } catch (PDOException $e) {
      echo "Error adding review: " . $e->getMessage();
      return false;
    }
  }

  /**
   * Retrieves all reviews from the database.
   *
   * @return array An array of review objects, or an empty array if none are found.
   */
  public function getAllReviews() {
    $reviews = [];
    try {
      $stmt = $this->conn->prepare("SELECT id, user_id, review_text, created_at FROM reviews");
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if ($result) {
        foreach ($result as $row) {
          $reviews[] = new ReviewObject($row['id'], $row['user_id'], $row['review_text'], $row['created_at']);
        }
      }
      return $reviews;
    } catch (PDOException $e) {
      echo "Error retrieving reviews: " . $e->getMessage();
      return [];
    }
  }

  /**
   * Retrieves a specific review by ID.
   *
   * @param int $id The ID of the review to retrieve.
   * @return ReviewObject|null A ReviewObject if found, null otherwise.
   */
  public function getReviewById(int $id) {
    try {
      $stmt = $this->conn->prepare("SELECT id, user_id, review_text, created_at FROM reviews WHERE id = :id");
      $stmt->bindParam(':id', $id);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result) {
        return new ReviewObject($result['id'], $result['user_id'], $result['review_text'], $result['created_at']);
      } else {
        return null;
      }
    } catch (PDOException $e) {
      echo "Error retrieving review by ID: " . $e->getMessage();
      return null;
    }
  }

  /**
   * Deletes a review from the database.
   *
   * @param int $id The ID of the review to delete.
   * @return bool True on success, false on failure.
   */
  public function deleteReview(int $id) {
    try {
      $stmt = $this->conn->prepare("DELETE FROM reviews WHERE id = :id");
      $stmt->bindParam(':id', $id);
      $result = $stmt->execute();
      return $result;
    } catch (PDOException $e) {
      echo "Error deleting review: " . $e->getMessage();
      return false;
    }
  }
}

// Custom Review Object (to represent a review)
class ReviewObject {
  public int $id;
  public string $user_id;
  public string $review_text;
  public string $created_at; // Timestamp for when the review was created

  public function __construct(int $id, string $user_id, string $review_text, string $created_at) {
    $this->id = $id;
    $this->user_id = $user_id;
    $this->review_text = $review_text;
    $this->created_at = $created_at;
  }
}


// Example Usage:
// Create an instance of the Review class
$reviewSystem = new UserReview();

// Add a review
$reviewSystem->addReview('user123', 'This is a fantastic product!');

// Get all reviews
$allReviews = $reviewSystem->getAllReviews();
foreach ($allReviews as $review) {
  echo "User ID: " . $review->user_id . "<br>";
  echo "Review: " . $review->review_text . "<br>";
  echo "Created At: " . $review->created_at . "<br><br>";
}

// Get a specific review
$specificReview = $reviewSystem->getReviewById(1);  // Assuming ID 1 exists

if ($specificReview) {
    echo "Specific Review: " . $specificReview->review_text . "<br>";
} else {
    echo "Review not found.";
}

// Delete a review (assuming ID 1 exists)
$reviewSystem->deleteReview(1);
?>
