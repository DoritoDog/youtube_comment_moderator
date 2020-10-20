<?php

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use App\SeleniumCommentScraper;

class GetCommentsCommand extends Command
{
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addArgument('url', [
            'help' => 'The URL of the video to get comments from.'
        ]);
        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $url = $args->getArgument('url');

        $commentRetriever = new SeleniumCommentScraper($url);
        $comments = $commentRetriever->getComments();

        $io->out(json_encode($response));
    }
}
