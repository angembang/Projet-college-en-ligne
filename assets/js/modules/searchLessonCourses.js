function completionSearchCoursesByLessonName() {
    // Select the input element for the lesson name
    const lessonNameInput = document.querySelector('input[name="lessonName"]');
    // Select the element where suggestions will be displayed
    const suggestionsList = document.querySelector('#suggestions-list'); 
    
    lessonNameInput.addEventListener('input', function() {
        // Retrieve the lesson name entered by the user
        const lessonName = lessonNameInput.value.trim();

        if (lessonName.length >= 1) { 
            // Get the search string entered by the user
            const searchString = lessonName;

            // Perform a fetch request to retrieve the list of courses associated with the user's class
            fetch("index.php?route=classe-lessons" + searchString)
            // Send a GET request to the server to fetch the list of courses based on the search string
            .then(response => {
                // Check if the response from the server is successful
                if (!response.ok) {
                    // If the response is not OK, throw an error
                    throw new Error('Une erreur s\'est produite lors de la récupération des cours.');
                }
                // Parse the JSON response from the server
                return response.json();
            })
            // Once the JSON data is retrieved successfully
            .then(data => {
                // Extract the list of course names from the JSON data
                const courseNames = data.courseNames;

                // Get the search string entered by the user
                suggestionsList.innerHTML = '';

                // Get the search string entered by the user
                courseNames.forEach(courseName => {
                    // Create a suggestion element for each course name
                    const suggestion = document.createElement('li');
                    suggestion.textContent = courseName;
                    
                    // Add an event listener to detect click on the suggestion
                    suggestion.addEventListener('click', function() {
                        // When the user clicks on the suggestion, fill the search bar with the suggestion
                        lessonNameInput.value = courseName;
                        // Clear the suggestions list
                        suggestionsList.innerHTML = '';
                    });

                    // Add the suggestion to the suggestions list
                    suggestionsList.appendChild(suggestion);
                });
            })
            .catch(error => {
                console.error(error.message);
            });
        } else {
            // If the user hasn't entered enough characters yet, clear the suggestions list
            suggestionsList.innerHTML = '';
        }
    });
}
// export the function 
export { completionSearchCoursesByLessonName };