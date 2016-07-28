<?php
namespace Redaxscript\Console\Command;

use Redaxscript\Db;
use Redaxscript\Console\Parser;
use Redaxscript\Installer;

/**
 * children class to execute the install command
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Console
 * @author Henry Ruhs
 */

class Install extends CommandAbstract
{
	/**
	 * array of the command
	 *
	 * @var array
	 */

	protected $_commandArray =
	[
		'install' =>
		[
			'description' => 'Install command',
			'argumentArray' =>
			[
				'database' =>
				[
					'description' => 'Install the database',
					'optionArray' =>
					[
						'admin-name' =>
						[
							'description' => 'Required admin name'
						],
						'admin-user' =>
						[
							'description' => 'Required admin user'
						],
						'admin-password' =>
						[
							'description' => 'Required admin password'
						],
						'admin-email' =>
						[
							'description' => 'Required admin email'
						]
					]
				],
				'module' =>
				[
					'description' => 'Install the module',
					'optionArray' =>
					[
						'alias' =>
						[
							'description' => 'Required module alias'
						]
					]
				]
			]
		]
	];

	/**
	 * run the command
	 *
	 * @since 3.0.0
	 *
	 * @param string $mode name of the mode
	 *
	 * @return string
	 */

	public function run($mode = null)
	{
		$parser = new Parser($this->_request);
		$parser->init($mode);

		/* run command */

		$argumentKey = $parser->getArgument(1);
		if ($argumentKey === 'database')
		{
			return $this->_database($parser->getOption());
		}
		if ($argumentKey === 'module')
		{
			return $this->_module($parser->getOption());
		}
		return $this->getHelp();
	}

	/**
	 * install the database
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 *
	 * @return boolean
	 */

	protected function _database($optionArray = [])
	{
		$adminName = $this->prompt('admin-name', $optionArray);
		$adminUser = $this->prompt('admin-user', $optionArray);
		$adminPassword = $this->prompt('admin-password', $optionArray);
		$adminEmail = $this->prompt('admin-email', $optionArray);
		if ($adminName && $adminUser && $adminPassword && $adminEmail)
		{
			$installer = new Installer($this->_config);
			$installer->init();
			$installer->rawCreate();
			$installer->insertData(
			[
				'adminName' => $adminName,
				'adminUser' => $adminUser,
				'adminPassword' => $adminPassword,
				'adminEmail' => $adminEmail
			]);
			return Db::getStatus() === 2;
		}
		return false;
	}

	/**
	 * install the module
	 *
	 * @since 3.0.0
	 *
	 * @param array $optionArray
	 *
	 * @return boolean
	 */

	protected function _module($optionArray = [])
	{
		$alias = $this->prompt('alias', $optionArray);
		$moduleClass = 'Redaxscript\\Modules\\' . $alias . '\\' . $alias;
		if (class_exists($moduleClass))
		{
			$module = new $moduleClass;
			$module->install();
			return Db::forTablePrefix('modules')->where('alias', $alias)->count() > 0;
		}
		return false;
	}
}