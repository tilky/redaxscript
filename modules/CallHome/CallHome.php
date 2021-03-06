<?php
namespace Redaxscript\Modules\CallHome;

use Redaxscript\Filter;
use Redaxscript\Head;
use Redaxscript\Module;
use Redaxscript\Reader;

/**
 * provide version and news updates
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class CallHome extends Module\Notification
{
	/**
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray =
	[
		'name' => 'Call Home',
		'alias' => 'CallHome',
		'author' => 'Redaxmedia',
		'description' => 'Provide version and news updates',
		'version' => '3.3.1'
	];

	/**
	 * renderStart
	 *
	 * @since 3.0.0
	 */

	public function renderStart()
	{
		if ($this->_registry->get('loggedIn') === $this->_registry->get('token') && $this->_registry->get('firstParameter') === 'admin')
		{
			$script = Head\Script::getInstance();
			$script
				->init('foot')
				->appendFile('https://google-analytics.com/analytics.js')
				->appendFile('modules/CallHome/assets/scripts/init.js')
				->appendFile('modules/CallHome/dist/scripts/call-home.min.js');
		}
	}

	/**
	 * adminPanelNotification
	 *
	 * @since 3.0.1
	 *
	 * @return array|bool
	 */

	public function adminPanelNotification()
	{
		$reader = new Reader();
		$aliasFilter = new Filter\Alias();
		$version = $aliasFilter->sanitize($this->_language->get('version', '_package'));

		/* load result */

		$urlVersion = 'https://service.redaxscript.com/version/' . $version;
		$urlNews = 'https://service.redaxscript.com/news/' . $version;
		$versionArray = $reader->loadJSON($urlVersion)->getArray();
		$newsArray = $reader->loadJSON($urlNews)->getArray();

		/* process version */

		foreach ($versionArray as $version)
		{
			foreach ($version as $type => $message)
			{
				$this->setNotification($type, $message);
			}
		}

		/* process news */

		foreach ($newsArray as $news)
		{
			foreach ($news as $type => $message)
			{
				$this->setNotification($type, $message);
			}
		}
		return $this->getNotification();
	}
}
