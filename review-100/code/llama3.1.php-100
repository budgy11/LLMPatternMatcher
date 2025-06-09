
<?php

// Database connection settings
$host = 'localhost';
$dbname = 'reviews_database';
$username = 'your_username';
$password = 'your_password';

// Connect to database
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

function createUserReview($conn, $product_id, $user_id, $rating, $comment) {
    // SQL query to create a new review
    $sql = "INSERT INTO reviews (product_id, user_id, rating, comment)
            VALUES (:product_id, :user_id, :rating, :comment)";
    
    // Prepare statement and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    
    // Execute statement
    try {
        if ($stmt->execute()) {
            return true;  // Review created successfully
        } else {
            throw new PDOException("Failed to create review");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function getUserReviews($conn, $user_id) {
    // SQL query to retrieve all reviews for a user
    $sql = "SELECT * FROM reviews WHERE user_id = :user_id";
    
    // Prepare statement and bind parameter
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    
    // Execute statement and fetch results
    try {
        if ($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            throw new PDOException("Failed to retrieve reviews");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return array();  // Return an empty array on error
    }
}

function updateReview($conn, $review_id, $product_id, $user_id, $rating, $comment) {
    // SQL query to update an existing review
    $sql = "UPDATE reviews SET product_id = :product_id, user_id = :user_id, rating = :rating, comment = :comment WHERE id = :review_id";
    
    // Prepare statement and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':review_id', $review_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    
    // Execute statement
    try {
        if ($stmt->execute()) {
            return true;  // Review updated successfully
        } else {
            throw new PDOException("Failed to update review");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function deleteReview($conn, $review_id) {
    // SQL query to delete a review
    $sql = "DELETE FROM reviews WHERE id = :review_id";
    
    // Prepare statement and bind parameter
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':review_id', $review_id);
    
    // Execute statement
    try {
        if ($stmt->execute()) {
            return true;  // Review deleted successfully
        } else {
            throw new PDOException("Failed to delete review");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Example usage:
$user_id = 12345;  // Replace with actual user ID

// Create a new review
if (createUserReview($conn, 67890, $user_id, 4, 'Good product')) {
    echo "Review created successfully!";
} else {
    echo "Failed to create review.";
}

// Retrieve all reviews for the specified user
$reviews = getUserReviews($conn, $user_id);
foreach ($reviews as $review) {
    echo "ID: " . $review['id'] . "
";
    echo "Product ID: " . $review['product_id'] . "
";
    echo "User ID: " . $review['user_id'] . "
";
    echo "Rating: " . $review['rating'] . "
";
    echo "Comment: " . $review['comment'] . "

";
}

// Update an existing review
if (updateReview($conn, 12345, 67890, $user_id, 5, 'Excellent product')) {
    echo "Review updated successfully!";
} else {
    echo "Failed to update review.";
}

// Delete a review
if (deleteReview($conn, 12345)) {
    echo "Review deleted successfully!";
} else {
    echo "Failed to delete review.";
}


class Review {
  private $id;
  private $product_id;
  private $user_id;
  private $rating;
  private $content;

  public function __construct($data = array()) {
    if (!empty($data)) {
      $this->loadFromData($data);
    }
  }

  public function loadFromData(array $data) {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }

  public static function createReview(array $reviewData, $userId) {
    // Basic validation
    if (!isset($reviewData['rating']) || !in_array($reviewData['rating'], array(1, 2, 3, 4, 5))) {
      throw new Exception('Invalid rating');
    }

    if (empty($reviewData['content'])) {
      throw new Exception('Content is required');
    }

    // Create new review
    $newReview = new Review(array(
      'product_id' => isset($reviewData['product_id']) ? $reviewData['product_id'] : null,
      'user_id' => $userId,
      'rating' => $reviewData['rating'],
      'content' => $reviewData['content']
    ));

    // Save review to database (not implemented here)
    return $newReview;
  }

  public static function getReviewsByProduct($productId) {
    // Retrieve reviews from database (not implemented here)
    return array();
  }

  public static function updateReview($reviewId, array $data) {
    // Basic validation
    if (!isset($data['rating']) || !in_array($data['rating'], array(1, 2, 3, 4, 5))) {
      throw new Exception('Invalid rating');
    }

    if (empty($data['content'])) {
      throw new Exception('Content is required');
    }

    // Update review
    $updatedReview = new Review(array(
      'id' => $reviewId,
      'rating' => $data['rating'],
      'content' => $data['content']
    ));

    // Save updated review to database (not implemented here)
    return $updatedReview;
  }
}


// Create new review
$reviewData = array(
  'product_id' => 123,
  'rating' => 4,
  'content' => 'Great product!'
);
$newReview = Review::createReview($reviewData, 456);

// Retrieve reviews by product ID
$productReviews = Review::getReviewsByProduct(123);

// Update existing review
$updateData = array(
  'rating' => 5,
  'content' => 'Even better now!'
);
$updatedReview = Review::updateReview(789, $updateData);


// users.php

class User {
    private $id;
    private $name;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    // Getters and setters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
}

// reviews.php

class Review {
    private $id;
    private $userId;
    private $productName;
    private $rating;
    private $content;

    public function __construct($id, $userId, $productName, $rating, $content) {
        $this->id = $id;
        $this->userId = $userId;
        $this->productName = $productName;
        $this->rating = $rating;
        $this->content = $content;
    }

    // Getters and setters
    public function getId() { return $this->id; }
    public function getUserId() { return $this->userId; }
    public function getProductName() { return $this->productName; }
    public function getRating() { return $this->rating; }
    public function getContent() { return $this->content; }
}

// reviewSystem.php

class ReviewSystem {
    private $db;

    public function __construct($dbName) {
        $this->db = new PDO("mysql:host=localhost;dbname=$dbName", "username", "password");
    }

    // Add a review
    public function addReview(User $user, $productName, $rating, $content) {
        try {
            $query = $this->db->prepare("INSERT INTO reviews (user_id, product_name, rating, content) VALUES (:userId, :product_name, :rating, :content)");
            $query->bindParam(":userId", $user->getId());
            $query->bindParam(":product_name", $productName);
            $query->bindParam(":rating", $rating);
            $query->bindParam(":content", $content);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Get a review
    public function getReview($id) {
        try {
            $query = $this->db->prepare("SELECT * FROM reviews WHERE id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $reviewData = $query->fetch(PDO::FETCH_ASSOC);
            if ($reviewData === false) return null;
            return new Review($reviewData['id'], $reviewData['user_id'], $reviewData['product_name'], $reviewData['rating'], $reviewData['content']);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    // Get all reviews for a user
    public function getReviewsForUser($id) {
        try {
            $query = $this->db->prepare("SELECT * FROM reviews WHERE user_id = :id");
            $query->bindParam(":id", $id);
            $query->execute();
            $reviewData = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($reviewData === false) return array();
            $reviews = array();
            foreach ($reviewData as $data) {
                $reviews[] = new Review($data['id'], $data['user_id'], $data['product_name'], $data['rating'], $data['content']);
            }
            return $reviews;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return array();
        }
    }

    // Edit a review
    public function editReview(Review $review, $newProductName = null, $newRating = null, $newContent = null) {
        try {
            if ($newProductName !== null) $query = $this->db->prepare("UPDATE reviews SET product_name = :product_name WHERE id = :id");
            else if ($newRating !== null) $query = $this->db->prepare("UPDATE reviews SET rating = :rating WHERE id = :id");
            else if ($newContent !== null) $query = $this->db->prepare("UPDATE reviews SET content = :content WHERE id = :id");
            else return false;
            $query->bindParam(":product_name", $newProductName);
            $query->bindParam(":rating", $newRating);
            $query->bindParam(":content", $newContent);
            $query->bindParam(":id", $review->getId());
            $query->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Delete a review
    public function deleteReview(Review $review) {
        try {
            $this->db->prepare("DELETE FROM reviews WHERE id = :id")->bindParam(":id", $review->getId())->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}

// Usage
$reviewSystem = new ReviewSystem('your_database_name');

$user = new User(1, 'John Doe');
$productName = 'Example Product';
$rating = 5;
$content = 'This product is great!';

if ($reviewSystem->addReview($user, $productName, $rating, $content)) {
    echo "Review added successfully!";
} else {
    echo "Error adding review.";
}

$reviews = $reviewSystem->getReviewsForUser(1);
foreach ($reviews as $review) {
    echo "Product: " . $review->getProductName() . ", Rating: " . $review->getRating() . ", Content: " . $review->getContent();
}


<?php

// Review model
class Review {
    public $id;
    public $product_id;
    public $user_id;
    public $rating;
    public $review_text;

    function __construct($id, $product_id, $user_id, $rating, $review_text) {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->user_id = $user_id;
        $this->rating = $rating;
        $this->review_text = $review_text;
    }
}

// Review controller
class ReviewController {
    private $reviews;

    function __construct() {
        // Initialize reviews array (in a real application, this would likely come from a database)
        $this->reviews = array();
    }

    function add_review($product_id, $user_id, $rating, $review_text) {
        // Create new review object
        $new_review = new Review(count($this->reviews) + 1, $product_id, $user_id, $rating, $review_text);

        // Add review to reviews array
        $this->reviews[] = $new_review;

        return $new_review;
    }

    function get_reviews_for_product($product_id) {
        // Filter reviews for the specified product
        $product_reviews = array_filter($this->reviews, function ($review) use ($product_id) {
            return $review->product_id == $product_id;
        });

        return $product_reviews;
    }
}

// Example usage:
$review_controller = new ReviewController();

$new_review = $review_controller->add_review(123, 456, 5, "Great product!");
echo "New review created: 
";
print_r($new_review);

$product_reviews = $review_controller->get_reviews_for_product(123);
echo "
Reviews for product 123:
";
print_r($product_reviews);

?>


<?php

// Assume we have a database connection established with MySQLi
$mysqli = new mysqli("localhost", "username", "password", "database");

function createReview($productId, $rating, $reviewText) {
    global $mysqli;
    
    // Validate input
    if (!is_int($rating)) {
        throw new InvalidArgumentException("Rating must be an integer");
    }
    if (!preg_match('/^[0-9]+$/', $rating)) {
        throw new InvalidArgumentException("Rating must be a number between 1 and 5");
    }
    if (empty($reviewText) || strlen($reviewText) > 2000) {
        throw new InvalidArgumentException("Review text cannot be empty or longer than 2000 characters");
    }
    
    // Prepare query
    $query = "INSERT INTO reviews (product_id, user_id, rating, review_text)
              VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    
    // Bind parameters
    $stmt->bind_param("iii", $productId, 0, $rating, $reviewText); // Replace `0` with actual user ID
    
    // Execute query
    if (!$stmt->execute()) {
        throw new Exception("Failed to create review: " . $mysqli->error);
    }
    
    return $mysqli->insert_id;
}

function getReviews($productId) {
    global $mysqli;
    
    // Prepare query
    $query = "SELECT * FROM reviews WHERE product_id = ?";
    $stmt = $mysqli->prepare($query);
    
    // Bind parameter
    $stmt->bind_param("i", $productId);
    
    // Execute query and fetch results
    if (!$stmt->execute()) {
        throw new Exception("Failed to retrieve reviews: " . $mysqli->error);
    }
    $result = $stmt->get_result();
    return $result;
}

function displayReviews($reviews) {
    echo "<h2>Reviews</h2>";
    foreach ($reviews as $review) {
        echo "<p>" . $review['rating'] . "/5 stars: " . $review['review_text'] . "</p>";
    }
}

// Example usage
try {
    $productId = 1; // Replace with actual product ID
    
    $reviews = getReviews($productId);
    
    displayReviews($reviews);
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}


<?php

// assume we have the following database table for reviews
// CREATE TABLE reviews (
//   id INT PRIMARY KEY AUTO_INCREMENT,
//   product_id INT,
//   user_id INT,
//   rating TINYINT,
//   comment TEXT,
//   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// );

class Review {
  private $id;
  private $product_id;
  private $user_id;
  private $rating;
  private $comment;

  public function __construct($data) {
    $this->id = $data['id'];
    $this->product_id = $data['product_id'];
    $this->user_id = $data['user_id'];
    $this->rating = $data['rating'];
    $this->comment = $data['comment'];
  }

  public function getId() { return $this->id; }
  public function getProductId() { return $this->product_id; }
  public function getUserId() { return $this->user_id; }
  public function getRating() { return $this->rating; }
  public function getComment() { return $this->comment; }

  // calculate average rating for a product
  public static function getAverageRating($productId) {
    global $db;
    $query = "SELECT AVG(rating) AS average FROM reviews WHERE product_id = :product_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
  }

  // create a new review
  public static function createReview($data) {
    global $db;
    $query = "INSERT INTO reviews (product_id, user_id, rating, comment)
              VALUES (:product_id, :user_id, :rating, :comment)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':product_id', $data['product_id'], PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':rating', $data['rating'], PDO::PARAM_INT);
    $stmt->bindParam(':comment', $data['comment'], PDO::PARAM_STR);
    return $stmt->execute();
  }

  // update an existing review
  public static function updateReview($reviewId, $data) {
    global $db;
    $query = "UPDATE reviews SET product_id = :product_id, user_id = :user_id,
                        rating = :rating, comment = :comment
              WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $reviewId, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $data['product_id'], PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':rating', $data['rating'], PDO::PARAM_INT);
    $stmt->bindParam(':comment', $data['comment'], PDO::PARAM_STR);
    return $stmt->execute();
  }

  // delete a review
  public static function deleteReview($reviewId) {
    global $db;
    $query = "DELETE FROM reviews WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $reviewId, PDO::PARAM_INT);
    return $stmt->execute();
  }
}

// assume we have a database connection object called $db

function getUserReviews($userId) {
  global $db;
  $query = "SELECT * FROM reviews WHERE user_id = :user_id";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_CLASS, 'Review');
}

function getProductReviews($productId) {
  global $db;
  $query = "SELECT * FROM reviews WHERE product_id = :product_id";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_CLASS, 'Review');
}

function displayReviews() {
  // get all reviews
  global $db;
  $query = "SELECT * FROM reviews";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // loop through each review and echo its details
  foreach ($reviews as $review) {
    echo 'Product: ' . $review['product_id'] . '<br>';
    echo 'User: ' . $review['user_id'] . '<br>';
    echo 'Rating: ' . $review['rating'] . '/5<br>';
    echo 'Comment: ' . $review['comment'] . '<br><hr>';
  }
}

?>


<?php

// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

// Function to display all reviews for a product
function get_reviews($product_id) {
  global $conn;
  $stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = :id");
  $stmt->bindParam(':id', $product_id);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to display a single review
function get_review($review_id) {
  global $conn;
  $stmt = $conn->prepare("SELECT * FROM reviews WHERE id = :id");
  $stmt->bindParam(':id', $review_id);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to create a new review
function create_review($product_id, $user_id, $review, $rating) {
  global $conn;
  $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, review, rating)
                          VALUES (:product_id, :user_id, :review, :rating)");
  $stmt->bindParam(':product_id', $product_id);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->bindParam(':review', $review);
  $stmt->bindParam(':rating', $rating);
  return $stmt->execute();
}

// Function to update a review
function update_review($review_id, $new_review) {
  global $conn;
  $stmt = $conn->prepare("UPDATE reviews SET review = :review WHERE id = :id");
  $stmt->bindParam(':id', $review_id);
  $stmt->bindParam(':review', $new_review);
  return $stmt->execute();
}

// Function to delete a review
function delete_review($review_id) {
  global $conn;
  $stmt = $conn->prepare("DELETE FROM reviews WHERE id = :id");
  $stmt->bindParam(':id', $review_id);
  return $stmt->execute();
}

?>


$product_id = 1;
$reviews = get_reviews($product_id);
foreach ($reviews as $review) {
  echo "Review by " . $review['user_id'] . ": " . $review['review'];
}


$product_id = 1;
$user_id = 2;
$review_text = "This product is great!";
$rating = 5;
create_review($product_id, $user_id, $review_text, $rating);


$review_id = 1;
$new_review_text = "I changed my mind about this product!";
update_review($review_id, $new_review_text);


$review_id = 1;
delete_review($review_id);


<?php

// configuration
require_once 'config.php';

// get the product id from the URL parameter
if (isset($_GET['product_id'])) {
  $product_id = $_GET['product_id'];
} else {
  echo "Product ID not specified.";
  exit;
}

// connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// get the product name from the products table
$product_name_query = "SELECT name FROM products WHERE id = '$product_id'";
$product_name_result = $conn->query($product_name_query);
if (!$product_name_result) {
  echo "Error getting product name.";
  exit;
}
$product_name = $product_name_result->fetch_assoc()['name'];

// display reviews for the product
$reviews_query = "SELECT * FROM reviews WHERE product_id = '$product_id'";
$reviews_result = $conn->query($reviews_query);
if (!$reviews_result) {
  echo "Error getting reviews.";
  exit;
}
$reviews = array();
while ($review = $reviews_result->fetch_assoc()) {
  $reviews[] = $review;
}

// display form for submitting new review
?>
<h2>Reviews for <?php echo $product_name; ?></h2>

<?php if (isset($_SESSION['user_id'])) : ?>
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="rating">Rating:</label>
    <select name="rating" id="rating">
      <?php for ($i = 1; $i <= 5; $i++) : ?>
        <option value="<?php echo $i; ?>"><?php echo $i . '/5'; ?></option>
      <?php endfor; ?>
    </select>
    <br><br>
    <label for="review">Review:</label>
    <textarea name="review" id="review"></textarea>
    <br><br>
    <input type="submit" value="Submit Review">
  </form>
<?php endif; ?>

<h3>Reviews:</h3>

<ul>
  <?php foreach ($reviews as $review) : ?>
    <li>
      Rating: <?php echo $review['rating']; ?>/5
      <br>
      Review: <?php echo nl2br($review['review']); ?>
      <br>
      By: <?php // get the user name from the users table, using the review's user_id; ?>
    </li>
  <?php endforeach; ?>
</ul>

<?php

// check if form has been submitted
if (isset($_POST['submit'])) {
  $rating = $_POST['rating'];
  $review = $_POST['review'];

  // insert new review into database
  $new_review_query = "INSERT INTO reviews (user_id, product_id, rating, review) VALUES ('$product_id', '$product_id', '$rating', '$review')";
  if ($conn->query($new_review_query)) {
    echo "Review submitted successfully.";
  } else {
    echo "Error submitting review.";
  }
}

// close database connection
$conn->close();

?>


// Review class
class Review {
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=reviews', 'username', 'password');
    }

    // Add a review
    public function addReview($product_id, $user_name, $rating, $comment) {
        try {
            $query = "INSERT INTO reviews (product_id, user_name, rating, comment) VALUES (:product_id, :user_name, :rating, :comment)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'product_id' => $product_id,
                'user_name' => $user_name,
                'rating' => $rating,
                'comment' => $comment
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Get reviews for a product
    public function getReviews($product_id) {
        try {
            $query = "SELECT * FROM reviews WHERE product_id = :product_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['product_id' => $product_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return array();
        }
    }

    // Calculate average rating for a product
    public function getAverageRating($product_id) {
        try {
            $query = "SELECT AVG(rating) AS average FROM reviews WHERE product_id = :product_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['product_id' => $product_id]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return 0;
        }
    }
}

// Example usage
$review = new Review();

// Add a review for product with id=1
if ($review->addReview(1, 'John Doe', 5, 'Great product!')) {
    echo 'Review added successfully!';
} else {
    echo 'Error adding review.';
}

// Get reviews for product with id=1
$reviews = $review->getReviews(1);
echo 'Reviews for product with id=1:';
foreach ($reviews as $review) {
    echo '<br>' . $review['user_name'] . ': ' . $review['rating'] . '/5 - ' . $review['comment'];
}

// Calculate average rating for product with id=1
$averageRating = $review->getAverageRating(1);
echo '<br>Average rating: ' . $averageRating;


// database connection settings
$host = 'localhost';
$dbname = 'reviews_db';
$username = 'root';
$password = '';

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  class ReviewSystem {
    private $conn;

    public function __construct($conn) {
      $this->conn = $conn;
    }

    // add a new review
    public function addReview($user_id, $product_id, $rating, $review_text) {
      $stmt = $this->conn->prepare("INSERT INTO reviews (user_id, product_id, rating, review_text) VALUES (:user_id, :product_id, :rating, :review_text)");
      $stmt->bindParam(':user_id', $user_id);
      $stmt->bindParam(':product_id', $product_id);
      $stmt->bindParam(':rating', $rating);
      $stmt->bindParam(':review_text', $review_text);
      $stmt->execute();
    }

    // get all reviews for a product
    public function getReviewsForProduct($product_id) {
      $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE product_id = :product_id");
      $stmt->bindParam(':product_id', $product_id);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // get all products
    public function getProducts() {
      $stmt = $this->conn->prepare("SELECT * FROM products");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // add a new product
    public function addProduct($name, $description) {
      $stmt = $this->conn->prepare("INSERT INTO products (name, description) VALUES (:name, :description)");
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':description', $description);
      $stmt->execute();
    }
  }

  // usage example
  $reviewSystem = new ReviewSystem($conn);

  // add a new product
  $reviewSystem->addProduct('Test Product', 'This is a test product');

  // get all products
  $products = $reviewSystem->getProducts();
  print_r($products);

  // add a new review
  $reviewSystem->addReview(1, 1, 5, 'Great product!');

  // get all reviews for a product
  $reviews = $reviewSystem->getReviewsForProduct(1);
  print_r($reviews);

} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}


<?php

class Review {
    private $id;
    private $product_id;
    private $user_id;
    private $rating;
    private $review;

    public function __construct($product_id, $user_id, $rating, $review) {
        $this->product_id = $product_id;
        $this->user_id = $user_id;
        $this->rating = $rating;
        $this->review = $review;
    }

    public function getId() {
        return $this->id;
    }

    public function getProductID() {
        return $this->product_id;
    }

    public function getUserID() {
        return $this->user_id;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getReview() {
        return $this->review;
    }
}

class ReviewSystem {
    private $reviews = array();

    public function addReview(Review $review) {
        if (!isset($this->reviews[$review->getProductID()])) {
            $this->reviews[$review->getProductID()] = array();
        }

        $this->reviews[$review->getProductID()][count($this->reviews[$review->getProductID()])] = $review;
    }

    public function updateReview(Review $review) {
        if (isset($this->reviews[$review->getProductID()])) {
            foreach ($this->reviews[$review->getProductID()] as &$item) {
                if ($item->getUserID() == $review->getUserID()) {
                    $item = $review;
                }
            }
        } else {
            $this->addReview($review);
        }
    }

    public function deleteReview(Review $review) {
        if (isset($this->reviews[$review->getProductID()])) {
            foreach ($this->reviews[$review->getProductID()] as &$item) {
                if ($item->getUserID() == $review->getUserID()) {
                    unset($item);
                    return;
                }
            }
        }
    }

    public function getReviewsForProduct($product_id) {
        if (isset($this->reviews[$product_id])) {
            return $this->reviews[$product_id];
        } else {
            return array();
        }
    }
}

// Example usage
$review_system = new ReviewSystem();

$review1 = new Review(1, 1, 5, 'Great product!');
$review2 = new Review(1, 1, 3, 'Good but not perfect.');
$review3 = new Review(1, 2, 4, 'Nice one!');

$review_system->addReview($review1);
$review_system->addReview($review2);

echo "Reviews for product 1:
";
foreach ($review_system->getReviewsForProduct(1) as $review) {
    echo 'Rating: ' . $review->getRating() . ', Review: ' . $review->getReview() . "
";
}

$review_system->updateReview($review3);
echo "Updated reviews for product 1:
";
foreach ($review_system->getReviewsForProduct(1) as $review) {
    echo 'Rating: ' . $review->getRating() . ', Review: ' . $review->getReview() . "
";
}

$review_system->deleteReview($review2);
echo "Updated reviews for product 1:
";
foreach ($review_system->getReviewsForProduct(1) as $review) {
    echo 'Rating: ' . $review->getRating() . ', Review: ' . $review->getReview() . "
";
}

?>


// db.php

class DB {
  private $host = 'localhost';
  private $username = 'your_username';
  private $password = 'your_password';
  private $dbname = 'your_database';

  public function connect() {
    try {
      $conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbname, $this->username, $this->password);
      return $conn;
    } catch (PDOException $e) {
      echo 'Error connecting to database: ' . $e->getMessage();
    }
  }

  public function insertReview($reviewData) {
    $sql = "INSERT INTO reviews SET user_id = :user_id, product_name = :product_name, rating = :rating, review = :review";
    try {
      $conn = $this->connect();
      $stmt = $conn->prepare($sql);
      $stmt->execute([
        ':user_id' => $reviewData['user_id'],
        ':product_name' => $reviewData['product_name'],
        ':rating' => $reviewData['rating'],
        ':review' => $reviewData['review']
      ]);
    } catch (PDOException $e) {
      echo 'Error inserting review: ' . $e->getMessage();
    }
  }

  public function getReviews() {
    $sql = "SELECT * FROM reviews";
    try {
      $conn = $this->connect();
      $stmt = $conn->query($sql);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo 'Error fetching reviews: ' . $e->getMessage();
    }
  }

  public function getUserReviews($userId) {
    $sql = "SELECT * FROM reviews WHERE user_id = :user_id";
    try {
      $conn = $this->connect();
      $stmt = $conn->prepare($sql);
      $stmt->execute([
        ':user_id' => $userId
      ]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo 'Error fetching user reviews: ' . $e->getMessage();
    }
  }

  public function deleteReview($reviewId) {
    $sql = "DELETE FROM reviews WHERE id = :id";
    try {
      $conn = $this->connect();
      $stmt = $conn->prepare($sql);
      $stmt->execute([
        ':id' => $reviewId
      ]);
    } catch (PDOException $e) {
      echo 'Error deleting review: ' . $e->getMessage();
    }
  }

}


class Review {
  private $db;

  public function __construct() {
    $this->db = new DB();
  }

  public function insert($reviewData) {
    return $this->db->insertReview($reviewData);
  }

  public function getAllReviews() {
    return $this->db->getReviews();
  }

  public function getUserReviews($userId) {
    return $this->db->getUserReviews($userId);
  }

  public function deleteReview($reviewId) {
    return $this->db->deleteReview($reviewId);
  }
}


$review = new Review();

// Insert a new review
$reviewData = [
  'user_id' => 1,
  'product_name' => 'Product Name',
  'rating' => 5,
  'review' => 'This is a great product!'
];
$review->insert($reviewData);

// Get all reviews
$reviews = $review->getAllReviews();
print_r($reviews);

// Get user's reviews
$userReviews = $review->getUserReviews(1);
print_r($userReviews);

// Delete a review
$reviewId = 1;
$review->deleteReview($reviewId);


// Database connection settings
$host = 'your_host';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

// Create a database connection
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

function getUserReviews($userId) {
    global $conn;
    
    try {
        // Prepare the query to get reviews for a specific user
        $stmt = $conn->prepare("SELECT r.*, p.name as product_name FROM reviews r JOIN products p ON r.product_id = p.id WHERE r.user_id = :user_id");
        
        // Bind the parameters
        $stmt->bindParam(':user_id', $userId);
        
        // Execute the query and fetch results
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $reviews;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return array();
    }
}

function getProductReviews($productId) {
    global $conn;
    
    try {
        // Prepare the query to get reviews for a specific product
        $stmt = $conn->prepare("SELECT r.*, u.name as user_name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = :product_id");
        
        // Bind the parameters
        $stmt->bindParam(':product_id', $productId);
        
        // Execute the query and fetch results
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $reviews;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return array();
    }
}

function addReview($userId, $productId, $rating, $reviewText) {
    global $conn;
    
    try {
        // Prepare the query to insert a new review
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, review) VALUES (:user_id, :product_id, :rating, :review)");
        
        // Bind the parameters
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':review', $reviewText);
        
        // Execute the query
        $stmt->execute();
        
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Example usage:

// Get reviews for a specific user
$userId = 1;
$reviews = getUserReviews($userId);
print_r($reviews);

// Get reviews for a specific product
$productId = 1;
$reviews = getProductReviews($productId);
print_r($reviews);

// Add a new review
$userId = 1;
$productId = 1;
$rating = 5;
$reviewText = "This is an awesome product!";
$isAdded = addReview($userId, $productId, $rating, $reviewText);
echo $isAdded ? 'Review added successfully!' : 'Error adding review.';


// config/database.php
return [
    'connections' => [
        'mysql' => [
            'driver'   => 'mysql',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'reviews'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
        ],
    ],
];

// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    protected $fillable = ['username', 'email'];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

// app/Models/Review.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'rating',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// app/Controllers/UserReviewController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Review;

class UserReviewController extends Controller
{
    public function index()
    {
        // Display a list of reviews for the current user.
        $reviews = Review::where('user_id', auth()->id())->get();
        return view('review.index', compact('reviews'));
    }

    public function create()
    {
        // Show the form to create a new review.
        return view('review.create');
    }

    public function store(Request $request)
    {
        // Handle the form submission and save the review.
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'rating' => 'required|numeric|between:1,5',
        ]);

        Review::create($validatedData);
        return redirect()->route('review.index')->withSuccess('Review created successfully!');
    }

    public function edit(Review $review)
    {
        // Display the form to update an existing review.
        return view('review.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        // Update the review based on the submitted data.
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'rating' => 'required|numeric|between:1,5',
        ]);

        $review->update($validatedData);
        return redirect()->route('review.index')->withSuccess('Review updated successfully!');
    }

    public function destroy(Review $review)
    {
        // Delete the review.
        $review->delete();
        return redirect()->route('review.index')->withSuccess('Review deleted successfully!');
    }
}

// routes/web.php
Route::get('/reviews', [UserReviewController::class, 'index'])->name('review.index');
Route::get('/reviews/create', [UserReviewController::class, 'create'])->name('review.create');
Route::post('/reviews', [UserReviewController::class, 'store'])->name('review.store');
Route::get('/reviews/{review}/edit', [UserReviewController::class, 'edit'])->name('review.edit');
Route::patch('/reviews/{review}', [UserReviewController::class, 'update'])->name('review.update');
Route::delete('/reviews/{review}', [UserReviewController::class, 'destroy'])->name('review.destroy');


// resources/views/review/index.blade.php
@foreach ($reviews as $review)
    <div>
        {{ $review->title }} ({{ $review->rating }}/5 stars)
        <p>{{ $review->content }}</p>
        @if ($review->status === 'Approved')
            Approved by Admin
        @elseif ($review->status === 'Denied')
            Denied by Admin
        @endif
    </div>
@endforeach

// resources/views/review/create.blade.php
<form method="POST" action="{{ route('review.store') }}">
    @csrf
    <label for="title">Title:</label>
    <input type="text" id="title" name="title"><br><br>
    <label for="content">Content:</label>
    <textarea id="content" name="content"></textarea><br><br>
    <label for="rating">Rating (1-5):</label>
    <select id="rating" name="rating">
        @for ($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select><br><br>
    <button type="submit">Create Review</button>
</form>

// resources/views/review/edit.blade.php
<form method="PATCH" action="{{ route('review.update', $review) }}">
    @csrf
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" value="{{ $review->title }}"><br><br>
    <label for="content">Content:</label>
    <textarea id="content" name="content">{{ $review->content }}</textarea><br><br>
    <label for="rating">Rating (1-5):</label>
    <select id="rating" name="rating">
        @for ($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}" {{ $review->rating === $i ? 'selected' : '' }}">{{ $i }}</option>
        @endfor
    </select><br><br>
    <button type="submit">Update Review</button>
</form>


// config.php: Database configuration
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>

// review.php
<?php
require_once 'config.php';

if (isset($_POST['review'])) {
    // Handle submission of a new review
    $rating = $_POST['rating'];
    $user_name = $_POST['user_name'];
    $review = $_POST['review'];
    $product_id = (int) $_GET['id'];

    try {
        $stmt = $pdo->prepare('INSERT INTO reviews SET product_id = :product_id, user_name = :user_name, rating = :rating, review = :review');
        $stmt->execute([
            ':product_id' => $product_id,
            ':user_name' => $user_name,
            ':rating' => (float) $rating,
            ':review' => $review
        ]);

        echo 'Review submitted successfully!';
    } catch (PDOException $e) {
        echo 'Failed to submit review: ' . $e->getMessage();
    }
} else if (isset($_GET['id'])) {
    // Display reviews for a specific product
    $product_id = (int) $_GET['id'];

    try {
        $stmt = $pdo->prepare('SELECT * FROM reviews WHERE product_id = :product_id');
        $stmt->execute([':product_id' => $product_id]);

        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo 'Reviews for "' . getProductName($product_id) . '":';
        foreach ($reviews as $review) {
            // Display average rating
            $avgRating = calculateAverageRating($product_id);
            echo '<h2>' . $review['user_name'] . '</h2>';
            echo '<p>Rating: <strong>' . $review['rating'] . '/5</strong></p>';
            echo '<p>Review: ' . $review['review'] . '</p>';
        }
    } catch (PDOException $e) {
        echo 'Failed to retrieve reviews: ' . $e->getMessage();
    }

    // Helper function to calculate average rating
    function calculateAverageRating($product_id)
    {
        try {
            $stmt = $pdo->prepare('SELECT AVG(rating) AS avg_rating FROM reviews WHERE product_id = :product_id');
            $stmt->execute([':product_id' => (int) $product_id]);

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            echo 'Failed to calculate average rating: ' . $e->getMessage();
        }
    }

    // Helper function to get product name
    function getProductName($id)
    {
        try {
            $stmt = $pdo->prepare('SELECT name FROM products WHERE id = :id');
            $stmt->execute([':id' => (int) $id]);

            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            echo 'Failed to retrieve product name: ' . $e->getMessage();
        }
    }
}
?>

// review-form.php
<?php
require_once 'config.php';
?>

<form method="post" action="review.php">
    <label for="rating">Rating:</label>
    <input type="number" id="rating" name="rating" step="0.01" required>

    <br><br>

    <label for="user_name">Your Name:</label>
    <input type="text" id="user_name" name="user_name" required>

    <br><br>

    <textarea id="review" name="review" cols="30" rows="10" placeholder="Write your review here..."></textarea>

    <input type="hidden" name="product_id" value="<?php echo (int) $_GET['id']; ?>">

    <button type="submit" name="review">Submit Review</button>
</form>


class User {
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');
    }

    public function createUser($username, $password) {
        $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
        $stmt->execute();
    }

    public function login($username, $password) {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        return false;
    }
}


class Product {
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');
    }

    public function createProduct($name, $description) {
        $query = "INSERT INTO products (name, description) VALUES (:name, :description)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
    }

    public function getProduct($id) {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}


class Review {
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');
    }

    public function createReview($product_id, $user_id, $rating, $review) {
        $query = "INSERT INTO reviews (product_id, user_id, rating, review) VALUES (:product_id, :user_id, :rating, :review)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':review', $review);
        $stmt->execute();
    }

    public function getReviewsForProduct($id) {
        $query = "SELECT * FROM reviews WHERE product_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}


class ReviewController {
    private $review;

    public function __construct() {
        $this->review = new Review();
    }

    public function createReview($product_id, $user_id, $rating, $review) {
        $this->review->createReview($product_id, $user_id, $rating, $review);
        return "Review created successfully!";
    }

    public function getReviewsForProduct($id) {
        return $this->review->getReviewsForProduct($id);
    }
}


$router->get('/reviews/{id}', 'ReviewController@getReviewsForProduct');
$router->post('/reviews', 'ReviewController@createReview');


@extends('layout')

@section('content')
    <h1>Leave a review for {{ $product['name'] }}</h1>
    <form method="POST" action="/reviews">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product['id'] }}">
        <label for="rating">Rating:</label>
        <select id="rating" name="rating">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <label for="review">Review:</label>
        <textarea id="review" name="review"></textarea>
        <input type="submit" value="Submit review">
    </form>
@endsection


// db_config.php (database connection settings)
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// connect to database
$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);

function addReview($user_id, $product_name, $rating, $review_text) {
  global $mysqli;
  
  // insert review into database
  $stmt = $mysqli->prepare("INSERT INTO reviews (user_id, product_name, rating, review_text) VALUES (?, ?, ?, ?)");
  $stmt->bind_param('isss', $user_id, $product_name, $rating, $review_text);
  $result = $stmt->execute();
  
  if ($result) {
    return true; // review added successfully
  } else {
    return false; // error adding review
  }
}

function getUserReviews($user_id) {
  global $mysqli;
  
  // retrieve reviews for user from database
  $stmt = $mysqli->prepare("SELECT * FROM reviews WHERE user_id = ?");
  $stmt->bind_param('i', $user_id);
  $result = $stmt->execute();
  
  if ($result) {
    return $stmt->get_result(); // fetch array of review data
  } else {
    return false; // error fetching reviews
  }
}

function getReview($review_id) {
  global $mysqli;
  
  // retrieve single review from database by ID
  $stmt = $mysqli->prepare("SELECT * FROM reviews WHERE id = ?");
  $stmt->bind_param('i', $review_id);
  $result = $stmt->execute();
  
  if ($result) {
    return $stmt->get_result()->fetch_assoc(); // fetch single review data as associative array
  } else {
    return false; // error fetching review
  }
}

function updateReview($review_id, $new_rating, $new_review_text) {
  global $mysqli;
  
  // update existing review in database
  $stmt = $mysqli->prepare("UPDATE reviews SET rating = ?, review_text = ? WHERE id = ?");
  $stmt->bind_param('issi', $new_rating, $new_review_text, $review_id);
  $result = $stmt->execute();
  
  if ($result) {
    return true; // review updated successfully
  } else {
    return false; // error updating review
  }
}

function deleteReview($review_id) {
  global $mysqli;
  
  // delete review from database by ID
  $stmt = $mysqli->prepare("DELETE FROM reviews WHERE id = ?");
  $stmt->bind_param('i', $review_id);
  $result = $stmt->execute();
  
  if ($result) {
    return true; // review deleted successfully
  } else {
    return false; // error deleting review
  }
}


// add a new review for user with ID 1
$review_data = array(
  'product_name' => 'Apple iPhone',
  'rating' => 5,
  'review_text' => 'This is an amazing phone!'
);
addReview(1, $review_data['product_name'], $review_data['rating'], $review_data['review_text']);

// retrieve all reviews for user with ID 2
$user_reviews = getUserReviews(2);

// display individual review data
$review_id = 3; // example review ID
$review_data = getReview($review_id);
echo "Review Text: " . $review_data['review_text'];

// update existing review with ID 4
$new_rating = 4;
$new_review_text = 'This phone is great!';
updateReview(4, $new_rating, $new_review_text);

// delete review with ID 5
deleteReview(5);


<?php

// Database connection settings
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
  // Connect to database
  $db = new PDO($dsn, $username, $password);

  // Function to get user reviews
  function getUserReviews($userId) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM reviews WHERE user_id = :user_id');
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Function to add review
  function addReview($productId, $rating, $review) {
    global $db;
    $userId = $_SESSION['user']['id']; // assuming you have a session variable with the user's ID
    $stmt = $db->prepare('INSERT INTO reviews (product_id, user_id, rating, review) VALUES (:product_id, :user_id, :rating, :review)');
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':review', $review);
    return $stmt->execute();
  }

  // Function to get product reviews
  function getProductReviews($productId) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM reviews WHERE product_id = :product_id');
    $stmt->bindParam(':product_id', $productId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

} catch (PDOException $e) {
  echo 'Error: ' . $e->getMessage();
}

?>


// Get user reviews
$userReviews = getUserReviews(1); // assuming the user ID is 1
print_r($userReviews);

// Add review
addReview(1, 5, 'Great product!'); // assuming the product ID is 1

// Get product reviews
$productReviews = getProductReviews(1);
print_r($productReviews);


<?php

// Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create a review
function create_review($user_id, $product_id, $rating, $comment) {
    global $conn;
    
    // Prepare query
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $product_id, $rating, $comment);
    
    // Execute query
    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error: " . $stmt->error;
        return false;
    }
}

// Function to retrieve reviews for a product
function get_reviews($product_id) {
    global $conn;
    
    // Prepare query
    $stmt = $conn->prepare("SELECT r.id, u.name, r.rating, r.comment FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ?");
    $stmt->bind_param("i", $product_id);
    
    // Execute query and fetch results
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            echo "<p>Review by " . $row['name'] . ":</p>";
            echo "<p>Rating: " . $row['rating'] . "/5</p>";
            echo "<p>" . $row['comment'] . "</p>";
        }
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Function to display review form
function display_review_form($product_id) {
    global $conn;
    
    // Prepare query to retrieve user data
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    
    // Execute query and fetch result
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            echo "<h2>Review " . $product_id . "</h2>";
            
            // Display rating options
            echo "<select name='rating'>";
            for ($i = 1; $i <= 5; $i++) {
                echo "<option value='" . $i . "'>" . $i . "/5</option>";
            }
            echo "</select>";
            
            // Display comment textarea
            echo "<textarea name='comment' rows='10' cols='50'></textarea>";
        }
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Function to submit review form
function submit_review($product_id) {
    global $conn;
    
    // Retrieve rating and comment from form data
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    // Call create_review function
    if (create_review($_SESSION['user_id'], $product_id, $rating, $comment)) {
        echo "Review submitted successfully!";
    } else {
        echo "Error submitting review.";
    }
}

// Initialize review form display based on user action
if (isset($_GET['action']) && $_GET['action'] == 'review') {
    display_review_form($_GET['product_id']);
    
} elseif (isset($_POST['rating']) && isset($_POST['comment'])) {
    submit_review($_GET['product_id']);
}

?>


<?php
// Configuration settings
define('DATABASE_HOST', 'localhost');
define('DATABASE_USERNAME', 'your_username');
define('DATABASE_PASSWORD', 'your_password');
define('DATABASE_NAME', 'your_database_name');

// Connect to database
$mysqli = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

function getReviews($product_id) {
    // SQL query to retrieve reviews for a product
    $query = "SELECT * FROM reviews WHERE product_id = '$product_id'";
    $result = $mysqli->query($query);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $reviews[] = array(
                'id' => $row['id'],
                'rating' => $row['rating'],
                'review' => $row['review'],
                'created_at' => $row['created_at']
            );
        }
    } else {
        $reviews = array();
    }

    return $reviews;
}

function addReview($product_id, $rating, $review) {
    // SQL query to insert a new review
    $query = "INSERT INTO reviews (product_id, rating, review, created_at) VALUES ('$product_id', '$rating', '$review', NOW())";
    
    if ($mysqli->query($query)) {
        return true;
    } else {
        return false;
    }
}

function getAverageRating($product_id) {
    // SQL query to calculate average rating for a product
    $query = "SELECT AVG(rating) AS average FROM reviews WHERE product_id = '$product_id'";
    $result = $mysqli->query($query);
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()['average'];
    } else {
        return 0;
    }
}

// Example usage:
$product_id = 1; // Replace with the actual product ID
$reviews = getReviews($product_id);

if (!empty($reviews)) {
    foreach ($reviews as $review) {
        echo "Rating: $review[rating]/5, Review: $review[review], Created At: $review[created_at]<br>";
    }
    
    // Calculate average rating
    $average_rating = getAverageRating($product_id);
    echo "Average Rating: $average_rating/5<br>";
} else {
    echo "No reviews found for this product.";
}

// Close database connection
$mysqli->close();
?>


<?php

class Review {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Function to add review
    public function add_review($data) {
        try {
            $query = "INSERT INTO reviews (product_id, user_id, rating, comment)
                      VALUES (:product_id, :user_id, :rating, :comment)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':product_id', $data['product_id']);
            $stmt->bindParam(':user_id', $data['user_id']);
            $stmt->bindParam(':rating', $data['rating']);
            $stmt->bindParam(':comment', $data['comment']);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Function to get all reviews for a product
    public function get_reviews($product_id) {
        try {
            $query = "SELECT * FROM reviews WHERE product_id = :product_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Function to update review
    public function update_review($data) {
        try {
            $query = "UPDATE reviews SET rating = :rating, comment = :comment WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $data['review_id']);
            $stmt->bindParam(':rating', $data['new_rating']);
            $stmt->bindParam(':comment', $data['new_comment']);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Function to delete review
    public function delete_review($id) {
        try {
            $query = "DELETE FROM reviews WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

}

// Usage example
$review = new Review();

// Add review
$data = array(
    'product_id' => 1,
    'user_id' => 1,
    'rating' => 5,
    'comment' => "Good product!"
);
if ($review->add_review($data)) {
    echo "Review added successfully!";
}

// Get reviews for a product
$product_id = 1;
$reviews = $review->get_reviews($product_id);
foreach ($reviews as $review) {
    echo "Rating: " . $review['rating'] . ", Comment: " . $review['comment'];
}

// Update review
$data = array(
    'review_id' => 1,
    'new_rating' => 4,
    'new_comment' => "Better product!"
);
if ($review->update_review($data)) {
    echo "Review updated successfully!";
}

// Delete review
$id = 1;
if ($review->delete_review($id)) {
    echo "Review deleted successfully!";
}


// configuration.php
<?php
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "reviews_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>


// functions.php
<?php
    include 'configuration.php';

    function add_review($title, $content, $rating) {
        global $conn;

        // Check if review already exists for the user
        $query = "SELECT * FROM reviews WHERE title='$title' AND content='$content'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            return "Review already exists.";
        }

        // Add new review
        $sql = "INSERT INTO reviews (title, content, rating)
                VALUES ('$title', '$content', $rating)";
        if ($conn->query($sql) === TRUE) {
            echo "New review created successfully";
        } else {
            return "Error creating review: " . $conn->error;
        }
    }

    function view_reviews() {
        global $conn;

        // Get all reviews
        $sql = "SELECT * FROM reviews ORDER BY date_added DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "Title: " . $row["title"] . "<br>Content: " . $row["content"]. "<br>Rating: " . $row["rating"]. "<br>Date Added: " . $row["date_added"]. "<hr>";
            }
        } else {
            return "No reviews available.";
        }
    }

    function delete_review($id) {
        global $conn;

        // Check if review exists
        $query = "SELECT * FROM reviews WHERE id='$id'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            // Delete review
            $sql = "DELETE FROM reviews WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                return "Review deleted successfully.";
            } else {
                return "Error deleting review: " . $conn->error;
            }
        } else {
            return "Review does not exist.";
        }
    }

?>


// index.php (example usage)
<?php
    include 'functions.php';

    // Add a new review
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $rating = $_POST['rating'];

        echo add_review($title, $content, $rating);
    }

    // View all reviews
    view_reviews();

    // Delete a review by id
    if (isset($_GET['delete_id'])) {
        echo delete_review($_GET['delete_id']);
    }
?>


class Review {
  private $db;

  public function __construct() {
    $this->db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
  }

  // Create a review
  public function createReview($userId, $productId, $rating, $reviewText) {
    $query = "INSERT INTO reviews (user_id, product_id, rating, review_text) VALUES (:user_id, :product_id, :rating, :review_text)";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':product_id', $productId);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':review_text', $reviewText);
    return $stmt->execute();
  }

  // Read all reviews
  public function getAllReviews() {
    $query = "SELECT * FROM reviews";
    $stmt = $this->db->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Update a review
  public function updateReview($reviewId, $newRating, $newReviewText) {
    $query = "UPDATE reviews SET rating = :rating, review_text = :review_text WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':id', $reviewId);
    $stmt->bindParam(':rating', $newRating);
    $stmt->bindParam(':review_text', $newReviewText);
    return $stmt->execute();
  }

  // Delete a review
  public function deleteReview($reviewId) {
    $query = "DELETE FROM reviews WHERE id = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':id', $reviewId);
    return $stmt->execute();
  }
}


$review = new Review();

// Create a review
$user_id = 1;
$product_id = 1;
$rating = 4;
$review_text = 'Great product!';
$created = $review->createReview($user_id, $product_id, $rating, $review_text);
echo "Created review with id: " . $this->db->lastInsertId();

// Read all reviews
$reviews = $review->getAllReviews();
foreach ($reviews as $review) {
  echo 'User ID: ' . $review['user_id'] . ', Product ID: ' . $review['product_id'] . ', Rating: ' . $review['rating'];
}

// Update a review
$reviewId = 1;
$new_rating = 5;
$new_review_text = 'Excellent product!';
$updated = $review->updateReview($reviewId, $new_rating, $new_review_text);
echo "Updated review with id: $reviewId";

// Delete a review
$deleted = $review->deleteReview(2);
echo "Deleted review with id: 2";


// config.php (database connection settings)
$db_host = 'localhost';
$db_user = 'username';
$db_password = 'password';
$db_name = 'database';

// connect to database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// user_review.php (user review function)
function insertReview($productId, $userName, $reviewText, $rating) {
    global $conn;

    // prepare and execute query
    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_name, review_text, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $productId, $userName, $reviewText, $rating);
    $result = $stmt->execute();

    if ($result) {
        return true; // review inserted successfully
    } else {
        return false; // error inserting review
    }
}

function getUserReviews($userId, $pageSize = 10) {
    global $conn;

    // prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE user_name = ? LIMIT ?");
    $stmt->bind_param("si", $userId, $pageSize);
    $result = $stmt->execute();

    if ($result) {
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // retrieve reviews
    } else {
        return array(); // error retrieving reviews
    }
}

function getProductReviews($productId, $pageSize = 10) {
    global $conn;

    // prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ? LIMIT ?");
    $stmt->bind_param("is", $productId, $pageSize);
    $result = $stmt->execute();

    if ($result) {
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // retrieve reviews
    } else {
        return array(); // error retrieving reviews
    }
}

// example usage:
$product_id = 1;
$user_name = 'John Doe';
$review_text = 'Great product!';
$rating = 5;

if (insertReview($product_id, $user_name, $review_text, $rating)) {
    echo "Review inserted successfully!";
} else {
    echo "Error inserting review.";
}

$reviews = getUserReviews(1);
foreach ($reviews as $review) {
    echo "Username: " . $review['user_name'] . "
";
    echo "Review Text: " . $review['review_text'] . "
";
    echo "Rating: " . $review['rating'] . "/5

";
}

$reviews = getProductReviews(1);
foreach ($reviews as $review) {
    echo "Username: " . $review['user_name'] . "
";
    echo "Review Text: " . $review['review_text'] . "
";
    echo "Rating: " . $review['rating'] . "/5

";
}


<?php

class Review {
    private $id;
    private $title;
    private $content;
    private $rating;
    private $username;

    public function __construct($id, $title, $content, $rating, $username) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->rating = $rating;
        $this->username = $username;
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getUsername() {
        return $this->username;
    }
}

class ReviewService {
    private $reviews = array();

    public function addReview(Review $review) {
        $this->reviews[] = $review;
    }

    public function getReviews() {
        return $this->reviews;
    }

    public function getReviewsByUser($username) {
        $reviews = array();
        foreach ($this->reviews as $review) {
            if ($review->getUsername() == $username) {
                $reviews[] = $review;
            }
        }
        return $reviews;
    }
}

class ReviewController {
    private $reviewService;

    public function __construct(ReviewService $reviewService) {
        $this->reviewService = $reviewService;
    }

    public function addReview($title, $content, $rating, $username) {
        $review = new Review(count($this->reviewService->getReviews()) + 1, $title, $content, $rating, $username);
        $this->reviewService->addReview($review);
        return $review;
    }

    public function getReviews() {
        return $this->reviewService->getReviews();
    }

    public function getReviewsByUser($username) {
        return $this->reviewService->getReviewsByUser($username);
    }
}

// Example usage
$reviewService = new ReviewService();
$reviewController = new ReviewController($reviewService);

// Add some reviews
$review1 = $reviewController->addReview('Great product!', 'I love this product!', 5, 'JohnDoe');
$review2 = $reviewController->addReview('Good product, but not great.', 'It\'s okay, I guess.', 3, 'JaneDoe');

// Get all reviews
$reviews = $reviewController->getReviews();
foreach ($reviews as $review) {
    echo "Title: " . $review->getTitle() . ", Content: " . $review->getContent() . ", Rating: " . $review->getRating() . ", Username: " . $review->getUsername() . "
";
}

// Get reviews by user
$reviewsByJohn = $reviewController->getReviewsByUser('JohnDoe');
foreach ($reviewsByJohn as $review) {
    echo "Title: " . $review->getTitle() . ", Content: " . $review->getContent() . ", Rating: " . $review->getRating() . ", Username: " . $review->getUsername() . "
";
}


<?php

// Database connection settings
$dsn = 'mysql:host=localhost;dbname=your_database';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// User Review Function
function create_review($product_id, $user_id, $review, $rating)
{
    global $pdo;
    
    $sql = "INSERT INTO reviews (product_id, user_id, review, rating) VALUES (:product_id, :user_id, :review, :rating)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':review', $review);
    $stmt->bindParam(':rating', $rating);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function get_reviews($product_id)
{
    global $pdo;
    
    $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    
    if ($stmt->execute()) {
        return $stmt->fetchAll();
    } else {
        return false;
    }
}

function get_review($review_id)
{
    global $pdo;
    
    $sql = "SELECT * FROM reviews WHERE id = :review_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':review_id', $review_id);
    
    if ($stmt->execute()) {
        return $stmt->fetch();
    } else {
        return false;
    }
}

function update_review($review_id, $review, $rating)
{
    global $pdo;
    
    $sql = "UPDATE reviews SET review = :review, rating = :rating WHERE id = :review_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':review', $review);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':review_id', $review_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function delete_review($review_id)
{
    global $pdo;
    
    $sql = "DELETE FROM reviews WHERE id = :review_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':review_id', $review_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

?>


// Create a new review
create_review(1, 1, "Great product!", 5);

// Get all reviews for a specific product
$reviews = get_reviews(1);
print_r($reviews);

// Get a single review by ID
$review = get_review(1);
var_dump($review);

// Update an existing review
update_review(1, "Even better now!", 4);

// Delete a review
delete_review(1);


<?php

// Include database connection settings
require 'config.php';

// Function to create a new review
function create_review($user_id, $product_name, $rating, $review) {
  global $conn;

  $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_name, rating, review)
    VALUES (:user_id, :product_name, :rating, :review)");
  $stmt->bindParam(':user_id', $user_id);
  $stmt->bindParam(':product_name', $product_name);
  $stmt->bindParam(':rating', $rating);
  $stmt->bindParam(':review', $review);

  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

// Function to read reviews
function get_reviews($user_id = null) {
  global $conn;

  if ($user_id !== null) {
    $stmt = $conn->prepare("SELECT * FROM reviews WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll();
  } else {
    $stmt = $conn->prepare("SELECT * FROM reviews");
    $stmt->execute();
    return $stmt->fetchAll();
  }
}

// Function to update a review
function update_review($review_id, $user_id, $product_name, $rating, $review) {
  global $conn;

  $stmt = $conn->prepare("UPDATE reviews SET user_id = :user_id, product_name = :product_name, rating = :rating, review = :review WHERE id = :review_id");
  $stmt->bindParam(':user_id', $user_id);
  $stmt->bindParam(':product_name', $product_name);
  $stmt->bindParam(':rating', $rating);
  $stmt->bindParam(':review', $review);
  $stmt->bindParam(':review_id', $review_id);

  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

// Function to delete a review
function delete_review($review_id) {
  global $conn;

  $stmt = $conn->prepare("DELETE FROM reviews WHERE id = :review_id");
  $stmt->bindParam(':review_id', $review_id);

  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

?>


// Create a new review
$user_id = 1;
$product_name = 'Product X';
$rating = 5;
$review = 'This is a great product!';
if (create_review($user_id, $product_name, $rating, $review)) {
  echo 'Review created successfully!';
} else {
  echo 'Error creating review.';
}

// Read reviews
$user_id = 1;
$reviews = get_reviews($user_id);
foreach ($reviews as $review) {
  echo 'Product: ' . $review['product_name'] . ', Rating: ' . $review['rating'] . ', Review: ' . $review['review'];
}

// Update a review
$review_id = 1;
$user_id = 1;
$product_name = 'Updated Product X';
$rating = 4;
$review = 'This is an updated review.';
if (update_review($review_id, $user_id, $product_name, $rating, $review)) {
  echo 'Review updated successfully!';
} else {
  echo 'Error updating review.';
}

// Delete a review
$review_id = 1;
if (delete_review($review_id)) {
  echo 'Review deleted successfully!';
} else {
  echo 'Error deleting review.';
}


// db.php (database connection file)
$dsn = 'mysql:host=localhost;dbname=review_system';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

function createUserReview($user_id, $product_id, $review, $rating)
{
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO reviews (user_id, product_id, review, rating) VALUES (:user_id, :product_id, :review, :rating)');
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':review', $review);
    $stmt->bindParam(':rating', $rating);
    return $stmt->execute();
}

function getReviews($product_id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM reviews WHERE product_id = :product_id ORDER BY created_at DESC');
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    return $stmt->fetchAll();
}

function updateReview($review_id, $review)
{
    global $pdo;
    $stmt = $pdo->prepare('UPDATE reviews SET review = :review WHERE id = :review_id');
    $stmt->bindParam(':review', $review);
    $stmt->bindParam(':review_id', $review_id);
    return $stmt->execute();
}

function deleteReview($review_id)
{
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM reviews WHERE id = :review_id');
    $stmt->bindParam(':review_id', $review_id);
    return $stmt->execute();
}


// Create a new review for product 1 by user 1 with rating 5 and review "Great product!"
createUserReview(1, 1, 'Great product!', 5);

// Get all reviews for product 1
$reviews = getReviews(1);
print_r($reviews);

// Update the first review to have a new text
updateReview($reviews[0]['id'], 'Excellent product!');

// Delete the first review
deleteReview($reviews[0]['id']);


<?php

// Connect to database
$conn = mysqli_connect("localhost", "username", "password", "database");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function createUser($name) {
  global $conn;
  // Prepare query
  $stmt = mysqli_prepare($conn, "INSERT INTO users (name) VALUES (?)");
  // Bind parameters
  mysqli_stmt_bind_param($stmt, "s", $name);
  // Execute query
  if (!mysqli_stmt_execute($stmt)) {
    echo "Error creating user: " . mysqli_error($conn);
  }
  // Get ID of newly created user
  $newUserId = mysqli_insert_id($conn);
  return $newUserId;
}

function createReview($userId, $content, $rating) {
  global $conn;
  // Prepare query
  $stmt = mysqli_prepare($conn, "INSERT INTO reviews (user_id, content, rating) VALUES (?, ?, ?)");
  // Bind parameters
  mysqli_stmt_bind_param($stmt, "isi", $userId, $content, $rating);
  // Execute query
  if (!mysqli_stmt_execute($stmt)) {
    echo "Error creating review: " . mysqli_error($conn);
  }
}

function getReviews() {
  global $conn;
  // Prepare query
  $stmt = mysqli_prepare($conn, "SELECT r.id, u.name, r.content, r.rating FROM reviews r JOIN users u ON r.user_id = u.id");
  // Execute query and fetch results
  if (!mysqli_stmt_execute($stmt)) {
    echo "Error fetching reviews: " . mysqli_error($conn);
  }
  $reviews = array();
  while ($row = mysqli_fetch_assoc($stmt)) {
    $reviews[] = $row;
  }
  return $reviews;
}

function updateReview($reviewId, $content, $rating) {
  global $conn;
  // Prepare query
  $stmt = mysqli_prepare($conn, "UPDATE reviews SET content = ?, rating = ? WHERE id = ?");
  // Bind parameters
  mysqli_stmt_bind_param($stmt, "sii", $content, $rating, $reviewId);
  // Execute query
  if (!mysqli_stmt_execute($stmt)) {
    echo "Error updating review: " . mysqli_error($conn);
  }
}

function deleteReview($reviewId) {
  global $conn;
  // Prepare query
  $stmt = mysqli_prepare($conn, "DELETE FROM reviews WHERE id = ?");
  // Bind parameter
  mysqli_stmt_bind_param($stmt, "i", $reviewId);
  // Execute query
  if (!mysqli_stmt_execute($stmt)) {
    echo "Error deleting review: " . mysqli_error($conn);
  }
}

?>


// Create new user
$newUserId = createUser("John Doe");

// Create new review for the newly created user
createReview($newUserId, "This is a great product!", 5);

// Get all reviews
$reviews = getReviews();

// Update an existing review
updateReview(1, "This is a decent product.", 3);

// Delete a review
deleteReview(2);


<?php

// Configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'reviews_database');

// Function to connect to the database
function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Function to get all reviews
function get_reviews($product_id) {
    $conn = db_connect();
    $query = "SELECT * FROM reviews WHERE product_id = '$product_id'";
    $result = $conn->query($query);
    
    if (!$result) {
        die("Error: " . $conn->error);
    }
    
    $reviews = array();
    while ($row = $result->fetch_assoc()) {
        $reviews[] = array(
            'id' => $row['id'],
            'product_id' => $row['product_id'],
            'user_id' => $row['user_id'],
            'rating' => $row['rating'],
            'review' => $row['review']
        );
    }
    
    $conn->close();
    
    return $reviews;
}

// Function to get a single review
function get_review($id) {
    $conn = db_connect();
    $query = "SELECT * FROM reviews WHERE id = '$id'";
    $result = $conn->query($query);
    
    if (!$result) {
        die("Error: " . $conn->error);
    }
    
    $review = $result->fetch_assoc();
    
    $conn->close();
    
    return array(
        'id' => $review['id'],
        'product_id' => $review['product_id'],
        'user_id' => $review['user_id'],
        'rating' => $review['rating'],
        'review' => $review['review']
    );
}

// Function to create a new review
function create_review($data) {
    $conn = db_connect();
    
    // Sanitize input data
    $product_id = htmlspecialchars($data['product_id']);
    $user_id = htmlspecialchars($data['user_id']);
    $rating = htmlspecialchars($data['rating']);
    $review = htmlspecialchars($data['review']);
    
    $query = "INSERT INTO reviews (product_id, user_id, rating, review) VALUES ('$product_id', '$user_id', '$rating', '$review')";
    
    if ($conn->query($query)) {
        echo "Review created successfully!";
    } else {
        die("Error: " . $conn->error);
    }
    
    $conn->close();
}

// Function to update an existing review
function update_review($data) {
    $conn = db_connect();
    
    // Sanitize input data
    $id = htmlspecialchars($data['id']);
    $product_id = htmlspecialchars($data['product_id']);
    $user_id = htmlspecialchars($data['user_id']);
    $rating = htmlspecialchars($data['rating']);
    $review = htmlspecialchars($data['review']);
    
    $query = "UPDATE reviews SET product_id = '$product_id', user_id = '$user_id', rating = '$rating', review = '$review' WHERE id = '$id'";
    
    if ($conn->query($query)) {
        echo "Review updated successfully!";
    } else {
        die("Error: " . $conn->error);
    }
    
    $conn->close();
}

// Function to delete a review
function delete_review($id) {
    $conn = db_connect();
    
    $query = "DELETE FROM reviews WHERE id = '$id'";
    
    if ($conn->query($query)) {
        echo "Review deleted successfully!";
    } else {
        die("Error: " . $conn->error);
    }
    
    $conn->close();
}

// Example usage
$product_id = 1;
$reviews = get_reviews($product_id);

echo "Reviews for Product ID $product_id:
";
foreach ($reviews as $review) {
    echo "ID: " . $review['id'] . "
";
    echo "Product ID: " . $review['product_id'] . "
";
    echo "User ID: " . $review['user_id'] . "
";
    echo "Rating: " . $review['rating'] . "
";
    echo "Review: " . $review['review'] . "

";
}

// Create a new review
$data = array(
    'product_id' => 1,
    'user_id' => 1,
    'rating' => 5,
    'review' => 'This product is amazing!'
);
create_review($data);

?>


// Database connection settings
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


class Review {
    private $id;
    private $reviewer_name;
    private $review_text;
    private $rating;

    function __construct($id, $reviewer_name, $review_text, $rating) {
        $this->id = $id;
        $this->reviewer_name = $reviewer_name;
        $this->review_text = $review_text;
        $this->rating = $rating;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getReviewerName() { return $this->reviewer_name; }
    public function getReviewText() { return $this->review_text; }
    public function getRating() { return $this->rating; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setReviewerName($name) { $this->reviewer_name = $name; }
    public function setReviewText($text) { $this->review_text = $text; }
    public function setRating($rating) { $this->rating = $rating; }

    // Display review
    public function display() {
        echo "ID: $this->id <br>";
        echo "Reviewer Name: $this->reviewer_name <br>";
        echo "Review Text: $this->review_text <br>";
        echo "Rating: $this->rating/5";
    }
}


function addReview($review) {
    global $conn;
    $query = "INSERT INTO reviews (reviewer_name, review_text, rating)
              VALUES ('$review->getReviewerName()', '$review->getReviewText()', '$review->getRating()')";
    if ($conn->query($query)) {
        echo "Review added successfully!";
    } else {
        echo "Error adding review: " . $conn->error;
    }
}


function editReview($id, $review) {
    global $conn;
    $query = "UPDATE reviews SET reviewer_name='$review->getReviewerName()', review_text='$review->getReviewText()', rating='$review->getRating()' WHERE id=$id";
    if ($conn->query($query)) {
        echo "Review edited successfully!";
    } else {
        echo "Error editing review: " . $conn->error;
    }
}


function deleteReview($id) {
    global $conn;
    $query = "DELETE FROM reviews WHERE id=$id";
    if ($conn->query($query)) {
        echo "Review deleted successfully!";
    } else {
        echo "Error deleting review: " . $conn->error;
    }
}


function getReviews() {
    global $conn;
    $query = "SELECT * FROM reviews";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"] . "<br>";
            echo "Reviewer Name: " . $row["reviewer_name"] . "<br>";
            echo "Review Text: " . $row["review_text"] . "<br>";
            echo "Rating: " . $row["rating"] / 5 . "<br><hr>";
        }
    } else {
        echo "No reviews found.";
    }
}


$review = new Review(1, "John Doe", "Great product!", 4);
addReview($review);

getReviews();

$review->setId(1);
$review->setReviewerName("Jane Doe");
$review->setReviewText("Good product!");
$review->setRating(5);
editReview(1, $review);

deleteReview(1);


// Define the database connection settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'database_name');
define('DB_USER', 'username');
define('DB_PASSWORD', 'password');

function connectToDatabase() {
  $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
  return $conn;
}

// Create a review
function createReview($product_id, $user_id, $rating, $review) {
  $conn = connectToDatabase();
  $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, review) VALUES (:product_id, :user_id, :rating, :review)");
  $stmt->bindParam(':product_id', $product_id);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->bindParam(':rating', $rating);
  $stmt->bindParam(':review', $review);
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

// Read all reviews for a product
function getReviewsForProduct($product_id) {
  $conn = connectToDatabase();
  $stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = :product_id");
  $stmt->bindParam(':product_id', $product_id);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Read a single review
function getReview($review_id) {
  $conn = connectToDatabase();
  $stmt = $conn->prepare("SELECT * FROM reviews WHERE id = :id");
  $stmt->bindParam(':id', $review_id);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update a review
function updateReview($review_id, $new_rating, $new_review) {
  $conn = connectToDatabase();
  $stmt = $conn->prepare("UPDATE reviews SET rating = :rating, review = :review WHERE id = :id");
  $stmt->bindParam(':rating', $new_rating);
  $stmt->bindParam(':review', $new_review);
  $stmt->bindParam(':id', $review_id);
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

// Delete a review
function deleteReview($review_id) {
  $conn = connectToDatabase();
  $stmt = $conn->prepare("DELETE FROM reviews WHERE id = :id");
  $stmt->bindParam(':id', $review_id);
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}


// Create a new review
$product_id = 1;
$user_id = 1;
$rating = 5;
$review = "This product is amazing!";
createReview($product_id, $user_id, $rating, $review);

// Get all reviews for a product
$product_reviews = getReviewsForProduct(1);
print_r($product_reviews);

// Get a single review
$single_review = getReview(1);
print_r($single_review);

// Update a review
updateReview(1, 4, "I loved it!");
$updated_review = getReview(1);
print_r($updated_review);

// Delete a review
deleteReview(1);


class Review {
  private $db;

  public function __construct($db) {
    $this->db = $db;
  }

  public function createReview($product_id, $rating, $comment, $user_id) {
    $query = "INSERT INTO reviews (product_id, rating, comment, user_id)
              VALUES (:product_id, :rating, :comment, :user_id)";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    $stmt->bindParam(':user_id', $user_id);
    return $stmt->execute();
  }

  public function getReviews($product_id) {
    $query = "SELECT * FROM reviews WHERE product_id = :product_id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getUserReviews($user_id) {
    $query = "SELECT * FROM reviews WHERE user_id = :user_id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}


class UserReview {
  private $review;

  public function __construct($db) {
    $this->review = new Review($db);
  }

  public function addReview($product_id, $rating, $comment, $user_id) {
    return $this->review->createReview($product_id, $rating, $comment, $user_id);
  }

  public function getReviewsForProduct($product_id) {
    return $this->review->getReviews($product_id);
  }

  public function getReviewsByUser($user_id) {
    return $this->review->getUserReviews($user_id);
  }
}


$db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');

$user_review = new UserReview($db);

$product_id = 1;
$rating = 5;
$comment = 'Great product!';
$user_id = 1;

$user_review->addReview($product_id, $rating, $comment, $user_id);

$reviews_for_product = $user_review->getReviewsForProduct($product_id);
echo json_encode($reviews_for_product);

$reviews_by_user = $user_review->getReviewsByUser($user_id);
echo json_encode($reviews_by_user);


<?php

// Define the Review class
class Review {
    public $id;
    public $product_id;
    public $rating;
    public $review_text;
    public $user_name;
    public $created_at;

    // Constructor to initialize the object properties
    public function __construct($id, $product_id, $rating, $review_text, $user_name) {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->rating = $rating;
        $this->review_text = $review_text;
        $this->user_name = $user_name;
        $this->created_at = date('Y-m-d H:i:s');
    }

    // Method to display the review
    public function displayReview() {
        return "Product ID: $this->product_id, Rating: $this->rating, User Name: $this->user_name, Review Text: $this->review_text, Created At: $this->created_at";
    }
}

// Function to create a new review
function create_review($data) {
    // Validate the input data
    if (!isset($data['product_id']) || !isset($data['rating']) || !isset($data['review_text'])) {
        throw new Exception('Invalid input data');
    }

    // Create a new Review object
    $new_review = new Review(null, $data['product_id'], $data['rating'], $data['review_text'], $data['user_name']);

    // Save the review to database (for simplicity, assume we have a function called 'save_to_database' that does this)
    save_to_database($new_review);

    return $new_review;
}

// Function to display all reviews
function get_reviews() {
    // Assume we have an array of Review objects in our example code for simplicity
    $reviews = [
        new Review(1, 1, 5, 'Great product!', 'John Doe'),
        new Review(2, 1, 4, 'Good product.', 'Jane Smith'),
        new Review(3, 2, 3, 'Average product.', 'Bob Johnson')
    ];

    foreach ($reviews as $review) {
        echo $review->displayReview() . "
";
    }
}

// Function to save a review to database (for simplicity, assume we have this function)
function save_to_database($review) {
    // Simulate saving the review to database
    return true;
}

// Example usage:
$data = [
    'product_id' => 1,
    'rating' => 5,
    'review_text' => 'Great product!',
    'user_name' => 'John Doe'
];

try {
    $new_review = create_review($data);
    echo "New review created successfully!" . "
";
} catch (Exception $e) {
    echo "Error creating new review: " . $e->getMessage() . "
";
}

get_reviews();

?>


<?php

// Include database connection settings
require_once 'db_config.php';

// Function to create a review
function create_review($user_id, $product_id, $rating, $comment) {
  global $db;
  
  // Check if user is logged in
  if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    return array('error' => 'Please log in to leave a review');
  }
  
  try {
    // Insert review into database
    $stmt = $db->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (:user_id, :product_id, :rating, :comment)");
    $stmt->execute(array(
      ':user_id' => $user_id,
      ':product_id' => $product_id,
      ':rating' => $rating,
      ':comment' => $comment
    ));
    
    // Get the review ID
    $review_id = $db->lastInsertId();
    
    return array('success' => true, 'review_id' => $review_id);
  } catch (PDOException $e) {
    return array('error' => 'Error creating review: ' . $e->getMessage());
  }
}

// Function to retrieve reviews for a product
function get_reviews($product_id) {
  global $db;
  
  try {
    // Retrieve reviews from database
    $stmt = $db->prepare("SELECT * FROM reviews WHERE product_id = :product_id");
    $stmt->execute(array(':product_id' => $product_id));
    
    return array('success' => true, 'reviews' => $stmt->fetchAll());
  } catch (PDOException $e) {
    return array('error' => 'Error retrieving reviews: ' . $e->getMessage());
  }
}

// Function to retrieve user's reviews
function get_user_reviews($user_id) {
  global $db;
  
  try {
    // Retrieve reviews from database
    $stmt = $db->prepare("SELECT * FROM reviews WHERE user_id = :user_id");
    $stmt->execute(array(':user_id' => $user_id));
    
    return array('success' => true, 'reviews' => $stmt->fetchAll());
  } catch (PDOException $e) {
    return array('error' => 'Error retrieving user reviews: ' . $e->getMessage());
  }
}

// Example usage:
$user_id = $_SESSION['logged_in'];
$product_id = 1;
$rating = 5;
$comment = "Great product!";

$result = create_review($user_id, $product_id, $rating, $comment);
if ($result['success']) {
  echo 'Review created successfully!';
} else {
  echo 'Error creating review: ' . $result['error'];
}

?>


function add_review($product_id, $username, $rating, $comment) {
  // Check if product exists
  $product = get_product_by_id($product_id);
  if (!$product) {
    throw new Exception("Product not found");
  }

  // Create a new review object
  $review = array(
    'product_id' => $product_id,
    'username' => $username,
    'rating' => $rating,
    'comment' => $comment
  );

  // Save the review to the database
  insert_review($review);

  return $review;
}

function get_reviews_by_product($product_id) {
  // Retrieve reviews from the database for a specific product
  $reviews = retrieve_reviews_by_product($product_id);
  return $reviews;
}


// Insert review into database
function insert_review($review) {
  global $db;
  $query = "INSERT INTO reviews (product_id, username, rating, comment)
            VALUES (:product_id, :username, :rating, :comment)";
  $stmt = $db->prepare($query);
  $stmt->execute(array(
    ':product_id' => $review['product_id'],
    ':username' => $review['username'],
    ':rating' => $review['rating'],
    ':comment' => $review['comment']
  ));
}

// Retrieve reviews from database for a specific product
function retrieve_reviews_by_product($product_id) {
  global $db;
  $query = "SELECT * FROM reviews WHERE product_id = :product_id";
  $stmt = $db->prepare($query);
  $stmt->execute(array(':product_id' => $product_id));
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Create a new review for a product
$review = add_review(123, 'JohnDoe', 5, 'Great product!');

// Retrieve all reviews for a specific product
$reviews = get_reviews_by_product(123);

// Display the reviews on a web page
foreach ($reviews as $review) {
  echo "Username: $review['username']";
  echo "Rating: $review['rating']";
  echo "Comment: $review['comment']";
}


<?php

// Configuration
require_once 'config.php';

// Database connection
$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

function createUser($username, $password) {
  global $conn;
  
  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  
  try {
    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO Users (username, password_hash) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();
    
    return true;
  } catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "
";
    return false;
  }
}

function loginUser($username, $password) {
  global $conn;
  
  try {
    // Retrieve user from database
    $stmt = $conn->prepare("SELECT * FROM Users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $user = $stmt->fetch();
    
    if($user && password_verify($password, $user['password_hash'])) {
      return $user;
    } else {
      return false;
    }
  } catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "
";
    return false;
  }
}

function submitReview($product_name, $review_text, $rating) {
  global $conn;
  
  try {
    // Insert review into database
    $stmt = $conn->prepare("INSERT INTO Reviews (user_id, product_name, review_text, rating) VALUES (:user_id, :product_name, :review_text, :rating)");
    
    // Get user ID from logged in user
    $loggedInUser = $_SESSION['user'];
    $stmt->bindParam(':user_id', $loggedInUser['id']);
    $stmt->bindParam(':product_name', $product_name);
    $stmt->bindParam(':review_text', $review_text);
    $stmt->bindParam(':rating', $rating);
    
    $stmt->execute();
    
    return true;
  } catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "
";
    return false;
  }
}

function viewReviews() {
  global $conn;
  
  try {
    // Retrieve reviews from database
    $stmt = $conn->prepare("SELECT * FROM Reviews ORDER BY id DESC");
    $stmt->execute();
    
    return $stmt->fetchAll();
  } catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "
";
    return array();
  }
}

// Session handling
session_start();

if(isset($_POST['createUser'])) {
  createUser($_POST['username'], $_POST['password']);
} elseif(isset($_POST['loginUser'])) {
  $user = loginUser($_POST['username'], $_POST['password']);
  
  if($user) {
    $_SESSION['user'] = $user;
  }
}

if(isset($_POST['submitReview'])) {
  submitReview($_POST['product_name'], $_POST['review_text'], $_POST['rating']);
}

$reviews = viewReviews();

?>


// Review Model
class Review {
  private $db;

  public function __construct($db) {
    $this->db = $db;
  }

  public function addReview($productId, $userId, $rating, $comment) {
    $query = "INSERT INTO reviews (product_id, user_id, rating, comment)
              VALUES (:product_id, :user_id, :rating, :comment)";
    try {
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':product_id', $productId);
      $stmt->bindParam(':user_id', $userId);
      $stmt->bindParam(':rating', $rating);
      $stmt->bindParam(':comment', $comment);
      return $stmt->execute();
    } catch (PDOException $e) {
      echo "Error adding review: " . $e->getMessage();
      return false;
    }
  }

  public function getReviews($productId = null, $userId = null) {
    if ($productId !== null && $userId !== null) {
      $query = "SELECT * FROM reviews WHERE product_id = :product_id AND user_id = :user_id";
    } elseif ($productId !== null) {
      $query = "SELECT * FROM reviews WHERE product_id = :product_id";
    } elseif ($userId !== null) {
      $query = "SELECT * FROM reviews WHERE user_id = :user_id";
    } else {
      $query = "SELECT * FROM reviews";
    }
    try {
      $stmt = $this->db->prepare($query);
      if ($productId !== null) {
        $stmt->bindParam(':product_id', $productId);
      }
      if ($userId !== null) {
        $stmt->bindParam(':user_id', $userId);
      }
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo "Error fetching reviews: " . $e->getMessage();
      return array();
    }
  }

  public function updateReview($reviewId, $rating = null, $comment = null) {
    if ($rating !== null || $comment !== null) {
      $query = "UPDATE reviews SET ";
      if ($rating !== null) {
        $query .= "rating = :rating";
      }
      if ($comment !== null) {
        if (strpos($query, "WHERE") === false) {
          $query .= ", ";
        }
        $query .= "comment = :comment";
      }
      $query .= " WHERE id = :id";
    } else {
      echo "Error updating review: no rating or comment provided.";
      return false;
    }
    try {
      $stmt = $this->db->prepare($query);
      if ($rating !== null) {
        $stmt->bindParam(':rating', $rating);
      }
      if ($comment !== null) {
        $stmt->bindParam(':comment', $comment);
      }
      $stmt->bindParam(':id', $reviewId);
      return $stmt->execute();
    } catch (PDOException $e) {
      echo "Error updating review: " . $e->getMessage();
      return false;
    }
  }

  public function deleteReview($reviewId) {
    $query = "DELETE FROM reviews WHERE id = :id";
    try {
      $stmt = $this->db->prepare($query);
      $stmt->bindParam(':id', $reviewId);
      return $stmt->execute();
    } catch (PDOException $e) {
      echo "Error deleting review: " . $e->getMessage();
      return false;
    }
  }
}


$db = new PDO('mysql:host=localhost;dbname=reviews', 'username', 'password');

$reviewModel = new Review($db);

// Add a new review
$product_id = 123;
$user_id = 456;
$rating = 4;
$comment = "Great product!";
$result = $reviewModel->addReview($product_id, $user_id, $rating, $comment);
if ($result) {
    echo "Review added successfully!";
} else {
    echo "Error adding review";
}

// Get reviews for a specific product
$product_id = 123;
$reviews = $reviewModel->getReviews($productId);
foreach ($reviews as $review) {
    echo $review['id'] . ": " . $review['comment'];
}

// Update an existing review
$reviewId = 1;
$rating = 5;
$result = $reviewModel->updateReview($reviewId, null, null, $rating);
if ($result) {
    echo "Review updated successfully!";
} else {
    echo "Error updating review";
}


// Connect to database
$mysqli = new mysqli("localhost", "username", "password", "database");

function getReviews($product_id) {
  // Get all reviews for a specific product
  $query = "SELECT r.id, u.name, r.rating, r.review FROM reviews r JOIN users u ON r.user_id = u.id WHERE product_id = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  return $result;
}

function addReview($user_id, $product_id, $rating, $review) {
  // Add a new review to the database
  $query = "INSERT INTO reviews (user_id, product_id, rating, review) VALUES (?, ?, ?, ?)";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("iiss", $user_id, $product_id, $rating, $review);
  $stmt->execute();

  return true;
}

function getUserReviewHistory($user_id) {
  // Get all reviews written by a specific user
  $query = "SELECT p.name, r.rating, r.review FROM reviews r JOIN products p ON r.product_id = p.id WHERE r.user_id = ?";
  $stmt = $mysqli->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  return $result;
}

// Example usage
$user_id = 1; // Replace with actual user ID
$product_id = 1; // Replace with actual product ID

$reviews = getReviews($product_id);
foreach ($reviews as $review) {
  echo "User: {$review['name']}, Rating: {$review['rating']}, Review: {$review['review']}
";
}

// Add a new review
addReview($user_id, $product_id, 5, "This product is great!");

// Get user's review history
$user_reviews = getUserReviewHistory($user_id);
foreach ($user_reviews as $review) {
  echo "Product: {$review['name']}, Rating: {$review['rating']}, Review: {$review['review']}
";
}


class UserReview {
    private $id;
    private $reviewText;
    private $rating;

    public function __construct($id = null, $reviewText = '', $rating = 0) {
        $this->id = $id;
        $this->reviewText = $reviewText;
        $this->rating = $rating;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getReviewText() { return $this->reviewText; }
    public function getRating() { return $this->rating; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setReviewText($text) {
        if (empty($text)) {
            throw new Exception('Review text cannot be empty.');
        }
        $this->reviewText = $text;
    }
    public function setRating($rating) {
        if (!is_int($rating) || $rating < 0 || $rating > 5) {
            throw new Exception('Invalid rating. Must be an integer between 0 and 5.');
        }
        $this->rating = $rating;
    }

    // Methods
    public static function createReview($reviewText, $rating) {
        return new UserReview(null, $reviewText, $rating);
    }

    public static function getReviews() {
        // Simulate fetching reviews from database (replace with actual implementation)
        return array(
            new UserReview(1, 'Great product!', 5),
            new UserReview(2, 'Average experience.', 3),
        );
    }
}


// Create a new review
$review = UserReview::createReview('Excellent service!', 5);
echo $review->getReviewText() . PHP_EOL; // Excellent service!
echo $review->getRating() . PHP_EOL;     // 5

// Get all reviews
$reviews = UserReview::getReviews();
foreach ($reviews as $r) {
    echo "ID: {$r->getId()} | Review Text: {$r->getReviewText()} | Rating: {$r->getRating()}" . PHP_EOL;
}

// Update a review
$review = new UserReview(1, '', 0);
$review->setReviewText('Even better experience!');
echo $review->getReviewText() . PHP_EOL; // Even better experience!

// Delete a review (not implemented in this example)


/**
 * @class UserReview
 * @brief Represents a user review.
 *
 * @property int $id Review ID (unique identifier)
 * @property string $reviewText Text of the review
 * @property int $rating Rating given by the user (0-5)
 */


// Review class
class Review {
  private $db;

  function __construct() {
    $this->db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
  }

  // Get all reviews for a product
  function getReviews($productId) {
    $stmt = $this->db->prepare("SELECT * FROM reviews WHERE product_id = :productId");
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Add a new review
  function addReview($product_id, $reviewer, $rating, $comment) {
    $stmt = $this->db->prepare("INSERT INTO reviews (product_id, reviewer, rating, comment) VALUES (:product_id, :reviewer, :rating, :comment)");
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':reviewer', $reviewer);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    return $stmt->execute();
  }

  // Get the average rating for a product
  function getAverageRating($productId) {
    $stmt = $this->db->prepare("SELECT AVG(rating) FROM reviews WHERE product_id = :productId");
    $stmt->bindParam(':productId', $productId);
    $stmt->execute();
    return $stmt->fetchColumn();
  }
}

// User review system
class ReviewSystem {
  private $review;

  function __construct() {
    $this->review = new Review();
  }

  // Display a form to add a new review
  function displayAddReviewForm($productId) {
    ?>
    <form action="" method="post">
      <label for="reviewer">Your name:</label>
      <input type="text" id="reviewer" name="reviewer"><br><br>
      <label for="rating">Rating (1-5):</label>
      <select id="rating" name="rating">
        <?php for ($i = 1; $i <= 5; $i++) { ?>
          <option value="<?php echo $i ?>"><?php echo $i ?></option>
        <?php } ?>
      </select><br><br>
      <label for="comment">Comment:</label>
      <textarea id="comment" name="comment"></textarea><br><br>
      <input type="submit" name="add_review" value="Add review">
    </form>
    <?php
  }

  // Process a new review submission
  function processReviewSubmission($productId) {
    if (isset($_POST['add_review'])) {
      $reviewer = $_POST['reviewer'];
      $rating = $_POST['rating'];
      $comment = $_POST['comment'];

      if ($this->review->addReview($productId, $reviewer, $rating, $comment)) {
        echo "Thank you for your review!";
      } else {
        echo "Error adding review.";
      }
    }
  }

  // Display a list of reviews
  function displayReviews($productId) {
    $reviews = $this->review->getReviews($productId);
    ?>
    <h2>Reviews:</h2>
    <?php foreach ($reviews as $review) { ?>
      <p><strong><?php echo $review['reviewer'] ?></strong> gave this product a rating of <?php echo $review['rating'] ?>/5 and wrote: <?php echo $review['comment'] ?></p>
    <?php } ?>
    <?php
  }

  // Display the average rating for a product
  function displayAverageRating($productId) {
    $averageRating = $this->review->getAverageRating($productId);
    ?>
    <h2>Average Rating:</h2>
    <p><?php echo 'The average rating for this product is: ' . $averageRating ?>/5</p>
    <?php
  }
}


$reviewSystem = new ReviewSystem();

// Display a form to add a new review for product #123
$productId = 123;
$reviewSystem->displayAddReviewForm($productId);

// Process a new review submission
if (isset($_POST['add_review'])) {
  $reviewSystem->processReviewSubmission($productId);
}

// Display reviews for product #123
$reviews = $reviewSystem->getReviews($productId);
?>

<h2>Reviews:</h2>
<?php foreach ($reviews as $review) { ?>
  <p><strong><?php echo $review['reviewer'] ?></strong> gave this product a rating of <?php echo $review['rating'] ?>/5 and wrote: <?php echo $review['comment'] ?></p>
<?php } ?>

// Display the average rating for product #123
$averageRating = $reviewSystem->getAverageRating($productId);
?>
<h2>Average Rating:</h2>
<p><?php echo 'The average rating for this product is: ' . $averageRating ?>/5</p>


// Review Model
class Review {
  private $db;

  public function __construct() {
    $this->db = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
  }

  public function createReview($user_id, $product_id, $rating, $comment) {
    $sql = "INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (:user_id, :product_id, :rating, :comment)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    return $stmt->execute();
  }

  public function getReviewsForProduct($product_id) {
    $sql = "SELECT * FROM reviews WHERE product_id = :product_id ORDER BY created_at DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':product_id', $product_id);
    return $stmt->execute() ? $stmt->fetchAll(PDO::FETCH_ASSOC) : array();
  }

  public function getReviewsForUser($user_id) {
    $sql = "SELECT * FROM reviews WHERE user_id = :user_id ORDER BY created_at DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    return $stmt->execute() ? $stmt->fetchAll(PDO::FETCH_ASSOC) : array();
  }
}


// Create a new review
if (isset($_POST['submit'])) {
  $review = new Review();
  $result = $review->createReview($_SESSION['user_id'], $_POST['product_id'], $_POST['rating'], $_POST['comment']);
  if ($result) {
    echo "Review created successfully!";
  } else {
    echo "Error creating review.";
  }
}

// Get reviews for a product
$reviews = new Review();
$product_reviews = $reviews->getReviewsForProduct($_GET['product_id']);

// Get reviews for a user
$user_reviews = $reviews->getReviewsForUser($_SESSION['user_id']);


<!-- Create review form -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input type="hidden" name="product_id" value="<?php echo $_GET['product_id']; ?>">
  <label for="rating">Rating:</label>
  <select id="rating" name="rating">
    <?php foreach (range(1, 5) as $i) { ?>
      <option value="<?php echo $i; ?>"><?php echo $i; ?>/5</option>
    <?php } ?>
  </select>
  <br><br>
  <label for="comment">Comment:</label>
  <textarea id="comment" name="comment"></textarea>
  <br><br>
  <input type="submit" name="submit" value="Submit Review">
</form>

<!-- Display reviews for a product -->
<h2>Reviews for <?php echo $_GET['product_id']; ?></h2>
<ul>
  <?php foreach ($product_reviews as $review) { ?>
    <li>
      <strong><?php echo $review['rating']; ?>/5 by <?php echo $review['user_name']; ?></strong><br>
      <?php echo $review['comment']; ?><br><br>
    </li>
  <?php } ?>
</ul>

<!-- Display reviews for a user -->
<h2>Reviews by <?php echo $_SESSION['name']; ?></h2>
<ul>
  <?php foreach ($user_reviews as $review) { ?>
    <li>
      <strong><?php echo $review['rating']; ?>/5 on <?php echo $review['product_title']; ?></strong><br>
      <?php echo $review['comment']; ?><br><br>
    </li>
  <?php } ?>
</ul>


// connect to database
$mysqli = new mysqli('localhost', 'username', 'password', 'database_name');

function get_user_reviews($user_id) {
  $query = "SELECT r.id, u.name, p.name AS product_name, r.rating, r.review 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            JOIN products p ON r.product_id = p.id 
            WHERE r.user_id = '$user_id'";
  
  $result = $mysqli->query($query);
  
  $reviews = array();
  while ($row = $result->fetch_assoc()) {
    $reviews[] = array(
      'id' => $row['id'],
      'name' => $row['name'],
      'product_name' => $row['product_name'],
      'rating' => $row['rating'],
      'review' => $row['review']
    );
  }
  
  return $reviews;
}

function add_review($user_id, $product_id, $rating, $review) {
  $query = "INSERT INTO reviews (user_id, product_id, rating, review) 
            VALUES ('$user_id', '$product_id', '$rating', '$review')";
  
  if ($mysqli->query($query)) {
    return true;
  } else {
    return false;
  }
}

function delete_review($review_id) {
  $query = "DELETE FROM reviews WHERE id = '$review_id'";
  
  if ($mysqli->query($query)) {
    return true;
  } else {
    return false;
  }
}


// get user's reviews
$user_id = 1; // replace with actual user ID
$reviews = get_user_reviews($user_id);
print_r($reviews);

// add review
$product_id = 1; // replace with actual product ID
$rating = 5;
$review = "Great product!";
if (add_review($user_id, $product_id, $rating, $review)) {
  echo "Review added successfully";
} else {
  echo "Failed to add review";
}

// delete review
$review_id = 1; // replace with actual review ID
if (delete_review($review_id)) {
  echo "Review deleted successfully";
} else {
  echo "Failed to delete review";
}


// db.php (database connection settings)
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// Connect to the database
function connectToDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Disconnect from the database
function disconnectFromDatabase($conn) {
    $conn->close();
}

// Review class
class Review {
    private $id;
    private $userId;
    private $review;
    private $rating;

    public function __construct($id = null, $userId = null, $review = null, $rating = null) {
        if ($id !== null) {
            $this->setId($id);
        }
        
        if ($userId !== null) {
            $this->setUserId($userId);
        }
        
        if ($review !== null) {
            $this->setReview($review);
        }
        
        if ($rating !== null) {
            $this->setRating($rating);
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getReview() {
        return $this->review;
    }

    public function getRating() {
        return $this->rating;
    }

    private function setId($id) {
        $this->id = $id;
    }

    private function setUserId($userId) {
        $this->userId = $userId;
    }

    private function setReview($review) {
        $this->review = $review;
    }

    private function setRating($rating) {
        $this->rating = $rating;
    }
}

// Review functions
function createReview($conn, Review $review) {
    $query = "INSERT INTO reviews (user_id, review, rating) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $review->getUserId(), $review->getReview(), $review->getRating());
    
    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error creating review: " . $stmt->error;
        return false;
    }
}

function getReviews($conn) {
    $query = "SELECT * FROM reviews";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $reviews = array();
        
        while ($row = $result->fetch_assoc()) {
            $review = new Review($row['id'], $row['user_id'], $row['review'], $row['rating']);
            $reviews[] = $review;
        }
        
        return $reviews;
    } else {
        echo "No reviews found";
        return array();
    }
}

function getReviewById($conn, $id) {
    $query = "SELECT * FROM reviews WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $review = new Review();
            
            while ($row = $result->fetch_assoc()) {
                $review->setId($row['id']);
                $review->setUserId($row['user_id']);
                $review->setReview($row['review']);
                $review->setRating($row['rating']);
            }
            
            return $review;
        } else {
            echo "No review found with ID: $id";
            return null;
        }
    } else {
        echo "Error fetching review by ID: " . $stmt->error;
        return null;
    }
}

function updateReview($conn, Review $review) {
    $query = "UPDATE reviews SET user_id = ?, review = ?, rating = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isii", $review->getUserId(), $review->getReview(), $review->getRating(), $review->getId());
    
    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error updating review: " . $stmt->error;
        return false;
    }
}

function deleteReview($conn, $id) {
    $query = "DELETE FROM reviews WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        echo "Error deleting review: " . $stmt->error;
        return false;
    }
}


$conn = connectToDatabase();

$review1 = new Review(null, 1, 'This is a great product!', 5);
$review2 = new Review(null, 2, 'Not bad but not great either.', 3);

if (createReview($conn, $review1)) {
    echo "Review created successfully!";
}

if (createReview($conn, $review2)) {
    echo "Review created successfully!";
}

$reviews = getReviews($conn);
foreach ($reviews as $review) {
    echo "ID: " . $review->getId() . ", User ID: " . $review->getUserId() . ", Review: " . $review->getReview() . ", Rating: " . $review->getRating() . "
";
}

$updatedReview = new Review(null, 1, 'This is an updated review!', 5);
if (updateReview($conn, $updatedReview)) {
    echo "Review updated successfully!";
}

if (deleteReview($conn, 2)) {
    echo "Review deleted successfully!";
}

disconnectFromDatabase($conn);


// config.php
<?php
require_once 'db.inc.php';

// database connection settings
define('DB_HOST', 'your_host');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// function to connect to the database
function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>

// review.php (display reviews for a product)
<?php
require_once 'config.php';

// get the product id from the URL or form data
$product_id = $_GET['id'];

// connect to the database
$conn = db_connect();

// get all reviews for the current product
$stmt = $conn->prepare("SELECT * FROM reviews WHERE product_id = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// display reviews
echo '<h2>Reviews for ' . $_GET['name'] . '</h2>';
foreach ($reviews as $review) {
    echo '<p>' . $review['comment'] . ' (' . $review['rating'] . '/5)</p>';
}
?>

// submit_review.php (handle form submission)
<?php
require_once 'config.php';

// get the product id and user data from the form
$product_id = $_POST['product_id'];
$user_name = $_POST['user_name'];
$comment = $_POST['comment'];
$rating = $_POST['rating'];

// connect to the database
$conn = db_connect();

// insert review into the reviews table
$stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
$stmt->bind_param('iiss', $product_id, $_SESSION['user_id'], $rating, $comment);
$stmt->execute();
?>


<?php

// assume we have the following classes and functions defined
class Review {
    public $id;
    public $review_text;
    public $rating;
}

class Product {
    public $id;
    public $name;
}

class User {
    public $id;
    public $username;
    public $reviews; // an array of Review objects
}

function create_review($product_id, $user_id, $text, $rating) {
    // database interaction to insert new review into reviews table
    // for simplicity we'll just simulate a database connection here
    $review = new Review();
    $review->id = uniqid(); // unique id for the review
    $review->review_text = $text;
    $review->rating = $rating;

    // update user's reviews array
    return $review;
}

function get_user_reviews($user_id) {
    // database interaction to retrieve user's reviews from reviews table
    // for simplicity we'll just simulate a database connection here
    $reviews = [];

    // loop through all reviews and filter by user id
    foreach (get_all_reviews() as $review) {
        if ($review->user_id == $user_id) {
            $reviews[] = $review;
        }
    }

    return $reviews;
}

function get_all_reviews() {
    // database interaction to retrieve all reviews from reviews table
    // for simplicity we'll just simulate a database connection here
    $reviews = [
        new Review(['id' => 1, 'user_id' => 1, 'product_id' => 1, 'review_text' => 'Great product!', 'rating' => 5]),
        new Review(['id' => 2, 'user_id' => 2, 'product_id' => 2, 'review_text' => 'Good', 'rating' => 4]),
    ];

    return $reviews;
}

function update_product_rating($product_id) {
    // calculate average rating for the product
    $total_rating = 0;
    $num_reviews = 0;

    foreach (get_all_reviews() as $review) {
        if ($review->product_id == $product_id) {
            $total_rating += $review->rating;
            $num_reviews++;
        }
    }

    // calculate average rating
    $average_rating = $num_reviews > 0 ? $total_rating / $num_reviews : 0;

    return $average_rating;
}

function write_review_form($product) {
?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="review_text">Your review:</label>
        <textarea name="review_text" id="review_text"></textarea><br>

        <label for="rating">Rating (1-5):</label>
        <select name="rating" id="rating">
            <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo "<option value=\"$i\">$i stars</option>";
                }
            ?>
        </select><br>

        <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
        <button type="submit">Submit review!</button>
    </form>
<?php
}

// main function to handle user review submission
function display_review_form() {
    if (isset($_POST['review_text']) && isset($_POST['rating'])) {
        // create new review object and save it to database
        $product_id = $_POST['product_id'];
        $text = $_POST['review_text'];
        $rating = $_POST['rating'];

        // check if user is logged in (for simplicity we'll just assume they are)
        $user_reviews = get_user_reviews(1); // replace 1 with actual user id
        $new_review = create_review($product_id, 1, $text, $rating);
        $user_reviews[] = $new_review;

        // update product rating (calculate average rating for the product)
        $average_rating = update_product_rating($product_id);

        echo "Review submitted! Average rating for this product: $average_rating stars";
    } else {
        // render review form
        $product = new Product(['id' => 1, 'name' => 'Example Product']);
        write_review_form($product);
    }
}

// call display_review_form() to start the process
display_review_form();

?>


<?php

// Database connection settings
$host = 'your_host';
$dbname = 'your_database';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die('Error connecting to database: ' . $e->getMessage());
}

// Function to add a review
function add_review($user_id, $product_id, $review_text, $rating) {
    global $pdo;
    
    // Validate input
    if (!ctype_digit($user_id) || !ctype_digit($product_id)) {
        throw new Exception('Invalid user or product ID');
    }
    
    // Insert review into database
    $stmt = $pdo->prepare('INSERT INTO reviews (user_id, product_id, review_text, rating) VALUES (:user_id, :product_id, :review_text, :rating)');
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':review_text', $review_text);
    $stmt->bindParam(':rating', $rating);
    
    try {
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        throw new Exception('Error adding review: ' . $e->getMessage());
    }
}

// Function to get all reviews for a product
function get_reviews($product_id) {
    global $pdo;
    
    // Retrieve reviews from database
    $stmt = $pdo->prepare('SELECT * FROM reviews WHERE product_id = :product_id');
    $stmt->bindParam(':product_id', $product_id);
    
    try {
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception('Error retrieving reviews: ' . $e->getMessage());
    }
}

?>


// Add a review for product 1 with user ID 123 and rating 4
if (add_review(123, 1, 'This product is great!', 4)) {
    echo "Review added successfully!";
} else {
    echo "Error adding review";
}

// Get all reviews for product 2
$reviews = get_reviews(2);
foreach ($reviews as $review) {
    echo "User: " . $review['user_id'] . ", Rating: " . $review['rating'] . ", Review Text: " . $review['review_text'];
}


<?php

// Review model
class Review {
  public $id;
  public $product_id;
  public $user_id;
  public $rating;
  public $review;

  // Constructor
  public function __construct($data) {
    $this->id = $data['id'];
    $this->product_id = $data['product_id'];
    $this->user_id = $data['user_id'];
    $this->rating = $data['rating'];
    $this->review = $data['review'];
  }
}

// Review controller
class ReviewController {
  public function index() {
    // Retrieve all reviews from the database
    $reviews = Review::all();
    return $reviews;
  }

  public function add($product_id, $user_id, $rating, $review) {
    // Create a new review object
    $new_review = new Review([
      'product_id' => $product_id,
      'user_id' => $user_id,
      'rating' => $rating,
      'review' => $review
    ]);

    // Insert the review into the database
    $result = Review::create($new_review);

    return $result;
  }

  public function edit($id, $product_id, $user_id, $rating, $review) {
    // Retrieve the review from the database
    $existing_review = Review::find($id);

    // Update the review object
    $existing_review->product_id = $product_id;
    $existing_review->user_id = $user_id;
    $existing_review->rating = $rating;
    $existing_review->review = $review;

    // Save the updated review to the database
    Review::update($id, $existing_review);

    return true;
  }

  public function delete($id) {
    // Delete the review from the database
    Review::delete($id);
    return true;
  }
}

// Review model implementation
class Review extends Model {
  protected static $_table = 'reviews';

  public static function all() {
    $query = "SELECT * FROM reviews";
    $results = DB::query($query);
    return array_map(function($row) { return new Review($row); }, $results);
  }

  public static function create($data) {
    $fields = [
      'product_id',
      'user_id',
      'rating',
      'review'
    ];

    $values = array_fill(0, count($fields), '?');
    $query = "INSERT INTO reviews (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ")";
    DB::query($query, ...$data);

    return true;
  }

  public static function update($id, $data) {
    $fields = [
      'product_id',
      'user_id',
      'rating',
      'review'
    ];

    $values = array_fill(0, count($fields), '?');
    $query = "UPDATE reviews SET " . implode(', ', array_map(function($field, $value) { return "$field = ?"; }, $fields, $data)) . " WHERE id = ?";
    DB::query($query, ...$data);

    return true;
  }

  public static function delete($id) {
    $query = "DELETE FROM reviews WHERE id = ?";
    DB::query($query, $id);
    return true;
  }
}

?>


// Create a new review
$review_controller = new ReviewController();
$new_review_id = $review_controller->add(1, 2, 5, 'This product is great!');

// Retrieve all reviews for a product
$product_reviews = $review_controller->index();

// Edit an existing review
$review_controller->edit($new_review_id, 3, 4, 4, 'I agree with the previous reviewer.');

// Delete a review
$review_controller->delete($new_review_id);


class Review {
    private $id;
    private $rating;
    private $comment;
    private $user_id;
    private $product_id;

    function __construct($id = null, $rating = null, $comment = null, $user_id = null, $product_id = null) {
        $this->id = $id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->user_id = $user_id;
        $this->product_id = $product_id;
    }

    function save() {
        // Connect to database
        require_once 'connect.php';
        $db = new Database();

        $query = "INSERT INTO reviews (rating, comment, user_id, product_id) VALUES (:rating, :comment, :user_id, :product_id)";
        try {
            $result = $db->prepare($query);
            $result->execute(array(
                ':rating' => $this->rating,
                ':comment' => $this->comment,
                ':user_id' => $this->user_id,
                ':product_id' => $this->product_id
            ));
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function getAllReviews() {
        // Connect to database
        require_once 'connect.php';
        $db = new Database();

        $query = "SELECT * FROM reviews ORDER BY id DESC";
        try {
            $result = $db->prepare($query);
            $result->execute(array());
            return $result->fetchAll();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function getReviewById($id) {
        // Connect to database
        require_once 'connect.php';
        $db = new Database();

        $query = "SELECT * FROM reviews WHERE id = :id";
        try {
            $result = $db->prepare($query);
            $result->execute(array(':id' => $id));
            return $result->fetch();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function updateReview() {
        // Connect to database
        require_once 'connect.php';
        $db = new Database();

        $query = "UPDATE reviews SET rating = :rating, comment = :comment WHERE id = :id";
        try {
            $result = $db->prepare($query);
            $result->execute(array(
                ':rating' => $this->rating,
                ':comment' => $this->comment,
                ':id' => $this->id
            ));
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function deleteReview() {
        // Connect to database
        require_once 'connect.php';
        $db = new Database();

        $query = "DELETE FROM reviews WHERE id = :id";
        try {
            $result = $db->prepare($query);
            $result->execute(array(':id' => $this->id));
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}


class reviewController extends Controller {

    function index() {
        require_once 'reviewModel.php';

        $reviews = new Review();

        if (isset($_POST['submit'])) {
            $reviews->rating = $_POST['rating'];
            $reviews->comment = $_POST['comment'];
            $reviews->user_id = $_SESSION['id'];
            $reviews->save();
        }

        $data['reviews'] = $reviews->getAllReviews();

        $this->loadView('reviewView.php', $data);
    }
}


<?php foreach ($reviews as $key => $value): ?>
    <div class="review">
        <p>Rating: <?php echo $value['rating']; ?></p>
        <p>Comment: <?php echo $value['comment']; ?></p>
    </div>
<?php endforeach; ?>

<form action="" method="post">
    Rating: <input type="number" name="rating"><br><br>
    Comment: <textarea name="comment"></textarea><br><br>
    <input type="submit" name="submit" value="Submit Review">
</form>


<?php

class ReviewManager {
  private $db;

  public function __construct() {
    // Establish a database connection (e.g., using PDO)
    $this->db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
  }

  /**
   * Create a new review
   *
   * @param int $userId The ID of the user submitting the review
   * @param int $productId The ID of the product being reviewed
   * @param float $rating The rating given by the user (0-5)
   * @param string $content The content of the review
   */
  public function createReview($userId, $productId, $rating, $content) {
    // Validate input data
    if (!is_numeric($userId) || !is_numeric($productId)) {
      throw new Exception('Invalid user or product ID');
    }
    if ($rating < 0 || $rating > 5) {
      throw new Exception('Rating must be between 0 and 5');
    }

    // Insert review into database
    $stmt = $this->db->prepare('INSERT INTO reviews (user_id, product_id, rating, content) VALUES (:userId, :productId, :rating, :content)');
    $stmt->execute([
      ':userId' => $userId,
      ':productId' => $productId,
      ':rating' => $rating,
      ':content' => $content
    ]);

    // Return the newly created review ID
    return $this->db->lastInsertId();
  }

  /**
   * Update an existing review
   *
   * @param int $reviewId The ID of the review to update
   * @param float $rating The new rating given by the user (0-5)
   * @param string $content The new content of the review
   */
  public function updateReview($reviewId, $rating, $content) {
    // Validate input data
    if (!is_numeric($reviewId)) {
      throw new Exception('Invalid review ID');
    }
    if ($rating < 0 || $rating > 5) {
      throw new Exception('Rating must be between 0 and 5');
    }

    // Update review in database
    $stmt = $this->db->prepare('UPDATE reviews SET rating = :rating, content = :content WHERE id = :reviewId');
    $stmt->execute([
      ':rating' => $rating,
      ':content' => $content,
      ':reviewId' => $reviewId
    ]);
  }

  /**
   * Retrieve all reviews for a product or user
   *
   * @param int $productId The ID of the product to retrieve reviews for (optional)
   * @param int $userId The ID of the user to retrieve reviews from (optional)
   */
  public function getReviews($productId = null, $userId = null) {
    // Build query parameters
    $params = [];
    if ($productId !== null) {
      $params[':productId'] = $productId;
    }
    if ($userId !== null) {
      $params[':userId'] = $userId;
    }

    // Retrieve reviews from database
    $stmt = $this->db->prepare('SELECT * FROM reviews WHERE product_id = :productId OR user_id = :userId');
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}

?>


$reviewManager = new ReviewManager();

// Create a new review
$reviewId = $reviewManager->createReview(1, 1, 4.5, 'Great product!');

// Update an existing review
$reviewManager->updateReview($reviewId, 5.0, 'Even better now!');

// Retrieve all reviews for a product or user
$productReviews = $reviewManager->getReviews(1);
$userReviews = $reviewManager->getReviews(null, 1);

print_r($productReviews); // Array of product reviews
print_r($userReviews); // Array of user reviews


// Review class to encapsulate review logic
class Review {
  private $db;

  public function __construct() {
    // Initialize database connection (e.g. using PDO)
    $this->db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
  }

  /**
   * Create a new review for a product
   *
   * @param int $product_id Product ID to leave a review for
   * @param int $user_id User ID leaving the review
   * @param float $rating Rating (0-5)
   * @param string $comment Review comment
   */
  public function createReview($product_id, $user_id, $rating, $comment) {
    // Validate input data
    if (!is_int($product_id) || !is_int($user_id)) {
      throw new Exception('Invalid product/user ID');
    }
    if ($rating < 0 || $rating > 5) {
      throw new Exception('Rating must be between 0 and 5');
    }

    // Insert review into database
    $stmt = $this->db->prepare("INSERT INTO reviews (product_id, user_id, rating, comment)
                                VALUES (:product_id, :user_id, :rating, :comment)");
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    $stmt->execute();
  }

  /**
   * Get all reviews for a product
   *
   * @param int $product_id Product ID to retrieve reviews for
   */
  public function getReviews($product_id) {
    // Retrieve reviews from database
    $stmt = $this->db->prepare("SELECT * FROM reviews WHERE product_id = :product_id");
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Get a single review by ID
   *
   * @param int $review_id Review ID to retrieve
   */
  public function getReview($review_id) {
    // Retrieve review from database
    $stmt = $this->db->prepare("SELECT * FROM reviews WHERE id = :id");
    $stmt->bindParam(':id', $review_id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Update a review
   *
   * @param int $review_id Review ID to update
   * @param float $rating New rating (0-5)
   * @param string $comment New comment
   */
  public function updateReview($review_id, $rating, $comment) {
    // Validate input data
    if ($rating < 0 || $rating > 5) {
      throw new Exception('Rating must be between 0 and 5');
    }

    // Update review in database
    $stmt = $this->db->prepare("UPDATE reviews SET rating = :rating, comment = :comment WHERE id = :id");
    $stmt->bindParam(':id', $review_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':comment', $comment);
    $stmt->execute();
  }

  /**
   * Delete a review
   *
   * @param int $review_id Review ID to delete
   */
  public function deleteReview($review_id) {
    // Delete review from database
    $this->db->prepare("DELETE FROM reviews WHERE id = :id")->bindParam(':id', $review_id)->execute();
  }
}


// Initialize Review class instance
$review = new Review();

// Create a new review for a product
$review->createReview(123, 456, 4.5, 'Great product!');

// Get all reviews for a product
$reviews = $review->getReviews(123);

// Get a single review by ID
$single_review = $review->getReview(789);

// Update an existing review
$review->updateReview(789, 4.8, 'Excellent service!');

// Delete a review
$review->deleteReview(987);


class Product {
    public $id;
    public $name;
    public $reviews;

    function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
        $this->reviews = array();
    }
}

function add_review($product_id, $user_name, $review_text) {
    // Get the product from the array
    foreach (get_products() as $product) {
        if ($product->id == $product_id) {
            // Add review to the product's reviews array
            $product->reviews[] = new Review($user_name, $review_text);
            return true;
        }
    }

    // Product not found
    return false;
}

function get_reviews($product_id) {
    // Get the product from the array
    foreach (get_products() as $product) {
        if ($product->id == $product_id) {
            return $product->reviews;
        }
    }

    // Product not found
    return null;
}

function display_reviews($product_id) {
    $reviews = get_reviews($product_id);
    if ($reviews !== null) {
        foreach ($reviews as $review) {
            echo "User: " . $review->user_name . "
";
            echo "Review: " . $review->text . "

";
        }
    } else {
        echo "No reviews found for this product.
";
    }
}

class Review {
    public $user_name;
    public $text;

    function __construct($user_name, $text) {
        $this->user_name = $user_name;
        $this->text = $text;
    }
}

// Example usage:
$product1 = new Product(1, "Product 1");
$product2 = new Product(2, "Product 2");

add_review(1, "John Doe", "Great product!");
add_review(1, "Jane Doe", "Not so great.");
add_review(2, "Bob Smith", "Best product ever!");

display_reviews(1);


class Review {
    private $id;
    private $reviewer_id;
    private $product_id;
    private $rating;
    private $title;
    private $content;

    public function __construct($id, $reviewer_id, $product_id, $rating, $title, $content) {
        $this->id = $id;
        $this->reviewer_id = $reviewer_id;
        $this->product_id = $product_id;
        $this->rating = $rating;
        $this->title = $title;
        $this->content = $content;
    }

    public function getId() {
        return $this->id;
    }

    public function getReviewerId() {
        return $this->reviewer_id;
    }

    public function getProductID() {
        return $this->product_id;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }
}


class User {
    private $id;
    private $username;
    private $email;

    public function __construct($id, $username, $email) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }
}


class Product {
    private $id;
    private $title;
    private $description;

    public function __construct($id, $title, $description) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }
}


class ReviewController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createReview(Review $review, User $user, Product $product) {
        // Insert review into database
        $query = "INSERT INTO reviews (reviewer_id, product_id, rating, title, content) VALUES (:reviewer_id, :product_id, :rating, :title, :content)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':reviewer_id' => $user->getId(),
            ':product_id' => $product->getId(),
            ':rating' => $review->getRating(),
            ':title' => $review->getTitle(),
            ':content' => $review->getContent()
        ]);
    }

    public function getReviews() {
        // Retrieve reviews from database
        $query = "SELECT * FROM reviews";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Review');
    }
}


class ReviewService {
    private $reviewController;

    public function __construct($reviewController) {
        $this->reviewController = $reviewController;
    }

    public function createReview(Review $review, User $user, Product $product) {
        $this->reviewController->createReview($review, $user, $product);
    }

    public function getReviews() {
        return $this->reviewController->getReviews();
    }
}


use App\Controllers\ReviewController;
use App\Services\ReviewService;

$app->map('/reviews', ['ReviewController', 'getReviews'], function ($request, $response) use ($app) {
    return ReviewService::getInstance($app)->getReviews();
})->via('GET');

$app->post('/reviews', function ($request, $response) use ($app) {
    $review = new Review(0, 1, 1, 5, 'Test review', 'This is a test review.');
    $user = new User(1, 'testuser', 'test@example.com');
    $product = new Product(1, 'Test product', 'This is a test product.');

    ReviewService::getInstance($app)->createReview($review, $user, $product);

    return $response->withJson(['message' => 'Review created successfully.']);
})->via('POST');


// Get all reviews
$response = $client->get('/reviews');
$reviews = json_decode($response->getBody()->getContents(), true);
print_r($reviews);

// Create a new review
$response = $client->post('/reviews', ['json' => [
    'rating' => 5,
    'title' => 'Test review',
    'content' => 'This is a test review.'
]]);
print_r(json_decode($response->getBody()->getContents(), true));


// Review class to encapsulate functionality
class Review {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Submit a new review
    public function submitReview($productId, $rating, $comment, $userId) {
        try {
            $stmt = $this->db->prepare("INSERT INTO Reviews (product_id, rating, comment, user_id) VALUES (:product_id, :rating, :comment, :user_id)");
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);
            $stmt->bindParam(':user_id', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error submitting review: " . $e->getMessage();
            return false;
        }
    }

    // Get all reviews for a product
    public function getReviews($productId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM Reviews WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching reviews: " . $e->getMessage();
            return array();
        }
    }

    // Get all reviews for a user
    public function getReviewsByUser($userId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM Reviews WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching reviews: " . $e->getMessage();
            return array();
        }
    }

    // Get average rating for a product
    public function getAverageRating($productId) {
        try {
            $stmt = $this->db->prepare("SELECT AVG(rating) as avg_rating FROM Reviews WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['avg_rating'] ?? 0;
        } catch (PDOException $e) {
            echo "Error fetching average rating: " . $e->getMessage();
            return 0;
        }
    }

    // Helper function to get product by id
    public function getProduct($productId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM Products WHERE id = :product_id");
            $stmt->bindParam(':product_id', $productId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching product: " . $e->getMessage();
            return array();
        }
    }
}


$db = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');

$review = new Review($db);

// Submit a review
$user_id = 1;
$product_id = 1;
$rating = 5;
$comment = "Great product!";

if ($review->submitReview($product_id, $rating, $comment, $user_id)) {
    echo "Review submitted successfully!";
} else {
    echo "Error submitting review.";
}

// Get all reviews for a product
$product_id = 1;
$reviews = $review->getReviews($product_id);
echo "Reviews for product ID: " . print_r($reviews, true);

// Get average rating for a product
$product_id = 1;
$average_rating = $review->getAverageRating($product_id);
echo "Average rating for product ID: " . print_r($average_rating, true);

// Helper function to get product by id
$product_id = 1;
$product = $review->getProduct($product_id);
echo "Product details: " . print_r($product, true);


<?php

// Define the Review class
class Review {
  public $id;
  public $reviewer_name;
  public $rating;
  public $review_text;
  public $created_at;

  function __construct($id, $reviewer_name, $rating, $review_text) {
    $this->id = $id;
    $this->reviewer_name = $reviewer_name;
    $this->rating = $rating;
    $this->review_text = $review_text;
    $this->created_at = date('Y-m-d H:i:s');
  }
}

// Define the ReviewModel class
class ReviewModel {
  public function get_reviews($product_id) {
    // Get reviews from database (using PDO or MySQLi)
    $reviews = array();
    // Example query to retrieve reviews
    $query = "SELECT * FROM reviews WHERE product_id = :product_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
      $review = new Review($row['id'], $row['reviewer_name'], $row['rating'], $row['review_text']);
      $reviews[] = $review;
    }
    return $reviews;
  }

  public function add_review($product_id, $review_data) {
    // Get product ID and review data from the request
    $query = "INSERT INTO reviews (product_id, reviewer_name, rating, review_text)
              VALUES (:product_id, :reviewer_name, :rating, :review_text)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':reviewer_name', $review_data['reviewer_name']);
    $stmt->bindParam(':rating', $review_data['rating']);
    $stmt->bindParam(':review_text', $review_data['review_text']);
    return $stmt->execute();
  }
}

// Define the ReviewController class
class ReviewController {
  public function index($product_id) {
    // Get reviews for a specific product
    $reviews = (new ReviewModel())->get_reviews($product_id);
    echo json_encode($reviews);
  }

  public function add_review() {
    // Handle adding new review
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $review_data = $_POST;
      $result = (new ReviewModel())->add_review($_GET['product_id'], $review_data);
      echo json_encode(array('success' => $result));
    }
  }
}

// Example usage:
$review_controller = new ReviewController();

// Get reviews for a specific product
echo $review_controller->index(123);

// Add new review
$_POST = array(
  'reviewer_name' => 'John Doe',
  'rating' => 5,
  'review_text' => 'Great product!'
);
$review_controller->add_review();
?>


// Review Model
class Review {
    private $id;
    private $product_id;
    private $user_id;
    private $rating;
    private $comment;

    public function __construct($id, $product_id, $user_id, $rating, $comment) {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->user_id = $user_id;
        $this->rating = $rating;
        $this->comment = $comment;
    }

    public function getId() {
        return $this->id;
    }

    public function getProductID() {
        return $this->product_id;
    }

    public function getUserID() {
        return $this->user_id;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getComment() {
        return $this->comment;
    }
}

// Review Controller
class ReviewController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createReview(Review $review) {
        // Check if review already exists for user and product
        $existingReview = $this->getReviewByUserAndProduct($review->getUserID(), $review->getProductID());
        if ($existingReview !== null) {
            return "Review already exists";
        }

        // Insert new review into database
        $sql = "INSERT INTO reviews (product_id, user_id, rating, comment)
                VALUES (:product_id, :user_id, :rating, :comment)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":product_id", $review->getProductID());
        $stmt->bindParam(":user_id", $review->getUserID());
        $stmt->bindParam(":rating", $review->getRating());
        $stmt->bindParam(":comment", $review->getComment());
        $stmt->execute();

        return "Review created successfully";
    }

    public function getReviewsByProduct($product_id) {
        // Get all reviews for product
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->execute();
        $reviews = $stmt->fetchAll();

        return $reviews;
    }

    public function getReviewByUserAndProduct($user_id, $product_id) {
        // Get review for user and product
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $review = $stmt->fetch();

        return $review;
    }
}


// Create database connection
$db = new PDO("mysql:host=localhost;dbname=mydatabase", "username", "password");

// Create review controller
$reviewController = new ReviewController($db);

// Create new review
$review = new Review(null, 1, 1, 5, "Great product!");

// Create review
$result = $reviewController->createReview($review);
echo $result; // Output: Review created successfully

// Get all reviews for product
$product_id = 1;
$reviews = $reviewController->getReviewsByProduct($product_id);
foreach ($reviews as $review) {
    echo "Rating: {$review['rating']}, Comment: {$review['comment']}<br>";
}


CREATE TABLE reviews (
  id INT PRIMARY KEY AUTO_INCREMENT,
  product_id INT NOT NULL,
  user_id INT NOT NULL,
  rating TINYINT(1) NOT NULL,
  review TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL
);


class Review {
  private $db;

  public function __construct($db) {
    $this->db = $db;
  }

  public function getAllReviewsForProduct($productId) {
    $query = "SELECT * FROM reviews WHERE product_id = ?";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$productId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function addReview($data) {
    $query = "INSERT INTO reviews (product_id, user_id, rating, review) VALUES (?, ?, ?, ?)";
    $stmt = $this->db->prepare($query);
    $stmt->execute([
      $data['product_id'],
      $data['user_id'],
      $data['rating'],
      $data['review']
    ]);
  }

  public function getReviewById($id) {
    $query = "SELECT * FROM reviews WHERE id = ?";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}


function addUserReview($productId, $userId, $rating, $review) {
  $db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
  $reviewModel = new Review($db);

  if ($review) {
    $reviewData = [
      'product_id' => $productId,
      'user_id' => $userId,
      'rating' => $rating,
      'review' => $review
    ];

    $reviewModel->addReview($reviewData);
    echo "Review added successfully!";
  } else {
    echo "Please enter a review.";
  }
}


addUserReview(1, 1, 5, 'This product is amazing!');


<?php
class Review {
  private $db;

  public function __construct() {
    // Connect to database (e.g. MySQL)
    $this->db = new mysqli('localhost', 'username', 'password', 'database');
    if ($this->db->connect_error) {
      die("Connection failed: " . $this->db->connect_error);
    }
  }

  public function addReview($userId, $productId, $rating, $comment) {
    // Validate input
    if (!$rating || !is_numeric($rating)) {
      throw new Exception('Invalid rating');
    }
    if (empty($comment)) {
      throw new Exception('Comment is required');
    }

    // Insert review into database
    $query = "INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param('iiii', $userId, $productId, $rating, $comment);
    $result = $stmt->execute();
    if (!$result) {
      throw new Exception('Error adding review');
    }
  }

  public function getReviewsForProduct($productId) {
    // Get reviews for specific product
    $query = "SELECT * FROM reviews WHERE product_id = ?";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param('i', $productId);
    $result = $stmt->execute();
    if ($result) {
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
      throw new Exception('Error retrieving reviews');
    }
  }

  public function getReviewsForUser($userId) {
    // Get reviews for specific user
    $query = "SELECT * FROM reviews WHERE user_id = ?";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param('i', $userId);
    $result = $stmt->execute();
    if ($result) {
      return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
      throw new Exception('Error retrieving reviews');
    }
  }

  public function deleteReview($reviewId) {
    // Delete review from database
    $query = "DELETE FROM reviews WHERE id = ?";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param('i', $reviewId);
    $result = $stmt->execute();
    if (!$result) {
      throw new Exception('Error deleting review');
    }
  }
}
?>


// Create instance of Review class
$review = new Review();

// Add a review for a product
try {
  $review->addReview(1, 5, 4.5, 'Great product!');
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}

// Get reviews for a product
$reviews = $review->getReviewsForProduct(5);
foreach ($reviews as $review) {
  echo $review['comment'] . "
";
}

// Delete a review
try {
  $review->deleteReview(1);
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}


// database connection details
$db_host = 'localhost';
$db_username = 'username';
$db_password = 'password';
$db_name = 'database';

// connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

function user_review($user_id, $product_id) {
  global $conn;

  // check if review already exists for this product and user
  $sql = "SELECT * FROM reviews WHERE user_id = '$user_id' AND product_id = '$product_id'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    return false; // review already exists, do not allow editing
  }

  // get user rating and review for this product
  $sql = "SELECT rating, review FROM reviews WHERE user_id = '$user_id' AND product_id = '$product_id'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      return array(
        'rating' => $row['rating'],
        'review' => $row['review']
      );
    }
  }

  // no review found, create new one
  $sql = "INSERT INTO reviews (user_id, product_id, rating, review) VALUES ('$user_id', '$product_id', NULL, '')";
  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return false; // error inserting review
  }
}


$user_id = 1;
$product_id = 3;

$result = user_review($user_id, $product_id);

if ($result === true) {
  echo "Review created successfully!";
} elseif ($result === false) {
  echo "Error creating review.";
} else {
  echo "Rating: " . $result['rating'];
  echo "Review: " . $result['review'];
}


<?php

class Review {
  private $id;
  private $product_id;
  private $rating;
  private $review_text;

  public function __construct($data) {
    $this->id = $data['id'];
    $this->product_id = $data['product_id'];
    $this->rating = $data['rating'];
    $this->review_text = $data['review_text'];
  }

  public static function getReviews($productId, $limit = 10) {
    // Retrieve reviews from database
    $reviews = array();
    // Simulate database query for simplicity
    if ($productId == 1) {
      $reviews[] = new Review(array('id' => 1, 'product_id' => 1, 'rating' => 5, 'review_text' => 'Great product!'));
      $reviews[] = new Review(array('id' => 2, 'product_id' => 1, 'rating' => 4, 'review_text' => 'Good product, but expensive.'));
    }
    return $reviews;
  }

  public static function addReview($data) {
    // Validate review data
    if (!isset($data['product_id']) || !isset($data['rating']) || !isset($data['review_text'])) {
      throw new Exception('Invalid review data');
    }
    // Insert review into database
    // Simulate database insertion for simplicity
    $newReview = new Review(array('id' => 3, 'product_id' => $data['product_id'], 'rating' => $data['rating'], 'review_text' => $data['review_text']));
    return $newReview;
  }
}

?>


// Get reviews for a product with ID 1
$reviews = Review::getReviews(1);
foreach ($reviews as $review) {
  echo "Rating: {$review->rating} - Review: {$review->review_text}
";
}

// Add a new review for a product with ID 1
$newReviewData = array('product_id' => 1, 'rating' => 5, 'review_text' => 'Love this product!');
$newReview = Review::addReview($newReviewData);
echo "New review added: {$newReview->id} - Rating: {$newReview->rating} - Review: {$newReview->review_text}
";


CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255)
);

CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    description TEXT
);

CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    product_id INT,
    rating INT,
    comment TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);


// Review.php

class Review {
    private $id;
    private $userId;
    private $productId;
    private $rating;
    private $comment;

    public function __construct($id = null, $userId = null, $productId = null, $rating = null, $comment = null) {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->rating = $rating;
        $this->comment = $comment;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getComment() {
        return $this->comment;
    }
}


// ReviewController.php

class ReviewController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Get all reviews for a product
    public function getReviewsForProduct($productId) {
        $sql = "SELECT * FROM reviews WHERE product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Review');
    }

    // Create a new review
    public function createReview(Review $review) {
        $sql = "INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (:user_id, :product_id, :rating, :comment)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $review->getUserId());
        $stmt->bindParam(':product_id', $review->getProductId());
        $stmt->bindParam(':rating', $review->getRating());
        $stmt->bindParam(':comment', $review->getComment());
        return $stmt->execute();
    }
}


// index.php

require_once 'config.php';
require_once 'ReviewController.php';

$db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');

$reviewController = new ReviewController($db);

// Get all reviews for a product
$reviews = $reviewController->getReviewsForProduct(1);
foreach ($reviews as $review) {
    echo "Review ID: " . $review->getId() . "
";
    echo "User ID: " . $review->getUserId() . "
";
    echo "Product ID: " . $review->getProductId() . "
";
    echo "Rating: " . $review->getRating() . "
";
    echo "Comment: " . $review->getComment() . "

";
}

// Create a new review
$review = new Review(0, 1, 1, 5, "Great product!");
$success = $reviewController->createReview($review);
if ($success) {
    echo "Review created successfully!
";
} else {
    echo "Error creating review.
";
}


<?php

// Connect to database
require_once 'dbconfig.php';
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if (!$connection) {
  die("Connection failed: " . mysqli_connect_error());
}

// Function to create a new review
function create_review($user_id, $product_name, $rating, $review_text) {
  global $connection;
  
  $sql = "INSERT INTO reviews (user_id, product_name, rating, review_text)
          VALUES ('$user_id', '$product_name', '$rating', '$review_text')";
  
  if (mysqli_query($connection, $sql)) {
    echo 'Review created successfully!';
  } else {
    echo 'Error creating review: ' . mysqli_error($connection);
  }
}

// Function to view all reviews
function view_reviews() {
  global $connection;
  
  $sql = "SELECT * FROM reviews";
  $result = mysqli_query($connection, $sql);
  
  if (mysqli_num_rows($result) > 0) {
    echo '<table border="1" cellpadding="10">';
    while ($row = mysqli_fetch_assoc($result)) {
      echo '<tr>';
      echo '<td>' . $row['product_name'] . '</td>';
      echo '<td>' . $row['rating'] . '</td>';
      echo '<td>' . $row['review_text'] . '</td>';
      echo '<td><a href="update_review.php?id=' . $row['id'] . '">Update</a></td>';
      echo '<td><a href="delete_review.php?id=' . $row['id'] . '">Delete</a></td>';
      echo '</tr>';
    }
    echo '</table>';
  } else {
    echo 'No reviews found.';
  }
}

// Function to update a review
function update_review($review_id, $product_name, $rating, $review_text) {
  global $connection;
  
  $sql = "UPDATE reviews SET product_name='$product_name', rating='$rating', review_text='$review_text' WHERE id=$review_id";
  
  if (mysqli_query($connection, $sql)) {
    echo 'Review updated successfully!';
  } else {
    echo 'Error updating review: ' . mysqli_error($connection);
  }
}

// Function to delete a review
function delete_review($review_id) {
  global $connection;
  
  $sql = "DELETE FROM reviews WHERE id=$review_id";
  
  if (mysqli_query($connection, $sql)) {
    echo 'Review deleted successfully!';
  } else {
    echo 'Error deleting review: ' . mysqli_error($connection);
  }
}

// Test the functions
if (isset($_GET['action'])) {
  switch ($_GET['action']) {
    case 'create':
      create_review($_POST['user_id'], $_POST['product_name'], $_POST['rating'], $_POST['review_text']);
      break;
    case 'view_reviews':
      view_reviews();
      break;
    case 'update':
      update_review($_POST['id'], $_POST['product_name'], $_POST['rating'], $_POST['review_text']);
      break;
    case 'delete':
      delete_review($_GET['id']);
      break;
  }
} else {
  echo '<form action="reviews.php?action=create" method="post">';
  echo 'Product Name: <input type="text" name="product_name"><br>';
  echo 'Rating: <select name="rating">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select><br>';
  echo 'Review Text: <textarea name="review_text"></textarea><br>';
  echo '<input type="hidden" name="user_id" value="' . $_SESSION['id'] . '">';
  echo '<input type="submit" value="Create Review">';
  echo '</form>';
}

?>


// Connect to database
$db = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');

function addReview($productId, $userId, $rating, $review) {
  // Insert review into database
  $stmt = $db->prepare('INSERT INTO reviews (product_id, user_id, rating, review) VALUES (:product_id, :user_id, :rating, :review)');
  $stmt->execute([
    ':product_id' => $productId,
    ':user_id' => $userId,
    ':rating' => $rating,
    ':review' => $review
  ]);
}

function getAverageRating($productId) {
  // Retrieve average rating from database
  $stmt = $db->prepare('SELECT AVG(rating) FROM reviews WHERE product_id = :product_id');
  $stmt->execute([':product_id' => $productId]);
  return (int)$stmt->fetchColumn();
}

function getReviewsForProduct($productId, $limit = 10) {
  // Retrieve reviews for a specific product from database
  $stmt = $db->prepare('SELECT * FROM reviews WHERE product_id = :product_id ORDER BY created_at DESC LIMIT :limit');
  $stmt->execute([':product_id' => $productId, ':limit' => $limit]);
  return $stmt->fetchAll();
}

// Example usage:
addReview(1, 1, 5, 'Great product!');
echo getAverageRating(1); // Output: 5
$reviews = getReviewsForProduct(1);
print_r($reviews); // Output: Array of reviews for product ID 1


<?php

function create_review($product_id, $rating, $comment) {
  // Check if product exists
  if (!exists_product($product_id)) {
    return array("error" => "Product not found");
  }

  // Create new review
  $review = array(
    "product_id" => $product_id,
    "rating" => $rating,
    "comment" => $comment
  );

  // Insert review into database
  try {
    db_connect();
    query("INSERT INTO reviews (product_id, rating, comment) VALUES (:product_id, :rating, :comment)", array(
      ":product_id" => $product_id,
      ":rating" => $rating,
      ":comment" => $comment
    ));
    close_db();
  } catch (PDOException $e) {
    return array("error" => "Failed to create review");
  }

  // Return success message
  return array("success" => "Review created successfully");
}

function get_reviews($product_id) {
  // Check if product exists
  if (!exists_product($product_id)) {
    return array("error" => "Product not found");
  }

  // Get reviews from database
  try {
    db_connect();
    $reviews = query("SELECT * FROM reviews WHERE product_id = :product_id", array(
      ":product_id" => $product_id
    ));
    close_db();

    // Format reviews as JSON
    foreach ($reviews as &$review) {
      $review["rating"] = (int)$review["rating"];
    }
    return json_encode($reviews);
  } catch (PDOException $e) {
    return array("error" => "Failed to retrieve reviews");
  }
}

function exists_product($product_id) {
  // Check if product exists in database
  try {
    db_connect();
    $result = query("SELECT * FROM products WHERE id = :id", array(
      ":id" => $product_id
    ));
    close_db();

    return !empty($result);
  } catch (PDOException $e) {
    return false;
  }
}

?>


$review_data = create_review(1, 4, "Great product!");
print_r($review_data);


$reviews = get_reviews(1);
echo $reviews;


// Include database connection settings
require 'db_connection.php';

// Function to display all reviews
function display_reviews() {
  $sql = "SELECT * FROM reviews ORDER BY created_at DESC";
  $result = mysqli_query($conn, $sql);
  while ($review = mysqli_fetch_assoc($result)) {
    echo '<div class="review">';
    echo '<h2>' . $review['product_name'] . '</h2>';
    echo '<p>Rating: ' . $review['rating'] . '/5</p>';
    echo '<p>' . substr($review['review'], 0, 200) . '...</p>';
    echo '<p>Posted by ' . get_user_name($review['user_id']) . '</p>';
    echo '</div>';
  }
}

// Function to display user reviews
function display_user_reviews($user_id) {
  $sql = "SELECT * FROM reviews WHERE user_id = '$user_id' ORDER BY created_at DESC";
  $result = mysqli_query($conn, $sql);
  while ($review = mysqli_fetch_assoc($result)) {
    echo '<div class="review">';
    echo '<h2>' . $review['product_name'] . '</h2>';
    echo '<p>Rating: ' . $review['rating'] . '/5</p>';
    echo '<p>' . substr($review['review'], 0, 200) . '...</p>';
    echo '<p>Posted by ' . get_user_name($review['user_id']) . '</p>';
    echo '</div>';
  }
}

// Function to add a new review
function add_review($product_name, $rating, $review, $user_id) {
  $sql = "INSERT INTO reviews (product_name, rating, review, user_id) VALUES ('$product_name', '$rating', '$review', '$user_id')";
  if (mysqli_query($conn, $sql)) {
    echo 'Review added successfully!';
  } else {
    echo 'Error adding review: ' . mysqli_error($conn);
  }
}

// Function to get the name of a user
function get_user_name($user_id) {
  $sql = "SELECT username FROM users WHERE id = '$user_id'";
  $result = mysqli_query($conn, $sql);
  return mysqli_fetch_assoc($result)['username'];
}


<?php display_reviews(); ?>


<?php display_user_reviews(1); // Replace 1 with the desired user ID ?>


<?php add_review('Product Name', 4, 'This is a great product!', 2); // Replace values with your own ?>


// models/Review.php

class Review {
  private $id;
  private $userId;
  private $productId;
  private $rating;
  private $comment;

  public function __construct($data) {
    $this->id = (int)$data['id'];
    $this->userId = (int)$data['user_id'];
    $this->productId = (int)$data['product_id'];
    $this->rating = (float)$data['rating'];
    $this->comment = $data['comment'];
  }

  public function getId() {
    return $this->id;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function getProductId() {
    return $this->productId;
  }

  public function getRating() {
    return $this->rating;
  }

  public function getComment() {
    return $this->comment;
  }
}


// services/ReviewService.php

class ReviewService {
  private $db;

  public function __construct($db) {
    $this->db = $db;
  }

  public function getAllReviews($productId) {
    $query = "SELECT * FROM reviews WHERE product_id = ?";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$productId]);
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Review');
  }

  public function getReview($id) {
    $query = "SELECT * FROM reviews WHERE id = ?";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$id]);
    return $stmt->fetchObject('Review');
  }

  public function addReview(Review $review) {
    $query = "INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$review->getUserId(), $review->getProductId(), $review->getRating(), $review->getComment()]);
  }

  public function updateReview(Review $review) {
    $query = "UPDATE reviews SET rating = ?, comment = ? WHERE id = ?";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$review->getRating(), $review->getComment(), $review->getId()]);
  }

  public function deleteReview($id) {
    $query = "DELETE FROM reviews WHERE id = ?";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$id]);
  }
}


