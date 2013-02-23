<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Interface for legacy system models.
 * Classes that implement this interface should be able to import some data from
 * the remote legacy system.
 *
 * @since 0.2
 */
interface Kohana_Minion_Import_Importable
{

	/**
	 * Import data from the remote system
	 *
	 * @since 0.2
	 * @abstract
	 * @throws Import_Exception
	 * @return int The number of imported records (rows)
	 */
	public function import();

	/**
	 * Import a single row
	 *
	 * @param stdClass $row_data
	 * @return Minion_Import_Importable
	 */
	public function migrate(stdClass $row_data);

	/**
	 * Limit the max number of records to import.
	 *
	 * @param int $limit
	 * @return Minion_Import_Importable
	 */
	public function set_limit($limit);
}