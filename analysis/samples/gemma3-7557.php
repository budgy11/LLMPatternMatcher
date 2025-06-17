
    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method">
      <option value="credit_card">Credit Card</option>
      <option value="paypal">PayPal</option>
    </select><br><br>

    <input type="submit" value="Place Order">
  </form>

  <?php if (isset($error_message)) {
    echo "<p class='error-message'>" . $error_message . "</p>";
  } else if (isset($success_message)) {
    echo "<p class='success-message'>" . $success_message . "</p>";
  }
?>
