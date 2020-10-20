<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Error\Debugger;
use Cake\Log\Engine\ConsoleLog;
use Cake\ORM\TableRegistry;
use Cake\Http\CorsBuilder;
use \CaseConverter\CaseString;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 * @method \App\Model\Entity\Comment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CommentsController extends AppController
{
    private $console;

    private $googleService;

    public function getGoogleService()
    {
        return $this->googleService;
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->console = new ConsoleLog();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function status($status)
    {
        // convert the end of the url to a status, e.g. "in-review" will become "IN_REVIEW".
        $status = strtoupper(CaseString::kebab($status)->snake());

        $comments = $this->Comments->find()
                                   ->where(['comments.status' => $status])
                                   ->contain(['GoogleAccounts'])
                                   ->toList();;
        
        $this->set(['comments' => $comments]);
        $this->viewBuilder()
             ->setOption('serialize', ['comments']);

        $this->response = $this->response->cors($this->request)
                                         ->allowOrigin(['*'])
                                         ->allowCredentials()
                                         ->build();
    }

    /**
     * View method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $comment = $this->Comments->get($id, [
            'contain' => ['GoogleAccounts'],
        ]);

        $this->set(compact('comment'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void
     */
    public function add()
    {
        /*$comments = $this->Comments->newEntities($this->request->getData());

        // First tries to save all submitted comments in one transaction.
        if (!$this->Comments->saveMany($comments)) {
            $this->console->log('debug', 'FAIL');
            // If that fails, saves each comment individually.
            foreach ($comments as $comment) {
                $this->Comments->save($comment);
            }
        }*/

        $accounts = TableRegistry::getTableLocator()->get('GoogleAccounts');
        $videos = TableRegistry::getTableLocator()->get('Videos');

        if ($this->request->is('post')) {
            // $requestData contains an associative array of comments.
            $requestData = $this->request->getData();
            foreach ($requestData as $data) {

                $author = $accounts->findOrCreate(
                    ['channel_url' => $data['google_account']['channel_url']],
                    function ($account) use ($data, $accounts) {
                        $account = $accounts->patchEntity($account, $data['google_account']);
                    }
                );

                $video = $videos->findOrCreate(
                    ['youtube_id' => $data['video']['youtube_id']],
                    function ($createdVideo) use ($data, $videos) {
                        $video = $videos->patchEntity($video, $data['video']);
                    }
                );

                $comment = $this->Comments->findOrCreate(
                    ['youtube_id' => $data['youtube_id']],
                    function ($createdComment) use ($data) {
                        $comment = $this->Comments->patchEntity($createdComment, $data, ['fields' => ['text', 'youtube_id', 'url']]);
                    }
                );
                
                $comment = $this->Comments->patchEntity($comment, ['fields' => ['text', 'youtube_id', 'url']]);
                $comment->author = $author;
                $comment->video = $video;
                $this->Comments->save($comment);
            }
        }

        $this->viewBuilder()
             ->setOption('serialize', [])
             ->setOption('jsonOptions', JSON_FORCE_OBJECT);
    }

    /**
     * Edit method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $comment = $this->Comments->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $comment = $this->Comments->patchEntity($comment, $this->request->getData());
            if ($this->Comments->save($comment)) {
                $this->Flash->success(__('The comment has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The comment could not be saved. Please, try again.'));
        }
        $googleAccounts = $this->Comments->GoogleAccounts->find('list', ['limit' => 200]);
        $this->set(compact('comment', 'googleAccounts'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comment = $this->Comments->get($id);
        if ($this->Comments->delete($comment)) {
            $this->Flash->success(__('The comment has been deleted.'));
        } else {
            $this->Flash->error(__('The comment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
