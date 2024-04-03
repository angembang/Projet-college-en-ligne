<?php

/**
 * Class Router
 * Handles the routing of requests.
 */
class Router
{
    /**
     * Handles the incoming request based on the provided $_GET parameters.
     *
     * @param array $get The associative array of $_GET parameters.
     * @return void
     */
    public function handleRequest(array $get): void
    {
        // Instantiate the necessary controllers
        $lessonController = new LessonController();
        $lessonController = new LessonController(); 
        $authLoginController = new AuthLoginController();
        $pageController = new PageController();
        $authRegisterController = new AuthRegisterController();
        $showController = new ShowController();
        
        // Check if a route is provided
        if(isset($get["route"]))
        {
            // Switch statement for routing
            switch($get["route"]) {
                case "inscription":
                    $authRegisterController->register(); 
                    break;
                    
                case "checkRegister":
                    $authRegisterController->checkRegister();
                    break;
                
                case "connexion":
                    $authLoginController->login();
                    break;
                    
                case "checkLogin":
                    $authLoginController->checkLogin(); 
                    break;
                    
                case "super-admin":
                    $pageController->homePrincipal();
                    break;
                    
                case "teacher":
                    $showController->showAllTeachers();
                    break;
                    
                case "cours":
                    // Check if lesson_id is provided
                    if (isset($get["lesson_id"])) {
                        $lessonController->showCoursesByIdLesson($get["lesson_id"]);
                    } else {
                        // Redirect to error page if lesson_id is not provided
                        echo "error";
                    }
                    break;
                
                case "ajouter-lesson":
                    $lessonController->addLesson();
                    break;
                    
                case "checkAddLesson":
                    $lessonController->checkAddLesson();
                    break;
                    
                case "lesson":
                    $lessonController->lessonsListOfTheDay(); 
                    break;
                    
                case "ajouter-cours":
                    $lessonController->addCourse();
                    break;
                    
                default:
                    $pageController->home(); 
                    break;
                } 
            
            } 
            else {
                // Route is not provided/ render the home page
                $pageController->home();
                die;
        } 
    }
        
}