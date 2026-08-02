<?php
declare(strict_types=1);

namespace eurokeep\Controller;

/**
 * Categories Controller
 *
 * @property \eurokeep\Model\Table\CategoriesTable $Categories
 */
class CategoriesController extends AppController
{
    public array $paginate = [
        'limit' => 100,
        'maxLimit' => 100,
        'order' => [
            'Transactions.created' => 'desc'
        ]
    ];

    /**
     * Index method
     */
    public function index(): void
    {
        $this->request->allowMethod(['get']);
        $this->Categories->recover();
        $categoriesQuery = $this
            ->Categories
            ->find()
            ->orderBy(['Categories.name' => 'ASC']);

        $categories = $this->paginate($categoriesQuery);

        $this->set('items', $categories);
        $this->viewBuilder()->setOption('serialize', ['items', 'success']);
    }

    /**
     * View method
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->request->allowMethod(['get']);
        $this->Categories->recover();
        $category = $this->Categories->get($id, contain: ['Budget', 'Transactions', 'Subscription']);
        $this->set(compact('category'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post']);
        $category = $this->Categories->newEmptyEntity();
        $category = $this->Categories->patchEntity($category, $this->request->getData());
        if ($this->Categories->save($category)) {
            $this->Flash->success(__('The category has been saved.'));

            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The category could not be saved. Please, try again.'));
        $this->set(compact('category'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['post']);
        $category = $this->Categories->get($id, contain: []);
        $category = $this->Categories->patchEntity($category, $this->request->getData());
        if ($this->Categories->save($category)) {
            $this->Flash->success(__('The category has been saved.'));

            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The category could not be saved. Please, try again.'));
        $this->set(compact('category'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $category = $this->Categories->get($id);
        if ($this->Categories->delete($category)) {
            $this->Flash->success(__('The category has been deleted.'));
        } else {
            $this->Flash->error(__('The category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Tree function
     */
    public function tree()
    {
        $categories = $this->Categories
            ->find('threaded')
            ->orderBy(['lft' => 'ASC'])
            ->toArray();

        $this->set('categoryTree', $categories);
        $this->viewBuilder()->setOption('serialize', ['categoryTree', 'success']);

    }

}
