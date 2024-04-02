// Import the form validation function from validator.js
import { validateRolesForm } from "././modules/validator.js";
import { updateRemainingTime } from "././modules/countdown.js";
import { configureTinyMCE } from "././modules/tinyMCE-config.js";
import {showLoginError} from "././modules/login.js";


// Listen for the DOMContentLoaded event
document.addEventListener("DOMContentLoaded", () => {
    
    // Select all inputs fields in the form
    const roles = document.querySelectorAll("#role select");
    
    // Ajout de l'écouteur d'événements "input" à chaque champ de saisie
    roles.forEach((role) => {
        role.addEventListener("click", (event) => {
            // Appel de la fonction de validation du formulaire lorsque l'utilisateur quitte un champ de saisie
            validateRolesForm();
            
        });
    });
    
    
    // Call the function to update the remaining time for lesson links
    updateRemainingTime();
    
    // Appeler la fonction pour configurer TinyMCE
    configureTinyMCE();
    
    // Appeler la fonction d'affichage d'erreur de connexion
    showLoginError();
});