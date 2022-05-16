let stars: any = document.querySelectorAll('[data-value]');
console.log(stars);
let note: HTMLInputElement = document.querySelector('#add_comment_note');
console.log(note);

stars.forEach(star => {
    star.addEventListener('click', () => {
        let valueNote = star.getAttribute('data-value');
        console.log(valueNote);
        note.value = valueNote;
        console.log(note);

        // AUTRE METHODE
        // note.setAttribute('value', value);
        // console.log(note);

        stars.forEach((starElem) => {
            starElem.style.color = "#FFF";
        })

        star.style.color = "orange";
        let previousStar: any = star.previousElementSibling;
        while (previousStar) {
            previousStar.style.color = "orange";
            // Tant qu'il y a une etoile précédente, on la sélectionne
            previousStar = previousStar.previousElementSibling;
        }
    })
})