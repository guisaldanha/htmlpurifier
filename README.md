HTML Purifier [![Build Status](https://github.com/ezyang/htmlpurifier/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/ezyang/htmlpurifier/actions/workflows/ci.yml)
=============

HTML Purifier is an HTML filtering solution that uses a unique combination
of robust whitelists and aggressive parsing to ensure that not only are
XSS attacks thwarted, but the resulting HTML is standards compliant.

HTML Purifier is oriented towards richly formatted documents from
untrusted sources that require CSS and a full tag-set.  This library can
be configured to accept a more restrictive set of tags, but it won't be
as efficient as more bare-bones parsers. It will, however, do the job
right, which may be more important.

Places to go:

* See INSTALL for a quick installation guide
* See docs/ for developer-oriented documentation, code examples and
  an in-depth installation guide.
* See WYSIWYG for information on editors like TinyMCE and FCKeditor

HTML Purifier can be found on the web at: [http://htmlpurifier.org/](http://htmlpurifier.org/)

## Installation

Package available on [Composer](https://packagist.org/packages/ezyang/htmlpurifier).

If you're using Composer to manage dependencies, you can use

    $ composer require ezyang/htmlpurifier

## Para configurar para tags HTML5
```php
$config = HTMLPurifier_Config::createDefault();
$config->set('HTML.DefinitionID', 'html5 draft');
$config->set('HTML.Doctype', 'HTML 4.01 Transitional');
$config->set('CSS.AllowTricky', true);
$config->set('Cache.SerializerPath', CAMINHO . 'temp');
$config->set('HTML.Forms', true);
$config->set('HTML.SafeIframe', true);
$config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%'); //allow YouTube and Vimeo

if ($def = $config->maybeGetRawHTMLDefinition()) {
	// http://developers.whatwg.org/sections.html
	$def->addElement('section', 'Block', 'Flow', 'Common');
	$def->addElement('nav',     'Block', 'Flow', 'Common');
	$def->addElement('article', 'Block', 'Flow', 'Common');
	$def->addElement('aside',   'Block', 'Flow', 'Common');
	$def->addElement('header',  'Block', 'Flow', 'Common');
	$def->addElement('footer',  'Block', 'Flow', 'Common');

	// Content model actually excludes several tags, not modelled here
	$def->addElement('address', 'Block', 'Flow', 'Common');
	$def->addElement('hgroup', 'Block', 'Required: h1 | h2 | h3 | h4 | h5 | h6', 'Common');

	// http://developers.whatwg.org/grouping-content.html
	$def->addElement('figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common');
	$def->addElement('figcaption', 'Inline', 'Flow', 'Common');

	// http://developers.whatwg.org/the-video-element.html#the-video-element
	$def->addElement('video', 'Block', 'Optional: (source, Flow) | (Flow, source) | Flow', 'Common', array(
		'src' => 'URI',
		'type' => 'Text',
		'width' => 'Length',
		'height' => 'Length',
		'poster' => 'URI',
		'preload' => 'Enum#auto,metadata,none',
		'controls' => 'Bool',
	));
	$def->addElement('source', 'Block', 'Flow', 'Common', array(
		'src' => 'URI',
		'type' => 'Text',
	));

	// http://developers.whatwg.org/text-level-semantics.html
	$def->addElement('s',    'Inline', 'Inline', 'Common');
	$def->addElement('var',  'Inline', 'Inline', 'Common');
	$def->addElement('sub',  'Inline', 'Inline', 'Common');
	$def->addElement('sup',  'Inline', 'Inline', 'Common');
	$def->addElement('mark', 'Inline', 'Inline', 'Common');
	$def->addElement('wbr',  'Inline', 'Empty', 'Core');

	// http://developers.whatwg.org/edits.html
	$def->addElement('ins', 'Block', 'Flow', 'Common', array('cite' => 'URI', 'datetime' => 'CDATA'));
	$def->addElement('del', 'Block', 'Flow', 'Common', array('cite' => 'URI', 'datetime' => 'CDATA'));

	// TinyMCE
	$def->addAttribute('img', 'data-mce-src', 'Text');
	$def->addAttribute('img', 'data-mce-json', 'Text');

	// Others
	$def->addAttribute('iframe', 'allowfullscreen', 'Bool');
	$def->addAttribute('table', 'height', 'Text');
	$def->addAttribute('td', 'border', 'Text');
	$def->addAttribute('th', 'border', 'Text');
	$def->addAttribute('tr', 'width', 'Text');
	$def->addAttribute('tr', 'height', 'Text');
	$def->addAttribute('tr', 'border', 'Text');
}

$purifier = new HTMLPurifier($config);
$clean_html = $purifier->purify($_POST[$key]);
```
