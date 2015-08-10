<?php
namespace Redaxscript\Modules\SyntaxHighlighter;

use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * javascript powered syntax highlighter
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class SyntaxHighlighter extends Module
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'Syntax highlighter',
		'alias' => 'SyntaxHighlighter',
		'author' => 'Redaxmedia',
		'description' => 'Javascript powered syntax highlighter',
		'version' => '2.6.0'
	);

	/**
	 * loaderStart
	 *
	 * @since 2.6.0
	 */

	public static function loaderStart()
	{
		global $loader_modules_scripts;
		$loader_modules_scripts[] = 'modules/SyntaxHighlighter/scripts/init.js';
		$loader_modules_scripts[] = 'modules/SyntaxHighlighter/scripts/syntax_highlighter.js';
	}

	/**
	 * stylesEnd
	 *
	 * @since 2.6.0
	 */

	public static function stylesEnd()
	{
		$output = '<link href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.7/styles/github.min.css" rel="stylesheet" />';
		echo $output;
	}

	/**
	 * scriptsEnd
	 *
	 * @since 2.6.0
	 */

	public static function scriptsEnd()
	{
		$output = '<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.7/highlight.min.js"></script>';
		echo $output;
	}
}
