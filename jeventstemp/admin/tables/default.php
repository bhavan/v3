<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: jevuser.php 1630 2009-11-26 05:31:25Z geraint $
 * @package     JEvents
 * @copyright   Copyright (C)  2008-2009 GWE Systems Ltd
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://www.jevents.net
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
* User Table class
*
* @subpackage	Users
* @since 1.0
*/
class TableDefault extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var string
	 */
	var $name = null;

	var $title = null;
	var $subject = null;
	var $value = null;
	var $state = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct() {
		$db =& JFactory::getDBO();
		parent::__construct('#__jev_defaults', 'name', $db);
	}


}