// controllers/ReviewController.php

class ReviewController {
  private $reviewService;

  public function __construct(ReviewService $reviewService) {
    $this->reviewService = $reviewService;
  }

  public function index() {
    $productId = $_GET['product_id'];
    $reviews = $this->reviewService->getAllReviews($productId);
    return view('reviews.index', ['reviews' => $reviews]);
  }

  public function show($id) {
    $review = $this->reviewService->getReview($id);
    return view('reviews.show', ['review' => $review]);
  }

  public function create() {
    // render create form
  }

  public function store(Request $request) {
    $review = new Review(['user_id' => auth()->id(), 'product_id' => $_GET['product_id'], 'rating' => (float)$request->input('rating'), 'comment' => $request->input('comment')]);
    $this->reviewService->addReview($review);
    return redirect()->back();
  }

  public function edit($id) {
    // render edit form
  }

  public function update(Request $request, $id) {
    $review = new Review(['rating' => (float)$request->input('rating'), 'comment' => $request->input('comment')]);
    $this->reviewService->updateReview($review);
    return redirect()->back();
  }

  public function destroy($id) {
    $this->reviewService->deleteReview($id);
    return redirect()->back();
  }
}


// views/reviews/index.blade.php

@foreach ($reviews as $review)
  {{ $review->getRating() }} stars by {{ $review->getUserId() }}
  {{ $review->getComment() }}
