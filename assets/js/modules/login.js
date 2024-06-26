/**
 * Function to handle login form submission and display errors.
 * It sends a POST request to the server to validate login credentials.
 * Redirects the user based on their role if login is successful.
 * Displays error messages if login fails or unexpected server response.
 */
function showLoginError() {
    // Get the login form element
    const loginForm = document.getElementById("login-form");

    // Get the alert message element
    const alertMessage = document.querySelector(".alert-message");
    // Get the error message element
    const errorMessageElement = document.getElementById("error-message");
    // Get the close button element for error message
    const closeBtnError = document.querySelector(".close-btn-error");

     // Add event listener for form submission
    loginForm.addEventListener("submit", function(event) {
        // Prevent default form submission behavior
        event.preventDefault();
        
        const formData = new FormData(loginForm);
         
        const options = {
            // Set request method to POST
            method: "POST",
            // Set request body with form data
            body: formData
        };
        
        // Retrieve information from the server to validate login credentials
        fetch("index.php?route=checkLogin", options)
        .then(response => {
             // Check if the response is different thaan OK 
            if (!response.ok) {
                throw new Error('Réponse du serveur non valide');
            }
            // Parse response as JSON
            return response.json();
        })
        .then(data => {
            // Check if login is successful
            if (data && data.success) {
                // Check user's role and redirect accordingly
                if (data.role === "Principal") {
                    window.location.href = "index.php?route=super-admin";
                } else if (data.role === "Professeur référent") {
                    window.location.href = "index.php?route=admin";
                } else if(data.role === "Professeur") {
                    window.location.href="index.php?route=sub-admin";
                    
                } else if (data.role === "Collégien") {
                    window.location.href = "index.php?route=lesson";
                } else {
                    // Role is not defined or not handled
                    console.error("Rôle non géré:", data.role);
                }
            } else if (data && data.error) {
                // Display error message
                errorMessageElement.textContent = data.error;
                alertMessage.style.display = "block";
            } else {
                // Handle unexpected server response
                console.error("Réponse inattendue du serveur:", data);
            }
        })
        .catch(error => {
            console.error("Erreur lors de la vérification de la connexion:", error);
             // Handle others errors : display a generic error message
            errorMessageElement.textContent = "Une erreur s'est produite lors de la vérification de la connexion.";
            alertMessage.style.display = "block";
        });
    });
    
    // Add event listener for click on the close button
    closeBtnError.addEventListener("click", function() {
        // Hide alert message
        alertMessage.style.display = "none"; 
    });
    
}

// Export the function
export {showLoginError};