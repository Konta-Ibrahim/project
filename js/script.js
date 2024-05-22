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


// categorisation des films

// Fonction pour récupérer les images de films à partir de l'API TMDb
async function fetchMovieImages(movieTitle) {
   const apiKey = '02359bb3023e7e44f2623b72d8b61f60';
   const baseURL = 'https://api.themoviedb.org/3';
   const searchEndpoint = '/search/movie';
   
   try {
       const response = await fetch(`${baseURL}${searchEndpoint}?api_key=${apiKey}&query=${encodeURIComponent(movieTitle)}`);
       if (!response.ok) {
           throw new Error(`HTTP error! status: ${response.status}`);
       }
       const data = await response.json();
       if (data.results.length > 0) {
           const posterPath = data.results[0].poster_path;
           if (posterPath) {
               return `https://image.tmdb.org/t/p/w500${posterPath}`;
           }
       }
       return 'placeholder.jpg'; // Chemin vers une image par défaut au cas où aucune image n'est trouvée
   } catch (error) {
       console.error('Erreur lors de la récupération des images de film:', error);
       return 'placeholder.jpg'; // Chemin vers une image par défaut en cas d'erreur
   }
}

// Exemple d'utilisation : récupérer les images de films pour chaque élément du dataset
async function fetchAndDisplayMovieImages() {
   const boxContainer = document.getElementById('box-container');
   if (!boxContainer) {
       console.error('Le conteneur box-container est introuvable.');
       return;
   }

   const movies = document.querySelectorAll('.box .name');
   movies.forEach(async (movieElement) => {
       const movieTitle = movieElement.textContent;
       const movieImageURL = await fetchMovieImages(movieTitle);
       const imageElement = document.createElement('img');
       imageElement.src = movieImageURL;
       movieElement.parentNode.insertBefore(imageElement, movieElement.nextSibling);
   });
}

// Appel de la fonction pour récupérer et afficher les images de films
fetchAndDisplayMovieImages();


//meme informations par tous

function displayMovies(movies) {
   const boxContainer = document.getElementById('box-container-movies');

   if (!boxContainer) {
       console.error('Le conteneur box-container-movies est introuvable.');
       return;
   }

   if (!movies || movies.length === 0) {
       console.log('Aucun film à afficher.');
       return;
   }

   movies.forEach(movie => {
       const movieElement = document.createElement('form');
       movieElement.classList.add('box');
       movieElement.method = 'post';
       movieElement.style.maxWidth = '15rem';
       movieElement.style.margin = '0';

       const movieImageContainer = document.createElement('div');
       movieImageContainer.style.maxHeight = '15rem';
       movieImageContainer.style.overflow = 'hidden';

       const movieImage = document.createElement('img');
       movieImage.classList.add('image');
       movieImage.src = movie.poster_path ? `${imageBaseURL}${movie.poster_path}` : 'images/placeholder.png';
       movieImage.alt = movie.title;
       movieImage.style.width = '100%';
       movieImage.style.height = 'auto';

       movieImageContainer.appendChild(movieImage);

       const movieName = document.createElement('div');
       movieName.classList.add('name');
       movieName.textContent = movie.title;
       movieName.style.padding = '.5rem 0';
       movieName.style.fontSize = '1.5rem';

       const moviePrice = document.createElement('div');
       moviePrice.classList.add('price');
       moviePrice.textContent = `$${movie.vote_average}/-`;
       moviePrice.style.fontSize = '1.8rem';
       moviePrice.style.position = 'absolute';
       moviePrice.style.top = '1rem';
       moviePrice.style.left = '1rem';
       moviePrice.style.borderRadius = '.5rem';
       moviePrice.style.padding = '.8rem';
       moviePrice.style.color = 'var(--white)';
       moviePrice.style.backgroundColor = 'var(--red)';

       const movieCategory = document.createElement('div');
       movieCategory.classList.add('category');
       movieCategory.textContent = 'Movie';
       movieCategory.style.fontSize = '1.5rem';

       const movieQuantity = document.createElement('input');
       movieQuantity.type = 'number';
       movieQuantity.min = '1';
       movieQuantity.name = 'product_quantity';
       movieQuantity.value = '1';
       movieQuantity.classList.add('qty');
       movieQuantity.style.width = '100%';
       movieQuantity.style.padding = '.6rem .7rem';
       movieQuantity.style.borderRadius = '.5rem';
       movieQuantity.style.border = 'var(--border)';
       movieQuantity.style.margin = '.5rem 0';
       movieQuantity.style.fontSize = '1.5rem';

       const productId = document.createElement('input');
       productId.type = 'hidden';
       productId.name = 'product_id';
       productId.value = movie.id;

       const productName = document.createElement('input');
       productName.type = 'hidden';
       productName.name = 'product_name';
       productName.value = movie.title;

       const productPrice = document.createElement('input');
       productPrice.type = 'hidden';
       productPrice.name = 'product_price';
       productPrice.value = movie.vote_average;

       const productImage = document.createElement('input');
       productImage.type = 'hidden';
       productImage.name = 'product_image';
       productImage.value = movie.poster_path ? `${imageBaseURL}${movie.poster_path}` : '';

       const productCategory = document.createElement('input');
       productCategory.type = 'hidden';
       productCategory.name = 'product_category';
       productCategory.value = 'Movie';

       const addToCartButton = document.createElement('input');
       addToCartButton.type = 'submit';
       addToCartButton.value = 'add to cart';
       addToCartButton.name = 'add_to_cart';
       addToCartButton.classList.add('btn');
       addToCartButton.style.fontSize = '1.5rem';
       addToCartButton.style.padding = '.8rem';

       movieElement.appendChild(movieImageContainer);
       movieElement.appendChild(movieName);
       movieElement.appendChild(moviePrice);
       movieElement.appendChild(movieCategory);
       movieElement.appendChild(movieQuantity);
       movieElement.appendChild(productId);
       movieElement.appendChild(productName);
       movieElement.appendChild(productPrice);
       movieElement.appendChild(productImage);
       movieElement.appendChild(productCategory);
       movieElement.appendChild(addToCartButton);
       boxContainer.appendChild(movieElement);
   });
}


//filtrer par lettre

function filterByLetter(letter) {
    // Implement the filter logic
    // For example, you can send an AJAX request to fetch products starting with the selected letter
    console.log("Filter products by letter: " + letter);
 }
 