@endforeach


<?php
class Review {
    private $id;
    private $user_id;
    private $product_id;
    private $rating;
    private $comment;

    public function __construct($data = null) {
        if ($data !== null) {
            $this->loadData($data);
        }
    }

    private function loadData($data) {
        $this->id = (int)$data['id'];
        $this->user_id = (int)$data['user_id'];
        $this->product_id = (int)$data['product_id'];
        $this->rating = (float)$data['rating'];
        $this->comment = trim($data['comment']);
    }

    public function getId() {
        return $this->id;
    }

    public function getUserID() {
        return $this->user_id;
    }

    public function getProductID() {
        return $this->product_id;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getComment() {
        return $this->comment;
    }
}

class ReviewManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function storeReview(Review $review) {
        $stmt = $this->db->prepare('INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$review->getUserID(), $review->getProductID(), $review->getRating(), $review->getComment()]);
    }

    public function getReviews($productID = null) {
        if ($productID === null) {
            $stmt = $this->db->prepare('SELECT * FROM reviews ORDER BY id DESC');
        } else {
            $stmt = $this->db->prepare('SELECT * FROM reviews WHERE product_id = ? ORDER BY id DESC');
            $stmt->execute([$productID]);
        }

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Review');
    }

    public function calculateAverageRating($productID) {
        $reviews = $this->getReviews($productID);
        if (empty($reviews)) {
            return 0;
        }

        $sum = array_sum(array_column($reviews, 'rating'));
        return round($sum / count($reviews));
    }
}
?>


require_once 'review.php';

// assume we have a PDO instance named `$db` that's connected to our database

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$manager = new ReviewManager($db);
$review1 = new Review(['user_id' => 1, 'product_id' => 10, 'rating' => 5.0, 'comment' => 'Great product!']);
$review2 = new Review(['user_id' => 2, 'product_id' => 10, 'rating' => 4.0, 'comment' => 'Good product']);

$manager->storeReview($review1);
$manager->storeReview($review2);

// get all reviews
$reviews = $manager->getReviews();

// get reviews for a specific product
$productID = 10;
$reviewsForProduct = $manager->getReviews($productID);

// calculate average rating for a product
$averageRating = $manager->calculateAverageRating($productID);
echo "Average rating for product $productID is $averageRating stars";


// Review system functions

// Function to display reviews for a given product
function get_reviews($product_id) {
    global $db;
    $query = "SELECT * FROM reviews WHERE product_id = '$product_id'";
    return mysqli_query($db, $query);
}

// Function to add a review for a given product
function create_review($product_id, $rating, $comment) {
    global $db;
    $query = "INSERT INTO reviews (product_id, rating, comment)
               VALUES ('$product_id', '$rating', '$comment')";
    return mysqli_query($db, $query);
}

// Function to display review form for a given product
function get_review_form($product_id) {
    ?>
    <h2>Leave a Review</h2>
    <form action="create_review.php" method="post">
        <label for="rating">Rating:</label>
        <select id="rating" name="rating">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select><br>
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment"></textarea><br>
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <button type="submit">Submit Review</button>
    </form>
    <?php
}

// Function to display individual review details
function get_review_details($review_id) {
    global $db;
    $query = "SELECT * FROM reviews WHERE id = '$review_id'";
    return mysqli_fetch_assoc(mysqli_query($db, $query));
}


// Display reviews for a given product
<?php
$product_id = 1;
$reviews = get_reviews($product_id);
foreach ($reviews as $review) {
    echo $review['comment'] . " (" . $review['rating'] . "/5)";
}
?>

// Add a review for a given product
<?php
$product_id = $_POST['product_id'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];

if (create_review($product_id, $rating, $comment)) {
    echo "Review created successfully!";
} else {
    echo "Error creating review.";
}
?>


// Example using PDO and prepared statement
$stmt = $db->prepare("INSERT INTO reviews (product_id, rating, comment)
                     VALUES (:product_id, :rating, :comment)");
$stmt->bindParam(':product_id', $product_id);
$stmt->bindParam(':rating', $rating);
$stmt->bindParam(':comment', $comment);
if ($stmt->execute()) {
    echo "Review created successfully!";
} else {
    echo "Error creating review.";
}


// Database connection settings
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function createUserReview($userId, $productId, $rating, $comment) {
    global $conn;

    // Query to create a new review
    $sql = "INSERT INTO reviews (user_id, product_id, rating, comment)
            VALUES ('$userId', '$productId', '$rating', '$comment')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }
}

function getUserReviews($userId) {
    global $conn;

    // Query to retrieve all reviews from a user
    $sql = "SELECT * FROM reviews WHERE user_id = '$userId'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result;
    } else {
        echo "No reviews found.";
        return null;
    }
}

