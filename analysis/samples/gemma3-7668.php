    <br><br>

    <label for="notes">Notes (Optional):</label>
    <textarea id="notes" name="notes"><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
