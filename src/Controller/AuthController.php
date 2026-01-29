<?php
declare(strict_types=1);

namespace eurokeep\Controller;

/**
 * Auth Controller.
 *
 * I handle logging in and out.
 *
 */
class AuthController extends AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->Authentication->allowUnauthenticated(['login']);
    }

    /**
     * I handle the login.
     */
    public function login(): void {
        $result = $this->Authentication->getResult();

        $errors = [];
        if($result && $result->isValid()) {
            $user = $result->getData();
            $this->set('success', true);
            $this->set('user', $user);
        } else {
            $this->set('success', false);
            $errors[] = 'User authentication failed';
        }

        $this->set('errors', $errors);
        $this->viewBuilder()->setOption('serialize', ['user', 'success', 'errors']);
    }

    /**
     * I handle the logout.
     */
    public function logout(): void {
        $this->Authentication->logout();
        header("Location: /a/login");
        exit(302);
    }
}