function getProductReviews($productId) {
    global $conn;

    // Query to retrieve all reviews for a product
    $sql = "SELECT * FROM reviews WHERE product_id = '$productId'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result;
    } else {
        echo "No reviews found.";
        return null;
    }
}

function updateReviewRating($reviewId, $newRating) {
    global $conn;

    // Query to update a review's rating
    $sql = "UPDATE reviews SET rating = '$newRating' WHERE id = '$reviewId'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }
}

function updateReviewComment($reviewId, $newComment) {
    global $conn;

    // Query to update a review's comment
    $sql = "UPDATE reviews SET comment = '$newComment' WHERE id = '$reviewId'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }
}

function deleteReview($reviewId) {
    global $conn;

    // Query to delete a review
    $sql = "DELETE FROM reviews WHERE id = '$reviewId'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }
}


// Create a new user review
createUserReview(1, 1, 5, 'This product is great!');

// Retrieve all reviews from a user
$result = getUserReviews(1);
while ($row = $result->fetch_assoc()) {
    echo "Rating: " . $row['rating'] . ", Comment: " . $row['comment'];
}

// Update a review's rating
updateReviewRating(1, 4);

// Retrieve all reviews for a product
$result = getProductReviews(1);
while ($row = $result->fetch_assoc()) {
    echo "Rating: " . $row['rating'] . ", Comment: " . $row['comment'];
}

