<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {
    $data = [];
    $char_ban = [';', ',', '.'];

    foreach ($dom->getElementsByTagName('a') as $link) {
      $link_class = explode(' ', $link->getAttribute('class'));
      if (!in_array('paper-card', $link_class)) {
        continue;
      }

      $id = $link->lastChild->lastChild->textContent;
      $title = $link->firstChild->textContent;
      $type = $link->lastChild->firstChild->textContent;

      $paper = new Paper($id, $title, $type);

      foreach ($link->getElementsByTagName('span') as $span) {
        $name = str_replace($char_ban, "", $span->textContent);
        $institution = $span->getAttribute('title');

        if (!strlen($name) || !strlen($institution)) {
          continue;
        }

        array_push($paper->authors, new Person($name, $institution));
      }

      array_push($data, $paper);
    }

    return $data;
  }

}
