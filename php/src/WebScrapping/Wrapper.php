<?php

namespace Chuva\Php\WebScrapping;

use Box\Spout\Common\Exception\IOException;
use Box\Spout\Reader\Exception\InvalidValueException;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Chuva\Php\WebScrapping\Entity\Paper;

/**
 * Wrapper object to output file.
 */
class Wrapper {

  /**
   * Save papers data to XLSX.
   *
   * @param \Chuva\Php\WebScrapping\Entity\Paper[] $papers
   *   The papers to be convert on rows.
   * @param string $outputFilePath
   *   Path of the output file that will contain the data.
   *
   * @throws \Box\Spout\Reader\Exception\InvalidValueException
   *   If the papers do not have valid data.
   */
  public static function papersToExcel(array $papers, string $outputFilePath) {
    $rows = [];
    $header = [];

    foreach ($papers as $paper) {
      if (!$paper instanceof Paper) {
        continue;
      }

      $values = $paper->toArray();

      $header = count($values) > count($header) ? array_keys($values) : $header;

      $row = WriterEntityFactory::createRowFromArray($values);
      array_push($rows, $row);
    }

    if (!count($rows)) {
      throw new InvalidValueException('Does not have valid papers data.');
    }

    $row_header = [WriterEntityFactory::createRowFromArray($header)];
    $rows = array_merge($row_header, $rows);

    Wrapper::saveToExcel($rows, $outputFilePath);
  }

  /**
   * Save rows data to XLSX.
   *
   * @param \Box\Spout\Common\Entity\Row[] $rows
   *   The rows to be appended to the data.
   * @param string $outputFilePath
   *   Path of the output file that will contain the data.
   */
  private static function saveToExcel(array $rows, string $outputFilePath) {
    try {
      $writer = WriterEntityFactory::createXLSXWriter();
      $writer->openToFile($outputFilePath);
      $writer->addRows($rows);
    }
    catch (IOException $ex) {
      print("Error writing to file. \n" . $ex->getMessage());
    }
    finally {
      $writer->close();
    }
  }

}