// Update a review's comment
updateReviewComment(1, 'This product is okay.');

// Delete a review
deleteReview(1);


// configuration file
require 'config.php';

// function to get all reviews for a product
function getReviews($productId) {
    global $db;
    $query = "SELECT * FROM reviews WHERE product_id = '$productId'";
    return $db->query($query)->fetch_all(MYSQLI_ASSOC);
}

// function to add new review
function addReview($data) {
    global $db;
    extract($data);

    // sanitize inputs
    $rating = (int)$rating;
    $review = mysqli_real_escape_string($db, $review);

    // insert into database
    $query = "INSERT INTO reviews (product_id, user_id, rating, review)
              VALUES ('$productId', '$userId', '$rating', '$review')";
    return $db->query($query);
}

// function to display average rating for a product
function getAverageRating($productId) {
    global $db;
    $query = "SELECT AVG(rating) as average FROM reviews WHERE product_id = '$productId'";
    $result = $db->query($query)->fetch_assoc();
    return isset($result['average']) ? (float)$result['average'] : 0;
}

// example usage:
$productId = 1; // replace with actual product ID

// get all reviews for this product
$reviews = getReviews($productId);
echo "Reviews for Product $productId:<br>";

foreach ($reviews as $review) {
    echo "Rating: $review[rating] - Review: $review[review]<br>";
}

