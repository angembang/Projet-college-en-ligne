<?php

/**
 * Abstract class defining a base controller for managing Twig views.
 */
abstract class AbstractController
{
    /** @var \Twig\Environment $twig The instance of Twig environment. */
    private \Twig\Environment $twig;
    
    /**
     * Constructor of the AbstractController class.
     * Initialize the Twig environment with default parameters.
     */
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader, [
            "debug" => true
        ]);
        
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        $this->twig = $twig;
    }
    
    /**
     * Render the Twig template with the provided data.
     *
     * @param string $template The name of the template to render.
     * @param array $data The data to pass to the template.
     * @return void
     */
    protected function render(string $template, array $data): void
    {
        echo $this->twig->render($template, $data);
    }
}