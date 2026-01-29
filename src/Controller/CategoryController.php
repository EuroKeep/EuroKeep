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
    }

}
