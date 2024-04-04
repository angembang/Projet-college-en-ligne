<?php

class AuthLoginController extends AbstractController
{
    /**
     * Displays the registration form with required data.
     */
    public function login() : void
    {
        // Render the registration form
        $this->render("login.html.twig", []);
    }
    
    
    /**
     * Validates user credentials and performs login if successful.
     */
    public function checkLogin() : void
    {
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            // Check if required fields are set
            if(isset($_POST["email"]) && isset($_POST["password"])) {
                
                /*
                 * Validate the CSRF token
                 * Instantiate CSRF Token manager
                 * Check the validity of the token
                 */
                $tokenManager = new CSRFTokenManager();
                if(isset($_POST["csrf-token"]) && $tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                    
                    // Retrieve the form provided data
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    
                    /*
                     * Check to found the user by his email
                     * Instantiate all type of users
                     * Call the function findByEmail for verifing if the user is found
                     * check if the user exist
                     */
                    $principalManager = new PrincipalManager();
                    $teacherReferentManager = new TeacherReferentManager();
                    $teacherManager = new TeacherManager();
                    $collegianManager = new CollegianManager();
                    
                    $principal = $principalManager->findPrincipalByEmail($email);
                    $teacherReferent = $teacherReferentManager->findTeacherReferentByEmail($email);
                    $teacher = $teacherManager->findTeacherByEmail($email);
                    $collegian = $collegianManager->findCollegianByEmail($email);
                    
                    // Check if any user is found
                    if($principal || $teacherReferent || $teacher || $collegian) {
                        // Get the password of the user retrieve into the database
                        $user = $principal ?? $teacherReferent ?? $teacher ?? $collegian;
                        $userPassword = $user->getPassword();
                        
                        // Check if password match with the password provided into the form
                        if(password_verify($password, $userPassword)) {
                            /*
                             * Retreive the role of the user
                             * Get role by his unique identifier
                             * Get the name of the role
                             */
                            $userRoleId = $user->getIdRole();
                            $roleManager = new RoleManager();
                            $role = $roleManager->getRoleById($userRoleId);
                            $roleName = $role->getName();
                            
                            // Redirection based on the user's role
                            switch ($roleName) {
                                case "Principal":
                                    $_SESSION["user"] = $principal->getId();
                                    $this->renderJson(["success" => true, "role" => "Principal"]);
                                    break;
                                
                                case "Professeur référent":
                                
                                case "Professeur":
                                     $this->renderJson(["success" => true, "role" => "Professeur"]);
                                    break;
                                
                                case "Collégien":
                                    // Get the class id of the collegian
                                    $collegianClassId = $collegian->getIdClass();
                                    $_SESSION["user"] = $collegian->getId();
                                    $_SESSION['classId'] = $collegianClassId;
                                    $_SESSION["role"] = "Collégien";
                                    $this->renderJson(["success" => true, "role" => "Collégien", "classId" => $collegianClassId]);
                                    break;
                                
                                default:
                                    // no role correspond
                                    $this->renderJson(["error" => "Rôle non trouvé"]);
                                    break;
                            }
                            
                        } else {
                            // Password not match | Show error message
                            $this->renderJson(["error" => "Mot de passe incorrect"]);
                            
                            
                        }
                    
                        
                    } else {
                        // user not found | Show error message
                        $this->renderJson(["error" => "Pas de compte avec cet email"]);
                    }
                    
                } else {
                    // INvalid CSRF token | Show error message
                    $_SESSION["error-message"] = "Jeton CSRF invalide";
                    $this->render("login.html.twig", []);
                } 
                
                
            } else {
                // Missing informations
                $this->renderJson(["error" => "Veuillez renseigner tous les champs"]);
                
            }
             
        
         } else {
             // Form not submitted via POST method
             $_SESSION["error-message"] = "Le formulaire n'est pas soumis par la méthode POST";
            $this->render("login.html.twig", []);
         }
         
     }
                
    
    
    /**
     * Logs out the user.
     */
    public function logout() : void
    {
        // Destroy session 
        session_destroy();
        // Render home page
        $this->render("home.html.twig");
   }
}
