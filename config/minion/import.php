<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Configuration for kohana-minion-importer
 *
 * @author Ando Roots <ando@sqroot.eu>
 */
return [

	/**
	 * The order in which DB tables are truncated.
	 * Used to avoid InnoDB FK errors.
	 * Only listed tables will be truncated.
	 */
	'truncate_order' => [
	],

	/**
	 * List of models to import from Minion_Import folder.
	 * The order is significant. Consider foreign key dependencies.
	 */
	'models'         => [

	]
];