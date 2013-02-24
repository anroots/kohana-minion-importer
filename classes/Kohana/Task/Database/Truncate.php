<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Truncate the local database.
 *
 * @used-by Task_Import
 * @author Ando Roots <ando@sqroot.eu>
 */
class Kohana_Task_Database_Truncate extends Minion_Task
{

	protected $_options = [
		'table' => FALSE // Only truncate a single table
	];

	/**
	 * @param array $params
	 * @throws Import_Exception
	 * @return string
	 */
	protected function _execute(array $params)
	{
		Minion_Import::write_replace("Truncating the local database...");
		try
		{
			Minion_Import::truncate_local_database($this->_options['table']);
			Minion_Import::write_replace(
				"Truncating the local database...\t\t".Minion_CLI::color('OK', 'green'),
				TRUE
			);
		} catch (Database_Exception $e)
		{
			Minion_Import::write_replace(
				"Truncating the local database...\t\t".Minion_CLI::color('FAILED', 'red'),
				TRUE
			);
			Minion_Import::write($e->getMessage());
			Minion_Import::write(Minion_CLI::color('Import aborted.', 'red'));
			throw new Import_Exception($e->getMessage());
		}
	}
}