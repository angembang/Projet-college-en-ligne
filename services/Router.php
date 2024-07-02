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
     * 
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
        
        // Check if a route is provided
        if(isset($get["route"])) {
            
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
                    
                case "register-login":
                    $authRegisterController->registerLogin(); 
                    break;
                    
                case "edit-password":
                    $authLoginController->editLogin();
                    break;
                    
                case "super-admin":
                    $pageController->homePrincipal();
                    break;
                    
                case "teacher":
                    $pageController->showAllTeachers();
                    break;
                    
                case "collegian":
                    $pageController->showAllcollegiansOfTheClass();
                    break;
                    
                case "delete-collegian":
                    $pageController->deleteCollegian();
                    break;
                    
                case "edit-collegian":
                    $pageController->updateCollegian();
                    break;
                    
                case "checkUpdateCollegian":
                    $pageController->checkUpdateCollegian();
                    break;
                    
                case "delete-teacher":
                    $pageController->deleteTeacher();
                    break;
                    
                case "update-teacher":
                    $pageController->updateTeacher();
                    break;
                    
                case "checkUpdateTeacher":
                    $pageController->checkUpdateTeacher();
                    break;
                    
                case "delete-teacher-referent":
                    $pageController->deleteTeacherReferent();
                    break;
                    
                case "edit-teacher-referent":
                    $pageController->editTeacherReferent();
                    break;
                    
                    
                case "admin":
                    $pageController->homeTeacherReferent();
                    break;
                    
                case "sub-admin":
                    $pageController->homeTeacher();
                    break;
                    
                case "teacher-courses":
                    // Check if lesson_id is provided
                    if (isset($get["lesson_id"])) {
                        $lessonController->showTeacherCoursesByLessonId((int)$get["lesson_id"]);
                    } else {
                        // Redirect to error page if lesson_id is not provided
                        $pageController->error(); 
                    }
                    break;
                    
                case "cours":
                    // Check if lesson_id is provided
                    if (isset($get["lesson_id"])) {
                        $lessonController->showCoursesByIdLesson((int)$get["lesson_id"]);
                    } else {
                        // Redirect to error page if lesson_id is not provided
                        $pageController->error();
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
                    
                case "update-course":
                    $lessonController->updateCourse();
                    break;
                    
                case "delete-course":
                    $lessonController->deleteCourse();
                    break;
                    
                case "checkUpdateCourse":
                    $lessonController->checkUpdateCourse();
                    break;
                    
                case "upload":
                    $lessonController->upload();
                    break;
                    
                case "check-addCourse":
                    $lessonController->checkAddCourse();
                    break;
                    
                case "search-course":
                    $lessonController->searchCoursesByLessonName();
                    break;
                    
                case "search-course-keyword":
                    $lessonController->searchCoursesByKeyword();
                    break;
                    
                case "classe-lessons":
                    $lessonController->getAllLessonsByLoggedInCollegianClassId();    
                    break;
                    
                case "about":
                    $pageController->about();    
                    break;
                    
                case "legacy-policy":
                    $pageController->legacyPolicy();    
                    break;
                    
                case "error":
                    $pageController->error();    
                    break;
                    
                case "logout":
                    $authLoginController->logout();    
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