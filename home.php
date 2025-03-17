<?php 
include 'config.php'; 
session_start(); 
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Software Development Company</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'header.php'; ?>


   <div class="content">
      <div class="image-container">
      <div class="background-color"></div>
    <div class="text-overlay">
        <h1>Bienvenue chez Pouina</h1>
        <p>Développement logiciel personnalisé pour vos besoins.</p>
        
      <h1>Transform Your Ideas Into Digital Reality</h1>
      <p>We provide cutting-edge software development solutions tailored to your business needs.</p>
      <a href="#services" class="btn">Explore Our Services</a>
    </div>
</div>

   </div>

<section id="services" class="services">
   <h2>Our Services</h2>
   <div class="box-container">
      <div class="box">
         <i class="fas fa-code"></i>
         <h3><strong>Web Development</strong></h3>
         <p>Create stunning and responsive websites to boost your online presence.</p>
      </div>
      <div class="box">
         <i class="fas fa-laptop-code"></i>
         <h3>Software Development</h3>
         <p>Custom software solutions to optimize your business processes.</p>
      </div>
      <div class="box">
         <i class="fas fa-mobile-alt"></i>
         <h3>Mobile App Development</h3>
         <p>Build feature-rich mobile applications for Android and iOS platforms.</p>
      </div>
      <div class="box">
         <i class="fas fa-cloud"></i>
         <h3>Cloud Solutions</h3>
         <p>Leverage the power of cloud computing to scale your business.</p>
      </div>
   </div>
</section>

<section id="team" class="team">
   <h2>Meet Our Team</h2>
   <div class="box-container">
      <div class="box">
         <img src="images/team1.jpg" alt="Team Member">
         <h3>John Doe</h3>
         <p>Lead Developer</p>
      </div>
      <div class="box">
         <img src="images/team2.jpg" alt="Team Member">
         <h3>Jane Smith</h3>
         <p>UI/UX Designer</p>
      </div>
      <div class="box">
         <img src="images/team3.jpg" alt="Team Member">
         <h3>Mark Johnson</h3>
         <p>Project Manager</p>
      </div>
   </div>
</section>

<section id="testimonials" class="testimonials">
   <h2>What Our Clients Say</h2>
   <div class="box-container">
      <div class="box">
         <p>"This team exceeded our expectations! Our website traffic has doubled since launch."</p>
         <h4>– Sarah Connor</h4>
      </div>
      <div class="box">
         <p>"Excellent service and support. The software they developed streamlined our operations."</p>
         <h4>– Michael Scott</h4>
      </div>
   </div>
</section>



<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