// add new review
$data = array(
    'product_id' => 1,
    'user_id' => 1,
    'rating' => 5,
    'review' => 'This is a great product!'
);
addReview($data);

// display average rating for this product
echo "Average Rating: ".getAverageRating(1)."<br>";



<?php

// Database configuration
$host = 'localhost';
$dbname = 'reviews_db';
$username = 'root';
$password = '';

try {
    // Establish a connection to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function createUser($name)
{
    global $pdo;

    try {
        // Create a prepared statement to insert user data
        $stmt = $pdo->prepare("INSERT INTO users (name) VALUES (:name)");
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        die("Error creating user: " . $e->getMessage());
    }
}

function createReview($userId, $productId, $rating, $review)
{
    global $pdo;

    try {
        // Create a prepared statement to insert review data
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, product_id, rating, review) VALUES (:user_id, :product_id, :rating, :review)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':review', $review);
        $stmt->execute();

        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        die("Error creating review: " . $e->getMessage());
    }
}

function getReview($id)
{
    global $pdo;

    try {
        // Create a prepared statement to select the specified review
        $stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    } catch (PDOException $e) {
        die("Error getting review: " . $e->getMessage());
    }
}

function updateReview($id, $rating = null, $review = null)
{
    global $pdo;

    try {
        // Create a prepared statement to update the specified review
        $stmt = $pdo->prepare("UPDATE reviews SET rating = :rating, review = :review WHERE id = :id");
        if ($rating !== null) {
            $stmt->bindParam(':rating', $rating);
        }
        if ($review !== null) {
            $stmt->bindParam(':review', $review);
        }
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        die("Error updating review: " . $e->getMessage());
    }
}

function deleteReview($id)
{
    global $pdo;

    try {
        // Create a prepared statement to delete the specified review
        $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        die("Error deleting review: " . $e->getMessage());
    }
}

// Example usage
$userName = 'John Doe';
$userId = createUser($userName);

$productName = 'Example Product';
$productDescription = 'This is an example product.';
$product = array('name' => $productName, 'description' => $productDescription);

$productId = createProduct($productName, $productDescription); // Note: This function doesn't exist in the original code snippet. You'll need to implement it separately.

$reviewRating = 5;
$reviewText = 'This product is amazing!';

$reviewId = createReview($userId, $productId, $reviewRating, $reviewText);

// Retrieve a review by ID
$review = getReview($reviewId);
print_r($review);

// Update the review
$updateData = array('rating' => 4, 'review' => 'This product is great!');
updateReview($reviewId, $updateData['rating'], $updateData['review']);

// Delete the review
deleteReview($reviewId);


// Connect to database
$dsn = 'mysql:host=localhost;dbname=mydatabase';
$username = 'myusername';
$password = 'mypassword';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Function to create a review
function create_review($product_id, $user_id, $rating, $review) {
    global $pdo;
    
    $stmt = $pdo->prepare('INSERT INTO reviews SET product_id = :product_id, user_id = :user_id, rating = :rating, review = :review');
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':review', $review);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Function to get all reviews for a product
function get_reviews($product_id) {
    global $pdo;
    
    $stmt = $pdo->prepare('SELECT * FROM reviews WHERE product_id = :product_id');
    $stmt->bindParam(':product_id', $product_id);
    
    if ($stmt->execute()) {
        return $stmt->fetchAll();
    } else {
        return array();
    }
}

// Function to get average rating for a product
function get_average_rating($product_id) {
    global $pdo;
    
    $stmt = $pdo->prepare('SELECT AVG(rating) AS average FROM reviews WHERE product_id = :product_id');
    $stmt->bindParam(':product_id', $product_id);
    
    if ($stmt->execute()) {
        $result = $stmt->fetch();
        return isset($result['average']) ? $result['average'] : 0;
    } else {
        return 0;
    }
}

// Example usage:
$product_id = 1;
$user_id = 1;
$rating = 4;
$review = 'Great product!';

if (create_review($product_id, $user_id, $rating, $review)) {
    echo 'Review created successfully!';
} else {
    echo 'Error creating review.';
}

$reviews = get_reviews($product_id);
echo 'Reviews for product ' . $product_id . ':';
print_r($reviews);

$average_rating = get_average_rating($product_id);
echo 'Average rating: ' . $average_rating;


// config.php
<?php
$host = 'localhost';
$dbname = 'reviews_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    echo "Connected to the database successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


