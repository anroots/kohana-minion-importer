<?php defined('SYSPATH') or die('No direct script access.');
class Kohana_Minion_Batch
{

	/**
	 * @param int $start_time
	 * @return float Number of elapsed seconds since $start_time
	 */
	public static function get_execution_time($start_time)
	{
		return round((microtime(TRUE) - $start_time), 4);
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

		Minion_Import::write_replace("Truncating...");

		foreach ($tables as $table)
		{
			if ($specific_table && $specific_table != $table)
			{
				continue;
			}

			Minion_Import::write_replace('Truncating... '.Minion_CLI::color('`'.$table.'`', 'light_blue'));
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
			$start_time = self::get_start_time();
		}

		$prefix = get_class($this)."... ";
		$is_done = $items_done === $total_items - 1; // We start from 0
		$separator = Text::alternate('/', '-', '\\', '|');

		if ($is_done)
		{
			$time = self::get_execution_time($start_time);
			$start_time = NULL;
			Minion_Import::write_replace($prefix.Minion_CLI::color(__('OK, {sec} sec', ['{sec}' => $time]), 'green'), TRUE);
		} else
		{
			Minion_Import::write_replace("{$prefix}{$items_done} {$separator} {$total_items}");
		}

		return $this;
	}





}