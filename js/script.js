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
 

 // Smooth Scroll for Buttons and Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
   anchor.addEventListener('click', function (e) {
       e.preventDefault();
       document.querySelector(this.getAttribute('href')).scrollIntoView({
           behavior: 'smooth'
       });
   });
});

// Add Animation on Scroll
const boxes = document.querySelectorAll('.box');

const showBoxesOnScroll = () => {
   const triggerBottom = window.innerHeight / 5 * 4;

   boxes.forEach(box => {
       const boxTop = box.getBoundingClientRect().top;

       if (boxTop < triggerBottom) {
           box.classList.add('show');
       } else {
           box.classList.remove('show');
       }
   });
};

window.addEventListener('scroll', showBoxes);

// Adding Active Class to Navigation Links
const navLinks = document.querySelectorAll('header a');

window.addEventListener('scroll', () => {
   let fromTop = window.scrollY;

   navLinks.forEach(link => {
       let section = document.querySelector(link.hash);

       if (section.offsetTop <= fromTop && section.offsetTop + section.offsetHeight > fromTop) {
           link.classList.add('active');
       } else {
           link.classList.remove('active');
       }
   });
});

 