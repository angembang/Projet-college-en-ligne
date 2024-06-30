function showRegisterError() 
{
    const form = document.getElementById("registerForm");

    if (form) {
        console.log("Form found and event listener added");
        form.addEventListener("submit", function(event) {
            event.preventDefault();
             console.log("Form submission intercepted");

            const formData = new FormData(form);

            fetch("index.php?route=checkRegister", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log("Fetch response received", data);
                showModal(data.message);
            })
            .catch(error => {
                console.error("Error:", error);
                showModal("Une erreur s'est produite lors de la mise à jour du cours.");
                console.error("Error:", error);
            });
        });
    }

    function showModal(message) {
        const modal = document.getElementById("registerMessageModal");
        const modalContent = document.getElementById("registerModalContent");
        modalContent.innerHTML = message;
        modal.style.display = "block";
    }

    window.closeModal = function() {
        const modal = document.getElementById("registerMessageModal");
        modal.style.display = "none";
    }

    // Close the modal when the user click out of it
    window.onclick = function(event) {
        const modal = document.getElementById("registerMessageModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

}
export {showRegisterError};