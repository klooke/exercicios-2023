<?php

namespace Chuva\Tests\Unit\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;
use Chuva\Php\WebScrapping\Scrapper;
use DOMDocument;
use PHPUnit\Framework\TestCase;

/**
 * Tests requirements for Scrapper.
 */
class ScrapperTest extends TestCase {

  protected DOMDocument $paperDOM;

  /**
   * Set UP
   */
  public function setUp(): void {
    $this->paperDOM = new DOMDocument();
    $this->paperDOM->loadHTML(
      '<html>'.
        '<body>'.
          '<a class="other-class paper-card">'.
            '<h4>Title</h4>\n'.
            '<div>'.
              '<span title="Instituto A">Pessoa A</span>'.
              '<span title="Instituto B">Pessoa B</span>'.
            '</div>'.
            '<div>'.
              '<div>Type</div>'.
              '<div>11111</div>'.
            '</div>'.
          '</a>'.
          '<a>Normal link</a>'.
          '<a class="not-paper-card">'.
            '<h4>Esse não é um paper</h4>\n'.
          '</a>'.
        '</body>'.
      '</html>'
    );
  }

  /**
   * Testing whether scrap correct paper data 
   */
  public function testScrapPaper() {
    $data = (new Scrapper())->scrap($this->paperDOM);
    $expectedData = [
      new Paper(
        11111,
        'Title',
        'Type',
        [
          new Person('Pessoa A', 'Instituto A'),
          new Person('Pessoa B', 'Instituto B')
        ]
      ),
    ];

    $this->assertCount(1, $data);
    $this->assertEquals($expectedData[0], $data[0]);
  }

}
