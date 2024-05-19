let userBox = document.querySelector('.header .header-2 .user-box');
document.querySelector('#user-btn').onclick = () =>{
   userBox.classList.toggle('active');
   navbar.classList.remove('active');
}
let navbar = document.querySelector('.header .header-2 .navbar');
document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   userBox.classList.remove('active');
}
window.onscroll = () =>{
   userBox.classList.remove('active');
   navbar.classList.remove('active');

   if(window.scrollY > 60){
      document.querySelector('.header .header-2').classList.add('active');
   }else{
      document.querySelector('.header .header-2').classList.remove('active');
   }
}

document.addEventListener('DOMContentLoaded', (event) => {
   const container = document.querySelector('.similar-products-container');
   if (container) {
       let scrollAmount = 0;
       const scrollStep = 1; // Ajustez cette valeur pour la vitesse de défilement
       const scrollDelay = 20; // Ajustez cette valeur pour la fluidité du défilement
       
       function startScrolling() {
           scrollAmount++;
           container.scrollLeft = scrollAmount;
           if (scrollAmount >= container.scrollWidth - container.clientWidth) {
               scrollAmount = 0; // Reset when end is reached
           }
       }

       setInterval(startScrolling, scrollDelay);
   }
});
