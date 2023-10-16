<?php

namespace Chuva\Php\WebScrapping\Entity;

/**
 * The Paper class represents the row of the parsed data.
 */
class Paper {

  /**
   * Paper Id.
   *
   * @var int
   */
  public $id;

  /**
   * Paper Title.
   *
   * @var string
   */
  public $title;

  /**
   * The paper type (e.g. Poster, Nobel Prize, etc).
   *
   * @var string
   */
  public $type;

  /**
   * Paper authors.
   *
   * @var \Chuva\Php\WebScrapping\Entity\Person[]
   */
  public $authors;

  /**
   * Builder.
   */
  public function __construct($id, $title, $type, $authors = []) {
    $this->id = $id;
    $this->title = $title;
    $this->type = $type;
    $this->authors = $authors;
  }

  /**
   * Convert the variables of this class into array.
   *
   * @return array
   *   Array data.
   */
  public function toArray(): array {
    $result = [
      'Id' => $this->id,
      'Title' => $this->title,
      'Type' => $this->type,
    ];

    $index = 1;
    foreach ($this->authors as $author) {
      $author_id = 'Author ' . $index++;

      $result[$author_id] = $author->name;
      $result[$author_id . ' Institution'] = $author->institution;
    }

    return $result;
  }

}
