<?php

/**
 * Controller responsible for handling user registration.
 */
class AuthRegisterController extends AbstractController
{
    /**
     * Displays the registration form with necessary data.
     */
    public function register(): void
    {
        // Instantiate neccessary classes managers
        $classeManager = new ClasseManager();
        $roleManager = new RoleManager();
        $languageManager = new LanguageManager();
        // Get all classes, roles, and languages from the database
        $classes = $classeManager->findAll();
        $roles = $roleManager->findAll();
        $languages = $languageManager->findAll();

        // Render the registration form with necessary data
        $this->render("register.html.twig", [
            "classes" => $classes,
            "roles" => $roles,
            "languages" => $languages
        ]);
    }
    
    
    /**
     * Displays the registrationLogin form with necessary data.
     */
    public function registerLogin(): void
    {
        // Instantiate neccessary classes managers
        $classeManager = new ClasseManager();
        $roleManager = new RoleManager();
        $languageManager = new LanguageManager();
        // Get all classes, roles, and languages from the database
        $classes = $classeManager->findAll();
        $roles = $roleManager->findAll();
        $languages = $languageManager->findAll();

        // Render the registration form with necessary data
        $this->render("registerLoginForm.html.twig", [
            "classes" => $classes,
            "roles" => $roles,
            "languages" => $languages
        ]);
    }

    /**
     * Cleans the form data retrieved from the user.
     *
     * @param array $formData The form data to clean.
     * @return array The cleaned form data.
     */
    private function cleanFormData(array $formData): array
    {
        $cleanedData = [];
        foreach ($formData as $key => $value) {
            $cleanedData[$key] = htmlspecialchars($value);
        }
        return $cleanedData;
    }

    /**
     * Sends an email to the specified recipient.
     *
     * @param string $to      The recipient's email address.
     * @param string $subject The subject of the email.
     * @param string $message The content of the email.
     * @return bool Returns true if the email was sent successfully, false otherwise.
     */
    private function sendMail(string $to, string $subject, string $message): bool
    {
        // Construct the HTML message with the link
        $htmlMessage = "
        <html>
            <head>
                <title>" . $subject . "</title>
            </head>
            <body>
                <p>" . $message . "</p>
                <p>To change your password, please click <a href='index.php?route=edit-password'>here</a>.</p>
             </body>
        </html>
        ";

        // Set additional headers
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $headers[] = 'From: angembang@gmail.com';

        // Send the email
        $success = mail($to, $subject, $htmlMessage, implode("\r\n", $headers));

        return $success;
    }

