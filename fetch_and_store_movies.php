<?php
include 'config.php';

$apiKey = '02359bb3023e7e44f2623b72d8b61f60';
$baseURL = 'https://api.themoviedb.org/3';

function fetchMovies($baseURL, $apiKey) {
    $url = "$baseURL/movie/popular?api_key=$apiKey&language=fr-FR&page=1";
    $response = file_get_contents($url);
    if ($response === FALSE) {
        die('Erreur lors de la récupération des films.');
    }
    return json_decode($response, true);
}

function storeMovies($movies, $conn) {
    foreach ($movies as $movie) {
        $movie_id = $movie['id'];
        $title = mysqli_real_escape_string($conn, $movie['title']);
        $poster_path = mysqli_real_escape_string($conn, $movie['poster_path']);
        $vote_average = $movie['vote_average'];

        $check_movie = mysqli_query($conn, "SELECT * FROM movies WHERE movie_id = '$movie_id'");
        if (mysqli_num_rows($check_movie) == 0) {
            $query = "INSERT INTO movies (movie_id, title, poster_path, vote_average) VALUES ('$movie_id', '$title', '$poster_path', '$vote_average')";
            mysqli_query($conn, $query) or die('Erreur lors de l\'insertion des films');
        }
    }
}

$data = fetchMovies($baseURL, $apiKey);
storeMovies($data['results'], $conn);
?>
