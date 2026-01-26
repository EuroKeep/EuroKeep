<?php
declare(strict_types=1);

namespace eurokeep\Controller;

/**
 * Accounttype Controller
 *
 * @property \eurokeep\Model\Table\AccounttypeTable $Accounttype
 * @method \eurokeep\Model\Entity\Accounttype[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AccounttypeController extends AuthenticatedController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $accounttypes = $this->paginate($this->Accounttype);

        $this->set(compact('accounttypes'));
        $this->set('items', $accounttypes);
        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['items', 'success', 'accounttypes', 'items']);

        $this->viewBuilder()->setClassName("Json");
    }

    /**
     * View method
     *
     * @param string|null $id Accounttype id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $accounttype = $this->Accounttype->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('accounttype'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $accounttype = $this->Accounttype->newEmptyEntity();
        if ($this->request->is('post')) {
            $accounttype = $this->Accounttype->patchEntity($accounttype, $this->request->getData());
            if ($this->Accounttype->save($accounttype)) {

                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('accounttype'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Accounttype id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $accounttype = $this->Accounttype->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $accounttype = $this->Accounttype->patchEntity($accounttype, $this->request->getData());
            if ($this->Accounttype->save($accounttype)) {

                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('accounttype'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Accounttype id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $accounttype = $this->Accounttype->get($id);
        if (! $this->Accounttype->delete($accounttype)) {
            $this->addError('Account type not deleted');
        }

        return $this->redirect(['action' => 'index']);
    }
}
