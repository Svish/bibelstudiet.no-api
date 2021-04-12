<!--
  Removes `<title>` and `<about>`
-->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:import href="identity.xsl" />

  <xsl:output method="xml" indent="yes" encoding="utf-8" />

	<xsl:template match="title|about|background|memory" />

</xsl:stylesheet>
