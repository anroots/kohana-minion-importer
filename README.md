# CLI Data Import Module For Kohana

**Status: Working, but undocumented and needs generalizing. Use at own risk.**

![Example terminal session](https://raw.github.com/anroots/kohana-minion-importer/master/Screenshot-1.png)

Two main use cases:

* Importing legacy data from external databases
* Generating dummy sample data for your Models

## Requires

* Kohana Framework 3.3
* PHP 5.4
* Minion, Database, ORM modules

## Installation

### Place the files in your modules directory.

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