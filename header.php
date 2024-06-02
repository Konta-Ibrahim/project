<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
<header class="header">
   <div class="header-2">
      <div class="flex">
         <a href="cart.php" class="logo">Recommend</a>
         <nav class="navbar">
            <a href="home.php">home</a>
            <a href="about.php">about</a>
            <!----
            <a href="orders.php">orders</a>
            <a href="recommendations.php">Top-ratings</a>
-->
            <a href="contact.php">contact</a>
            <a href="shop.php">Top-ratings</a>
         </nav>
         <div class="icons">
         <div id="menu-btn" class="fas fa-ellipsis-v"></div>
         <a href="search_page.php" class="fas fa-search-plus"></a>
         <div id="user-btn" class="fas fa-user-circle"></div>
            <?php
               $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               $cart_rows_number = mysqli_num_rows($select_cart_number); 
            ?>
            <a href="cart.php"> <i class="fas fa-star"></i> <span style="display: none;">(<?php echo $cart_rows_number; ?>)</span> </a>
         </div>
         <div class="user-box">
            <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
            <a href="logout.php" class="delete-btn">logout</a>
            <div>new <a href="login.php">login</a> | <a href="register.php">register</a></div>
         </div>
      </div>
   </div>
</header>