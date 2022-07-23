const field = document.getElementById('searchTitle');
const title = document.getElementById('film_title');
const year = document.getElementById('film_year');
const plot = document.getElementById('film_plot');
const poster = document.getElementById('film_poster');
const omdbId = document.getElementById('film_omdb_id');

if (field) {
    field.value = '';
    title.value = '';
    year.value = '';
    plot.value = '';
    poster.value = '';
    omdbId.value = '';

    field.addEventListener('change', (e) => {
        title.value = '';
        year.value = '';
        plot.value = '';
        poster.value = '';
        omdbId.value = '';

        e.preventDefault();

        fetch('search', {
            method: 'POST',
            headers: {
                'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
            },
            body: `searchTitle=${encodeURIComponent(field.value)}`,
        })
            .then((response) => response.json())
            .then((data) => {
                const objectData = JSON.parse(data);
                const results = document.getElementById('results');
                results.innerHTML = '';
                for (const film of objectData.Search) {
                    results.innerHTML += `
                    <div class="container bg-secondary col-11 film-card border rounded-3 filmResult py-1 my-1">
                        <div class="row">
                            <img class="col-5" src="${film.Poster}" alt="Poster of ${film.Title} (${film.Year})" id="poster_${film.imdbID}">  
                            <div class="col-7">
                                <h4 class="d-flex bg-color-primary">${film.Title}</h4>
                                <hr>  
                                ${film.Year} 
                            </div>
                        </div>
                        <input type="hidden" name="imdbId" id="imdbId" value="${film.imdbID}">
                    </div>
                    `;
                }
            })
            .then(() => {
                const filmResult = document.getElementsByClassName('filmResult');
                for (const film of filmResult) {
                    film.onclick = () => {
                        fetch('get', {
                            method: 'POST',
                            headers: {
                                'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
                            },
                            body: `getFilm=${encodeURIComponent(film.getElementsByTagName('input')[0].value)}`,
                        })
                            .then((response) => response.json())
                            .then((data) => {
                                const filmData = JSON.parse(data);

                                document.documentElement.scrollTop = 600;

                                title.value = filmData.Title;
                                year.value = filmData.Year;
                                plot.value = filmData.Plot;
                                if (!film.childNodes[1].childNodes[1].naturalWidth) {
                                    poster.value = '';
                                } else {
                                    poster.value = filmData.Poster;
                                    poster.dispatchEvent(new Event('change'));
                                }
                                omdbId.value = filmData.imdbID;
                            });
                    };
                }
            })
            .catch((error) => console.log(error));
    });
}
