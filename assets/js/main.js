// Import the form validation function from validator.js
import { validateRolesForm } from "././modules/validator.js";
import { updateRemainingTime } from "././modules/countdown.js";
import { configureTinyMCE } from "././modules/tinyMCE-config.js";
import {showLoginError} from "././modules/login.js";
import {completionSearchCoursesByLessonName} from "././modules/searchLessonCourses.js";


// Listen for the DOMContentLoaded event
document.addEventListener("DOMContentLoaded", () => {
    
    // Select all inputs fields in the form
    const roles = document.querySelectorAll("#role select");
    
    //Adding the 'input' event listener to each input field
    roles.forEach((role) => {
        role.addEventListener("click", (event) => {
            // Call the form validation function when the user exits an input field
            validateRolesForm();
            
        });
    });
    
    
    // Call the function to update the remaining time for lesson links
    updateRemainingTime();
    
    // Call the function to set up TinyMCE
    configureTinyMCE();
    
    // Call the function to display connection error
    showLoginError();
    
    // Call the function for lesson name autocompletion
    completionSearchCoursesByLessonName();
});