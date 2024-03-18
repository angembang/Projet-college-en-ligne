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
        // Instantiate the LessonController
        $lessonController = new LessonController(); 
        
        if(isset($get["route"]))
        {
            if($get["route"] === "inscription")
            {
                
            } else if($get["route"] === "connexion")
            {
                
            } else if($get["route"] === "cours")
            {
                
            } else if($get["route"] === "lessonList")
            {
                // Call the lessonList method from LessonController
                $lessonController->lessonListOfTheDay(); 
            }
        } else
        {
            // If route is not provided, redirect to home page 
            header("Location: home.html.twig"); 
            die;
        }
    }
}