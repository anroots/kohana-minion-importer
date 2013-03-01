<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Import data into the local database
 *
 * Runs your Minion_Import_* models to populate the local database.
 *
 * Options:
 *  - limit: Impose limit on import models. Max number of rows to insert.
 *  - quiet: Reduce debug output
 *  - model: Only import the specified model. No table is truncated.
 *  - no-truncate: Do not truncate the database
 *
 * @example ./minion import --limit=50 --quiet --no-truncate
 * @author Ando Roots <ando@sqroot.eu>
 */
class Task_Import extends Kohana_Task_Import
{

}