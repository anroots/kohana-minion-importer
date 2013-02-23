<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Example model implementation.
 * Imports rows from the legacy companies database
 */
class Minion_Import_Company extends Minion_Import implements Minion_Import_Importable
{

	/**
	 * Import data from the remote system
	 *
	 * @since 0.2
	 * @throws Import_Exception
	 * @return int The number of imported records (rows)
	 */
	public function import()
	{
		$companies = DB::select()
			->from('companies')
			->where('deleted', '=', 0)
			->limit($this->_limit)
			->as_object()
			->order_by('id', 'ASC')
			->execute(self::DB_PROFILE);

		foreach ($companies as $company)
		{
			$this->update_progress($companies->key(), $companies->count());
			Database::instance(self::DB_PROFILE)->begin();
			$this->migrate($company);
			Database::instance(self::DB_PROFILE)->commit();
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