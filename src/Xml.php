<?php

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

    throw new Error_NotFound("Unable to extract {$node->getNodePath()}");
  }

}
