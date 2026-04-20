<?php

namespace GeneratorTest;

use EDI\Generator\Invoic;
use EDI\Generator\Invoic\Item as InvoicItem;
use EDI\Generator\Message;
use PHPUnit\Framework\TestCase;

/**
 * Covers the word-boundary text splitter that backs FTX, IMD and NAD
 * free-text components.
 */
final class TextSplittingTest extends TestCase {

  public function testSplitsOnWordBoundary() {
    $text   = 'Bezüglich der Entgeltminderung verweisen wir auf die '
            . 'Zahlungs- und Konditionsvereinbarungen.';
    $result = Message::splitTextOnWordBoundary($text, 70);

    $this->assertGreaterThan(1, count($result));
    foreach ($result as $line) {
      $this->assertLessThanOrEqual(70, mb_strlen($line));
    }
    // Reassembled with single spaces the text must be intact — proof
    // that no word was split mid-character.
    $this->assertSame($text, implode(' ', $result));
  }

  public function testHardCutsTokensLongerThanLineLength() {
    $text   = 'short ' . str_repeat('A', 80) . ' tail';
    $result = Message::splitTextOnWordBoundary($text, 30);

    foreach ($result as $line) {
      $this->assertLessThanOrEqual(30, mb_strlen($line));
    }
    // Nothing dropped: concatenation contains the full long token.
    $this->assertStringContainsString(str_repeat('A', 80), implode('', $result));
  }

  public function testIsUtf8Safe() {
    $text   = 'Größe ' . str_repeat('ä', 40);
    $result = Message::splitTextOnWordBoundary($text, 20);

    foreach ($result as $line) {
      $this->assertTrue(mb_check_encoding($line, 'UTF-8'));
      $this->assertLessThanOrEqual(20, mb_strlen($line));
    }
  }

  public function testRespectsMaxLines() {
    $text   = 'one two three four five six seven eight nine ten';
    $result = Message::splitTextOnWordBoundary($text, 8, 2);

    $this->assertCount(2, $result);
  }

  public function testEmptyInputReturnsEmptyArray() {
    $this->assertSame([], Message::splitTextOnWordBoundary(null, 35));
    $this->assertSame([], Message::splitTextOnWordBoundary('', 35));
    $this->assertSame([], Message::splitTextOnWordBoundary('text', 0));
  }

  public function testFtxSegmentSplitsTextOnWordBoundary() {
    $text    = 'Bezüglich der Entgeltminderung verweisen wir auf die '
             . 'Zahlungs- und Konditionsvereinbarungen.';
    $segment = Invoic::addFTXSegment($text, 'OSI', 'HAE');

    $this->assertSame('FTX', $segment[0]);
    $components = $segment[4];
    $this->assertGreaterThan(1, count($components));
    foreach ($components as $component) {
      $this->assertLessThanOrEqual(70, mb_strlen($component));
    }
    // The legacy byte-split would have left "Ko" at the end of
    // component 0 and "nditionsvereinbarungen." at the start of
    // component 1. With word-boundary splitting the long word stays
    // whole.
    $this->assertStringEndsWith('und', $components[0]);
    $this->assertStringStartsWith('Konditionsvereinbarungen', $components[1]);
  }

  public function testImdSegmentSplitsLongDescriptionOnWordBoundary() {
    $segment = InvoicItem::addIMDSegment(
      '11/2"  6,0kw Eintauchtiefe 450mm kurz',
      'ZU'
    );

    $this->assertSame('IMD', $segment[0]);
    $components = $segment[3];
    // [type, '', org, line1, line2]
    $this->assertSame('ZU', $components[0]);
    $this->assertCount(5, $components, 'description should split into two C273 sub-components');
    $this->assertLessThanOrEqual(35, mb_strlen($components[3]));
    $this->assertLessThanOrEqual(35, mb_strlen($components[4]));
    // The word "kurz" must end up whole in the second sub-component,
    // not split as "ku" + "rz" (legacy byte-split behaviour).
    $this->assertSame('kurz', $components[4]);
    $this->assertStringEndsWith('450mm', $components[3]);
  }
}
