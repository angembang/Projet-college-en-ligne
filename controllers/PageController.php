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
        $this->render("home.html.twig", []);
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