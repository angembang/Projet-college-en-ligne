// Function to validate the role
function validateRoles() {
    // Retrieve the role
    const roles = document.querySelectorAll("#role select");
    
    // Loop through each role
    for (let i = 0; i < roles.length; i++) {
    const role = roles[i];
    // Retrieve the value of role 
    const value = role.value;
    // Retrieve the text content of the selected option in the role
    const roleName = role.options[role.selectedIndex].text;
    
    // Check if role is not empty
    if(role !== "") {
        // Return valid status and roleName if role is not empty
        return {
            valid: true,
            roleName: roleName
            }
        } 
        // Returning false if no role is selected
        return {
            valid: false
        }
    
    }
   
}


// Function to validate the form 
function validateRolesForm() {
    // Call validateRoles function to check if a role is selected
    const rolesValid = validateRoles();

    // Check if the selected roles are valid
    if (rolesValid.valid) {
        // Retrieve fieldsets for firstName, lastName, email, password, confirmPassword to display them
        const firstNameField = document.querySelector("fieldset fieldset:nth-of-type(2)");
        const lastNameField = document.querySelector("fieldset fieldset:nth-of-type(3)");
        const emailField = document.querySelector("fieldset fieldset:nth-of-type(4)");
        const passwordField = document.querySelector("fieldset fieldset:nth-of-type(5)");
        const confirmPasswordField = document.querySelector("fieldset fieldset:nth-of-type(6)");
        const idClassField = document.querySelector("fieldset fieldset:nth-of-type(7)");
        const idLanguageField = document.querySelector("fieldset fieldset:nth-of-type(8)");

        // Retrieve the selected role
        const selectedRole = rolesValid.roleName;
        
        // Check if the selected role is either "Principal" or "Professeur"
        if ((selectedRole === "Principal") || (selectedRole === "Professeur")) {
            // Show fields for Principal or Professeur, hiding idClassField and idLanguageField
            showFields([firstNameField, lastNameField, emailField, passwordField, confirmPasswordField]);
            hideFields([idClassField, idLanguageField]);
    
        } 
        // Check if the selected role is "Professeur référent"
        else if (selectedRole === "Professeur référent") {
            // Show fields for Professeur référent, hiding idLanguageField, enabling submitButton
            showFields([firstNameField, lastNameField, emailField, passwordField, confirmPasswordField, idClassField]);
            hideFields([idLanguageField]);
            
            
        } 
        // Check if the selected role is "Collégien"
        else if (selectedRole === "Collégien") {
            // Showing fields for Collégien
            showFields([firstNameField, lastNameField, emailField, passwordField, confirmPasswordField, idClassField]);
            
            // Get the class selected element by its unique identifier
            const classSelector = document.getElementById("idClass");
            
            // Add an event listener to classSelector to handle changes
            classSelector.addEventListener("change", function() {
                
                // Retrieve the text content of the selected option in the class selection
                const classLevel = classSelector.options[classSelector.selectedIndex].text;
                if (classLevel === "5ème" || classLevel === "4ème" || classLevel === "3ème") {
                    // Show idLanguageField if class level is 5ème, 4ème or 3ème
                    idLanguageField.style.display = "block";
                } else {
                    // Hiding idLanguageField for other class levels
                    idLanguageField.style.display = "none";
                }
            });
           
        }
    }
}

// Function to display fields
function showFields(fields) {
    // Loop through each field in the array and set its display style to "block"
    fields.forEach(field => {
        field.style.display = "block";
    });
}

// Function to hide fields
function hideFields(fields) {
    // Loop through each field in the array and set its display style to "none"
    fields.forEach(field => {
        field.style.display = "none";
    });
}


// Export de la fonction de validation du formulaire
export  {validateRolesForm}; 