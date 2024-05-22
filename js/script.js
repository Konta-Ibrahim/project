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
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'filter_movies.php?letter=' + letter, true);
    xhr.onload = function() {
       if (this.status === 200) {
          const products = JSON.parse(this.responseText);
          displayFilteredProducts(products);
       }
    };
    xhr.send();
 }
 
 function displayFilteredProducts(products) {
    const boxContainer = document.getElementById('box-container-products');
    boxContainer.innerHTML = '';
 
    if (products.length === 0) {
       boxContainer.innerHTML = '<p class="empty">No movies found!</p>';
       return;
    }
 
    products.forEach(product => {
       const productElement = document.createElement('form');
       productElement.action = '';
       productElement.method = 'post';
       productElement.className = 'box';
       productElement.style.maxWidth = '15rem';
       productElement.style.margin = '0';
 
       const productImageContainer = document.createElement('div');
       productImageContainer.style.maxHeight = '15rem';
       productImageContainer.style.overflow = 'hidden';
 
       const productImage = document.createElement('img');
       productImage.className = 'image';
       productImage.src = 'uploaded_img/' + product.image;
       productImage.alt = '';
       productImage.style.width = '100%';
       productImage.style.height = 'auto';
 
       productImageContainer.appendChild(productImage);
 
       const productName = document.createElement('div');
       productName.className = 'name';
       productName.style.padding = '.5rem 0';
       productName.style.fontSize = '1.5rem';
       productName.textContent = product.name;
 
       const productPrice = document.createElement('div');
       productPrice.className = 'price';
       productPrice.style.fontSize = '1.8rem';
       productPrice.style.position = 'absolute';
       productPrice.style.top = '1rem';
       productPrice.style.left = '1rem';
       productPrice.style.borderRadius = '.5rem';
       productPrice.style.padding = '.8rem';
       productPrice.style.color = 'var(--white)';
       productPrice.style.backgroundColor = 'var(--red)';
       productPrice.textContent = '$' + product.price + '/-';
 
       const productCategory = document.createElement('div');
       productCategory.className = 'category';
       productCategory.style.fontSize = '1.5rem';
       productCategory.textContent = product.category;
 
       const productQuantity = document.createElement('input');
       productQuantity.type = 'number';
       productQuantity.min = '1';
       productQuantity.name = 'product_quantity';
       productQuantity.value = '1';
       productQuantity.className = 'qty';
       productQuantity.style.width = '100%';
       productQuantity.style.padding = '.6rem .7rem';
       productQuantity.style.borderRadius = '.5rem';
       productQuantity.style.border = 'var(--border)';
       productQuantity.style.margin = '.5rem 0';
       productQuantity.style.fontSize = '1.5rem';
 
       const productId = document.createElement('input');
       productId.type = 'hidden';
       productId.name = 'product_id';
       productId.value = product.id;
 
       const productNameInput = document.createElement('input');
       productNameInput.type = 'hidden';
       productNameInput.name = 'product_name';
       productNameInput.value = product.name;
 
       const productPriceInput = document.createElement('input');
       productPriceInput.type = 'hidden';
       productPriceInput.name = 'product_price';
       productPriceInput.value = product.price;
 
       const productImageInput = document.createElement('input');
       productImageInput.type = 'hidden';
       productImageInput.name = 'product_image';
       productImageInput.value = product.image;
 
       const productCategoryInput = document.createElement('input');
       productCategoryInput.type = 'hidden';
       productCategoryInput.name = 'product_category';
       productCategoryInput.value = product.category;
 
       const addToCartButton = document.createElement('input');
       addToCartButton.type = 'submit';
       addToCartButton.value = 'add to cart';
       addToCartButton.name = 'add_to_cart';
       addToCartButton.className = 'btn';
       addToCartButton.style.fontSize = '1.5rem';
       addToCartButton.style.padding = '.8rem';
 
       productElement.appendChild(productImageContainer);
       productElement.appendChild(productName);
       productElement.appendChild(productPrice);
       productElement.appendChild(productCategory);
       productElement.appendChild(productQuantity);
       productElement.appendChild(productId);
       productElement.appendChild(productNameInput);
       productElement.appendChild(productPriceInput);
       productElement.appendChild(productImageInput);
       productElement.appendChild(productCategoryInput);
       productElement.appendChild(addToCartButton);
 
       boxContainer.appendChild(productElement);
    });
 }
 
 