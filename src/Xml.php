<?php

namespace Bibelstudiet;

use Bibelstudiet\Error\DeveloperError;
use Bibelstudiet\Error\NotFoundError;

use DOMDocument;
use DOMXPath;
use DOMNode;
use DOMNodeList;
use SplFileInfo;
use XSLTProcessor;

/**
 * Helper class for dealing with XML documents.
 */
final class Xml
{
  private DOMDocument $doc;
  private DOMXpath $xpath;

  /**
   * Create a new helper for given xml-file.
   */
  public function __construct(SplFileInfo $file)
  {
    $this->doc = new DOMDocument();
    $this->doc->load($file->getPathname());
    $this->xpath = new DOMXpath($this->doc);
  }

  /**
   * Perform an XPath `query` on this document.
   */
  public function query(string $query, DOMNode $context = null): DOMNodeList {
    return $this->xpath->query($query, $context);
  }

  /**
   * Perform an XPath `evaluate` on this document.
   */
  public function evaluate(string $query, DOMNode $context = null) {
    return $this->xpath->evaluate($query, $context);
  }

  /**
   * Perform an XPath string `evaluate` on this document.
   */
  public function string(string $query, DOMNode $context = null) {
    return $this->xpath->evaluate("string($query)", $context);
  }

  /**
   * Convert given DOMNode or XPath query to string.
   *
   * @param DOMNode|string Node or query to convert to string.
   */
  public function toString($node): string {
    if (is_string($node))
      $node = $this->query($node)->item(0);

    $xml = $node->ownerDocument->saveXML($node);
    if ($xml !== false)
      return $xml;

    throw new NotFoundError("Unable to extract {$node->getNodePath()}");
  }

  /**
   * Transform given DOMNode or XPath query to string.
   *
   * @param DOMNode|string Node or query to convert to string.
   */
  public function transformToString($node, string $xslFilename = 'identity'): string {
    if (is_string($node))
      $node = $this->query($node)->item(0);

    // Load XSL
    $xslFile = new SplFileInfo(DOCROOT.'xsl'.DIRECTORY_SEPARATOR."$xslFilename.xsl");
    if(!$xslFile->isFile())
      throw new DeveloperError("Could not find $xslFilename XSL directory");

    $xsl = new DOMDocument();
    $xsl->load($xslFile->getPathname());

    $proc = new XSLTProcessor();
    $proc->importStylesheet($xsl);

    // Copy node into new document
    $copy = new DOMDocument();
    $copy->appendChild($copy->importNode($node, true));

    // Transform document
    $result = $proc->transformToDoc($copy);
    return $result->saveXML($result->documentElement);
  }
}
