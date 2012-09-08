<?php namespace Flare;

use \Session;

/**
 * A Laravel bundle to help storage and retrieval of messages
 * for output to users
 *
 * @package     Bundles
 * @subpackage  Messages
 * @author      JonoB
 * @version 	1.0.0
 *
 * @see http://github.com/JonoB/flare-messagely
 */
class Messagely
{
	/**
	 * The message container
	 */
	private static $messages = array();

	/**
	 * Flash message container
	 */
	private static $new_flash_messages = array();

	/**
	 * The name of the flash session container
	 */
	private static $flash_container = 'messagely';

	/**
	 * Store if the old flash messages have been retrieved
	 */
	private static $init = false;

	/**
	 * Add a message
	 *
	 * @param 	string $group
	 * @param 	mixed $message If this is an array, then each item will be added to the specified group
	 * @param 	bool $flash set to true if this is a flash message
	 * @access 	public
	 * @return 	void
	 */
	public static function add($group, $message = '', $flash = false)
	{
		// Skip empty messages
		if (empty($message))
		{
			return;
		}

		if (is_array($message))
		{
			foreach ($message as $msg)
			{
				self::add($group, $msg, $flash);
			}
		}
		else
		{
			if ($flash)
			{
				self::$new_flash_messages[$group][] = $message;
				Session::flash(self::$flash_container, self::$new_flash_messages);
			}
			else
			{
				self::$messages[$group][] = $message;
			}
		}
	}

	/**
	 * Add messages to flash
	 *
	 * @param 	string $group
	 * @param 	mixed $message If this is an array, then each item will be added to the specified group
	 * @access 	public
	 * @return 	void
	 */
	public static function flash($group, $message = '')
	{
		self::add($group, $message, true);
	}

	/**
	 * Fetches all messages for the specified group. If no group was found this
	 * method will return FALSE instead of an array.
	 *
	 * @access	public
	 * @param	string $group The name of the group you want to retrieve.
	 * @return	array
	 */
	public static function get($group = '')
	{
		if ( ! self::$init)
		{
			// Append all the old flash messages to the messages array
			$flash = Session::get(self::$flash_container);
			if ($flash)
			{
				foreach ($flash as $flash_group => $msgs)
				{
					foreach($msgs as $msg)
					{
						self::$messages[$flash_group][] = $msg;
					}
				}
			}
			self::$init = true;
		}

		// If a group is specified we'll return it
		if ( ! empty($group))
		{
			return (isset(self::$messages[$group])) ? self::$messages[$group] : array();
		}

		// Otherwise we'll return all items
		else
		{
			return self::$messages;
		}
	}
}