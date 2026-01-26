<?php
declare(strict_types=1);

namespace eurokeep\Controller;

use eurokeep\Model\Entity\User;
use eurokeep\Model\Table\UsersTable;
use Cake\Http\Session;
use Cake\ORM\TableRegistry;

/**
 * User Controller
 *
 * @property \eurokeep\Model\Table\UsersTable $User
 * @method \eurokeep\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AuthController extends AppController
{
    protected Session $Session;


    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->Authentication->allowUnauthenticated(['login']);
    }

    public function login(): void {
        $result = $this->Authentication->getResult();

        $errors = [];
        if($result && $result->isValid()) {
            $User = $result->getData();
            $this->set('success', true);
            $this->set('user', $User);
        } else {
            $this->set('success', false);
            $errors[] = 'User authentication failed';
        }

        $this->set('errors', $errors);
        $this->viewBuilder()->setOption('serialize', ['user', 'success', 'errors']);
        $this->viewBuilder()->setClassName("Json");
    }

    public function logout(): void {
        $this->Authentication->logout();
        header("Location: /a/login");
        exit(200);
    }
}
