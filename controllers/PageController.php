<?php

/**
 * Class PageController
 *
 * Controller class for managing page-related actions.
 */
class PageController extends AbstractController
{
     /**
     * Renders the home page.
     *
     * This method is responsible for rendering the home page view.
     *
     * @return void
     */
    public function home(): void
    {
        // Retrieve all roles from the database
        $roleManager = new RoleManager();
        $roles = $roleManager->findAllSortedByName();
        
        // Pass the roles to the view
        $this->render("home.html.twig", ['roles' => $roles]);
        
    }
    
    
    /**
     * Renders the principal home page.
     *
     * This method is responsible for rendering the principal home page view.
     *
     * @return void
     */
    public function homePrincipal(): void
    {
        $this->render("superAdminHome.html.twig", []);
        
    }
    
    
    /**
     * Renders the about page.
     *
     * This method is responsible for rendering the about page view.
     *
     * @return void
     */
    public function about(): void
    {
        $this->render("about.html.twig", []);
    }
    
    
    /**
     * Renders the teacher referent home page.
     *
     * This method is responsible for rendering the teacher referent home page view.
     *
     * @return void
     */
    public function homeTeacherReferent(): void {
        $this->render("adminHome.html.twig", []);   
    }
    
    
    /**
     * Renders the teacher home page.
     *
     * This method is responsible for rendering the teacher home page view.
     *
     * @return void
     */
    public function homeTeacher(): void {
        $this->render("subAdmin.html.twig", []);    
    }
    
    
    
    /**
     * Displays all teachers.
     *
     * Retrieves all teachers and teachers referents from the database,
     * then renders the teacher view with the retrieved data.
     *
     * @return void
     */
    public function showAllTeachers(): void
    {
        $teacherManager = new TeacherManager();
        $teacherReferentManager = new TeacherReferentManager();
        
        $teachers = $teacherManager->findAll();
        $teachersReferents = $teacherReferentManager->findAll();
        
        $this->render("teacher.html.twig", [
            "teachers" => $teachers,
            "teachersReferents" => $teachersReferents
            ]);
    }
    
    
    /**
     * Show all collegians of the class
     * 
     * This method fetches and displays all collegians associated with the class 
     * of the logged-in referent teacher.
     *
     * @return void
     */
    public function showAllcollegiansOfTheClass(): void 
    {
        // Start the session if it is not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Check if the user is logged in and is a referent teacher
        if (isset($_SESSION["user"]) && isset($_SESSION["role"]) && $_SESSION["role"] === "Professeur référent") {
            $idTeacherReferent = (int) $_SESSION["user"];
            // Instantiate the TeacherReferentManager and find the referent teacher by their identifier
            $teacherReferentManager = new TeacherReferentManager();
            $teacherReferent = $teacherReferentManager->findTeacherReferentById($idTeacherReferent);

            // Check if the referent teacher is found
            if ($teacherReferent) {
                // Get the class identifier associated with the referent teacher
                $teacherReferentClassId = $teacherReferent->getIdClass();
                // Check if the referent teacher has an assigned class
                if ($teacherReferentClassId) {
                    // Instantiate the CollegianManager and find collegians by the class identifier
                    $collegianManager = new CollegianManager();
                    $collegians = $collegianManager->findCollegiansByClassId($teacherReferentClassId);

                    // Check if any collegians are found
                    if ($collegians) {
                        $collegiansData = [];
                        $classeManager = new ClasseManager();
                        // Get the class name (level) for display purposes
                        $className = $classeManager->findClasseById($teacherReferentClassId)->getLevel();

                        // Prepare collegian data for rendering
                        foreach ($collegians as $collegian) {
                            $collegiansData[] = [
                                "id" => $collegian->getId(),
                                "firstName" => $collegian->getFirstName(),
                                "lastName" => $collegian->getLastName(),
                                "className" => $className
                            ];
                        }
                        // Render the collegians view with the data
                        $this->render("collegian.html.twig", [
                            "collegians" => $collegiansData,
                            "className" => $className
                        ]);
                    } else {
                        // No collegians found for the class
                        $this->renderJson(["error" => "Aucun collégien trouvé pour cette classe."]);
                    }
                } else {
                    // The referent teacher has no assigned class
                    $this->renderJson(["error" => "Ce professeur référent n'a pas de classe assignée."]);
                }
            } else {
                // Referent teacher not found
                $this->renderJson(["error" => "Professeur référent non trouvé."]);
            }
        } else {
            // User not authenticated or incorrect role
            $this->renderJson(["error" => "Utilisateur non authentifié ou rôle incorrect."]);
        }
    }
    
    
    /*
     * Delete a collegian
     * 
     * This method deletes a collegian identified by their ID from the platform.
     * It expects the ID to be passed as a GET parameter.
     *
     * @return void
     */
    public function deleteCollegian(): void 
    {
        // Check if the request method is GET and the collegian ID is set in the GET parameters
        if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
            // Retrieve the collegian ID from the GET parameters and cast it to an integer
            $collegianId = (int) $_GET["id"];
            // Instantiate the CollegianManager
            $collegianManager = new CollegianManager();
            // Call the method to delete the collegian by their ID
            $collegianManager->deleteCollegianById($collegianId);
        
            // Redirect to the collegian listing page after deletion
            header("Location: index.php?route=collegian");
            exit();
        }
    }
    
    
    /*
     * Edit a collegian
     * 
     * This method handles both displaying the edit form for a collegian and updating 
     * the collegian's information. It uses GET to display the form and POST to update the information.
     *
     * @return void
     */
    public function editCollegian(): void 
    {
        // Check if the request method is GET and the collegian ID is set in the GET parameters
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["id"])) {
            // Retrieve the collegian ID from the GET parameters and cast it to an integer
            $collegianId = (int) $_GET["id"];
            // Instantiate the CollegianManager
            $collegianManager = new CollegianManager();
            // Find the collegian by their ID
            $collegian = $collegianManager->findCollegianById($collegianId);
            // Render the edit form with the collegian's data
            $this->render("editCollegian.html.twig", [
                "collegian" => $collegian
            ]);
        } 
        // Check if the request method is POST and the collegian ID is set in the POST parameters
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["id"])) {
            // Retrieve the collegian ID from the POST parameters and cast it to an integer
            $collegianId = (int) $_POST["id"];
            // Instantiate the CollegianManager
            $collegianManager = new CollegianManager();
            // Create a new Collegian object with the updated information from the POST parameters
            $collegian = new Collegian(
                $collegianId,
                $_POST["firstName"],
                $_POST["lastName"],
                $_POST["email"],
                $_POST["password"],
                $_POST["classId"],
                $_POST["idLanguage"],
                $_POST["idRole"]
            );
            // Update the collegian in the database
            $collegianManager->updateCollegian($collegian);
        
            // Redirect to the collegian listing page after updating
            header("Location: index.php?route=collegian");
            exit();
        }
        
    }
}