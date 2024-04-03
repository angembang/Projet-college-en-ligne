<?php

class PageController extends AbstractController
{
    public function home(): void
    {
        $this->render("home.html.twig", []);
    }
    
    public function homePrincipal(): void
    {
        $this->render("superAdminHome.html.twig", []);
        
    }
}