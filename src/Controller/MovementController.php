<?php
declare(strict_types=1);

namespace eurokeep\Controller;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;
use DateMalformedStringException;
use DateTime;
use eurokeep\Model\Entity\Movement;
use eurokeep\Model\Table\AccountTable;
use eurokeep\Model\Table\MovementTable;
use InvalidArgumentException;

/**
 * Movement Controller
 *
 * @property MovementTable $Movement
 * @method Movement[]|ResultSetInterface paginate($object = null, array $settings = [])
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
     * @return Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['success']);

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
    }

    /**
     * Edit method
     *
     * @param int|null $id Movement id.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit(int|null $id = null): void
    {
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['success']);

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
    }

    /**
     * Index method
     *
     * @return Response|null|void Renders view
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
    }

    /**
     * @param int $accountId
     * @return void
     * @throws DateMalformedStringException
     */
    public function stream(int $accountId): void
    {
        /** @var $accountTable AccountTable */
        $accountTable = TableRegistry::getTableLocator()->get('Account');
        $account = $accountTable->get($accountId);

        $start = new DateTime($this->request->getQueryParams()['start'] ?? date('Y-m-d H:i:s'));
        $end = new DateTime($this->request->getQueryParams()['end'] ?? date('Y-m-d H:i:s'));

        $movements = $account->streamMovements($start, $end);
        $settlement = $account->settlement($movements);


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


        $this->set('movements', $movements);
        $this->set('settlement', $settlement);
        $this->set('account', $account);
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['settlement', 'movements', 'account', 'success']);
        $this->viewBuilder()->setClassName('Json');
    }

    public function mapping(): void
    {
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['success']);

        if (!$this->request->is('post')) {
            $this->set('errors', [
                'Method is not allowed'
            ]);
            return;
        }

        $comment = $this->request->getData('comment', '');
        if (empty($comment)) {
            throw new InvalidArgumentException('Comment cannot be empty');
        }
        $movement = $this
            ->Movement
            ->find()
            ->where([
                'comment LIKE' => '%' . h($comment) . '%',
            ])
            ->orderBy([
                'Movement.created' => 'DESC',
            ])
            ->contain(['Categories'])
            ->firstOrFail();
        $this->set('movement', $movement);

        $this->viewBuilder()->setOption('serialize', ['success', 'movement']);
    }

    /**
     * View method
     *
     * @param string|null $id Movement id.
     * @return Response|null|void Renders view
     * @throws RecordNotFoundException When record not found.
     */
    public function view(int|null $id = null)
    {
        $movement = $this->Movement->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('movement'));
        $this->viewBuilder()->setOption('serialize', ['movement']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Movement id.
     * @return Response|null|void Redirects to list.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete(int|null $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $movement = $this->Movement->get($id);
        if (! $this->Movement->delete($movement)) {
            $this->addError('Movement not deleted');
        }
    }
}
