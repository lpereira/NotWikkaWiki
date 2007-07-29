<?php
/**
 * A compatibility library.
 *
 * This file contains a number of functions that provide compatibility support
 * in various situations: missing functions that are present in other versions
 * of PHP, functions to get around environmental differences (such as settings
 * in php.ini that a Wikka admin may not be able to touch), and similar issues.
 * Some small utilities that may be helpful during debugging (such as 
 * getmicrotime()) which might be helpful when dealing with different
 * implementations are also forced in here as there's no better place yet. ;)
 *
 * @package		Wikka
 * @subpackage	Libs
 * @version		$Id$
 * @license		http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @see			/docs/Wikka.LICENSE
 * @filesource
 *
 * @author		{@link http://www.mornography.de/ Hendrik Mans}
 * @author		{@link http://wikkawiki.org/JsnX Jason Tourtelotte}
 * @author		{@link http://wikkawiki.org/JavaWoman Marjolein Katsma}
 *
 * @copyright	Copyright 2002-2003, Hendrik Mans <hendrik@mans.de>
 * @copyright	Copyright 2004-2005, Jason Tourtelotte <wikka-admin@jsnx.com>
 * @copyright	Copyright 2006-2007, {@link http://wikkawiki.org/CreditsPage Wikka Development Team}
 */

/**
 * Get a microtime, either as a string or as a float.
 *
 * Can be used to calculate page generation time, or SQL or function profiling.
 *
 * Serves a wrapper to replicate or use PHP 5 behavior:
 * Use getmicrotime(TRUE) to get a float for calculations, without a parameter
 * to get a string.
 * See {@link http://php.net/microtime microtime} and comments there for the
 * background of this implementation.
 *
 * @param	boolean	$get_as_float	optional: set to TRUE if you want a float;
 *					default FALSE which specifies a string
 * @return	mixed	microtime, in the form of a string (default) or a float
 */
function getmicrotime($get_as_float=FALSE)
{
	if (version_compare(phpversion(),'5','>='))		// >= PHP 5?
	{
		return microtime($get_as_float);
	}
	else
	{
		$time = strtok(microtime(), ' ') + strtok('');
		return (FALSE === $get_as_float) ? $time : (float)$time;
	}
}

if (!function_exists('mysql_real_escape_string'))
{
	/**
	 * Escape special characters in a string for use in a SQL statement.
	 *
	 * This function is added for back-compatibility with MySQL 3.23.
	 * @param	string	$string	the string to be escaped
	 * @return	string	a string with special characters escaped
	 */
	function mysql_real_escape_string($string)
	{
		return mysql_escape_string($string);
	}
}

/**
 * Workaround for the amazingly annoying magic quotes.
 *
 * Note that this function will only operate on an array; if a scalar is passed
 * it is left untouched.
 *
 * @param	array	$a	array to be cleaned of "magic quotes" (slashes); passed
 *					by reference
 */
function magicQuotesWorkaround(&$a)
{
	if (is_array($a))
	{
		foreach ($a as $k => $v)
		{
			if (is_array($v))
			{
				magicQuotesWorkaround($a[$k]);
			}
			else
			{
				$a[$k] = stripslashes($v);
			}
		}
	}
}

/**
 * Instantiate a class (PHP version-independent).
 *
 * For compatibility between PHP4 and PHP5, either an explicit assign by
 * reference is used (PHP4), or a simple assign (PHP5, where the "by reference"
 * is automatic and explicit asignment by reference is deprecated).
 * The function supports up to three variables to be passed to the class'
 * constuctor.
 *
 * @param	string	$class	mandatory: name of the class to be instantiated
 * @param	mixed	$par1	optional: first parameter to be passed to constructor
 * @param	mixed	$par2	optional: second parameter to be passed to constructor
 * @param	mixed	$par3	optional: third parameter to be passed to constructor
 * @return	object	a reference of an object resulting from instantiation of the
 *			specified class
 */
function instantiate($class,$par1=NULL,$par2=NULL,$par3=NULL)
{
	if (version_compare(phpversion(),'5','>='))		// >= PHP 5?
	{
		$obj =  new $class($par1,$par2,$par3);		// [558] / #496 - comment 3
	}
	else
	{
		$obj =& new $class($par1,$par2,$par3);		// reverting [558] see #496 - comment 4
	}
	return $obj;
}
?>