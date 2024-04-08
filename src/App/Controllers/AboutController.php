<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;

class AboutController
{
    public function __construct(private TemplateEngine $view)
    {
    }

    public function about()
    {
        $this->view->render('/About.php', [
            'danger' => escape("<h1>test</h1>")
        ]);
    }
}
