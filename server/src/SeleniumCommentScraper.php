<?php

namespace App;

class SeleniumCommentScraper implements iCommentRetriever
{

  protected $url;

  function __construct($url)
  {
    $this->url = $url;
  }

  public function getComments(): array
  {
    $cmd = "python \"D:\intel\Documents\python\YouTube Comment Moderator\main.py\" $this->url";
    $response = json_decode(shell_exec($cmd));
    // for ($i = 0; $i < count($response); $i++)
    // {
    //   if (strlen($response[$i]->comment->text) > 60)
    //     $response[$i]comment->text = substr($response[$i]comment->text, 0, 60) . '...';
    // }

    return $response;
  }
}
