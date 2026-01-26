<?php
declare(strict_types=1);

namespace eurokeep\Controller;

use eurokeep\Model\Table\AccountTable;
use eurokeep\Model\Table\CategoryMappingTable;
use Cake\Chronos\Chronos;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;

/**
 * Movement Controller
 *
 * @property \eurokeep\Model\Table\MovementTable $Movement
 * @method \eurokeep\Model\Entity\Movement[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MovementController extends AuthenticatedController
{
    public array $paginate = [
        'limit' => 100,
        'maxLimit' => 100,
        'order' => [
            'Movement.created' => 'desc'
        ]
    ];

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
        unset($data['created']);
        unset($data['modified']);
        $movementTable = $this->Movement;
        $entity = $movementTable->newEmptyEntity();
        $entity = $movementTable->patchEntity($entity, $data);
        $movementTable->save($entity);

        if ($entity->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $entity->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        // Add transfer if required. Swap transfer_id and account_id. Negate value.
        if ($entity->get('transfer_id')) {
            // Save for later
            $oldAccNowTransfer = $data['account_id'];

            // Swap data
            $data['account_id'] = $data['transfer_id'];
            $data['transfer_id'] = $oldAccNowTransfer;
            $data['balance_value'] = -$data['balance_value'];

            $entity = $movementTable->newEmptyEntity();
            $entity = $movementTable->patchEntity($entity, $data);
            $movementTable->save($entity);

            if ($entity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $entity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }
        }


        $this->set('movement', $entity);
        $this->viewBuilder()->setOption('serialize', ['success', 'movement']);
        $this->viewBuilder()->setClassName("Json");
    }

    /**
     * Edit method
     *
     * @param string|null $id Movement id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['success']);
        $this->viewBuilder()->setClassName("Json");

        $entity = $this->Movement->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $entity = $this->Movement->patchEntity($entity, $this->request->getData());

            if ($entity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $entity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }
            if (!$this->Movement->save($entity)) {
                $this->set('success', false);
            }
        }
        $this->set('movement', $entity);
        $this->viewBuilder()->setOption('serialize', ['success', 'movement']);
        $this->viewBuilder()->setClassName("Json");
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $qry = $this->Movement->find();

        $filters = $this->getRequest()->getQueryParams()['filter'] ?? [];
        foreach ($filters as $fieldname => $value) {
            $qry->where(["$fieldname LIKE" => "$value%",]);
        }

        $movements = $this->paginate($qry);

        $this->set(compact('movements'));

        $this->set('movements', $movements);
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['movements', 'success']);

        $this->viewBuilder()->setClassName("Json");
    }

    /**
     * stream method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function stream(int $accountId)
    {
        /** @var $HostsTable AccountTable */
        $accountTable = TableRegistry::getTableLocator()->get('Account');
        $account = $accountTable->get($accountId);

        $start = new \DateTime($this->request->getQueryParams()['start']);
        $end = new \DateTime($this->request->getQueryParams()['end']);
        $startStr = $start->format('Y-m-d');
        $endStr = $end->format('Y-m-d');
        $movements = $this
            ->Movement
            ->find()
            ->contain(['Category'])
            ->where([
                'Movement.created >=' => $startStr,
                'Movement.created <' => $endStr,
                'account_id' => $accountId
            ])
            ->order(['Movement.created' => 'DESC'])
            ->limit(250)
            ->toArray();
        $this->set('movements', $movements);

      $query = $this->Movement->find();
        $value = $query
    ->select([
        'total' => $query->func()->sum('balance_value'),
    ])
    ->where([
        'account_id' => $accountId,
    ])
    ->first()
    ->get('total');

        $account->set('balance_value', $value);


        $this->set('account', $account);
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['movements', 'account', 'success']);
        $this->viewBuilder()->setClassName("Json");
    }

    /**
     * View method
     *
     * @param string|null $id Movement id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $movement = $this->Movement->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('movement'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Movement id.
     * @return \Cake\Http\Response|null|void Redirects to list.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $movement = $this->Movement->get($id);
        if (! $this->Movement->delete($movement)) {
            $this->addError('Movement not deleted');
        }
    }
}
