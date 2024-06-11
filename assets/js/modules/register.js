function showRegisterError() 
{
    // Get the login form element
    const registerForm = document.getElementById("register-form");
    // Get the alert message element
    const alertMessage = document.querySelector(".alert-message");
    // Get the error message element
    const errorMessageElement = document.getElementById("error-message");
    // Get the close button element for error message
    const closeBtnError = document.querySelector(".close-btn-error");

    // Add event listener for form submission
    registerForm.addEventListener("submit", function(event) {
        // Prevent default form submission behavior
        event.preventDefault();

        const formData = new FormData(registerForm);
        
        const options = {
          // Set request method to POST
          method: "POST",
          body: formData
        }
        // Retrieve information from the server to validate register credentials
        fetch("index.php?route=checkRegister", options)
        .then(response => {
            // Check if the response is different than ok
            if(!response.ok) {
              throw new Error("Reponse du serveur non valide")
            }
            // Parse response as JSON
            return response.json();
        })
        .then(data => {
            // Check if register is successful
            if(data && data.success) {
              alert("Inscription réussie !");
              window.location.href = "index.php?route=lesson";
            } else if(data && data.error) {
              // Display error message
              errorMessageElement.textContent = data.error;
              alertMessage.style.display = "block";
            } else {
              // Handle unexpected server response
              console.error("Réponse inattendue du serveur:", data);
            }
        })
        .catch(error => {
            console.error("Erreur lors de la vérification de l'insciption':", error);
            // Handle others errors : display a generic error message
            errorMessageElement.textContent = "Une erreur s'est produite lors de la vérification de l'inscription'.";
        });

    })
    // Add event listener for click on the close button
    closeBtnError.addEventListener("click", function() {
      // Hide alert message
      alertMessage.style.display = "none";
    })

}
export {showRegisterError};