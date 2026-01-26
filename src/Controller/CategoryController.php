<?php
declare(strict_types=1);

namespace eurokeep\Controller;

/**
 * Category Controller
 *
 * @property \eurokeep\Model\Table\CategoryTable $Category
 * @method \eurokeep\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoryController extends AuthenticatedController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $qry = $this->Category->find();

        $filters = $this->getRequest()->getQueryParams()['filter'] ?? [];
        foreach ($filters as $fieldname => $value) {
            $qry->where(["$fieldname LIKE" => "$value%",]);
        }

        $category = $this->paginate($qry);

        $this->set(compact('category'));
        $this->set('categorys', $category);
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['categorys', 'success']);

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
        $categoryTable = $this->Category;
        $entity = $categoryTable->newEmptyEntity();
        $entity = $categoryTable->patchEntity($entity, $data);
        $categoryTable->save($entity);

        if ($entity->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $entity->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $this->set('category', $entity);
        $this->viewBuilder()->setOption('serialize', ['success', 'category']);
        $this->viewBuilder()->setClassName("Json");
    }
}
