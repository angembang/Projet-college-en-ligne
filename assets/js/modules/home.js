/**
 * Function to toggle the visibility of the menu when the mouse hovers over the menu icon.
 * The menu will appear when the mouse enters and disappear after 2 seconds.
 */
function burgerMenu() {
    // Get the menu icon element
    const icon = document.getElementById("menu-icon");
    // Get the menu element
    const menu = document.querySelector(".display-none");
    // Add event listener to the menu icon for mouse enter event
    icon.addEventListener("mouseenter", function(event) {
        // Remove the 'display-none' class to show the menu
        menu.classList.remove("display-none");
        
        // Use setTimeout to define a delay of retauring the class display-one
        setTimeout(function() {
            // Add the 'display-none' class to hide the menu after 2 seconds
            menu.classList.add("display-none"); 
        }, 2000); 
    });
}

// Export the burgerMenu function
export {burgerMenu};