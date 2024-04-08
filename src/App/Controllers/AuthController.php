<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\UserService;
use Framework\TemplateEngine;
use App\Services\ValidatorService;

class AuthController
{
    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private UserService $userService
    ) {
    }

    public function registerView()
    {
        $this->view->render('register.php');
    }

    public function register()
    {
        $this->validatorService->validateRegister($_POST);

        $this->userService->isEmailExist($_POST['email']);

        $this->userService->create($_POST);

        redirectTo('/');
    }

    public function loginView()
    {
        $this->view->render('login.php');
    }

    public function login()
    {
        $this->validatorService->validateLogin($_POST);

        $this->userService->login($_POST);

        redirectTo('/');
    }

    public function logout()
    {
        $this->userService->logout();

        redirectTo('/login');
    }
}
