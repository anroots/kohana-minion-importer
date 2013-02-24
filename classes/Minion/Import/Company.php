<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Example import model implementation.
 *
 * This example assumes that you want to import companies from the old database to this new one.
 * Both databases hold companies info in the table `companies`, however the columns and data structure are different.
 * Data is selected from the remote, processed and inserted into the local database.
 *
 * @author Ando Roots <ando@sqroot.eu>
 */
class Minion_Import_Company extends Minion_Import implements Minion_Import_Importable
{

	/**
	 * Import company data from the remote system
	 *
	 * @throws Import_Exception
	 * @return int The number of imported records (rows)
	 */
	public function import()
	{
		// Select rows from the remote DB. Beware of really big tables, should be done in iterations
		$companies = DB::select()
			->from('companies')
			->where('deleted', '=', 0)
			->limit($this->_limit)
			->as_object()
			->order_by('id', 'ASC')
			->execute('import');

		// Import each legacy company
		foreach ($companies as $company)
		{
			// Update CLI progress bar
			$this->update_progress($companies->key(), $companies->count());

			// Actual migration
			Database::instance('import')->begin();
			$this->migrate($company);
			Database::instance('import')->commit();
		}

		return $companies->count();
	}

	/**
	 * Import a single row
	 *
	 * @param stdClass $row_data
	 * @return Minion_Import_Importable
	 */
	public function migrate(stdClass $row_data)
	{
		$company = ORM::factory('Company');
		$company->name = $row_data->name;
		$company->code = $row_data->code;
		return $company->save();
	}
}