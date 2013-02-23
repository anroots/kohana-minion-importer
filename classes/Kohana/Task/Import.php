<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Import data from the legacy system
 *
 * The import is destructive, the existing database will be truncated.
 *
 * @author Ando Roots <ando@sqroot.eu>
 */
class Kohana_Task_Import extends Minion_Task
{

	protected $_options = [
		'limit' => 50,
		'model' => FALSE, // Only import a specific model
		'quiet' => FALSE,
	];

	/**
	 * Truncate local database and import everything from the remote
	 *
	 * @param array $params CLI params
	 * @throws Minion_Exception
	 * @return null
	 */
	protected function _execute(array $params)
	{
		if ($this->_options['quiet'])
		{
			Minion_Import::set_quiet(TRUE);
		}

		Minion_Import::write('Import from remote database started.');

		if (! $this->_options['model'])
		{
			// Truncate local DB
			try
			{
				Minion_Task::factory(
					[
						'task' => 'database:truncate',
					]
				)->execute();
			} catch (Import_Exception $e)
			{
				return;
			}
		}
		// List of models that will be imported. Order is significant (DB FK)
		$import_models = Kohana::$config->load('minion/import.models');

		if (! is_array($import_models) || ! count($import_models))
		{
			Minion_Import::write(Minion_CLI::color('The config file does not specify any models to import.', 'red'));
			return;
		}
		foreach ($import_models as $model_name)
		{
			if ($this->_options['model'] && $this->_options['model'] !== $model_name)
			{
				continue;
			}

			$model_name = 'Minion_Import_'.$model_name;
			$model = new $model_name;

			if (! $model instanceof Minion_Import_Importable)
			{
				throw new Minion_Exception('Class :class does not implement the importable interface.', [
					':class' => $model_name
				], '500');
			}

			$model->set_limit($this->_options['limit'])
				->import();
		}

		Minion_Import::write(Minion_CLI::color("\nImport successfully completed.", 'green'));
	}


}

