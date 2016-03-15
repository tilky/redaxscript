<?php
namespace Redaxscript;

/* strict reporting */

error_reporting(E_STRICT || E_ERROR);

/* include files */

include_once('includes/Autoloader.php');
include_once('TestCase.php');
include_once('includes/query.php');

/* init */

Autoloader::init();
Request::init();

/* get instance */

$registry = Registry::getInstance();
$config = Config::getInstance();

/* get environment */

$dbType = getenv('DB_TYPE');

/* mysql and pgsql */

if ($dbType === 'mysql' || $dbType === 'pgsql')
{
	if ($dbType === 'mysql')
	{
		echo 'MySQL - ';
		$config->set('dbUser', 'root');
	}
	else
	{
		echo 'PostgreSQL - ';
		$config->set('dbUser', 'postgres');
	}
	$config->set('dbType', $dbType);
	$config->set('dbHost', '127.0.0.1');
	$config->set('dbName', 'test');
	$config->set('dbPassword', 'test');
	$config->set('dbSalt', 'test');
}

/* sqlite */

else
{
	echo 'SQLite - ';
	$config->set('dbType', 'sqlite');
}

/* database */

Db::construct($config);
Db::init();

/* installer */

$installer = new Installer($config);
$installer->init();
$installer->rawDrop();
$installer->rawCreate();
$installer->insertData(array(
	'adminName' => 'Admin',
	'adminUser' => 'admin',
	'adminPassword' => 'admin',
	'adminEmail' => 'admin@admin.com'
));

/* test user */

Db::forTablePrefix('users')
	->create()
	->set(array(
		'name' => 'test',
		'user' => 'test',
		'email' => 'test@test.com',
		'password' => 'test'
	))
	->save();

/* test module */

if (is_dir('modules/TestDummy'))
{
	$testDummy = new Modules\TestDummy\TestDummy;
	$testDummy->install();
}

/* hook */

Hook::construct($registry);
Hook::init();

/* language */

$language = Language::getInstance();
$language::init('en');
