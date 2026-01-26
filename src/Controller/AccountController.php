<?php
declare(strict_types=1);

namespace eurokeep\Controller;

/**
 * Account Controller
 *
 * @property \eurokeep\Model\Table\AccountTable $Account
 * @method \eurokeep\Model\Entity\Account[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AccountController extends AuthenticatedController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $qry = $this->Account->find();

        $this->applyDefaultFilters($qry)
            ->order(['Account.name' => 'ASC']);

        $filters = $this->getRequest()->getQueryParams()['filter'] ?? [];
        foreach ($filters as $fieldname => $value) {
            $qry->where(["$fieldname LIKE" => "$value%",]);
        }

        $account = $this->paginate($qry);

        $this->set('totalBalances', $this->user->getTotalBalances());
        $this->set('items', $account);
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['items', 'success', 'totalBalances']);

        $this->viewBuilder()->setClassName("Json");
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['success']);
        $this->viewBuilder()->setClassName("Json");

        if (!$this->request->is('post')) {
            $this->set('errors', [
                'Method is not allowed'
            ]);
            return;
        }

        $data = $this->request->getData();
        $data['user_id'] = $this->user->id;
        $data['balance_value'] = 0;
        $accountTable = $this->Account;
        $entity = $accountTable->newEmptyEntity();
        $entity = $accountTable->patchEntity($entity, $data);
        $accountTable->save($entity);

        if ($entity->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $entity->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $this->set('account', $entity);
        $this->viewBuilder()->setOption('serialize', ['success', 'account']);
        $this->viewBuilder()->setClassName("Json");
    }

    /**
     * View method
     *
     * @param string|null $id Account id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $account = $this->Account->get($id, [
            'contain' => [],
        ]);

        $this->set('account', $account);
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['account', 'success']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Account id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $account = $this->Account->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $account = $this->Account->patchEntity($account, $this->request->getData());
            if ($this->Account->save($account)) {

                $this->viewBuilder()->setClassName("Json");
                return;
            }
        }
        $this->set(compact('account'));
        $this->viewBuilder()->setClassName("Json");
    }

    /**
     * Delete method
     *
     * @param string|null $id Account id.
     * @return \Cake\Http\Response|null|void Redirects to list.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $account = $this->Account->get($id);

        if (!$this->Account->delete($account)) {
            $this->addError('Account not deleted.');
        }
    }

    public function summarize($id = null): void
    {
        if($id) {
            $accounts = [$this->Account->find()->where(['id' => $id])->first()];
        } else {
            $accounts = $this->Account->find()->all()->toArray();
        }
        foreach ($accounts as $account) {
            $account->summarize();
        }
        $this->set('accounts', $account);
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['accounts', 'success']);

        $this->viewBuilder()->setClassName("Json");
    }
}
