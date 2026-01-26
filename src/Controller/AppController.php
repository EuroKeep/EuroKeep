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
use Cake\View\JsonView;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authentication.Authentication');
    }

    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    /**
     * @inheritDoc
     *
     * We always render JSON. There's no front-end here.
     */
    public function beforeRender(EventInterface $event)
    {
        // Make sure that the success key is true if not defined.
        if (!$this->has('success')) {
            $this->set('success', true);
        }

        // Make sure the errors array exists, even if empty.
        if (!$this->has('errors')) {
            $this->set('errors', []);
        }
        $serialize = $this->viewBuilder()->getOption('serialize') ?? ['success'];

        $this->viewBuilder()->setOption('serialize', $serialize);
        $this->viewBuilder()->setClassName("Json");
    }

    final protected function has(string $key): bool {
        return $this->viewBuilder()->hasVar($key);
    }

    final protected function addError(string $key, string $message): void {
        $errors = $this->viewBuilder()->getVar($key);
        $errors[$key] = $message;
        $this->set('errors', $errors);
    }

}