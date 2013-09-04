<?xml version='1.0' encoding='UTF-8'?>

<!-- XHTML-to-XHTML converter by Fletcher Penney
	specifically designed for use with MultiMarkdown created XHTML
	
	This file converts a MultiMarkdown text file into an XHTML file 
	suitable for publishing on a web site, including a header, footer, 
	and sidebar.
	
	It also uses the title and date metadata in the output, and eventually
	may do something useful with tag metadata if I decide what I want to do.
	
	It is specifically designed to be compatible with MultiMarkdown CMS.
	
-->

<!-- 
# Copyright (C) 2009-2011  Fletcher T. Penney <fletcher@fletcherpenney.net>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the
#    Free Software Foundation, Inc.
#    59 Temple Place, Suite 330
#    Boston, MA 02111-1307 USA
-->

	
<xsl:stylesheet
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	exclude-result-prefixes="xsl"
	version="1.0">

	<xsl:variable name="newline">
<xsl:text>
</xsl:text>
	</xsl:variable>
	
	<xsl:output method="html" encoding="US-ASCII" doctype-system="about:legacy-compat" />
   
<!--	<xsl:output method='xml' encoding="US-ASCII" version="1.0" doctype-public="-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN" doctype-system="http://www.w3.org/TR/MathML2/dtd/xhtml-math11-f.dtd" indent="no"/>
-->

	<!-- the identity template, based on http://www.xmlplease.com/xhtmlxhtml -->

	<xsl:template match="@*|node()">
		<xsl:copy>
			<xsl:apply-templates select="@*|node()"/>
		</xsl:copy>
	</xsl:template>

	<xsl:template match="head">
		<xsl:copy>
			<xsl:apply-templates select="@*|node()"/>
			<xsl:text disable-output-escaping="yes"><![CDATA[<!--#include virtual="${Base_URL}/templates/head.html" -->]]></xsl:text>
		</xsl:copy>
	</xsl:template>

	<xsl:template match="meta[@charset != '']"/>

	<xsl:template match="body">
		<xsl:copy>
			<xsl:text disable-output-escaping="yes"><![CDATA[<!--#include virtual="${Base_URL}/templates/header.html" -->]]></xsl:text>
			<xsl:if test="/html/head/title != ''">
				<h1 class="page-title"><xsl:value-of select="/html/head/title"/></h1>
			</xsl:if>
			<xsl:if test="/html/head/meta[translate(@name,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz') = 'date']/@content != ''">
				<div class="author">Written By: <xsl:value-of select="/html/head/meta[translate(@name,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz') = 'author']/@content"/>
				</div>
			</xsl:if>
			<xsl:if test="/html/head/meta[translate(@name,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz') = 'date']/@content != ''">
				<div class="date">Published On: <xsl:value-of select="/html/head/meta[translate(@name,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz') = 'date']/@content"/>
				</div>
			</xsl:if>
			<xsl:if test="/html/head/meta[translate(@name,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz') = 'tags']/@content != ''">
				<xsl:text disable-output-escaping="yes"><![CDATA[<!--#include virtual="${Base_URL}/cgi/tags.cgi" -->]]></xsl:text>
			</xsl:if>
			<xsl:apply-templates select="@*|node()"/>
			<xsl:text disable-output-escaping="yes"><![CDATA[<!--#include virtual="${Base_URL}/templates/footer.html" -->]]></xsl:text>
		</xsl:copy>
	</xsl:template>
	
	
</xsl:stylesheet>