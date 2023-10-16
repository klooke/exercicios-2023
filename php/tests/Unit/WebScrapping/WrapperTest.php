<?php

namespace Chuva\Tests\Unit\WebScrapping;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\Exception\InvalidValueException;
use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;
use Chuva\Php\WebScrapping\Wrapper;
use PHPUnit\Framework\TestCase;

/**
 * Tests requirements for Wrapper.
 */
class WrapperTest extends TestCase {
  protected $papers;
  protected $outputFilePath = __DIR__ . '/test.xlsx';

  /**
   * Set UP.
   */
  public function setUp(): void {
    $this->papers = [
      new Paper(
        11111,
        'Title A',
        'Type B',
        [
          new Person('Pessoa A', 'Instituto A'),
          new Person('Pessoa B', 'Instituto B')
        ]
      ),
      new Paper(
        22222,
        'Title B',
        'Type B',
        [
          new Person('Pessoa C', 'Instituto C'),
          new Person('Pessoa D', 'Instituto D')
        ]
      ),
    ];
  }

  /**
   * Testing whether XLSX file as created.
   */
  public function testPapersToXLSX() {
    Wrapper::papersToExcel($this->papers, $this->outputFilePath);

    $this->assertFileExists($this->outputFilePath);

    unlink($this->outputFilePath);
  }

  /**
   * Testing whether the paper data is in the file.
   */
  public function testPapersDataInFile() {
    try {
      Wrapper::papersToExcel($this->papers, $this->outputFilePath); 

      $rows = []; 

      $reader = ReaderEntityFactory::createXLSXReader();
      $reader->open($this->outputFilePath);

      foreach ($reader->getSheetIterator() as $sheet) {
        foreach ($sheet->getRowIterator() as $row) {
          array_push($rows, $row->toArray());
        }
      }

      $this->assertCount(3, $rows);
      $this->assertCount(7, $rows[0]);
      $this->assertEquals(
        array_values($this->papers[0]->toArray()),
        $rows[1]
      );

    } finally {
      $reader->close();

      unlink($this->outputFilePath);
    }
  }

  /**
   * Testing whether the wrong data is not saved.
   */
  public function testGarbageDataNotSaved() {
    $this->expectException(InvalidValueException::class);

    Wrapper::papersToExcel([ 'This', 'Garbage', 'Data' ], $this->outputFilePath);
  }

}
