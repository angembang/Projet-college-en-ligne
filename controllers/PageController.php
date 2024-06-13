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
}