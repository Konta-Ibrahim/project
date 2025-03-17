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
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      body {
         background: linear-gradient(to right, var(--light-bg), #3a35cf);
      }
   .btn {
      display: inline-block;
      padding: 1rem 2rem;
      font-size: 1.6rem;
      text-decoration: none;
      background-color: yellowgreen;
      color: var(--white);
      border-radius: .5rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: background-color 0.3s ease, transform 0.3s ease;
   }

   .btn:hover {
      background-color: var(--black);
      transform: translateY(-2px);
   }

   .btn:active {
      transform: translateY(0);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
   }
   
   /* Style général */
   .box-about {
    width: 130%;
    max-width: 800px;
    background: #fff;
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    animation: fadeIn 1s ease-in-out;
}

/* Image à droite */
.box-about img {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    object-fit: cover;
    transition: transform 0.3s ease-in-out;
    align-items: center;
    
}

.box-about img:hover {
    transform: scale(1.1);
}

/* Contenu à gauche */
.perso-infos {
    flex: 1;
    padding-right: 20px;
    animation: fadeInLeft 1s ease-in-out;
    text-align: justify;
    max-width: 120%;
    font-size: 15px;
}

h3 {
    font-size: 22px;
    color: #333;
    margin-bottom: 10px;
    animation: fadeInDown 1s ease-in-out;
}

p {
    font-size: 16px;
    color: #555;
    line-height: 1.5;
}

/* Animation des liens sociaux */
.share {
    margin-top: 10px;
}

.share a {
    display: inline-block;
    width: 35px;
    height: 35px;
    line-height: 35px;
    text-align: center;
    margin: 5px;
    border-radius: 50%;
    background: #333;
    color: white;
    font-size: 18px;
    transition: all 0.3s ease-in-out;
}

.share a:hover {
    background: #007bff;
    transform: translateY(-5px);
}

/* Animations */
@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .box-about {
        flex-direction: column-reverse;
        text-align: center;
    }

    .perso-infos {
        padding-right: 0;
    }

    .box-about img {
        margin-top: 20px;
    }
}


</style>

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <p><a href="home.php" class="btn">contact us</a></p>
</div>

<section class="authors">


   <div class="box-container">

      <div class="box-about">
         <img src="images/moi.png" alt="">
         <div class="share">
         <h3>Konta Ibrahim</h3><br>
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <br>
        
          <div class="perso-infos">
            <strong> 🌟 Profil Professionnel <br>
Ingénieur logiciel et développeur full-stack passionné par la création d'applications performantes et évolutives. Je maîtrise les technologies front-end (React, Angular, HTML, CSS, JavaScript) et back-end (PHP, Python, Spring Boot, Kotlin, Java), ainsi que les bases de données SQL et NoSQL. Expérimenté en gestion de projet, architecture logicielle et analyse de données, je collabore efficacement en équipe. Passionné par l'innovation, la performance et la sécurité, je cherche à développer des solutions impactantes.<br>
🚀 Prêt à relever de nouveaux défis et à contribuer à des projets innovants !
</strong>
         

      </div>
      </div>

   </div>

</section>

<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>