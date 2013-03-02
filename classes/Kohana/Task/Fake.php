<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 * @author Ando Roots <ando@sqroot.eu>
 */
class Kohana_Task_Fake extends Minion_Task_Batch
{

	protected $_options = [
		'limit'       => 50,
		'quiet'       => FALSE,
		'no-truncate' => FALSE
	];

	/**
	 *
	 * @param array $params CLI params
	 * @throws Minion_Exception
	 * @return null
	 */
	protected function _execute(array $params)
	{

		// Truncate local DB?
		if ($this->_options['no-truncate'] === FALSE)
		{
			Minion_Task::factory(
				[
					'task' => "batch".Minion_Task::$task_separator."database".Minion_Task::$task_separator."truncate",
				]
			)->execute();
		}

		// List of models that will be imported. Order is significant (DB FK)
		$import_models = Kohana::$config->load('minion/batch.fake.models');

		if (! is_array($import_models) || ! count($import_models))
		{
			Minion_CLI::write(Minion_CLI::color('config/minion/batch.php does not specify any models to fake.', 'red'));
			return;
		}

		foreach ($import_models as $model_name)
		{
			$imported_rows_count = $this->_import_model($model_name);
		}

		$this->_summary['execution_time'] = Minion_Batch::get_execution_time(static::$execution_start);

		Minion_Import::write(Minion_CLI::color('Import successfully completed.', 'green'));

	}

	/**
	 * Execute import model.
	 *
	 * @param string $model_name File name in Minion_Import_ dir
	 * @return int The number of imported rows
	 * @throws Minion_Exception
	 * @throws Import_Exception
	 */
	protected function _import_model($model_name)
	{
		$model_name = 'Minion_Import_'.$model_name;
		$model = new $model_name;

		if (! $model instanceof Minion_Import_Importable)
		{
			throw new Minion_Exception('Class :class does not implement the importable interface.', [
				':class' => $model_name
			], '500');
		}

		try
		{
			$imported_rows_count = $model->set_limit($this->_options['limit'])
				->import();
		} catch (ORM_Validation_Exception $e)
		{
			throw new Import_Exception('Validation exception: '.print_r($e->errors(), TRUE));
		} catch (Kohana_Exception $e)
		{
			throw new Import_Exception($e->getMessage());
		}
		return $imported_rows_count;
	}
}