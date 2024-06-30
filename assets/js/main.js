// Import the necessary modules
import {burgerMenu} from "./modules/home.js";
import {validateRolesForm} from "./modules/validator.js";
import {updateRemainingTime} from "./modules/countdown.js";
//import {configureTinyMCE} from "./modules/tinyMCE-config.js";
import {showLoginError} from "./modules/login.js";
import {completionSearchCoursesByLessonName} from "./modules/searchLessonCourses.js";
import {registerLogin} from "./modules/registerLogin.js";
import {updateCourseModal} from "./modules/updateCoursesModal.js";
import {showRegisterError} from "./modules/register.js";



// Listen for the DOMContentLoaded event
document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM fully loaded and parsed");
    
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
    
    // Call the function for menu burger
    burgerMenu();
    
    // Call the function to set up TinyMCE
    //configureTinyMCE();
    
    // Call the function to display update course form error
    updateCourseModal();
    
    // Call the function to display register error
    showRegisterError();
    
    // Call the function to display connection error
    showLoginError();
    
    // Call the function to display connection error
    registerLogin();
    
    // Call the function for lesson name autocompletion
    completionSearchCoursesByLessonName();
    
});
