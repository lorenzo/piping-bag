<?php
namespace PipingBag\Console;

use Composer\Script\Event;
use Exception;

/**
 * Provides installation hooks for when this application is installed via
 * composer. Customize this class to suit your needs.
 */
class Installer {

/**
 * Does some routine installation tasks so people don't have to.
 *
 * @param \Composer\Script\Event $event The composer event object.
 * @throws \Exception Exception raised by validator.
 * @return void
 */
	public static function postInstall(Event $event) {
		$io = $event->getIO();

		$rootDir = dirname(dirname(dirname(dirname(__DIR__))));

		// ask if it is ok to rewrite cake.php
		if ($io->isInteractive()) {
			$validator = (function ($arg) {
				if (in_array($arg, ['Y', 'y', 'N', 'n'])) {
					return $arg;
				}
				throw new Exception('This is not a valid answer. Please choose Y or n.');
			});
			$modify = $io->askAndValidate(
				'<info>Modify bin/cake.php to allow injection in Shells?</info> [<comment>Y,n</comment>]? ',
				$validator,
				false,
				'Y'
			);

			if (in_array($modify, ['Y', 'y'])) {
				static::modifyBin($rootDir, $io);
			}
		} else {
			static::modifyBin($rootDir, $io);
		}
	}

/**
 * Modifies bin/cake.php to allow injection in shells.
 *
 * @param string $dir The application's root directory.
 * @param \Composer\IO\IOInterface $io IO interface to write to console.
 * @return void
 */
	public static function modifyBin($dir, $io)
	{
		$bin = $dir . '/bin/cake.php';
		if (file_exists($bin)) {
			$contents = file_get_contents($bin);
			$contents = str_replace('Cake\Console\ShellDispatcher', 'PipingBag\Console\ShellDispatcher', $contents);
			file_put_contents($bin, $contents);
			$io->write('Done');
		} else {
			$io->write('Could not find the `bin/cake.php` file');
		}
	}

}
