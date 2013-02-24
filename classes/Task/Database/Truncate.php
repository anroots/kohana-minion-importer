<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Truncates the local database.
 *
 * The tables and truncate order is read from the config file.
 *
 * Options:
 *  - table: the name of the table to truncate. Default: all
 *
 * @author Ando Roots <ando@sqroot.eu>
 */
class Task_Database_Truncate extends Kohana_Task_Database_Truncate
{

}