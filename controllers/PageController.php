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
    public function homeTeacher(): void 
    {
        // Start the session if it is not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Check if the user is logged in and is a teacher
        if (isset($_SESSION["user"]) && 
        ($_SESSION["role"] === "Professeur")) {
            $teacherId = (int) $_SESSION["user"];
        
            // Instantiate the TeacherManager and the teacher referent
            $teacherManager = new TeacherManager();
        
            // Find the teacher by its ID
            $teacher = $teacherManager->findTeacherById($teacherId);

            // Check if the teacher or teacher is found
            if ($teacher) {
            $idTeacher = $teacher->getId();
            
            // Instantiate the LessonManager and find lessons by the teacher's ID
            $lessonManager = new LessonManager();
            $lessons = $lessonManager->findLessonsByIdTeacher($idTeacher);

            // Check if any lessons are found
            if (!empty($lessons)) {
                $classesData = [];

                foreach ($lessons as $lesson) {
                    $lessonId = $lesson->getId();
                    $lessonName = $lesson->getName();
                    $lessonIdClass = $lesson->getIdClass();

                    // Instantiate the ClasseManager and find the class by its ID
                    $classeManager = new ClasseManager();
                    $class = $classeManager->findClasseById($lessonIdClass);
                    
                    if ($class) {
                        $classLevel = $class->getLevel();

                        // Prepare lesson data for rendering
                        $lessonData = [
                            "lessonName" => $lessonName,
                            "lessonId" => $lessonId
                        ];

                        if (!isset($classesData[$class->getId()])) {
                            $classesData[$class->getId()] = [
                                "classLevel" => $classLevel,
                                "lessons" => []
                            ];
                        }

                        $classesData[$class->getId()]["lessons"][] = $lessonData;
                    }
                }

                // Render the subAdminHome view with the data
                $this->render("subAdmin.html.twig", [
                    "classes" => $classesData
                ]);
            } else {
                // No lessons found for the teacher
                $this->renderJson(["error" => "Aucune leçon trouvée pour ce professeur."]);
            }
        } else {
            // Teacher not found
            $this->renderJson(["error" => "Professeur non trouvé."]);
        }
    } else {
        // User not authenticated or incorrect role
        $this->renderJson(["error" => "Utilisateur non authentifié ou rôle incorrect."]);
    }
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
        // Debug: Log session data for debugging
        error_log(print_r($_SESSION, true));
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
    public function updateCollegian(): void 
    {
        // Check if the request method is GET and the collegian ID is set in the GET parameters
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["id"])) {
            // Retrieve the collegian ID from the GET parameters and cast it to an integer
            $collegianId = (int) $_GET["id"];
            // Instantiate the CollegianManager
            $collegianManager = new CollegianManager();
            // Find the collegian by their ID
            $collegian = $collegianManager->findCollegianById($collegianId);
            // Instantiate neccessary classes managers
            $classeManager = new ClasseManager();
            $roleManager = new RoleManager();
            $languageManager = new LanguageManager();
            // Get all classes, roles, and languages from the database
            $classes = $classeManager->findAll();
            $roles = $roleManager->findAll();
            $languages = $languageManager->findAll();

            // Render the registration form with necessary data
            $this->render("editCollegian.html.twig", [
                "classes" => $classes,
                "roles" => $roles,
                "languages" => $languages,
                "collegian" => $collegian
            ]);
        }
    }
    
    
    /**
     * Updates collegian information based on POST request data.
     * Validates and sanitizes input data, checks for the existence of the collegian in the database,
     * and updates the collegian's information if validation passes. 
     */
    public function checkUpdateCollegian(): void 
    {
        // Check if the request method is POST
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Ensure all required fields are present
            if (isset($_POST["id"], $_POST["firstName"], $_POST["lastName"], $_POST["email"], $_POST["password"], $_POST["confirmPassword"], $_POST["idClass"], $_POST["idRole"])) {
                // Check if the ID is an integer
                $collegianId = filter_var($_POST["id"], FILTER_VALIDATE_INT);
                if (!$collegianId) {
                    $this->renderJson(["success" => false, "message" => "Identifiant invalide."]);
                    return;
                }

                // Retrieve the collegian from the database to check existence
                $collegianManager = new CollegianManager();
                $existingCollegian = $collegianManager->findCollegianById($collegianId);
                if (!$existingCollegian) {
                    $this->renderJson(["success" => false, "message" => "Collégien non trouvé."]);
                    return;
                }

                // Verify that the provided passwords match.
                if ($_POST["password"] !== $_POST["confirmPassword"]) {
                    $this->renderJson(["success" => false, "message" => "Passwords do not match."]);
                    return;
                }

                // Validate the email address format.
                if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    $this->renderJson(["success" => false, "message" => "Invalid email address."]);
                    return;
                }

                // Check the strength of the provided password against the defined regex pattern.
                $passwordRegex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_=+{};:,<.>]).{8,}$/';
                if (!preg_match($passwordRegex, $_POST["password"])) {
                    $this->renderJson(["success" => false, "message" => "Password must contain at least 8 characters, including a digit, an uppercase letter, a lowercase letter, and a special character."]);
                    return;
                }

                // Only hash the password if it is new to avoid unnecessary processing.
                $hashedPassword = password_verify($_POST["password"], $existingCollegian->getPassword()) ? 
                $existingCollegian->getPassword() : 
                    password_hash($_POST["password"], PASSWORD_BCRYPT);

                // Retrieve and check the class details from the database.
                $classManager = new ClasseManager();
                $class = $classManager->findClasseById($_POST["idClass"]);
                if (!$class) {
                    $this->renderJson(["success" => false, "message" => "Class not found."]);
                    return;
                }

                // Determine if the idLanguage should be updated based on the class level.
                $idLanguage = null;
                if ($class->getLevel() !== "6ème") {
                    $idLanguage = isset($_POST["idLanguage"]) ? htmlspecialchars($_POST["idLanguage"]) : null;
                }

                // Update the collegian with sanitized and validated input.
                $updatedCollegian = new Collegian(
                    $collegianId,
                    htmlspecialchars($_POST["firstName"]),
                    htmlspecialchars($_POST["lastName"]),
                    htmlspecialchars($_POST["email"]),
                    $hashedPassword,
                    htmlspecialchars($_POST["idClass"]),
                    $idLanguage,
                    htmlspecialchars($_POST["idRole"])
                );

                // Perform the update in the database and check the result.
                if ($collegianManager->updateCollegian($updatedCollegian)) {
                    $this->renderJson(["success" => true, "message" => "Collegian update successful."]);
                } else {
                    $this->renderJson(["success" => false, "message" => "Failed to update collegian."]);
                }
            } else {
                $this->renderJson(["success" => false, "message" => "All fields are required."]);
            }
        } else {
            $this->renderJson(["success" => false, "message" => "Form must be submitted via POST method."]);
        }
    }
        
        
    
    
    
    /**
     * Display the update teacher form with necessary data
     * 
     */
    public function updateTeacher(): void 
    {
        // Check if the request method is GET and the teacher ID is set in the GET parameters
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["id"])) {
            // Retrieve the teacher ID from the GET parameters and cast it to an integer
            $teacherId = (int) $_GET["id"];
            // Instantiate the TeacherManager
            $teacherManager = new TeacherManager();
            // Find the teacher by its identifier
            $teacher = $teacherManager->findTeacherById($teacherId);
            // Render the edit form with the teacher's data
            $this->render("editTeacher.html.twig", [
                "teacher" => $teacher
            ]);
        } 
        
    }
    
    
    /**
     * Updates teacher information based on POST request data.
     * Validates and sanitizes input data, checks for the existence of the teacher in the database,
     * and updates the teacher's information if validation passes.  
     */
    public function checkUpdateTeacher(): void 
    {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ensure all required fields are present
            if (isset($_POST["id"], $_POST["firstName"], $_POST["lastName"], $_POST["email"], $_POST["password"], $_POST["confirmPassword"], $_POST["idRole"])) {
                // Validate the teacher ID
                $teacherId = filter_var($_POST["id"], FILTER_VALIDATE_INT);
                if (!$teacherId) {
                    $this->renderJson(["success" => false, "message" => "Identifiant invalide."]);
                    return;
                }

                // Instantiate the TeacherManager
                $teacherManager = new TeacherManager();
                // Check if the teacher exists
                $existingTeacher = $teacherManager->findTeacherById($teacherId);
                if (!$existingTeacher) {
                    $this->renderJson(["success" => false, "message" => "Professeur non trouvé."]);
                    return;
                }

                // Check if passwords match
                if ($_POST["password"] !== $_POST["confirmPassword"]) {
                    $this->renderJson(["success" => false, "message" => "Les mots de passe ne correspondent pas."]);
                    return;
                }

                // Validate the email address
                if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                    $this->renderJson(["success" => false, "message" => "Adresse email invalide."]);
                    return;
                }

                // Password strength check
                $passwordRegex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_=+{};:,<.>]).{8,}$/';
                if (!preg_match($passwordRegex, $_POST["password"])) {
                    $this->renderJson(["success" => false, "message" => "Le mot de passe doit contenir au moins 8 caractères, un chiffre, une lettre en majuscule, une lettre en minuscule et un caractère spécial."]);
                    return;
                }

                // Hash the password if it's new
                $hashedPassword = password_verify($_POST["password"], $existingTeacher->getPassword()) ? 
                $existingTeacher->getPassword() : // Use old password if unchanged 
                password_hash($_POST["password"], PASSWORD_BCRYPT); // Otherwise, hash new

                // Update the teacher with sanitized input
                $teacher = new Teacher(
                    $teacherId,
                    htmlspecialchars($_POST["firstName"]),
                    htmlspecialchars($_POST["lastName"]),
                    htmlspecialchars($_POST["email"]),
                    $hashedPassword,
                    htmlspecialchars($_POST["idRole"])
                );

                // Perform the update
                if ($teacherManager->updateTeacher($teacher)) {
                    $this->renderJson(["success" => true, "message" => "Mise à jour du professeur réussie"]);
                } else {
                    $this->renderJson(["success" => false, "message" => "Échec lors de la mise à jour du professeur."]);
                }
            } else {
                $this->renderJson(["success" => false, "message" => "Tous les champs sont requis."]);
            }
        } else {
            $this->renderJson(["success" => false, "message" => "Le formulaire doit être soumis par méthode POST."]);
        }
    }
    
    
    /**
     * Display error page
     * 
     * @return void
     * 
     */
    public function error(): void 
    {
        $this->render("error.html.twig", []);
    }
    
    
    /**
     * Display the legacy policy page
     * 
     */ 
    public function legacyPolicy() {
        $this->render("legacyPolicy.html.twig", []);
    }
}