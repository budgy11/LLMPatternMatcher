

<?php

/**
 *  A function to create and manage user reviews.
 *
 *  This function provides basic functionality for adding, displaying, and
 *  potentially moderating user reviews.  It's a simplified example and can
 *  be extended with more advanced features like rating systems, image uploads,
 *  and moderation workflows.
 */
class ReviewManager {

  private $reviews = [];
  private $dbConnection = null; // For database integration (optional)

  /**
   * Constructor - Initializes the ReviewManager.
   *
   * @param PDO $dbConnection (Optional)  A PDO database connection object.
   *                            If not provided, reviews are stored in memory.
   */
  public function __construct(PDO $dbConnection = null) {
    $this->dbConnection = $dbConnection;
  }


  /**
   * Adds a new review.
   *
   * @param string $userId    The ID of the user submitting the review.
   * @param string $reviewText The content of the review.
   * @return int|null The ID of the newly created review, or null on failure.
   */
  public function addReview(string $userId, string $reviewText): ?int {
    $reviewId = uniqid(); //Generate unique id
    $this->reviews[$reviewId] = [
      'userId' => $userId,
      'reviewText' => $reviewText,
      'timestamp' => time()
    ];
    return $reviewId;
  }


  /**
   * Retrieves all reviews.
   *
   * @return array  An array of review objects.
   */
  public function getAllReviews(): array {
    return $this->reviews;
  }

  /**
   * Retrieves a review by its ID.
   *
   * @param string $reviewId The ID of the review to retrieve.
   * @return array|null  The review object, or null if not found.
   */
  public function getReviewById(string $reviewId): ?array {
    return $this->reviews[$reviewId] ?? null; //Use null coalescing operator for cleaner code
  }


  /**
   *  Deletes a review by its ID. (Consider security implications before implementing)
   *
   * @param string $reviewId The ID of the review to delete.
   * @return bool True on successful deletion, false otherwise.
   */
  public function deleteReview(string $reviewId): bool {
    if (array_key_exists($reviewId, $this->reviews)) {
      unset($this->reviews[$reviewId]);
      return true;
    }
    return false;
  }

  /**
     * Example database integration using PDO
     *  (This is just a placeholder - you'll need to adapt it to your DB schema)
     *
     *  This demonstrates how you *could* store reviews in a database.
     *  Remove this if you're not using a database.
     *
     * @param string $userId
     * @param string $reviewText
     * @return int|null
     */
    public function addReviewToDB(string $userId, string $reviewText) {
        $reviewId = uniqid();

        if ($this->dbConnection) {
            $stmt = $this->dbConnection->prepare("INSERT INTO reviews (review_id, user_id, review_text, timestamp) VALUES (?, ?, ?, ?)");
            $stmt->execute([$reviewId, $userId, $reviewText, time()]);
            return $reviewId;
        } else {
            //Store in memory if no database connection
            $this->addReview($userId, $reviewText);
            return $reviewId;
        }
    }

}


// Example Usage:

// 1.  Using in-memory storage:
$reviewManager = new ReviewManager();

$reviewId1 = $reviewManager->addReview('user123', 'This is a great product!');
echo "Review ID: " . $reviewId1 . "
";

$review1 = $reviewManager->getReviewById($reviewId1);
echo "Review 1:
" . print_r($review1, true) . "
";


// 2.  With a database (requires database setup - PDO is used here)
//    *  Replace the placeholder connection details with your actual credentials.
try {
    $dbConnection = new PDO("mysql:host=localhost;dbname=your_database", "your_user", "your_password");
    $dbConnection = new ReviewManager($dbConnection);
    $reviewId2 = $dbConnection->addReviewToDB('user456', 'Excellent service!');
    echo "Review ID (from DB): " . $reviewId2 . "
";
} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage() . "
";
}


<?php

/**
 * Class Review
 *
 * Handles the creation, retrieval, and display of user reviews.
 */
class Review
{
    private $db; // Database connection (for demonstration - replace with your actual DB connection)

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Creates a new review.
     *
     * @param int $productId  The ID of the product being reviewed.
     * @param string $username The username of the reviewer.
     * @param string $rating   The rating given (e.g., 1-5).
     * @param string $comment  The user's review comment.
     *
     * @return int|false  The ID of the newly created review on success, or false on failure.
     */
    public function createReview(int $productId, string $username, string $rating, string $comment)
    {
        // Validate input (basic - you should add more robust validation)
        if (!$productId || !$username || !$rating || !$comment) {
            return false;
        }

        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return false;
        }

        // Prepare SQL query
        $sql = "INSERT INTO reviews (product_id, username, rating, comment)
                VALUES (:product_id, :username, :rating, :comment)";

        // Prepare statement
        $stmt = $this->db->prepare($sql); // Assuming $this->db has prepare() method

        // Bind parameters
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':comment', $comment);

        // Execute query
        $result = $stmt->execute();

        // Check for errors
        if (!$result) {
            return false;
        }

        // Get the ID of the new review
        $reviewId = $this->db->lastInsertId();

        return (int)$reviewId;
    }


    /**
     * Retrieves all reviews for a given product.
     *
     * @param int $productId  The ID of the product.
     *
     * @return array An array of review objects, or an empty array if no reviews are found.
     */
    public function getReviewsByProduct(int $productId)
    {
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();

        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = new ReviewObject($row); // Assuming you have a ReviewObject class
        }

        return $reviews;
    }


    /**
     *  A simplified ReviewObject class for easy access
     */
    private function __construct($data)
    {
      $this->id = $data['id'];
      $this->product_id = $data['product_id'];
      $this->username = $data['username'];
      $this->rating = $data['rating'];
      $this->comment = $data['comment'];
      $this->created_at = $data['created_at'];
    }


    //Getter methods
    public function getId()
    {
      return $this->id;
    }

    public function getProductId()
    {
      return $this->product_id;
    }

    public function getUsername()
    {
      return $this->username;
    }

    public function getRating()
    {
      return $this->rating;
    }

    public function getComment()
    {
      return $this->comment;
    }

    public function getCreatedAt()
    {
      return $this->created_at;
    }
}


/**
 * ReviewObject Class - a simple object to hold review data.
 */
class ReviewObject
{
    public $id;
    public $product_id;
    public $username;
    public $rating;
    public $comment;
    public $created_at;


    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->product_id = $data['product_id'];
        $this->username = $data['username'];
        $this->rating = $data['rating'];
        $this->comment = $data['comment'];
        $this->created_at = $data['created_at'];
    }
}


// Example Usage (with a dummy database connection)
// This will need to be replaced with your actual database setup
$db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');


$review = new Review($db);

// Create a new review
$reviewId = $review->createReview(123, 'john_doe', 5, 'Great product!  Highly recommended.');

if ($reviewId) {
    echo "New review created with ID: " . $reviewId . "<br>";
} else {
    echo "Failed to create review.<br>";
}

// Get reviews for product 123
$reviews = $review->getReviewsByProduct(123);

echo "<h2>Reviews for Product 123:</h2>";
foreach ($reviews as $review) {
    echo "<strong>Username:</strong> " . $review->getUsername() . "<br>";
    echo "<strong>Rating:</strong> " . $review->getRating() . " stars<br>";
    echo "<strong>Comment:</strong> " . $review->getComment() . "<br>";
    echo "<hr>";
}
?>