    /**
     * Validates user registration data and creates a new user account based on provided information.
     */
    public function checkRegister(): void
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $requiredFields = ["firstName", "lastName", "email", "password", "confirmPassword"];
                foreach ($requiredFields as $field) {
                    if (isset($_POST[$field])) {
                        $tokenManager = new CSRFTokenManager();
                        if (isset($_POST["csrf-token"]) || !$tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                            // Check if passwords match
                            if ($_POST["password"] === $_POST["confirmPassword"]) {
                                //Validate password against password pattern regex
                                $passwordRegex = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_=+{};:,<.>]).{8,}$/';
                                if (preg_match($passwordRegex, $_POST["password"])) {
                                    // Instantiate all users and Check if user with provided email already exists
                                    $principalManager = new PrincipalManager();
                                    $principal = $principalManager->findPrincipalByEmail($_POST["email"]);
                                    
                                    $teacherManager = new TeacherManager();
                                    $teacher = $teacherManager->findTeacherByEmail($_POST["email"]);
                                    
                                    $teacherReferentManager = new TeacherReferentManager();
                                    $teacherReferent = $teacherReferentManager->findTeacherReferentByEmail($_POST["email"]);
                                    
                                    $collegianManager = new CollegianManager();
                                    $collegian = $collegianManager->findCollegianByEmail($_POST["email"]);
                                    
                                    if(($principal === null) && 
                                    ($teacher === null) && 
                                    ($teacherReferent === null) &&
                                    ($collegian === null)) {
                                        // Check user role and proceed accordingly
                                        if (isset($_POST["idRole"])) {
                                            $idRole = $_POST["idRole"];
                                            $roleManager = new RoleManager();
                                            $role = $roleManager->getRoleById($idRole);
                                            $roleName = $role->getName();
                                        
                                            switch ($roleName) {
                                                case "Principal":
                                                    // Retrieve and sanitize input data
                                                    $cleanedFormData = $this->cleanFormData($_POST);
                                                    // Hash the password
                                                    $hashedPassword = password_hash($cleanedFormData
                                                    ["password"], PASSWORD_BCRYPT);
                                                    // Create a new objet principal with provided data
                                                    $principal = new Principal(null, $cleanedFormData
                                                    ["firstName"], $cleanedFormData["lastName"], 
                                                    $cleanedFormData["email"], $hashedPassword, $idRole);
                                                    $createdPrincipal = $principalManager->
                                                    createPrincipal($principal);
                                                    // Check if the principal is create
                                                    if($createdPrincipal) {
                                                        $this->renderJson(["success" => true, "message" => "Utilisateur enregistrer avec succes"]);
                                                    } 
                                                    $this->renderJson(["success" => false, "message" => "Une erreur s'est produite lors 
                                                    de la création de votre compte."]);
                                                    break;
                                            
                                            case "Professeur":
                                                
                                                // Retrieve and sanitize input data
                                                $cleanedFormData = $this->cleanFormData($_POST);
                                                // Hash the password
                                                $hashedPassword = password_hash
                                                ($cleanedFormData["password"], PASSWORD_BCRYPT);
                                                // Create a new objet teacher with provided data
                                                $teacher = new Teacher(null, $cleanedFormData["firstName"], 
                                                $cleanedFormData["lastName"], $cleanedFormData["email"], 
                                                $hashedPassword, $idRole);
                                                $createdTeacher = $teacherManager->createTeacher($teacher);
                                                // Check if the teacher is create
                                                if ($createdTeacher) {
                                                    // Send confirm mail to the teacher
                                                    $success = $this->sendMail($_POST["email"], 
                                                    "Création de compte Collège en ligne", 
                                                    "Bonjour, Nous venons de vous créer un compte 
                                                    sur Collège en ligne. Veuillez cliquer ici pour
                                                    changer votre mot de passe.");
                                                    // Check if the mail is send
                                                    if($success) {
                                                        $this->renderJson(["success" => true, "message" => "Message envoyé"]);
                                                    }
                                                    $this->renderJson(["success" => false, "message" => "Echec de l'envoi du mail"]);
                                                    return;
                                                    
                                                }
                                                $this->renderJson(["success" => false, "message" => "Une erreur s'est produite lors 
                                                de la création de votre compte."]);
                                                break;
                                            
                                            case "Professeur référent":
                                                // Check if the unique identifier of the classe is set
                                                if(isset($_POST["idClass"])) {
                                                    // Retrieve the provided unique identifier of the class
                                                    $idClass = $_POST["idClass"];
                                                    $classManager = new ClasseManager();
                                                    $classe = $classManager->findClasseById($idClass);
                                                    if($classe) {
                                                        // Retrieve and sanitize input data
                                                        $cleanedFormData = $this->cleanFormData($_POST);
                                                        // Hash the password
                                                        $hashedPassword = password_hash
                                                        ($cleanedFormData["password"], PASSWORD_BCRYPT);
                                                        // Create a new objet teacherReferent with provided data
                                                        $teacherReferent = new TeacherReferent(null, $cleanedFormData["firstName"], 
                                                        $cleanedFormData["lastName"], $cleanedFormData["email"], 
                                                        $hashedPassword, $idClass, $idRole);
                                                        $createdTeacherReferent = $teacherReferentManager->
                                                        createTeacherReferent($teacherReferent);
                                                        // Check if the teacherReferent is create
                                                        if($createdTeacherReferent) {
                                                            // Send confirm mail to the teacher
                                                            $success = $this->sendMail($_POST["email"], 
                                                            "Création de compte Collège en ligne", 
                                                            "Bonjour, Nous venons de vous créer un compte 
                                                            sur Collège en ligne. Veuillez cliquer ici pour
                                                            changer votre mot de passe.");
                                                            // Check if the mail is send
                                                            if($success) {
                                                                $this->renderJson(["success" => true, "message" => "Message envoyé"]);
                                                                return;    
                                                            }
                                                            $this->renderJson(["success" => false, "message" => "Echec de l'envoi du mail"]);
                                                            return;
                                                        }
                                                        $this->renderJson(["success" => false, "message" => "Une erreur s'est produite lors de la création de votre compte."]);
                                                        return;
                                                    }
                                                    
                                                }
                                                $this->renderJson(["success" => false, "message" => "Une erreur s'est produite lors de la création de votre compte."]);
                                                break;
                                            
                                            case "Collégien":
                                                // Check if the unique identifier of the classe is set
                                                if(isset($_POST["idClass"])) {
                                                    // Retrieve the provided unique identifier of the class
                                                    $idClass = $_POST["idClass"];
                                                    // Check if the classe exist 
                                                    $classManager = new ClasseManager();
                                                    $classe = $classManager->findClasseById($idClass);
                                                    if($classe) {
                                                        // Retreive the level of the classe
                                                        $classLevel = $classe->getLevel();
                                                        // Check if the classe level is found
                                                        if($classLevel) {
                                                            // Check if the class level is egal to 6ème
                                                            if($classLevel === "6ème") {
                                                                // Retrieve and sanitize input data
                                                                $cleanedFormData = $this->cleanFormData($_POST);
                                                                // Hash the password
                                                                $hashedPassword = password_hash
                                                                ($cleanedFormData["password"], PASSWORD_BCRYPT);
                                                                // Create a new collegian with provided data
                                                                $collegian = new Collegian(null, $cleanedFormData
                                                                ["firstName"], $cleanedFormData["lastName"], $cleanedFormData
                                                                ["email"], $hashedPassword, $idClass, null, $idRole);
                
                                                                $col= $collegianManager->createCollegian($collegian);
                                                                // Check if the collegian is create to the database
                                                                if($col) {
                                                                    // Send confirm mail to the teacher
                                                                    $success = $this->sendMail($_POST["email"], 
                                                                    "Création de compte Collège en ligne", 
                                                                    "Bonjour, Nous venons de vous créer un compte 
                                                                    sur Collège en ligne. Veuillez cliquer ici pour
                                                                    changer votre mot de passe.");
                                                                    // Check if the mail is send
                                                                    if($success) {
                                                                        $this->renderJson(["success" => true, "message" => "Message envoyé."]);
                                                                        return;
                                                                        
                                                                    }
                                                                    $this->renderJson(["success" => false, "message" => "Echec de l'envoi du mail"]);
                                                                    return;
                                                                }
                                                                $this->renderJson(["success" => false, "message" => "Echec lors de la création de compte"]);
                                                                return;
                                                                
                                                            } 
                                                            // Check if the language unique identifier is set
                                                            if(isset($_POST["idLanguage"])) {
                                                                // Retrieve the provided language identifier
                                                                $idLanguage = $_POST["idLanguage"];
                                                                // Check if the language exist
                                                                $languageManager = new LanguageManager();
                                                                $language = $languageManager->findNanguageById($idLanguage);
                                                                // Check if the object language is found
                                                                if($language) {
                                                                    // Retrieve and sanitize input data
                                                                    $cleanedFormData = $this->cleanFormData($_POST);
                                                                    // Hash the password
                                                                    $hashedPassword = password_hash
                                                                    ($cleanedFormData["password"], PASSWORD_BCRYPT);
                                                                    // Create a new collegian with provided data
                                                                    $collegian = new Collegian(null, $cleanedFormData["firstName"], 
                                                                    $cleanedFormData["lastName"], $cleanedFormData["email"], 
                                                                    $hashedPassword, $idClass, $idLanguage, $idRole);
                                                            
                                                                    $col= $collegianManager->createCollegian($collegian);
                                                                    // Send confirm mail to the teacher
                                                                    $success = $this->sendMail($_POST["email"], 
                                                                    "Création de compte Collège en ligne", 
                                                                    "Bonjour, Nous venons de vous créer un compte 
                                                                    sur Collège en ligne. Veuillez cliquer ici pour
                                                                    changer votre mot de passe.");
                                                                    // Check if the mail is send
                                                                    if($success) {
                                                                        $this->renderJson(["success" => true, "message" => "Message envoyé"]);
                                                                        return;
                                                                                
                                                                    }
                                                                    $this->renderJson(["success" => false, "message" => "Echec de l'envoi du mail"]);
                                                                    return;
                                                                    
                                                                }
                                                                $this->renderJson(["success" => false, "message" => "Langue non trouvée"]);
                                                                return;
                                                              
                                                            }
                                                            $this->renderJson(["success" => false, "message" => "Veuillez sélectionner une langue"]);
                                                            return;
                                                           
                                                        } 
                                                        $this->renderJson(["success" => false, "message" => "Nom de la classe non trouvé"]);
                                                        return;
                                                        
                                                    }
                                                    $this->renderJson(["success" => false, "message" => "la classe sélectionnée n'existe pas"]);
                                                    return;
                                            
                                                }
                                                $this->renderJson(["success" => false, "message" => "Veuillez sélectionner la classe"]);
                                                break;
                                            
                                            default:
                                                $this->renderJson(["success" => false, "message" => "Le rôle non trouvé"]);
                                                return;
                                                
                                        }
                                    }
                                    $this->renderJson(["success" => false, "message" => "Veuillez sélectionner le rôle"]);
                                    return;
                                   
                                }
                                $this->renderJson(["success" => false, "message" => "L'utilisateur existe dejà"]);
                                return;
                                
                            } 
                            $this->renderJson(["success" => false, "message" => "Le mot de passe doit contenir au 
                            moins 8 caractères, un chiffre, une lettre en majuscule, 
                            une lettre en minuscule et un caractère spécial."]);
                            return;
                            
                        }
                        $this->renderJson(["success" => false, "message" => "Les mots de passe ne correspondent pas"]);
                        return;
                        
                    }
                    $this->renderJson(["success" => false, "message" => "Jeton CSRF invalide"]);
                    return;
                   
                }
                $this->renderJson(["success" => false, "message" => "Veuillez remplir tous les champs"]);
                return;
                
            }    
        }
        $this->renderJson(["success" => false, "message" => "Le formulaire n'est pas soumis par la méthode POST"]);
        return;
        
        }
        catch (Exception $e) {
            $this->renderJson(["success" => false, "message" => $e->getMessage()]);
            
        }
    }

    /**
     * Renders the registration form with necessary data.
     */
    private function renderRegistrationForm()
    {
        // Instantiate neccessary classes managers
        $classeManager = new ClasseManager();
        $roleManager = new RoleManager();
        $languageManager = new LanguageManager();
        // Get all classes, roles, and languages from the database
        $classes = $classeManager->findAll();
        $roles = $roleManager->findAll();
        $languages = $languageManager->findAll();
        
        $classes = $classeManager->findAll();
        $roles = $roleManager->findAll();
        $languages = $languageManager->findAll();

        // Render the registration form with necessary data
        $this->render("register.html.twig", [
            "classes" => $classes,
            "roles" => $roles,
            "languages" => $languages
        ]);
    
    }
}