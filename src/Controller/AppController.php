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

use Override;
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

    /**
     * @inheritDoc
     * @throws
     */
    #[Override]
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authentication.Authentication');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function viewClasses(): array
    {
        $this->viewClasses = [JsonView::class];
        return parent::viewClasses();
    }

    /**
     * @inheritDoc
     *
     * We always render JSON. There's no front-end here.
     */
    #[Override]
    public function beforeRender(EventInterface $event): void
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
        $this->viewBuilder()->setClassName('Json');

        parent::beforeRender($event);
    }

    /**
     * I will verify if the viewBuilder has the given $key.
     * @param string $key The key you want to check for.
     * @return bool  Whether the $key is part of the viewBuilder.
     */
    final protected function has(string $key): bool {
        return $this->viewBuilder()->hasVar($key);
    }

    /**
     *  I will add a new error with the given $key and $message to the ViewBuilder.
     * @param string $key     The key for the error message. May be a field name, etc.
     * @param string $message The actual error message that.
     */
    final protected function addError(string $key, string $message): void {
        $errors = $this->viewBuilder()->getVar($key);
        $errors[$key] = $message;
        $this->set('errors', $errors);
    }

}