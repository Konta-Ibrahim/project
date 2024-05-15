<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
   exit();
}

// Fonction pour récupérer des produits similaires
function getSimilarProducts($product_id, $category, $conn) {
    $similar_products = array();
    if($stmt = $conn->prepare("SELECT * FROM products WHERE category = ? AND id != ? AND id NOT IN (SELECT product_id FROM ratings WHERE rating < 3)")){
        $stmt->bind_param("si", $category, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()){
            $similar_products[] = $row;
        }
        $stmt->close();
    }
    return $similar_products;
}

if(isset($_POST['update_cart'])){
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];
   
   if($stmt = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?")){
      $stmt->bind_param("ii", $cart_quantity, $cart_id);
      $stmt->execute();
      $stmt->close();
      $message[] = 'Cart quantity updated!';
   } else {
      $message[] = 'Failed to update cart quantity!';
   }
}

if(isset($_POST['submit_rating'])){
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    
    if($stmt = $conn->prepare("SELECT * FROM `ratings` WHERE user_id = ? AND product_id = ?")){
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0){
            if($update_stmt = $conn->prepare("UPDATE `ratings` SET rating = ? WHERE user_id = ? AND product_id = ?")){
                $update_stmt->bind_param("iii", $rating, $user_id, $product_id);
                $update_stmt->execute();
                $update_stmt->close();
                $message[] = 'Product rating updated!';
            }
        } else {
            if($insert_stmt = $conn->prepare("INSERT INTO `ratings` (user_id, product_id, rating) VALUES (?, ?, ?)")){
                $insert_stmt->bind_param("iii", $user_id, $product_id, $rating);
                $insert_stmt->execute();
                $insert_stmt->close();
                $message[] = 'Product rating submitted!';
            }
        }
        $stmt->close();
    } else {
        $message[] = 'Failed to submit rating!';
    }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   if($stmt = $conn->prepare("DELETE FROM `cart` WHERE id = ?")){
      $stmt->bind_param("i", $delete_id);
      $stmt->execute();
      $stmt->close();
      header('location:cart.php');
      exit();
   } else {
      $message[] = 'Failed to delete item from cart!';
   }
}

if(isset($_GET['delete_all'])){
   if($stmt = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?")){
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $stmt->close();
      header('location:cart.php');
      exit();
   } else {
      $message[] = 'Failed to delete all items from cart!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cart</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'header.php'; ?>

<?php
if(isset($message) && is_array($message)){
   foreach($message as $msg){
      echo '<div class="message"><span>'.$msg.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display=\'none\';"></i></div>';
   }
}
?>

<div class="heading">
   <h3>Shopping Cart</h3>
   <p><a href="home.php">Home</a> / Cart</p>
</div>

<section class="shopping-cart">
   <h1 class="title">Products Added</h1>
   <div class="box-container">
      <?php
         $grand_total = 0;
         if($stmt = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?")){
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
               while($fetch_cart = $result->fetch_assoc()){   
      ?>
      <div class="box">
         <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('Delete this from cart?');"></a>
         <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_cart['name']; ?></div>
         <div class="price">$<?php echo $fetch_cart['price']; ?>/-</div>
         <form action="" method="post">
            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
            <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
            <input type="submit" name="update_cart" value="Update" class="option-btn">
         </form>
         <div class="sub-total"> Sub Total: <span>$<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?>/-</span> </div>

         <form action="" method="post">
            <input type="hidden" name="product_id" value="<?php echo $fetch_cart['id']; ?>">
            <label for="rating">Rate this product:</label>
            <select name="rating" id="rating" required>
               <option value="">Select</option>
               <option value="1">1</option>
               <option value="2">2</option>
               <option value="3">3</option>
               <option value="4">4</option>
               <option value="5">5</option>
            </select>
            <input type="submit" name="submit_rating" value="Rate" class="option-btn">
         </form>

         <div class="average-rating">
            <?php
            if($avg_stmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM `ratings` WHERE product_id = ?")){
               $avg_stmt->bind_param("i", $fetch_cart['id']);
               $avg_stmt->execute();
               $avg_result = $avg_stmt->get_result();
               $avg_rating = $avg_result->fetch_assoc()['avg_rating'];
               echo $avg_rating ? 'Average Rating: ' . round($avg_rating, 2) : 'No ratings yet';
               $avg_stmt->close();
            }
            ?>
         </div>
      </div>
      <?php
               $grand_total += $sub_total;

               // Appel de la fonction pour récupérer les produits similaires
               $similar_products = getSimilarProducts($fetch_cart['id'], $fetch_cart['category'], $conn);
               
               // Affichage des produits similaires
               if (!empty($similar_products)) {
                   echo "<h2>Products You May Like</h2>";
                   foreach ($similar_products as $product) {
                       echo "<div class='similar-product'>";
                       echo "<img src='uploaded_img/{$product['image']}' alt='{$product['name']}'>";
                       echo "<div class='name'>{$product['name']}</div>";
                       echo "<div class='price'>$ {$product['price']} /-</div>";
                       // Ajoutez un lien pour ajouter ce produit au panier, etc.
                       echo "</div>";
                   }
               }
            }
         } else {
            echo '<p class="empty">Your cart is empty</p>';
         }
         $stmt->close();
      } else {
         echo '<p class="empty">Failed to fetch cart items</p>';
      }
      ?>
   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>" onclick="return confirm('Delete all from cart?');">Delete All</a>
   </div>

   <div class="cart-total">
      <p>Grand Total: <span>$<?php echo $grand_total; ?>/-</span></p>
      <div class="flex">
         <a href="shop.php" class="option-btn">Continue Shopping</a>
         <a href="checkout.php" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
      </div>
   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>