// Review.php
<?php
class Review {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Function to add new reviews
    public function add_review($product_id, $user_id, $rating, $comment) {
        try {
            $query = "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (:product_id, :user_id, :rating, :comment)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Function to view all reviews
    public function view_reviews() {
        try {
            $query = "SELECT * FROM reviews";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Function to update an existing review
    public function update_review($review_id, $new_comment) {
        try {
            $query = "UPDATE reviews SET comment = :comment WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $review_id);
            $stmt->bindParam(':comment', $new_comment);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Function to delete a review
    public function delete_review($review_id) {
        try {
            $query = "DELETE FROM reviews WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $review_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}


// user_reviews.php
<?php
require_once 'config.php';
require_once 'Review.php';

class UserReviews {
    private $review;

    public function __construct() {
        $this->review = new Review($conn);
    }

    // Function to add a review for a product
    public function add_review_for_product($product_id, $user_id, $rating, $comment) {
        return $this->review->add_review($product_id, $user_id, $rating, $comment);
    }

    // Function to view all reviews for a user
    public function view_reviews_by_user($user_id) {
        try {
            $query = "SELECT * FROM reviews WHERE user_id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Function to view all reviews for a product
    public function view_reviews_by_product($product_id) {
        try {
            $query = "SELECT * FROM reviews WHERE product_id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $product_id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Function to update an existing review
    public function update_review($review_id, $new_comment) {
        return $this->review->update_review($review_id, $new_comment);
    }

    // Function to delete a review
    public function delete_review($review_id) {
        return $this->review->delete_review($review_id);
    }
}

// Example usage:
$reviews = new UserReviews();

// Add a review for a product
$product_id = 1;
$user_id = 1;
$rating = 5;
$comment = "Excellent product!";
echo var_export($reviews->add_review_for_product($product_id, $user_id, $rating, $comment), true);

// View all reviews by user
$user_id = 1;
echo "<pre>";
print_r($reviews->view_reviews_by_user($user_id));
echo "</pre>";

// Update an existing review
$review_id = 1;
$new_comment = "Best product ever!";
echo var_export($reviews->update_review($review_id, $new_comment), true);

// Delete a review
$review_id = 1;
echo var_export($reviews->delete_review($review_id), true);


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'your_database';

// Create a new database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function submitReview() {
    global $conn;

    // Get the review text and rating from the form
    $review_text = $_POST['review_text'];
    $rating = $_POST['rating'];

    // Insert a new review into the database
    $sql = "INSERT INTO reviews (user_id, review_text, rating)
            VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', 1, $review_text, $rating);
    if (!$stmt->execute()) {
        echo "Error submitting review: " . $stmt->error;
    }
}

function getReviews() {
    global $conn;

    // Retrieve all reviews from the database
    $sql = "SELECT r.id, r.review_text, r.rating, u.username
            FROM reviews r JOIN users u ON r.user_id = u.id";
    $result = $conn->query($sql);

    // Display each review in a table row
    echo "<table border='1'>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['review_text'] . "</td>";
        echo "<td>" . $row['rating'] . "/5</td>";
        echo "<td>Submitted by " . $row['username'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    submitReview();
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="review_text">Enter your review:</label>
    <br>
    <textarea id="review_text" name="review_text"></textarea>
    <br>
    <label for="rating">Rating (1-5):</label>
    <select id="rating" name="rating">
        <?php
            for ($i = 1; $i <= 5; $i++) {
                echo "<option value='$i'>$i/5</option>";
            }
        ?>
    </select>
    <br>
    <input type="submit" value="Submit Review">
</form>

<h2>Reviews:</h2>
<?php getReviews(); ?>

<?php
// Close the database connection when finished
$conn->close();
?>


<?php
// database connection settings
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database');

// create a function to connect to the database
function dbConnect() {
    $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// create functions for CRUD operations
function createUserReview($user_id, $product_id, $rating, $review_text) {
    $conn = dbConnect();
    $sql = "INSERT INTO reviews (user_id, product_id, rating, review_text) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $user_id, $product_id, $rating, $review_text);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function getReviewsByProduct($product_id) {
    $conn = dbConnect();
    $sql = "SELECT * FROM reviews WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getUserReview($user_id, $product_id) {
    $conn = dbConnect();
    $sql = "SELECT * FROM reviews WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function updateReview($id, $rating, $review_text) {
    $conn = dbConnect();
    $sql = "UPDATE reviews SET rating = ?, review_text = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $rating, $review_text, $id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function deleteReview($id) {
    $conn = dbConnect();
    $sql = "DELETE FROM reviews WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


<?php
$reviews = getReviewsByProduct(123); // replace with your product ID
?>
<ul>
  <?php foreach ($reviews as $review) { ?>
    <li>Rating: <?= $review['rating']; ?> - <?= $review['review_text']; ?></li>
  <?php } ?>
</ul>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    if (createUserReview($user_id, $product_id, $rating, $review_text)) {
        // review submitted successfully
    } else {
        // error submitting review
    }
}
?>


<?php

// Include database connection settings
require_once 'database.php';

// Function to submit a review
function submit_review($data) {
  global $db;

  try {
    // Insert review into database
    $stmt = $db->prepare("INSERT INTO reviews (user_id, product_id, review, rating) VALUES (:user_id, :product_id, :review, :rating)");
    $stmt->execute([
      'user_id' => $_SESSION['id'],
      'product_id' => $data['product_id'] ?? null,
      'review' => $data['review'],
      'rating' => $data['rating']
    ]);

    return true;

  } catch (PDOException $e) {
    echo "Error submitting review: " . $e->getMessage();
    return false;
  }
}

// Function to view all reviews
function view_reviews() {
  global $db;

  try {
    // Retrieve all reviews from database
    $stmt = $db->prepare("SELECT * FROM reviews JOIN users ON reviews.user_id = users.id");
    $stmt->execute();

    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $reviews;

  } catch (PDOException $e) {
    echo "Error viewing reviews: " . $e->getMessage();
    return false;
  }
}

// Function to view a specific review
function view_review($id) {
  global $db;

  try {
    // Retrieve review from database by ID
    $stmt = $db->prepare("SELECT * FROM reviews JOIN users ON reviews.user_id = users.id WHERE id = :id");
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);

  } catch (PDOException $e) {
    echo "Error viewing review: " . $e->getMessage();
    return false;
  }
}

?>


<?php

// Configuration
$databaseHost = 'localhost';
$databaseName = 'your_database_name';
$databaseUsername = 'your_database_username';
$databasePassword = 'your_database_password';

// Connect to database
$mysqli = new mysqli($databaseHost, $databaseUsername, $databasePassword, $databaseName);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function addReview($userId, $productId, $rating, $comment) {
    global $mysqli;

    // Check if review already exists
    $query = "SELECT * FROM reviews WHERE user_id = '$userId' AND product_id = '$productId'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        return array('error' => 'Review already exists');
    }

    // Insert new review
    $sql = "INSERT INTO reviews (user_id, product_id, rating, comment)
            VALUES ('$userId', '$productId', '$rating', '$comment')";
    if (!$mysqli->query($sql)) {
        return array('error' => 'Failed to add review');
    }

    return array('success' => true);
}

function getReview($reviewId) {
    global $mysqli;

    // Retrieve review by ID
    $query = "SELECT * FROM reviews WHERE id = '$reviewId'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return array('error' => 'Review not found');
}

function getUserReviews($userId) {
    global $mysqli;

    // Retrieve reviews by user ID
    $query = "SELECT * FROM reviews WHERE user_id = '$userId'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        return array_map(function($row) { return (object)array(
            'id' => $row['id'],
            'rating' => $row['rating'],
            'comment' => $row['comment']
        ); }, $result->fetch_all());
    }

    return array();
}

function getAllReviews() {
    global $mysqli;

    // Retrieve all reviews
    $query = "SELECT * FROM reviews";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        return array_map(function($row) { return (object)array(
            'id' => $row['id'],
            'rating' => $row['rating'],
            'comment' => $row['comment']
        ); }, $result->fetch_all());
    }

    return array();
}

// Close database connection
$mysqli->close();

?>


<?php

require_once 'review.php';

// Add new review
$userId = 1;
$productId = 1;
$rating = 5;
$comment = "Great product!";
$result = addReview($userId, $productId, $rating, $comment);
if ($result['success']) {
    echo "Review added successfully";
} else {
    echo "Error: " . $result['error'];
}

// Get review by ID
$reviewId = 1;
$reviewData = getReview($reviewId);
if (isset($reviewData['id'])) {
    echo "Review found with rating: " . $reviewData['rating'] . " and comment: " . $reviewData['comment'];
} else {
    echo "Error: Review not found";
}

// Get user reviews
$userId = 1;
$userReviews = getUserReviews($userId);
echo "User's reviews:
";
foreach ($userReviews as $review) {
    echo "Rating: " . $review->rating . ", Comment: " . $review->comment . "
";
}

// Get all reviews
$allReviews = getAllReviews();
echo "All reviews:
";
foreach ($allReviews as $review) {
    echo "Rating: " . $review->rating . ", Comment: " . $review->comment . "
";
}

?>


class Review {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Create a new review
     *
     * @param int $productId
     * @param int $userId
     * @param float $rating
     * @param string $review
     */
    public function createReview($productId, $userId, $rating, $review)
    {
        $query = "INSERT INTO reviews (product_id, user_id, rating, review) VALUES (:productId, :userId, :rating, :review)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':review', $review);
        return $stmt->execute();
    }

    /**
     * Get all reviews for a product
     *
     * @param int $productId
     */
    public function getReviewsForProduct($productId)
    {
        $query = "SELECT * FROM reviews WHERE product_id = :productId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':productId', $productId);
        return $stmt->execute()->fetchAll();
    }

    /**
     * Get all reviews for a user
     *
     * @param int $userId
     */
    public function getReviewsForUser($userId)
    {
        $query = "SELECT * FROM reviews WHERE user_id = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute()->fetchAll();
    }

    /**
     * Update a review
     *
     * @param int $reviewId
     * @param string $newRating
     * @param string $newReview
     */
    public function updateReview($reviewId, $newRating, $newReview)
    {
        $query = "UPDATE reviews SET rating = :rating, review = :review WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $reviewId);
        $stmt->bindParam(':rating', $newRating);
        $stmt->bindParam(':review', $newReview);
        return $stmt->execute();
    }

    /**
     * Delete a review
     *
     * @param int $reviewId
     */
    public function deleteReview($reviewId)
    {
        $query = "DELETE FROM reviews WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $reviewId);
        return $stmt->execute();
    }
}


$db = new PDO('mysql:host=localhost;dbname=your_database_name', 'username', 'password');
$review = new Review($db);

// Create a new review
$productId = 1;
$userId = 2;
$rating = 5.0;
$reviewText = "This product is amazing!";
$review->createReview($productId, $userId, $rating, $reviewText);

// Get all reviews for a product
$productReviews = $review->getReviewsForProduct(1);
print_r($productReviews);

// Update a review
$newRating = 4.5;
$newReviewText = "I've updated my review!";
$review->updateReview(1, $newRating, $newReviewText);

// Delete a review
$review->deleteReview(2);


<?php

// Review class to store and retrieve reviews
class Review {
  private $id;
  private $title;
  private $content;
  private $rating;
  private $date;

  public function __construct($id, $title, $content, $rating, $date) {
    $this->id = $id;
    $this->title = $title;
    $this->content = $content;
    $this->rating = $rating;
    $this->date = $date;
  }

  public function getId() { return $this->id; }
  public function getTitle() { return $this->title; }
  public function getContent() { return $this->content; }
  public function getRating() { return $this->rating; }
  public function getDate() { return $this->date; }
}

// ReviewRepository class to manage reviews
class ReviewRepository {
  private $reviews;

  public function __construct() {
    $this->reviews = array();
  }

  public function addReview(Review $review) {
    $this->reviews[] = $review;
  }

  public function getReviews() { return $this->reviews; }
}

// User review function
function createUserReview($title, $content, $rating, $date = null) {
  if (empty($title) || empty($content)) {
    throw new Exception("Both title and content are required");
  }

  if (!is_numeric($rating)) {
    throw new Exception("Rating must be a number");
  }

  if ($rating < 1 || $rating > 5) {
    throw new Exception("Rating must be between 1 and 5");
  }

  if (empty($date)) {
    $date = date('Y-m-d H:i:s');
  }

  $review = new Review(null, $title, $content, $rating, $date);
  $repository = new ReviewRepository();
  $repository->addReview($review);

  return $review;
}

// Example usage
$review = createUserReview("Great product!", "I love this product!", 5);
echo "Title: {$review->getTitle()}
";
echo "Content: {$review->getContent()}
";
echo "Rating: {$review->getRating()}
";
echo "Date: {$review->getDate()}
";

?>


class Review {
  private $db;

  public function __construct($db) {
    $this->db = $db;
  }

  public function displayReviews() {
    $query = "SELECT r.id, u.name, p.title, r.rating, r.review_text FROM reviews r JOIN users u ON r.user_id = u.id JOIN products p ON r.product_id = p.id";
    return $this->db->fetchAll($query);
  }

  public function addReview($data) {
    $query = "INSERT INTO reviews (user_id, product_id, rating, review_text) VALUES (:user_id, :product_id, :rating, :review_text)";
    try {
      $stmt = $this->db->prepare($query);
      $stmt->execute(array(
        ":user_id" => $data['user_id'],
        ":product_id" => $data['product_id'],
        ":rating" => $data['rating'],
        ":review_text" => $data['review_text']
      ));
      return true;
    } catch (PDOException $e) {
      echo "Error adding review: " . $e->getMessage();
      return false;
    }
  }

  public function deleteReview($id) {
    $query = "DELETE FROM reviews WHERE id = :id";
    try {
      $stmt = $this->db->prepare($query);
      $stmt->execute(array(":id" => $id));
      return true;
    } catch (PDOException $e) {
      echo "Error deleting review: " . $e->getMessage();
      return false;
    }
  }

  public function editReview($data) {
    $query = "UPDATE reviews SET rating = :rating, review_text = :review_text WHERE id = :id";
    try {
      $stmt = $this->db->prepare($query);
      $stmt->execute(array(
        ":rating" => $data['rating'],
        ":review_text" => $data['review_text'],
        ":id" => $data['id']
      ));
      return true;
    } catch (PDOException $e) {
      echo "Error editing review: " . $e->getMessage();
      return false;
    }
  }
}


class ReviewController {
  private $review;

  public function __construct($db) {
    $this->review = new Review($db);
  }

  public function displayReviews() {
    return $this->review->displayReviews();
  }

  public function addReview() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = $_POST;
      if ($this->review->addReview($data)) {
        echo "Review added successfully!";
      } else {
        echo "Error adding review.";
      }
    }
  }

  public function deleteReview() {
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
      $id = $_GET['id'];
      if ($this->review->deleteReview($id)) {
        echo "Review deleted successfully!";
      } else {
        echo "Error deleting review.";
      }
    }
  }

  public function editReview() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
      $data = $_POST;
      if ($this->review->editReview($data)) {
        echo "Review edited successfully!";
      } else {
        echo "Error editing review.";
      }
    }
  }
}


class ReviewModel {
  private $db;

  public function __construct() {
    $this->db = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
  }

  public function displayReviews() {
    $query = "SELECT r.id, u.name, p.title, r.rating, r.review_text FROM reviews r JOIN users u ON r.user_id = u.id JOIN products p ON r.product_id = p.id";
    return $this->db->fetchAll($query);
  }

  public function addReview($data) {
    // Same code as above
  }

  public function deleteReview($id) {
    // Same code as above
  }

  public function editReview($data) {
    // Same code as above
  }
}


<?php
require_once 'ReviewController.php';
$review = new ReviewController(new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password'));

if (isset($_GET['action']) && $_GET['action'] == 'displayReviews') {
  $reviews = $review->displayReviews();
  echo "<h1>Reviews</h1>";
  echo "<table>";
  foreach ($reviews as $review) {
    echo "<tr>";
    echo "<td>$review[0]</td>";
    echo "<td>$review[1] - $review[2]</td>";
    echo "<td>$review[3]/5 stars</td>";
    echo "<td>$review[4]</td>";
    echo "</tr>";
  }
  echo "</table>";
} elseif (isset($_POST['action']) && $_POST['action'] == 'addReview') {
  $review->addReview();
} elseif (isset($_GET['action']) && $_GET['action'] == 'deleteReview') {
  $review->deleteReview();
} elseif (isset($_POST['action']) && $_POST['action'] == 'editReview') {
  $review->editReview();
}
?>


class Review {
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=yourdatabase', 'username', 'password');
    }

    /**
     * Get all reviews for a product
     *
     * @param int $productId ID of the product to get reviews for
     * @return array Reviews for the given product
     */
    public function getAllReviewsForProduct($productId) {
        $stmt = $this->db->prepare('SELECT * FROM reviews WHERE product_id = :product_id');
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a review by its ID
     *
     * @param int $reviewId ID of the review to get
     * @return array Review with the given ID, or false if not found
     */
    public function getReviewById($reviewId) {
        $stmt = $this->db->prepare('SELECT * FROM reviews WHERE id = :id');
        $stmt->bindParam(':id', $reviewId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }

    /**
     * Create a new review
     *
     * @param array $data Review data (product_id, rating, review)
     * @return int ID of the newly created review, or 0 on failure
     */
    public function createReview($data) {
        try {
            $stmt = $this->db->prepare('INSERT INTO reviews SET product_id = :product_id, rating = :rating, review = :review');
            $stmt->bindParam(':product_id', $data['product_id']);
            $stmt->bindParam(':rating', $data['rating']);
            $stmt->bindParam(':review', $data['review']);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            echo "Error creating review: " . $e->getMessage();
            return 0;
        }
    }

    /**
     * Update an existing review
     *
     * @param array $data Review data (id, product_id, rating, review)
     * @return int ID of the updated review, or 0 on failure
     */
    public function updateReview($data) {
        try {
            $stmt = $this->db->prepare('UPDATE reviews SET product_id = :product_id, rating = :rating, review = :review WHERE id = :id');
            $stmt->bindParam(':id', $data['id']);
            $stmt->bindParam(':product_id', $data['product_id']);
            $stmt->bindParam(':rating', $data['rating']);
            $stmt->bindParam(':review', $data['review']);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            echo "Error updating review: " . $e->getMessage();
            return 0;
        }
    }

    /**
     * Delete a review
     *
     * @param int $reviewId ID of the review to delete
     * @return bool Whether the deletion was successful
     */
    public function deleteReview($reviewId) {
        try {
            $stmt = $this->db->prepare('DELETE FROM reviews WHERE id = :id');
            $stmt->bindParam(':id', $reviewId);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error deleting review: " . $e->getMessage();
            return false;
        }
    }
}


class ReviewController extends Controller {
    public function index() {
        // Get all reviews for a product
        $reviews = Review::getAllReviewsForProduct(1);
        return view('review.index', ['reviews' => $reviews]);
    }

    public function create($productId) {
        // Create a new review
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            $reviewId = Review::createReview([
                'product_id' => $productId,
                'rating' => (int)$data['rating'],
                'review' => $data['review']
            ]);
            return redirect('reviews')->with('success', 'Review created successfully!');
        }
    }

    public function update($id) {
        // Update an existing review
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            Review::updateReview([
                'id' => (int)$id,
                'product_id' => 1, // Set product ID to whatever value is desired
                'rating' => (int)$data['rating'],
                'review' => $data['review']
            ]);
            return redirect('reviews')->with('success', 'Review updated successfully!');
        }
    }

    public function delete($id) {
        // Delete a review
        Review::deleteReview((int)$id);
        return redirect('reviews')->with('success', 'Review deleted successfully!');
    }
}


Route::get('/reviews', 'ReviewController@index');
Route::post('/reviews/create/{productId}', 'ReviewController@create');
Route::get('/reviews/update/{id}', 'ReviewController@update');
Route::post('/reviews/update/{id}', 'ReviewController@update');
Route::get('/reviews/delete/{id}', 'ReviewController@delete');


// db.php: database connection settings
$dbHost = 'localhost';
$dbUsername = 'username';
$dbPassword = 'password';
$dbName = 'database_name';

// Connect to the database
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function addReview($product_id, $user_id, $rating, $comment) {
    global $conn;

    $sql = "INSERT INTO reviews (product_id, user_id, rating, comment)
            VALUES ('$product_id', '$user_id', '$rating', '$comment')";

    if ($conn->query($sql) === TRUE) {
        echo "New review created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function getReviews($product_id) {
    global $conn;

    $sql = "SELECT * FROM reviews WHERE product_id = '$product_id'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "Rating: " . $row["rating"]. "<br>Comment: " . $row["comment"]. "<br><br>";
        }
    } else {
        echo "No reviews found";
    }

}

// Example usage:
addReview(1, 1, 5, 'This product is amazing!');
getReviews(1);


<?php

function getRatingStars($rating) {
    $stars = '';
    for ($i = 0; $i < $rating; $i++) {
        $stars .= '<span class="fa fa-star checked"></span>';
    }
    return $stars;
}

// Example usage:
$rating = 4;
echo getRatingStars($rating);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>

<?php include 'review.php'; ?>

<div class="container">
    <h2>Reviews for Product 1</h2>

    <?php getReviews(1); ?>
</div>

<form action="" method="post">
    <input type="hidden" name="product_id" value="1">
    <label for="rating">Rating:</label>
    <select id="rating" name="rating">
        <option value="1">1 star</option>
        <option value="2">2 stars</option>
        <option value="3">3 stars</option>
        <option value="4">4 stars</option>
        <option value="5">5 stars</option>
    </select><br>

    <label for="comment">Comment:</label>
    <textarea id="comment" name="comment"></textarea><br>

    <input type="submit" value="Submit Review">
</form>

</body>
</html>


// User class
class User {
  private $id;
  private $username;

  public function __construct($id, $username) {
    $this->id = $id;
    $this->username = $username;
  }

  // Getters and setters
  public function getId() {
    return $this->id;
  }

  public function getUsername() {
    return $this->username;
  }
}

// Product class
class Product {
  private $id;
  private $name;

  public function __construct($id, $name) {
    $this->id = $id;
    $this->name = $name;
  }

  // Getters and setters
  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }
}

// Review class
class Review {
  private $id;
  private $user_id;
  private $product_id;
  private $rating;
  private $review_text;

  public function __construct($id, $user_id, $product_id, $rating, $review_text) {
    $this->id = $id;
    $this->user_id = $user_id;
    $this->product_id = $product_id;
    $this->rating = $rating;
    $this->review_text = $review_text;
  }

  // Getters and setters
  public function getId() {
    return $this->id;
  }

  public function getUserId() {
    return $this->user_id;
  }

  public function getProductId() {
    return $this->product_id;
  }

  public function getRating() {
    return $this->rating;
  }

  public function getReviewText() {
    return $this->review_text;
  }
}


// Reviews class
class Reviews {
  private $db;

  public function __construct($db) {
    $this->db = $db;
  }

  // Add review
  public function addReview(User $user, Product $product, $rating, $review_text) {
    try {
      $stmt = $this->db->prepare("INSERT INTO reviews (user_id, product_id, rating, review_text) VALUES (?, ?, ?, ?)");
      $stmt->bindParam(1, $user->getId());
      $stmt->bindParam(2, $product->getId());
      $stmt->bindParam(3, $rating);
      $stmt->bindParam(4, $review_text);
      return $stmt->execute();
    } catch (PDOException $e) {
      echo "Error adding review: " . $e->getMessage();
      return false;
    }
  }

  // Get all reviews
  public function getAllReviews() {
    try {
      $stmt = $this->db->query("SELECT * FROM reviews");
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo "Error fetching reviews: " . $e->getMessage();
      return array();
    }
  }

  // Get review by id
  public function getReview($id) {
    try {
      $stmt = $this->db->prepare("SELECT * FROM reviews WHERE id = ?");
      $stmt->bindParam(1, $id);
      return $stmt->execute() ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
    } catch (PDOException $e) {
      echo "Error fetching review: " . $e->getMessage();
      return null;
    }
  }

  // Edit review
  public function editReview(Review $review, $new_rating, $new_review_text) {
    try {
      $stmt = $this->db->prepare("UPDATE reviews SET rating = ?, review_text = ? WHERE id = ?");
      $stmt->bindParam(1, $new_rating);
      $stmt->bindParam(2, $new_review_text);
      $stmt->bindParam(3, $review->getId());
      return $stmt->execute();
    } catch (PDOException $e) {
      echo "Error editing review: " . $e->getMessage();
      return false;
    }
  }

  // Delete review
  public function deleteReview($id) {
    try {
      $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = ?");
      $stmt->bindParam(1, $id);
      return $stmt->execute();
    } catch (PDOException $e) {
      echo "Error deleting review: " . $e->getMessage();
      return false;
    }
  }
}


// Initialize database connection
$db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');

// Create reviews instance
$reviews = new Reviews($db);

// Get user and product instances
$user = new User(1, 'johnDoe');
$product = new Product(1, 'Product A');

// Add review
if ($reviews->addReview($user, $product, 4, 'Great product!')) {
  echo "Review added successfully!";
} else {
  echo "Error adding review.";
}

// Get all reviews
$allReviews = $reviews->getAllReviews();
print_r($allReviews);

// Get review by id
$review = $reviews->getReview(1);
echo $review['rating'] . ' - ' . $review['review_text'];

// Edit review
if ($reviews->editReview($review, 5, 'Excellent product!')) {
  echo "Review edited successfully!";
} else {
  echo "Error editing review.";
}

// Delete review
if ($reviews->deleteReview(1)) {
  echo "Review deleted successfully!";
} else {
  echo "Error deleting review.";
}


function createUserReview($data) {
  try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
      throw new Exception('User not logged in');
    }

    // Insert review into database
    $review = array(
      'product_id' => $data['product_id'],
      'rating' => $data['rating'],
      'comment' => $data['comment']
    );
    db_insert('reviews', $review);

    // Return success message
    return array('message' => 'Review created successfully');
  } catch (Exception $e) {
    // Handle errors and return error message
    return array('error' => $e->getMessage());
  }
}

function getReviews($product_id = null, $page = 1, $limit = 10) {
  try {
    // Get reviews from database
    if ($product_id) {
      $reviews = db_select('reviews', 'id', '*', "product_id = '$product_id'", $limit, $page);
    } else {
      $reviews = db_select('reviews', '*', '*', '', $limit, $page);
    }

    // Return reviews as JSON
    return json_encode($reviews);
  } catch (Exception $e) {
    // Handle errors and return error message
    return array('error' => $e->getMessage());
  }
}

function updateReview($review_id, $data) {
  try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
      throw new Exception('User not logged in');
    }

    // Update review in database
    db_update('reviews', 'rating = ?', array($data['rating']), "id = '$review_id'");

    // Return success message
    return array('message' => 'Review updated successfully');
  } catch (Exception $e) {
    // Handle errors and return error message
    return array('error' => $e->getMessage());
  }
}

function deleteReview($review_id) {
  try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
      throw new Exception('User not logged in');
    }

    // Delete review from database
    db_delete('reviews', "id = '$review_id'");

    // Return success message
    return array('message' => 'Review deleted successfully');
  } catch (Exception $e) {
    // Handle errors and return error message
    return array('error' => $e->getMessage());
  }
}


// Create a new review
$data = array(
  'product_id' => 123,
  'rating' => 5,
  'comment' => 'Great product!'
);
$response = createUserReview($data);
echo json_encode($response);

// Get all reviews for a specific product
$product_id = 123;
$page = 1;
$limit = 10;
$response = getReviews($product_id, $page, $limit);
echo json_encode($response);

// Update an existing review
$review_id = 456;
$data = array(
  'rating' => 4,
  'comment' => 'Good product!'
);
$response = updateReview($review_id, $data);
echo json_encode($response);

// Delete a review
$review_id = 789;
$response = deleteReview($review_id);
echo json_encode($response);


// Review.php

class Review {
    private $id;
    private $reviewer_name;
    private $rating;
    private $comment;

    public function __construct($id, $reviewer_name, $rating, $comment) {
        $this->id = $id;
        $this->reviewer_name = $reviewer_name;
        $this->rating = $rating;
        $this->comment = $comment;
    }

    public function getId() {
        return $this->id;
    }

    public function getReviewerName() {
        return $this->reviewer_name;
    }

    public function getRating() {
        return $this->rating;
    }

    public function getComment() {
        return $this->comment;
    }
}


// ReviewController.php

class ReviewController {
    private $reviews = [];

    public function addReview($reviewer_name, $rating, $comment) {
        $review_id = count($this->reviews) + 1;
        $review = new Review($review_id, $reviewer_name, $rating, $comment);
        array_push($this->reviews, $review);
        return $review;
    }

    public function getReviews() {
        return $this->reviews;
    }
}


// ReviewService.php

class ReviewService {
    private $reviewController;

    public function __construct(ReviewController $reviewController) {
        $this->reviewController = $reviewController;
    }

    public function addReview($reviewer_name, $rating, $comment) {
        return $this->reviewController->addReview($reviewer_name, $rating, $comment);
    }

    public function getReviews() {
        return $this->reviewController->getReviews();
    }
}


// review.php

function display_reviews() {
    $reviewService = new ReviewService(new ReviewController());
    $reviews = $reviewService->getReviews();

    foreach ($reviews as $review) {
        echo "Reviewer: {$review->getReviewerName()}<br>";
        echo "Rating: {$review->getRating()} / 5<br>";
        echo "Comment: {$review->getComment()}<br><br>";
    }
}

function add_review() {
    if (isset($_POST['submit'])) {
        $reviewer_name = $_POST['reviewer_name'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];

        $reviewService = new ReviewService(new ReviewController());
        $newReview = $reviewService->addReview($reviewer_name, $rating, $comment);

        echo "Review added successfully!";
    } else {
        // Display review form
        ?>
        <form action="" method="post">
            <label for="reviewer_name">Reviewer Name:</label>
            <input type="text" id="reviewer_name" name="reviewer_name"><br><br>

            <label for="rating">Rating (1-5):</label>
            <input type="number" id="rating" name="rating" min="1" max="5"><br><br>

            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment"></textarea><br><br>

            <input type="submit" name="submit" value="Add Review">
        </form>
    <?php
    }
}


<?php

// Database connection settings
$host = 'localhost';
$dbname = 'reviews_db';
$username = 'root';
$password = '';

try {
  // Create database connection
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

// Function to create a review
function create_review($user_id, $product_id, $rating, $comment) {
  global $conn;
  
  // Insert review into database
  $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (:user_id, :product_id, :rating, :comment)");
  $stmt->bindParam(":user_id", $user_id);
  $stmt->bindParam(":product_id", $product_id);
  $stmt->bindParam(":rating", $rating);
  $stmt->bindParam(":comment", $comment);
  
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

// Function to read reviews
function get_reviews() {
  global $conn;

  // Retrieve all reviews from database
  $stmt = $conn->query("SELECT * FROM reviews");
  $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
  return $reviews;
}

// Function to update a review
function update_review($id, $new_rating, $new_comment) {
  global $conn;

  // Update review in database
  $stmt = $conn->prepare("UPDATE reviews SET rating=:rating, comment=:comment WHERE id=:id");
  $stmt->bindParam(":rating", $new_rating);
  $stmt->bindParam(":comment", $new_comment);
  $stmt->bindParam(":id", $id);
  
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

// Function to delete a review
function delete_review($id) {
  global $conn;

  // Delete review from database
  $stmt = $conn->prepare("DELETE FROM reviews WHERE id=:id");
  $stmt->bindParam(":id", $id);
  
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

?>


// Create a new review
$user_id = 1;
$product_id = 1;
$rating = 5;
$comment = "Great product!";

create_review($user_id, $product_id, $rating, $comment);

// Read all reviews
$reviews = get_reviews();

print_r($reviews);

// Update a review
$id = 1;
$new_rating = 4;
$new_comment = "Good product!";

update_review($id, $new_rating, $new_comment);

// Delete a review
delete_review(2);


// db.php (database connection file)

<?php
$dsn = 'mysql:host=localhost;dbname=reviews';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO($dsn, $username, $password);
} catch(PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

function addUserReview($userId, $productId, $rating, $review) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, review) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $productId, $rating, $review]);
        
        return true;
    } catch(PDOException $e) {
        echo "Error adding review: " . $e->getMessage();
        return false;
    }
}

function getUserReviews($userId) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT * FROM reviews WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Error retrieving reviews: " . $e->getMessage();
        return array();
    }
}

function addProductReview($productId, $rating, $review) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("INSERT INTO reviews (product_id, rating, review) VALUES (?, ?, ?)");
        $stmt->execute([$productId, $rating, $review]);
        
        return true;
    } catch(PDOException $e) {
        echo "Error adding product review: " . $e->getMessage();
        return false;
    }
}
?>


// index.php

<?php
require_once 'db.php';

if (isset($_POST['add_review'])) {
    $userId = $_POST['user_id'];
    $productId = $_POST['product_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    if (addUserReview($userId, $productId, $rating, $review)) {
        echo "Review added successfully!";
    } else {
        echo "Error adding review.";
    }
} elseif (isset($_POST['get_reviews'])) {
    $userId = $_POST['user_id'];
    
    $reviews = getUserReviews($userId);
    foreach ($reviews as $review) {
        echo "Rating: {$review['rating']}, Review: {$review['review']}<br>";
    }
}

?>

<form action="" method="post">
    <label for="user_id">User ID:</label>
    <input type="text" id="user_id" name="user_id"><br><br>
    
    <label for="product_id">Product ID:</label>
    <input type="text" id="product_id" name="product_id"><br><br>
    
    <label for="rating">Rating:</label>
    <input type="number" id="rating" name="rating"><br><br>
    
    <label for="review">Review:</label>
    <textarea id="review" name="review"></textarea><br><br>
    
    <input type="submit" name="add_review" value="Add Review">
</form>

<form action="" method="post">
    <label for="user_id">User ID:</label>
    <input type="text" id="user_id" name="user_id"><br><br>
    
    <input type="submit" name="get_reviews" value="Get Reviews">
</form>


// config.php (database connection settings)
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

$conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
?>

// Review.php (Review class)
<?php
class Review {
  private $id;
  private $user_id;
  private $product_id;
  private $rating;
  private $review_text;

  public function __construct($id = null) {
    if ($id) {
      $stmt = $conn->prepare("SELECT * FROM reviews WHERE id = :id");
      $stmt->bindParam(":id", $id);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $this->id = $row['id'];
      $this->user_id = $row['user_id'];
      $this->product_id = $row['product_id'];
      $this->rating = $row['rating'];
      $this->review_text = $row['review_text'];
    }
  }

  public function addReview($product_id, $rating, $review_text) {
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, review_text) VALUES (:user_id, :product_id, :rating, :review_text)");
    $stmt->bindParam(":user_id", $_SESSION['user']['id']);
    $stmt->bindParam(":product_id", $product_id);
    $stmt->bindParam(":rating", $rating);
    $stmt->bindParam(":review_text", $review_text);
    return $stmt->execute();
  }

  public function updateReview($id, $rating, $review_text) {
    $stmt = $conn->prepare("UPDATE reviews SET rating = :rating, review_text = :review_text WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":rating", $rating);
    $stmt->bindParam(":review_text", $review_text);
    return $stmt->execute();
  }

  public function deleteReview($id) {
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = :id");
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
  }
}

// ReviewController.php (Review controller class)
<?php
class ReviewController {
  private $review;

  public function __construct() {
    $this->review = new Review();
  }

  public function addReview($product_id, $rating, $review_text) {
    return $this->review->addReview($product_id, $rating, $review_text);
  }

  public function updateReview($id, $rating, $review_text) {
    return $this->review->updateReview($id, $rating, $review_text);
  }

  public function deleteReview($id) {
    return $this->review->deleteReview($id);
  }
}

// ReviewView.php (Review view class)
<?php
class ReviewView {
  private $reviewController;

  public function __construct() {
    $this->reviewController = new ReviewController();
  }

  public function displayReviews() {
    // Retrieve all reviews from database
    $stmt = $conn->prepare("SELECT * FROM reviews");
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display each review in a table
    echo "<table>";
    foreach ($reviews as $review) {
      echo "<tr>";
      echo "<td>" . $review['user_id'] . "</td>";
      echo "<td>" . $review['product_id'] . "</td>";
      echo "<td>" . $review['rating'] . "</td>";
      echo "<td>" . $review['review_text'] . "</td>";
      echo "<td><a href='#' onclick='updateReview($review[id])'>Update</a> | <a href='#' onclick='deleteReview($review[id])'>Delete</a></td>";
      echo "</tr>";
    }
    echo "</table>";
  }

  public function displayAddReviewForm() {
    // Display form to add new review
    echo "<form action '#' method='post'>";
    echo "<label for='product_id'>Product ID:</label>";
    echo "<input type='text' id='product_id' name='product_id'>";
    echo "<br>";
    echo "<label for='rating'>Rating:</label>";
    echo "<select id='rating' name='rating'>";
    echo "<option value='1'>1</option>";
    echo "<option value='2'>2</option>";
    echo "<option value='3'>3</option>";
    echo "<option value='4'>4</option>";
    echo "<option value='5'>5</option>";
    echo "</select>";
    echo "<br>";
    echo "<label for='review_text'>Review:</label>";
    echo "<textarea id='review_text' name='review_text'></textarea>";
    echo "<br>";
    echo "<input type='submit' value='Add Review'>";
    echo "</form>";
  }

  public function addReview($product_id, $rating, $review_text) {
    // Add new review using controller
    return $this->reviewController->addReview($product_id, $rating, $review_text);
  }
}
?>


