<?php

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Datasource\FactoryLocator;
use App\Controller\CommentsController;

class DeleteSpamCommand extends Command
{
  public function execute(Arguments $args, ConsoleIo $io)
  {
    $seconds = $io->ask('How many seconds should this command wait before checking for spam to delete?');

    $client = new \Google_Client();
    $client->setApplicationName('API code samples');
    $client->setScopes(['https://www.googleapis.com/auth/youtube.force-ssl']);
    $client->setAuthConfig('D:\intel\Documents\youtube_comment_moderator\server\resources\client_secret_300681308331-24rhlvfljmchnm9h4oa3rpm7379q0bee.apps.googleusercontent.com.json');
    $client->setAccessType('offline');
    $client->setRedirectUri('http://localhost:8765/rules/redir/');

    $authUrl = $client->createAuthUrl();
    $io->out($client->getAccessToken());
    $io->out('Authorize the app to delete comments on your behalf through this URL: ' . $authUrl);
    $authCode = $io->ask('Enter the verification code: ');

    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
    $client->setAccessToken($accessToken);

    $service = new \Google_Service_YouTube($client);


    $comments = FactoryLocator::get('Table')->get('Comments');
    $googleAccounts = FactoryLocator::get('Table')->get('GoogleAccounts');

    while (true) {

      // Get all the spam comments.
      $query = $comments->find()
                        ->where(['Comments.status' => 'SPAM'])
                        ->contain(['GoogleAccounts']);
      foreach ($query as $comment) {
        
        // Deletes the comment.
        $service->comments->setModerationStatus($comment->youtube_id, 'rejected', ['banAuthor' => false]);

        // Mark the comment as deleted to avoid trying to delete it in future calls.
        $query = $comments->query()
                          ->update()
                          ->set(['status' => 'REJECTED'])
                          ->where(['id' => $comment->id])
                          ->execute();
      }

      sleep($seconds);
    }
  }
}
