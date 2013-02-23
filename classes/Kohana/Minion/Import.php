<?php defined('SYSPATH') or die('No direct script access.');
abstract class Kohana_Minion_Import extends Model
{

	/**
	 * Name of the Database profile to use as the import DB, remote database where data is fetched
	 */
	const DB_PROFILE = 'import';

	protected $_limit;
	public static $_quiet = FALSE;


	public function set_limit($limit)
	{
		$this->_limit = $limit;
		return $this;
	}

	/**
	 * Truncates the local database of all data so that the import script can repopulate it.
	 *
	 * @static
	 * @since 0.1
	 * @param bool $specific_table
	 * @return bool
	 */
	public static final function truncate_local_database($specific_table = FALSE)
	{

		// The order of tables is important - FK exceptions
		$tables = Kohana::$config->load('minion/import.truncate_order');

		if (! is_array($tables) || ! count($tables))
		{
			return FALSE;
		}
		foreach ($tables as $table)
		{
			if ($specific_table && $specific_table != $table)
			{
				continue;
			}
			DB::delete($table)
				->execute();
		}

		return TRUE;
	}

	/**
	 * Update current model's import progress.
	 *
	 * @param int $items_done
	 * @param int $total_items
	 * @return \Minion_Import
	 */
	public function update_progress($items_done, $total_items)
	{

		if (self::$_quiet)
		{
			return $this;
		}

		static $start_time;

		if ($start_time === NULL)
		{
			$start_time = self::_get_start_time();
		}

		$prefix = get_class($this)."...\t\t";
		$is_done = $items_done === $total_items - 1; // We start from 0
		$separator = Text::alternate('/', '-', '\\', '|');

		if (! $is_done)
		{
			Minion_Import::write_replace("{$prefix}{$items_done} {$separator} {$total_items}");
		} else
		{
			$time = self::_get_execution_time($start_time);
			$start_time = NULL;
			Minion_Import::write_replace($prefix.Minion_CLI::color(__('OK, {sec} sec', ['{sec}' => $time]), 'green'), TRUE);
		}

		return $this;
	}

	protected static function _get_start_time()
	{
		$time = microtime();
		$time = explode(' ', $time);
		return $time[1] + $time[0];
	}

	protected static function _get_execution_time($start_time)
	{
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		return round(($finish - $start_time), 4);
	}

	/**
	 * @param bool $quiet
	 */
	public static function set_quiet($quiet)
	{
		self::$_quiet = $quiet;
	}

	/**
	 * @param string $text
	 */
	public static function write($text = '')
	{
		if (self::$_quiet)
		{
			return;
		}
		Minion_CLI::write($text);
	}

	/**
	 * @param string $text
	 * @param bool $end_line
	 */
	public static function write_replace($text = '', $end_line = FALSE)
	{
		if (self::$_quiet)
		{
			return;
		}
		Minion_CLI::write_replace($text, $end_line);
	}
}