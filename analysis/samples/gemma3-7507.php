        </select><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="1" min="1"><br><br>

        <button type="submit">Add to Cart</button>
    </form>

    <?php
    displayCart($cart);
    ?>
