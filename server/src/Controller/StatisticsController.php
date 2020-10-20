<?php

namespace App\Controller;

use Cake\ORM\TableRegistry;

/**
 * Statistics Controller
 *
 * @method \App\Model\Entity\Comment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StatisticsController extends AppController
{
  public function initialize(): void
  {
    parent::initialize();
    $this->loadComponent('RequestHandler');
  }

  public function index()
  {
    $comments = TableRegistry::getTableLocator()->get('Comments');
    $accounts = TableRegistry::getTableLocator()->get('GoogleAccounts');

    $spam = $comments->find('all', ['conditions' => ['status' => 'SPAM']])->count();
    $botsBanned = $accounts->find('all', ['conditions' => ['status' => 'BANNED']])->count();

    $this->set(['spam' => $spam, 'botsBanned' => $botsBanned]);
    $this->viewBuilder()->setOption('serialize', ['spam', 'botsBanned'])->setOption('jsonOptions', JSON_FORCE_OBJECT);

    $this->response = $this->response->cors($this->request)
                                     ->allowOrigin(['*'])
                                     ->allowCredentials()
                                     ->build();
  }
}
