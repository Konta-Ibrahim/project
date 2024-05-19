const usernameInput = document.getElementById('username');
const categorySelect = document.getElementById('category');
const submitButton = document.getElementById('submit-user');
const recommendationList = document.getElementById('movie-list');
const recommendationsSection = document.getElementById('recommendations');

// Function to perform AJAX request (replace with your preferred library)
function makeAjaxRequest(url, data, callback) {
  const xhr = new XMLHttpRequest();
  xhr.open('POST', url, true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onload = function() {
    if (xhr.status === 200) {
      callback(xhr.responseText);
    } else {
      console.error('Error fetching data:', xhr.statusText);
    }
  };
  xhr.send(data);
}

async function recommendMovies(username, categoryId) {
  const data = `username=${username}&category=${categoryId}`;
  return new Promise((resolve, reject) => {
    makeAjaxRequest('recommend.php', data, (response) => {
      try {
        const recommendations = JSON.parse(response);
        resolve(recommendations);
      } catch (error) {
        console.error('Error parsing response:', error);
        reject(error);
      }
    });
  });
}

submitButton.addEventListener('click', async function() {
  const username = usernameInput.value.trim();
  const categoryId = categorySelect.value;
  if (username) {
    try {
      const recommendedMovies = await recommendMovies(username, categoryId);
      if (recommendedMovies.length > 0) {
        recommendationsSection.style.display = 'block';
        recommendationList.innerHTML = '';
        for (const movie of recommendedMovies) {
          const listItem = document.createElement('li');
          listItem.textContent = `${movie.title} (Rating: ${movie.rating})`;
          recommendationList.appendChild(listItem);
        }
      } else {
        alert('No recommendations found for this user and category.');
      }
    } catch (error) {
      console.error('Error fetching recommendations:', error);
      alert('An error occurred. Please try again later.');
    }
  }
});
