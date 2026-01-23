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

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
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

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    /**
     * Add CSRF token to all .json requests
     */
    public function beforeRender(EventInterface $event) {

        $this->set('_csrfToken', $this->request->getAttribute('csrfToken'));
        $serialize = $this->viewBuilder()->getOption('serialize');
        if ($serialize === null) {
            $serialize = [];
        }
        $serialize[] = '_csrfToken';
        //Add Paginator info to json response
        $paging = $this->viewBuilder()->getVar('paging');
        if ($paging !== null) {
            $serialize[] = 'paging';
        }

        //Add Scroll Paginator info to json response
        $scroll = $this->viewBuilder()->getVar('scroll');
        if ($scroll !== null) {
            $serialize[] = 'scroll';
        }
        $this->viewBuilder()->setOption('serialize', $serialize);
    }

}
