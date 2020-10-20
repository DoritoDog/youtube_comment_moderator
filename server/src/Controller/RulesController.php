<?php

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Log\Engine\ConsoleLog;
use Cake\ORM\TableRegistry;

/**
 * Rules Controller
 *
 * @method \App\Model\Entity\Comment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RulesController extends AppController
{
  private $console;

  private $filters;

  public function initialize(): void
  {
    parent::initialize();

    $this->loadComponent('RequestHandler');

    $this->console = new ConsoleLog();
    $this->filters = $this->getTableLocator()->get('Filters');
  }

  public function beforeFilter(EventInterface $event) {
    parent::beforeFilter($event);

    $this->response = $this->response->cors($this->request)
                                     ->allowOrigin(['*'])
                                     ->allowCredentials()
                                     ->build();
  }

  public function index()
  {
    $filters = $this->filters->find()
                             ->enableHydration(false)
                             ->toList();
    
    $this->set(['filters' => $filters]);
    $this->viewBuilder()->setOption('serialize', ['filters']);
  }

  public function edit()
  {
    if ($this->request->is(['get', 'patch'])) {
      $this->filters->query()
                    ->update()
                    ->set(['regex' => $this->request->getQuery('regex')])
                    ->where(['id' => $this->request->getQuery('id')])
                    ->execute();
    }

    $this->viewBuilder()->setOption('serialize', []);
  }

  public function add() {
    if ($this->request->is(['get', 'post'])) {
      $this->filters
        ->query()
        ->insert(['regex', 'content'])
        ->values(['regex' => '', 'content' => $this->request->getQuery('content')])
        ->execute();
      $filter = $this->filters
        ->query()
        ->order(['id' => 'DESC'])
        ->first();
      $this->set(['filter' => $filter]);
      $this->viewBuilder()->setOption('serialize', ['filter']);
    }
  }

  public function delete() {
    if ($this->request->is(['get', 'delete'])) {
      $this->filters
        ->query()
        ->delete()
        ->where(['id' => $this->request->getQuery('id')])
        ->execute();
      
      $this->viewBuilder()->setOption('serialize', []);
    }
  }

  public function editSetting() {
    if ($this->request->is(['get', 'patch'])) {
      $settings = $this->getTableLocator()->get('Settings');
      $settings
        ->query()
        ->update()
        ->set(['value' => $this->request->getQuery('value')])
        ->where(['name' => $this->request->getQuery('name')])
        ->execute();
      
      $this->viewBuilder()->setOption('serialize', []);
    }
  }
  
  public function getSetting() {
    if ($this->request->is(['get'])) {
      $settings = $this->getTableLocator()->get('Settings');
      $setting = $settings
        ->find('all')
        ->where(['name' => $this->request->getQuery('name')]);
      
      $this->set(['setting' => $setting]);
      $this->viewBuilder()->setOption('serialize', ['setting']);
    }
  }
}
