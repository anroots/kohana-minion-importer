# CLI Data Import Module For Kohana

Extends the [Minion](https://github.com/kohana/minion) module to provide a framework for importing/populating data.

![Example terminal session](https://raw.github.com/anroots/kohana-minion-importer/master/Screenshot-1.png)

Two main use cases:

* Importing legacy data from external databases
* Generating dummy sample data

### Real world use case

You are tasked with creating a new version of your in-house issue tracker, from scratch. You design your new database model and
 now you need to import existing data from the old system. The schema has changed so the columns do not match one-to-one and
 maybe you want to filter out deleted issues and what-knot.

Use this module to help you do that. Simply define a `Minion_Import_*` model for your entity to do data fetching,
mapping and filtering. [See example implementation for companies](https://github.com/anroots/kohana-minion-importer/blob/master/classes/Minion/Import/Company.php).

## Usage

* Create class: `Minion_Import_Company`
* Implement `Minion_Import_Importable`
* Modify `APPPATH.'config/minion/import.php'` (copy sample from the module dir), add your new model to the `truncate_order` and
 `models` arrays
* Run `./minion import` from the CLI

## Requires

* Kohana Framework 3.3
* PHP 5.4
* Minion, Database, ORM modules

## Installation

### Place the files in your modules directory

#### As a Git submodule:

```bash
git clone git://github.com/anroots/kohana-minion-importer.git modules/minion-importer
```
#### As a [Composer dependency](http://getcomposer.org)

```javascript
{
	"require": {
		"anroots/minion-importer":"1.*"
	}
}
```

### Activate the module in `bootstrap.php`.

```php
<?php
Kohana::modules(array(
	...
	'minion-importer' => MODPATH.'minion-importer',
));
```

## Licence

MIT, do whatever. Pull requests and feedback appreciated.