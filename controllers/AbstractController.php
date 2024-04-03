<?php

abstract class AbstractController
{
    private \Twig\Environment $twig;
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader,[
            'debug' => true,
        ]);

        $twig->addExtension(new \Twig\Extension\DebugExtension());
        
        $twig->addGlobal('csrf_token', $_SESSION["csrf-token"]);

        // Ajoutez les messages d'erreur à Twig
        $twig->addGlobal('error_message', isset($_SESSION["error-message"]) ? 
        $_SESSION["error-message"] : null);
        //$twig->addGlobal('user', $_SESSION["user"]);
        // Vérifie si $_SESSION["user"] est défini avant de l'ajouter à Twig
        //$twig->addGlobal('user', isset($_SESSION["user"]) ? $_SESSION["user"] : null);
        

        $this->twig = $twig;
    }

    protected function render(string $template, array $data) : void
    {
        echo $this->twig->render($template, $data);
    }
    
    protected function renderJson(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

}