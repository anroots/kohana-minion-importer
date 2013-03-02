<?php defined('SYSPATH') or die('No direct script access.');
abstract class Kohana_Minion_Task_Batch extends Minion_Task
{


	/**
	 * Execute the task with the specified set of options
	 *
	 * @return null
	 */
	public function execute()
	{
		$options = $this->get_options();

		// Validate $options
		$validation = Validation::factory($options);
		$validation = $this->build_validation($validation);

		if ($this->_method != '_help' AND ! $validation->check())
		{
			echo View::factory('minion/error/validation')
				->set('task', Minion_Task::convert_class_to_task($this))
				->set('errors', $validation->errors($this->get_errors_file()));
		} else
		{
			// Finally, run the task
			$method = $this->_method;

			$task_start = microtime(TRUE);

			echo $this->{$method}($options);

			Minion_CLI::write(
				__(
					'Execution time: {time} sec.',
					[
						'{time}' => Minion_Batch::get_execution_time($task_start)
					]
				)
			);
		}
	}


}