
## PrismJS 1.20.0

Prism is a lightweight, extensible syntax highlighter, built with modern web standards in mind.

- Site : https://prismjs.com/index.html
- Date : 2020.06.05
- Core : Minified version 6.77KB
- XCL Custom local path : src="./common/js/prismjs/clipboard.min.js"

## Basic usage

You will need to include the prism.css and prism.js files you downloaded in your page. Example:

'<!DOCTYPE html>
<html>
<head>
	...
	<link href="themes/prism.css" rel="stylesheet" />
</head>
<body>
	...
	<script src="prism.js"></script>
</body>
</html>'

Prism does its best to encourage good authoring practices. Therefore, it only works with <code> elements, since marking up code without a <code> element is semantically invalid. According to the HTML5 spec, the recommended way to define a code language is a language-xxxx class, which is what Prism uses. Alternatively, Prism also supports a shorter version: lang-xxxx.

To make things easier however, Prism assumes that this language definition is inherited. Therefore, if multiple <code> elements have the same language, you can add the language-xxxx class on one of their common ancestors. This way, you can also define a document-wide default language, by adding a language-xxxx class on the <body> or <html> element.

If you want to opt-out of highlighting for a <code> element that is a descendant of an element with a declared code language, you can add the class language-none to it (or any non-existing language, really).

The recommended way to mark up a code block (both for semantics and for Prism) is a <pre> element with a <code> element inside, like so:

<pre><code class="language-css">p { color: red }</code></pre>

If you use that pattern, the <pre> will automatically get the language-xxxx class (if it doesn’t already have it) and will be styled as a code block.

--------------------
Theme
--------------------

Tomorrow Night, Rosey,  1.72KB

--------------------
Languages
--------------------

Markup + HTML + XML + SVG + MathML + SSML + Atom + RSS, 2.15KB
CSS, 1.08KB
C-like, 0.68KB
JavaScript, 3.18KB

CSS Extras, milesj, 3.05KB
Diff, uranusjr, 0.59KB
JavaDoc-like, RunDevelopment, 0.83KB
JSON + Web App Manifest, CupOfTea696, 0.37KB
Markup templating, Golmote, 1.04KB
PHP, milesj, 2.41KB
PHPDoc, RunDevelopment, 0.63KB
PHP Extras, milesj, 0.29KB
Smarty, Golmote, 1.1KB
SQL, multipetros, 3.06KB

--------------------
Plugins
--------------------

Autolinker 1.13KB
Copy to Clipboard Button, mAAdhaTTah, 0.9KB
Diff Highlight, RunDevelopment, 1.54KB
Download Button, Golmote, 0.44KB
File Highlight, 1.15KB
Normalize Whitespace, zeitgeist87, 2.41KB
Previewers, Golmote, 15.33KB
Toolbar, mAAdhaTTah, 3.02KB
Unescaped Markup, 1.5KB
WebPlatform Docs, 3.37KB

--------------------

Total filesize: 77.55KB (65% JavaScript + 35% CSS)

Note: The filesizes displayed refer to non-gizipped files and include any CSS code required. The CSS code is not minified.

--------------------
Customize
--------------------

* PrismJS 1.20.0 customized for XCL 2.3.0
* copy the link below into your browser

https://prismjs.com/download.html#themes=prism-tomorrow&languages=markup+css+clike+javascript+css-extras+diff+javadoclike+json+markup-templating+php+phpdoc+php-extras+smarty+sql&plugins=autolinker+wpd+file-highlight+previewers+unescaped-markup+normalize-whitespace+toolbar+copy-to-clipboard+download-button+diff-highlight+treeview

--------------------

## Ajax

// Rerun Prism syntax highlighting on the current page
Prism.highlightAll();

If you don’t want Prism rescanning the entire DOM you can selectively highlight elements with the highlightElement() function.

// Say you have a code block like this
/**
  <pre>
    <code id="some-code" class="language-javascript">
      // This is some random code
      var foo = "bar"
    </code>
  </pre>
*/

// Be sure to select the inner <code> and not the <pre>

// Using plain Javascript
var block = document.getElementById('some-code')
Prism.highlightElement(block);

// Using JQuery
Prism.highlightElement($('#some-code')[0]);

It’s as simple as that!
