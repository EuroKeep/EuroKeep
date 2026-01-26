<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace eurokeep\Controller;

use eurokeep\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use eurokeep\Model\Table\UsersTable;

/**
 * I am an arbitrary Controller that makes sure a logged +in user is found.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AuthenticatedController extends AppController
{
    /** @var User I am the authenticated User */
    protected User $user;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        $result = $this->Authentication->getResult();

        if($result && $result->isValid()) {
            // Force-Load the user from the Database to verify that the entry is still there.
            try {
                /** @var UsersTable $usersTable */
                $usersTable = TableRegistry::getTableLocator()->get('Users');
                $this->user = $usersTable->get($result->getData()->id);
            } catch (\Throwable $exception) {
                $this->http401();
            }
        } else {
            $this->http401();
        }
    }

    /**
     * I am the overall method to return HTTP403
     */
    final protected function http403() : void {
        header("HTTP/1.1 403 Forbidden");
        exit(403);
    }

    /**
     * I am the overall method to return HTTP401
     */
    final protected function http401() : void {
        header("HTTP/1.1 401 Unauthorized");
        exit(401);
    }

    /**
     * Cake may provide something better here...
     */
    final protected function applyDefaultFilters(Query $query) : Query {
        return $query->where(['user_id' => $this->user->id]);
    }

}
