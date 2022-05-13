// Sélectionne les étoiles 
let stars: any = document.querySelectorAll('[data-value]');
console.log(stars);

// Sélectionne notre input, celui hiddent quie est lié à notre formulaire CommentType => symfony donne automatiquement un id à nos input 
let inputNote: HTMLInputElement = document.querySelector('#comment_Note');
// console.log(inputNote);

// Ajoute un évennement au click pour chaque étoile et le stock dans une variable value (on aura donc une note de 1 à 5 dans la value)
stars.forEach(star => {
    
    star.addEventListener('click' , () => {

        // Récupère la valeur de notre attribut 'data-value'
        let note = star.getAttribute('data-value');
        // console.log(note);

        // ### 2 méthodes pour modifier la valeur de l'attribut => ici 'value'

        // En utilisant le méthode setAttribute() :
        // inputNote.setAttribute('value', note);

        // En passant directement par l'attribut :
        inputNote.value = note;
        console.log(inputNote);
        

        // ##### modif couleur

        // Met en couleur par défaut les stars 
        stars.forEach(star => {
            star.style.color = "white";
        });

        // Met en orange la star sur laquelle on clic
        star.style.color = "orange";

        // Cible la star précédente
        let starPrecedente = star.previousElementSibling;

        // Tant qu'il y a une star précédente 
        while (starPrecedente) {
            // la met en orange
            starPrecedente.style.color = "orange";
            // Modifie la valeur de starPrecente pour cibler la star précédente 
            // ==> sinon boucle infini, car sans cette ligne on aura toujours une starPrecente car on ne modifie pas cette valeur à chaque tour de boucle
            starPrecedente = starPrecedente.previousElementSibling
        }
    });
});






