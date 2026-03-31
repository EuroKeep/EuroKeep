<?php
declare(strict_types=1);

namespace eurokeep\Controller;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use DateMalformedStringException;
use eurokeep\Model\Entity\Budget;
use eurokeep\Model\Table\BudgetTable;
use eurokeep\Model\Table\MovementTable;

/**
 * Budget Controller
 *
 * @property BudgetTable $Budget
 * @method Budget[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class BudgetController extends AuthenticatedController
{
    /**
     * Index method
     *
     */
    public function index(): void
    {
        $budgetQuery = $this
            ->Budget
            ->find();
        $budgetQuery->contain([
            'Categories'
        ]);

        $filters = $this->getRequest()->getQueryParams()['filter'] ?? [];
        foreach ($filters as $fieldname => $value) {
            $budgetQuery->where(["$fieldname LIKE" => "$value%",]);
        }

        $budgets = $budgetQuery->all()->toArray();

        $this->set('items', $budgets);
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['items', 'success']);
    }

    /**
     * @return void
     */
    public function list(): void
    {
        $startString = $this->request->getQueryParams()['start'] ?? null;
        $endString = $this->request->getQueryParams()['end'] ?? null;
        [$start, $end] = $this->getRange($startString, $endString);


        $budgetQuery = $this
            ->Budget
            ->find()
        ->contain(['Categories']);
        $budgets = $budgetQuery->all()->toArray();

        // TOTALS
        $total = [
            'spentValue' => 0,
            'total' => 0,
            'freeValue' => 0,
        ];

        /** @var Budget $budget */
        foreach ($budgets as $budget) {
            $budget->category->movement = $budget->getMovements($start, $end);
            $budget->spent = MovementTable::summarize($budget->category->movement);

            $total['spentValue'] += round($budget->spent, 2);
            $total['total'] += $budget->limit;
        }

        $total['freeValue'] += max(0, $total['total'] + $total['spentValue']);
        $total['spentPercent'] = min(100, round((-$total['spentValue']) / $total['total'] * 100, 2));
        $total['freePercent'] = 100 - $total['spentPercent'];

        $this->set('budgets', $budgets);
        $this->set('total', $total);
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['budgets', 'success', 'total']);
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

        $data = $this->request->getData();
        $data['user_id'] = $this->user->id;
        $budgetTable = $this->Budget;
        $entity = $budgetTable->newEmptyEntity();
        $entity = $budgetTable->patchEntity($entity, $data);
        $budgetTable->save($entity);

        if ($entity->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $entity->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $this->set('budget', $entity);
        $this->viewBuilder()->setOption('serialize', ['success', 'budget']);
    }

    /**
     * View method
     *
     * @param int|null $budgetId Budget id.
     * @throws RecordNotFoundException When record not found.
     */
    public function view(int|null $budgetId = null): void
    {
        $budget = $this->Budget->get($budgetId, [
            'contain' => [],
        ]);

        $this->set('budget', $budget);
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['budget', 'success']);
    }


    /**
     * Edit method
     *
     * @param int|null $budgetId Budget id.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit(int|null $budgetId = null): void
    {
        $budget = $this->Budget->get($budgetId, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $budget = $this->Budget->patchEntity($budget, $this->request->getData());
            if ($this->Budget->save($budget)) {
                return;
            }
        }
        $this->set(compact('budget'));
    }

    /**
     * Delete method
     *
     * @param int|null $budgetId Budget id.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete(int|null $budgetId = null): void
    {
        $this->request->allowMethod(['post', 'delete']);
        $budget = $this->Budget->get($budgetId);

        if ($budget->user_id !== $this->user->id) {
            $this->http401('This is not your budget.');
        }

        if (!$this->Budget->delete($budget)) {
            $this->addError('Error', 'Budget not deleted.');
        }
    }
}
