// Function to update the countdown timer
function updateCountdown(link, remainTimeContent) {
    // Check if the remaining time is less than or equal to zero
    if (remainTimeContent <= 0) {
        // If the remaining time is exhausted, update the link text to indicate "Accéder au cours"
        link.textContent = "Accéder";
        // Exit the function as time has elapsed
        return;
    }
    // Calculate hours, minutes, and seconds from the remaining time in seconds
    const hours = Math.floor(remainTimeContent / 3600);
    const minutes = Math.floor((remainTimeContent % 3600) / 60);
    const seconds = remainTimeContent % 60;

    // Format the remaining time as hours:minutes:seconds
    const formattedTime = (hours < 10 ? '0' : '') + hours + ' : ' +
    (minutes < 10 ? '0' : '') + minutes + ' : ' +
    (seconds < 10 ? '0' : '') + seconds;

    // Display the remaining time in the link
    link.textContent = formattedTime;
    link.style.color = "red";
    link.style.background = "white";
}

// Function to start the countdown timer
function startCountdown(link, remainTimeContent) {
    // Update the countdown timer every second
    const countdownInterval = setInterval(function() {
        // Decrement the remaining time by 1 second on each iteration
        remainTimeContent--;

        // Update the countdown timer with the new remaining time
        updateCountdown(link, remainTimeContent);

        // If the remaining time has elapsed, stop the countdown by clearing the interval
        if (remainTimeContent <= 0) {
            clearInterval(countdownInterval);
        }
    }, 1000);
}


// Function to update the remaining time for each lesson link
function updateRemainingTime() {
    // Select all lesson links
    const lessonLinks = document.querySelectorAll("a.lesson-link");

    // Loop through each link
    lessonLinks.forEach(link => {
        // Retrieve the remaining time from the data attribute
        const remainingTimeText = link.dataset.remainingTime;
        
        // If the remaining time is "Accéder au cours", do nothing
        if (remainingTimeText === "Accéder") {
            // Make the link clickable
            link.style.pointerEvents = "auto";
            // Exit the function as time has elapsed
            return;
        }
        
        // Split the remaining time string into hours, minutes, and seconds
        const [hoursStr, minutesStr, secondsStr] = remainingTimeText.split(' ');
        
        // Parse the hours, minutes, and seconds into integers
        const remainingHours = parseInt(hoursStr);
        const remainingMinutes = parseInt(minutesStr);
        const remainingSeconds = parseInt(secondsStr);
        
        // Calculate the total remaining seconds
        const totalRemainingSeconds = remainingHours * 3600 + remainingMinutes * 60 + remainingSeconds;
        
        // If the total remaining seconds is less than or equal to 0, update the link text to "Accéder au cours"
        if (totalRemainingSeconds <= 0) {
            link.textContent = "Accéder";
            return;
        }
        
        // Disable the link if the remaining time is not yet reached
        link.style.pointerEvents = "none";

        // Start countdown for the remaining time
        startCountdown(link, totalRemainingSeconds);
    });
}
// Export the function to make it accessible from other modules
export { updateRemainingTime };