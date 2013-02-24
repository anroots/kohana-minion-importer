<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Base class for Minion_Import models
 *
 * @author Ando Roots <ando@sqroot.eu>
 */
abstract class Kohana_Minion_Import extends Model
{

	/**
	 * @var int Number of rows to import
	 */
	protected $_limit;

	/**
	 * @var bool TRUE to reduce CLI output
	 */
	public static $_quiet = FALSE;

	/**
	 * @param int $limit
	 * @return Kohana_Minion_Import
	 */
	public function set_limit($limit)
	{
		$this->_limit = $limit;
		return $this;
	}

	/**
	 * @param bool $quiet
	 */
	public static function set_quiet($quiet = TRUE)
	{
		self::$_quiet = $quiet;
	}

	/**
	 * Truncates the local database of all data so that the import script can repopulate it.
	 *
	 * @static
	 * @param bool|string $specific_table Only truncate the specified table. Defaults to all.
	 * @return bool FALSE on failure
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
	 * The progress is displayed on the CLI during import.
	 *
	 * @param int $items_done Number of items already imported
	 * @param int $total_items Total number of items to import
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

		if ($is_done)
		{
			$time = self::_get_execution_time($start_time);
			$start_time = NULL;
			Minion_Import::write_replace($prefix.Minion_CLI::color(__('OK, {sec} sec', ['{sec}' => $time]), 'green'), TRUE);
		} else
		{
			Minion_Import::write_replace("{$prefix}{$items_done} {$separator} {$total_items}");
		}

		return $this;
	}

	/**
	 * @return int The time the import started
	 */
	protected static function _get_start_time()
	{
		$time = microtime();
		$time = explode(' ', $time);
		return $time[1] + $time[0];
	}

	/**
	 * @param int $start_time
	 * @return float Number of elapsed seconds since $start_time
	 */
	protected static function _get_execution_time($start_time)
	{
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		return round(($finish - $start_time), 4);
	}

	/**
	 * Wrapper for text output. No output is given in quiet mode.
	 *
	 * @param string $text Text to write to the CLI
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
	 * Wrapper for text output. No output is given in quiet mode.
	 *
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