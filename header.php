
<header class="header" >
   <div class="header-2" style="background-color: white;">
      <div class="flex">
      <a href="home.php" class="logo">
    <img src="images/ca.png" alt="Pouina Enterprises Logo" style="height: 50px;">
</a>

         <nav class="navbar">
            <a href="home.php">home</a>
            <a href="about.php">about</a>
            <a href="#services">Services</a>
            <a href="Blog.php">Blog</a>
         </nav>
       
         <div class="icons">
         <div id="menu-btn" class="fas fa-ellipsis-v"></div>
 
       
         
         <div id="user-btn" class="fas fa-user-circle" ></div>
           
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