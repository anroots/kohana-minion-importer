<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @since 0.2
 */
class Kohana_Import_Exception extends Kohana_Exception {

	public function __construct($message = 'Import exception!', array $variables = NULL, $code = 0)
	{
		parent::__construct($message, $variables, $code);
	}

}