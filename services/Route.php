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
     */
    public function handleRequest(array $get): void
    {
        if(isset($get["routde"]))
        {
            if($get["route"] === "inscription")
            {
                
            }
        } else
        {
            header("Location: home.html.twig");
            die;
        }
    }
}