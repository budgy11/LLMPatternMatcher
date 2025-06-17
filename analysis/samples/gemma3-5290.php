    <input type="text" name="search" placeholder="Enter search term">
    <button type="submit">Search</button>
  </form>

  <?php
  if (!empty($errorMessage)) {
    echo "<p style='color:red;'>" . $errorMessage . "</p>";
  }
  if (!empty($successMessage)) {
    echo "<p style='color:green;'>" . $successMessage . "</p>";
  }
  ?>
