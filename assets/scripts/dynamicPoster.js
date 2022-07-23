const formPoster = document.getElementsByClassName('form-poster');

if (formPoster[0]) {
    const poster = document.getElementById('film_poster');
    const originalPosterSource = formPoster[0].src;

    if (poster) {
        try {
            formPoster[0].src = poster.value;
        } catch (error) {
            formPoster[0].src = originalPosterSource;
        }
    }
    poster.addEventListener('change', (e) => {
        formPoster[0].src = poster.value;
        formPoster[0].addEventListener('error', (err) => {
            formPoster[0].src = originalPosterSource;
        });
    });
}
