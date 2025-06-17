                <input type="hidden" name="cart_id" value="cart_<?php echo session_id(); ?>">
                <button type="submit" name="remove_from_cart">Remove</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