require_once 'Review.php';
require_once 'ReviewController.php';
require_once 'ReviewView.php';

// Initialize Review view
$view = new ReviewView();

// Display add review form
$view->displayAddReviewForm();

// Add new review (example)
$product_id = 1;
$rating = 4;
$review_text = "Great product!";
if ($view->addReview($product_id, $rating, $review_text)) {
  echo "Review added successfully!";
} else {
  echo "Error adding review.";
}

// Display reviews
$view->displayReviews();


<?php

// Database configuration
$host = 'localhost';
$dbname = 'reviews_database';
$username = 'your_username';
$password = 'your_password';

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
}

?>


<?php

class Review {
    private $pdo;

    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Add a new review
    function add_review($title, $content, $rating, $user_id) {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO reviews (title, content, rating, user_id) VALUES (?, ?, ?, ?)');
            $stmt->execute([$title, $content, $rating, $user_id]);
            return true;
        } catch (PDOException $e) {
            echo "Error adding review: " . $e->getMessage();
            return false;
        }
    }

    // Get all reviews
    function get_reviews() {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM reviews');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error retrieving reviews: " . $e->getMessage();
            return false;
        }
    }

    // Get a single review by ID
    function get_review($id) {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM reviews WHERE id = ?');
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error retrieving review: " . $e->getMessage();
            return false;
        }
    }

    // Update a review
    function update_review($id, $title, $content, $rating) {
        try {
            $stmt = $this->pdo->prepare('UPDATE reviews SET title = ?, content = ?, rating = ? WHERE id = ?');
            $stmt->execute([$title, $content, $rating, $id]);
            return true;
        } catch (PDOException $e) {
            echo "Error updating review: " . $e->getMessage();
            return false;
        }
    }

    // Delete a review
    function delete_review($id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM reviews WHERE id = ?');
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            echo "Error deleting review: " . $e->getMessage();
            return false;
        }
    }

}

?>


<?php

require_once 'config.php';
require_once 'review.php';

$pdo = new PDO('mysql:host=localhost;dbname=reviews_database', 'your_username', 'your_password');

$review = new Review($pdo);

// Add a new review
$title = 'Example Review';
$content = 'This is an example review.';
$rating = 5;
$user_id = 1;

if ($review->add_review($title, $content, $rating, $user_id)) {
    echo "Review added successfully.";
} else {
    echo "Error adding review.";
}

// Get all reviews
$reviews = $review->get_reviews();
foreach ($reviews as $review) {
    echo $review['title'] . ' - ' . $review['content'];
}

// Get a single review by ID
$review_id = 1;
$single_review = $review->get_review($review_id);
echo $single_review['title'];

// Update a review
$new_title = 'Updated Review';
$new_content = 'This is an updated review.';
$new_rating = 4;

if ($review->update_review($review_id, $new_title, $new_content, $new_rating)) {
    echo "Review updated successfully.";
} else {
    echo "Error updating review.";
}

// Delete a review
if ($review->delete_review($review_id)) {
    echo "Review deleted successfully.";
} else {
    echo "Error deleting review.";
}

?>


// review.php

class Review {
  private $db;

  public function __construct() {
    // Connect to database
    $this->db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
  }

  /**
   * Add a new review
   *
   * @param int $product_id Product ID
   * @param int $user_id User ID
   * @param int $rating Rating (1-5)
   * @param string $comment Comment text
   */
  public function addReview($product_id, $user_id, $rating, $comment) {
    try {
      // Insert review into database
      $stmt = $this->db->prepare('INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (:product_id, :user_id, :rating, :comment)');
      $stmt->execute(array(
        ':product_id' => $product_id,
        ':user_id' => $user_id,
        ':rating' => $rating,
        ':comment' => $comment
      ));
    } catch (PDOException $e) {
      echo 'Error adding review: ' . $e->getMessage();
    }
  }

  /**
   * Get all reviews for a product
   *
   * @param int $product_id Product ID
   */
  public function getReviews($product_id) {
    try {
      // Retrieve reviews from database
      $stmt = $this->db->prepare('SELECT * FROM reviews WHERE product_id = :product_id');
      $stmt->execute(array(':product_id' => $product_id));
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo 'Error getting reviews: ' . $e->getMessage();
      return array();
    }
  }

  /**
   * Get review by ID
   *
   * @param int $review_id Review ID
   */
  public function getReview($review_id) {
    try {
      // Retrieve review from database
      $stmt = $this->db->prepare('SELECT * FROM reviews WHERE id = :review_id');
      $stmt->execute(array(':review_id' => $review_id));
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo 'Error getting review: ' . $e->getMessage();
      return array();
    }
  }

  /**
   * Delete a review
   *
   * @param int $review_id Review ID
   */
  public function deleteReview($review_id) {
    try {
      // Delete review from database
      $stmt = $this->db->prepare('DELETE FROM reviews WHERE id = :review_id');
      $stmt->execute(array(':review_id' => $review_id));
    } catch (PDOException $e) {
      echo 'Error deleting review: ' . $e->getMessage();
    }
  }
}


// Create an instance of the Review class
$review = new Review();

// Add a new review
$review->addReview(1, 1, 4, "Great product!");

// Get all reviews for a product
$reviews = $review->getReviews(1);
echo '<pre>';
print_r($reviews);
echo '</pre>';

// Delete a review
$review->deleteReview(1);

// Get review by ID
$review = $review->getReview(2);
echo '<pre>';
print_r($review);
echo '</pre>';


class Review {
  private $db;

  public function __construct() {
    $this->db = new PDO('mysql:host=localhost;dbname=mydatabase', 'myuser', 'mypassword');
  }

  public function createReview($product_id, $user_id, $rating, $review) {
    try {
      $stmt = $this->db->prepare("INSERT INTO reviews (product_id, user_id, rating, review) VALUES (:product_id, :user_id, :rating, :review)");
      $stmt->execute([
        ':product_id' => $product_id,
        ':user_id' => $user_id,
        ':rating' => $rating,
        ':review' => $review
      ]);
      return true;
    } catch (PDOException $e) {
      echo "Error creating review: " . $e->getMessage();
      return false;
    }
  }

  public function getReviews($product_id = null, $limit = 10, $offset = 0) {
    try {
      if ($product_id) {
        $stmt = $this->db->prepare("SELECT * FROM reviews WHERE product_id = :product_id ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->execute([
          ':product_id' => $product_id,
          ':limit' => $limit,
          ':offset' => $offset
        ]);
      } else {
        $stmt = $this->db->query("SELECT * FROM reviews ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->execute([
          ':limit' => $limit,
          ':offset' => $offset
        ]);
      }
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      echo "Error fetching reviews: " . $e->getMessage();
      return array();
    }
  }

  public function updateReview($review_id, $rating, $review) {
    try {
      $stmt = $this->db->prepare("UPDATE reviews SET rating = :rating, review = :review WHERE id = :review_id");
      $stmt->execute([
        ':review_id' => $review_id,
        ':rating' => $rating,
        ':review' => $review
      ]);
      return true;
    } catch (PDOException $e) {
      echo "Error updating review: " . $e->getMessage();
      return false;
    }
  }

  public function deleteReview($review_id) {
    try {
      $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = :review_id");
      $stmt->execute([
        ':review_id' => $review_id
      ]);
      return true;
    } catch (PDOException $e) {
      echo "Error deleting review: " . $e->getMessage();
      return false;
    }
  }
}


$review = new Review();

// Create a new review
$product_id = 123;
$user_id = 456;
$rating = 5;
$review_text = "This product is amazing!";
$result = $review->createReview($product_id, $user_id, $rating, $review_text);
echo $result ? 'Review created successfully!' : 'Error creating review.';

// Fetch reviews for a specific product
$product_id = 123;
$limit = 10;
$offset = 0;
$reviews = $review->getReviews($product_id, $limit, $offset);
foreach ($reviews as $review) {
  echo "Review ID: {$review['id']}
";
  echo "Rating: {$review['rating']}
";
  echo "Review Text: {$review['review']}

";
}

// Update a review
$review_id = 123;
$new_rating = 4;
$new_review_text = "This product is okay...";
$result = $review->updateReview($review_id, $new_rating, $new_review_text);
echo $result ? 'Review updated successfully!' : 'Error updating review.';

// Delete a review
$review_id = 123;
$result = $review->deleteReview($review_id);
echo $result ? 'Review deleted successfully!' : 'Error deleting review.';


// database.php - Database connection settings
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// reviews.php - User review functions
<?php

function insert_review($user_id, $product_id, $review_text, $rating) {
    require_once 'database.php';
    $conn = db_connect();
    $sql = "INSERT INTO reviews (user_id, product_id, review_text, rating)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $user_id, $product_id, $review_text, $rating);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function get_all_reviews() {
    require_once 'database.php';
    $conn = db_connect();
    $sql = "SELECT * FROM reviews";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo $row['review_text'] . ' - Rating: ' . $row['rating'] . '<br>';
    }
}

function update_review($id, $new_rating) {
    require_once 'database.php';
    $conn = db_connect();
    $sql = "UPDATE reviews SET rating = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $new_rating, $id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


// Insert a new review for user with id 1 on product with id 2
insert_review(1, 2, 'This is a good product.', 4);

// Get all reviews from the database and print them out
get_all_reviews();

// Update a specific review's rating to 5
update_review(3, 5);


class Review {
  private $db;

  public function __construct() {
    // Connect to the database using PDO (PHP Data Objects)
    $this->db = new PDO('mysql:host=localhost;dbname=reviews', 'username', 'password');
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  public function createReview($userId, $productId, $reviewText, $rating) {
    // Prepare a SQL query to insert the review
    $stmt = $this->db->prepare('INSERT INTO reviews (user_id, product_id, review_text, rating) VALUES (:user_id, :product_id, :review_text, :rating)');
    
    try {
      // Bind parameters and execute the query
      $stmt->bindParam(':user_id', $userId);
      $stmt->bindParam(':product_id', $productId);
      $stmt->bindParam(':review_text', $reviewText);
      $stmt->bindParam(':rating', $rating);
      return $stmt->execute();
    } catch (PDOException $e) {
      // Handle any errors that occur during execution
      echo 'Error creating review: ' . $e->getMessage();
      return false;
    }
  }

  public function listReviews() {
    // Prepare a SQL query to select all reviews
    $stmt = $this->db->prepare('SELECT * FROM reviews');
    
    try {
      // Execute the query and return the results as an array of Review objects
      $stmt->execute();
      $reviews = [];
      while ($row = $stmt->fetch()) {
        $review = new Review($row['id'], $row['user_id'], $row['product_id'], $row['review_text'], $row['rating']);
        $reviews[] = $review;
      }
      return $reviews;
    } catch (PDOException $e) {
      // Handle any errors that occur during execution
      echo 'Error listing reviews: ' . $e->getMessage();
      return [];
    }
  }

  public function displayReview($id) {
    // Prepare a SQL query to select the review with the given ID
    $stmt = $this->db->prepare('SELECT * FROM reviews WHERE id = :id');
    
    try {
      // Bind parameters and execute the query
      $stmt->bindParam(':id', $id);
      $stmt->execute();
      
      // Fetch the result as an array
      return $stmt->fetch();
    } catch (PDOException $e) {
      // Handle any errors that occur during execution
      echo 'Error displaying review: ' . $e->getMessage();
      return [];
    }
  }
}


// Create a new Review object
$review = new Review();

// Set up user credentials (for demonstration purposes)
$userCredentials = [
  'username' => 'john',
  'email' => 'john@example.com',
  'password' => 'password123'
];

// Register the user
// ...

// Create a new review for the registered user
$userId = // User ID obtained from registration or login process
$productID = 1; // Product ID of interest
$reviewText = 'This product is great!';
$rating = 5;
if ($review->createReview($userId, $productID, $reviewText, $rating)) {
  echo 'Review created successfully!';
} else {
  echo 'Error creating review.';
}

// List all reviews for the user
$userReviews = $review->listReviews();
echo '<h2>Reviews by User:</h2>';
foreach ($userReviews as $review) {
  echo '<p>ID: ' . $review['id'] . '</p>';
  echo '<p>User ID: ' . $review['user_id'] . '</p>';
  echo '<p>Product ID: ' . $review['product_id'] . '</p>';
  echo '<p>Review Text: ' . $review['review_text'] . '</p>';
  echo '<p>Rating: ' . $review['rating'] . '/5</p>';
}

// Display a specific review
$reviewID = 1; // ID of the review to display
$singleReview = $review->displayReview($reviewID);
if ($singleReview) {
  echo '<h2>Review:</h2>';
  echo '<p>ID: ' . $singleReview['id'] . '</p>';
  echo '<p>User ID: ' . $singleReview['user_id'] . '</p>';
  echo '<p>Product ID: ' . $singleReview['product_id'] . '</p>';
  echo '<p>Review Text: ' . $singleReview['review_text'] . '</p>';
  echo '<p>Rating: ' . $singleReview['rating'] . '/5</p>';
} else {
  echo 'Error displaying review.';
}


<?php

// Define the Review class
class Review {
  private $id;
  private $rating;
  private $comment;
  private $reviewed_at;

  public function __construct($data) {
    $this->id = isset($data['id']) ? $data['id'] : null;
    $this->rating = isset($data['rating']) ? $data['rating'] : null;
    $this->comment = isset($data['comment']) ? $data['comment'] : null;
    $this->reviewed_at = isset($data['reviewed_at']) ? $data['reviewed_at'] : null;
  }

  public function getId() {
    return $this->id;
  }

  public function getRating() {
    return $this->rating;
  }

  public function getComment() {
    return $this->comment;
  }

  public function getReviewedAt() {
    return $this->reviewed_at;
  }
}

// Define the ReviewManager class
class ReviewManager {
  private $reviews = array();

  public function addReview($data) {
    $review = new Review($data);
    $this->reviews[] = $review;
    return $review;
  }

  public function getReviews() {
    return $this->reviews;
  }

  public function getReviewById($id) {
    foreach ($this->reviews as $review) {
      if ($review->getId() == $id) {
        return $review;
      }
    }
    return null;
  }
}

// Create a new ReviewManager instance
$reviewManager = new ReviewManager();

// Example usage:
$data = array(
  'rating' => 5,
  'comment' => 'Great product!',
  'reviewed_at' => date('Y-m-d H:i:s')
);

$review = $reviewManager->addReview($data);
echo "Review ID: {$review->getId()}<br>";
echo "Rating: {$review->getRating()}<br>";
echo "Comment: {$review->getComment()}<br>";
echo "Reviewed at: {$review->getReviewedAt()}<br>";

$reviews = $reviewManager->getReviews();
foreach ($reviews as $review) {
  echo "Review ID: {$review->getId()} - Rating: {$review->getRating()} - Comment: {$review->getComment()}<br>";
}

// Get a review by ID
$reviewId = 1;
$review = $reviewManager->getReviewById($reviewId);
if ($review) {
  echo "Review found with ID:{$reviewId} - Rating: {$review->getRating()} - Comment: {$review->getComment()}";
} else {
  echo "No review found with ID:{$reviewId}";
}


<?php

// Database connection settings
$db_host = 'localhost';
$db_username = 'your_username';
$db_password = 'your_password';
$db_name = 'reviews_database';

// Create a database connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function store_review($review_id, $product_id, $user_id, $rating, $comment)
{
    // SQL query to insert review
    $sql = "INSERT INTO reviews (id, product_id, user_id, rating, comment) VALUES (?, ?, ?, ?, ?)";
    
    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi", $review_id, $product_id, $user_id, $rating, $comment);
    $result = $stmt->execute();
    
    if (!$result) {
        echo "Error storing review: " . $conn->error;
    }
}

?>


<?php

// Database connection settings (same as above)

function display_reviews($product_id)
{
    // SQL query to select reviews for a product
    $sql = "SELECT * FROM reviews WHERE product_id = ? ORDER BY id DESC";
    
    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result) {
        echo "Error displaying reviews: " . $conn->error;
    } else {
        // Fetch and display the reviews
        while ($review = $result->fetch_assoc()) {
            echo "Rating: " . $review['rating'] . "/5, Comment: " . $review['comment'] . "<br>";
        }
    }
}

?>


store_review(1, 12345, 67890, 4, 'Great product!');


display_reviews(12345);


// models/Review.php

class Review {
  private $id;
  private $userId;
  private $productName;
  private $rating;
  private $reviewText;

  public function __construct($id = null, $userId = null, $productName = null, $rating = null, $reviewText = null) {
    $this->id = $id;
    $this->userId = $userId;
    $this->productName = $productName;
    $this->rating = $rating;
    $this->reviewText = $reviewText;
  }

  public function getId() {
    return $this->id;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function getProductName() {
    return $this->productName;
  }

  public function getRating() {
    return $this->rating;
  }

  public function getReviewText() {
    return $this->reviewText;
  }

  public static function create($user_id, $product_name, $rating, $review_text) {
    // Assume a database connection is established
    $db = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');
    $stmt = $db->prepare("INSERT INTO reviews (user_id, product_name, rating, review_text) VALUES (:user_id, :product_name, :rating, :review_text)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_name', $product_name);
    $stmt->bindParam(':rating', $rating);
    $stmt->bindParam(':review_text', $review_text);
    $stmt->execute();
    return new self($db->lastInsertId(), $user_id, $product_name, $rating, $review_text);
  }

  public static function getAllReviews() {
    // Assume a database connection is established
    $db = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');
    $stmt = $db->prepare("SELECT * FROM reviews");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
  }
}


// controllers/UserReview.php

function user_review($product_name, $rating, $review_text) {
  // Authenticate the user (not shown in this example)
  if (!isset($_SESSION['user_id'])) {
    // Handle unauthorized access
  }

  try {
    $review = Review::create(
      $_SESSION['user_id'],
      $product_name,
      $rating,
      $review_text
    );
    echo "Review submitted successfully!";
  } catch (Exception $e) {
    echo "Error submitting review: " . $e->getMessage();
  }
}


// views/user-review.php

<form action="controllers/UserReview.php" method="post">
  <label for="product_name">Product Name:</label>
  <input type="text" id="product_name" name="product_name"><br><br>
  <label for="rating">Rating:</label>
  <select id="rating" name="rating">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
  </select><br><br>
  <label for="review_text">Review Text:</label>
  <textarea id="review_text" name="review_text"></textarea><br><br>
  <input type="submit" value="Submit Review">
</form>


<?php
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'your_database_name');

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


<?php include_once 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviews</title>
</head>
<body>

<h1>Reviews</h1>

<ul>
    <?php
    $query = "SELECT * FROM reviews ORDER BY id DESC";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<li>' . $row['user_name'] . ' - ' . $row['rating'] . '/5: ' . $row['review_text'] . '</li>';
        }
    } else {
        echo "No reviews yet.";
    }
    ?>
</ul>

<form action="add_review.php" method="post">
    <input type="text" name="product_id" placeholder="Enter product ID">
    <input type="text" name="user_name" placeholder="Your Name">
    <textarea name="review_text" placeholder="Review"></textarea>
    <select name="rating">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select>
    <input type="submit" value="Submit Review">
</form>

<?php $conn->close(); ?>
</body>
</html>


<?php include_once 'config.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $user_name = $_POST['user_name'];
    $review_text = $_POST['review_text'];
    $rating = $_POST['rating'];

    if (!empty($product_id) && !empty($user_name) && !empty($review_text)) {
        $query = "INSERT INTO reviews (product_id, user_name, review_text, rating)
                  VALUES ('$product_id', '$user_name', '$review_text', '$rating')";
        $result = $conn->query($query);
        if ($result === TRUE) {
            echo "<p>Review submitted successfully!</p>";
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }
}
?>

<?php $conn->close(); ?>


// db.php

<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'reviews';

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

function addReview($data) {
  global $conn;
  
  $query = "INSERT INTO reviews (user_id, product_id, rating, review)
            VALUES (:user_id, :product_id, :rating, :review)";
  
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':user_id', $data['user_id']);
  $stmt->bindParam(':product_id', $data['product_id']);
  $stmt->bindParam(':rating', $data['rating']);
  $stmt->bindParam(':review', $data['review']);
  
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

function getReviews($productId) {
  global $conn;
  
  $query = "SELECT r.id, u.name, p.name AS product_name, r.rating, r.review
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            JOIN products p ON r.product_id = p.id
            WHERE r.product_id = :product_id";
  
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':product_id', $productId);
  
  $stmt->execute();
  
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function editReview($data) {
  global $conn;
  
  $query = "UPDATE reviews
            SET rating = :rating, review = :review
            WHERE id = :id";
  
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':rating', $data['rating']);
  $stmt->bindParam(':review', $data['review']);
  $stmt->bindParam(':id', $data['id']);
  
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}

function deleteReview($id) {
  global $conn;
  
  $query = "DELETE FROM reviews
            WHERE id = :id";
  
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':id', $id);
  
  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}
?>


// reviews.php

<?php
require_once 'db.php';

if (isset($_POST['submit'])) {
  $data = array(
    'user_id' => $_SESSION['user']['id'],
    'product_id' => $_GET['id'],
    'rating' => $_POST['rating'],
    'review' => $_POST['review']
  );
  
  if (addReview($data)) {
    echo "Review added successfully!";
  } else {
    echo "Failed to add review.";
  }
}

if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  
  $review = getReviews($id);
  foreach ($review as $row) {
    $rating = $row['rating'];
    $reviewText = $row['review'];
  }
}

if (isset($_POST['update'])) {
  $data = array(
    'id' => $_GET['edit'],
    'rating' => $_POST['rating'],
    'review' => $_POST['review']
  );
  
  if (editReview($data)) {
    echo "Review updated successfully!";
  } else {
    echo "Failed to update review.";
  }
}

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  
  if (deleteReview($id)) {
    echo "Review deleted successfully!";
  } else {
    echo "Failed to delete review.";
  }
}
?>


// db.php is a separate file containing the database connection settings

// Include database connection settings
require_once 'db.php';

function createReview($userId, $productId, $reviewText, $rating) {
  // Create query string
  $query = "
    INSERT INTO reviews (user_id, product_id, review, rating)
    VALUES (:userId, :productId, :reviewText, :rating)
  ";

  // Prepare and execute query
  $stmt = $db->prepare($query);
  $stmt->bindParam(':userId', $userId);
  $stmt->bindParam(':productId', $productId);
  $stmt->bindParam(':reviewText', $reviewText);
  $stmt->bindParam(':rating', $rating);

  if ($stmt->execute()) {
    // Get the new review's ID
    $newReviewId = $db->lastInsertId();
    return true;
  } else {
    echo "Error creating review: " . print_r($stmt->errorInfo(), true);
    return false;
  }
}

function getReviews() {
  // Create query string
  $query = "
    SELECT reviews.id, users.username, products.product_name, reviews.review, reviews.rating
    FROM reviews
    INNER JOIN users ON reviews.user_id = users.id
    INNER JOIN products ON reviews.product_id = products.id
    ORDER BY reviews.created_at DESC
  ";

  // Prepare and execute query
  $stmt = $db->prepare($query);
  $stmt->execute();

  return $stmt->fetchAll();
}


// Include review.php for database interactions
require_once 'review.php';

function displayReviews() {
  // Get all reviews from database
  $reviews = getReviews();

  if ($reviews) {
    foreach ($reviews as $review) {
      echo "<div>
        <h2>" . $review['username'] . "</h2>
        <p>" . $review['product_name'] . "</p>
        <p>" . nl2br($review['review']) . "</p>
        <p>Rating: " . $review['rating'] . "/5</p>
      </div>";
    }
  } else {
    echo "No reviews available.";
  }
}

function createReviewForm() {
  echo "
    <form method='post'>
      <label for='username'>Username:</label>
      <input type='text' id='username' name='username' required>

      <label for='product_id'>Product ID:</label>
      <input type='number' id='product_id' name='product_id' required>

      <label for='review_text'>Review:</label>
      <textarea id='review_text' name='review_text'></textarea>

      <label for='rating'>Rating (1-5):</label>
      <input type='number' id='rating' name='rating' min='1' max='5'>

      <button type='submit'>Submit Review</button>
    </form>
  ";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Create review
  $userId = $_SESSION['user_id'];
  $productId = $_POST['product_id'];
  $reviewText = $_POST['review_text'];
  $rating = $_POST['rating'];

  createReview($userId, $productId, $reviewText, $rating);
}


// Include user_review.php for display and form functionality
require_once 'user_review.php';

displayReviews();

createReviewForm();


<?php
// Include database connection settings
require 'db.php';

function createReview($userId, $productId, $rating, $comment) {
  // Check if user and product exist
  $user = getUserById($userId);
  if (!$user) return false;
  
  $product = getProductById($productId);
  if (!$product) return false;

  // Insert review into database
  $query = "INSERT INTO reviews (user_id, product_id, rating, comment)
            VALUES (:user_id, :product_id, :rating, :comment)";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':user_id', $userId);
  $stmt->bindParam(':product_id', $productId);
  $stmt->bindParam(':rating', $rating);
  $stmt->bindParam(':comment', $comment);
  return $stmt->execute();
}

function getReviewsForProduct($productId) {
  // Retrieve reviews for product
  $query = "SELECT r.id, u.username, p.name, r.rating, r.comment, r.created_at 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            JOIN products p ON r.product_id = p.id 
            WHERE r.product_id = :product_id";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':product_id', $productId);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateReview($reviewId, $newRating, $newComment) {
  // Check if review exists
  $review = getReviewById($reviewId);
  if (!$review) return false;

  // Update review in database
  $query = "UPDATE reviews SET rating = :rating, comment = :comment 
            WHERE id = :id";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':id', $reviewId);
  $stmt->bindParam(':rating', $newRating);
  $stmt->bindParam(':comment', $newComment);
  return $stmt->execute();
}

function deleteReview($reviewId) {
  // Check if review exists
  $review = getReviewById($reviewId);
  if (!$review) return false;

  // Delete review from database
  $query = "DELETE FROM reviews WHERE id = :id";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':id', $reviewId);
  return $stmt->execute();
}

function getUserReviews($userId) {
  // Retrieve reviews for user
  $query = "SELECT r.id, p.name, r.rating, r.comment, r.created_at 
            FROM reviews r 
            JOIN products p ON r.product_id = p.id 
            WHERE r.user_id = :user_id";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':user_id', $userId);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getReviewById($reviewId) {
  // Retrieve review by ID
  $query = "SELECT * FROM reviews WHERE id = :id";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':id', $reviewId);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getProductById($productId) {
  // Retrieve product by ID
  $query = "SELECT * FROM products WHERE id = :product_id";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':product_id', $productId);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserById($userId) {
  // Retrieve user by ID
  $query = "SELECT * FROM users WHERE id = :user_id";
  $stmt = $db->prepare($query);
  $stmt->bindParam(':user_id', $userId);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}


// Create a new review
createReview(1, 2, 5, 'Great product!');

// Get reviews for a specific product
$reviews = getReviewsForProduct(2);
print_r($reviews);

// Update an existing review
updateReview(1, 4, 'Good product, but not great.');

// Delete a review
deleteReview(1);

// Retrieve all reviews for a user
$userReviews = getUserReviews(1);
print_r($userReviews);

