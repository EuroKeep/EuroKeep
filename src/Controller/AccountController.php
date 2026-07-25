<?php
declare(strict_types=1);

namespace eurokeep\Controller;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use eurokeep\Model\Entity\Account;
use eurokeep\Model\Table\AccountTable;

/**
 * Account Controller
 *
 * @property AccountTable $Account
 * @method Account[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class AccountController extends AuthenticatedController
{
    public array $paginate = [
        'limit' => 100,
        'maxLimit' => 100
    ];
    /**
     * Index method
     *
     */
    public function index(): void
    {
        $accountQuery = $this
            ->Account
            ->find()
            ->orderBy(['Account.name' => 'ASC']);

        $filters = $this->getRequest()->getQueryParams()['filter'] ?? [];
        foreach ($filters as $fieldname => $value) {
            $accountQuery->where(["$fieldname LIKE" => "$value%",]);
        }

        $account = $this->paginate($accountQuery);

        $this->set('totalBalances', $this->user->getTotalBalances());
        $this->set('items', $account);
        $this->viewBuilder()->setOption('serialize', ['items', 'success', 'totalBalances']);
    }

    /**
     * Add method
     *
     */
    public function add(): void
    {
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['success']);

        if (!$this->request->is('post')) {
            $this->set('errors', [
                'Method is not allowed'
            ]);
            return;
        }

        // Fetch data
        $account = $this->request->getData();
        $account['user_id'] = $this->user->id;
        $account['balance_value'] = 0;

        // Add the Account
        $entity = $this->Account->newEmptyEntity();
        $entity = $this->Account->patchEntity($entity, $account);

        if ($entity->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $entity->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $this->Account->save($entity);

        $this->set('account', $entity);
        $this->viewBuilder()->setOption('serialize', ['success', 'account']);
    }

    /**
     * View method
     *
     * @param int|null $accountId Account id.
     * @throws RecordNotFoundException When record not found.
     */
    public function view(int|null $accountId = null): void
    {
        $account = $this->Account->get($accountId, [
            'contain' => [],
        ]);

        $this->set('account', $account);
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['account', 'success']);
    }


    /**
     * Edit method
     *
     * @param int|null $accountId Account id.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit(int|null $accountId = null): void
    {
        // Check method
        if (! $this->request->is(['patch', 'post', 'put'])) {
            $this->http401('method not allowed');
        }

        $account = $this->Account->get($accountId);

        // Check Ownership
        if ($account->user_id !== $this->user->id) {
            $this->http403('This account is not yours');
        }

        // Apply the new data
        $account = $this
            ->Account
            ->patchEntity($account, $this->request->getData());

        // Haz errors?
        if ($account->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $account->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        // Cannot save?
        if (!$this->Account->save($account)) {
            $this->set('success', false);
        }
        $this->set(compact('account'));
    }

    /**
     * Delete method
     *
     * @param int|null $accountId Account id.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete(int|null $accountId = null): void
    {
        $this->request->allowMethod(['post', 'delete']);
        $account = $this->Account->get($accountId);

        if (!$this->Account->delete($account)) {
            $this->addError('Error', 'Account not deleted.');
        }
    }

    /**
     * @param int|null $accountId Account id.
     * @return void
     */
    public function summarize(int|null $accountId = null): void
    {
        if ($accountId) {
            $accounts = [$this->Account->find()->where(['id' => $accountId])->first()];
        } else {
            $accounts = $this->Account->find()->all()->toArray();
        }
        foreach ($accounts as $account) {
            $account->summarize();
        }
        $this->set('accounts', $accounts);
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['accounts', 'success']);
    }
}
