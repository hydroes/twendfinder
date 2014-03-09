<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DeployCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'deploy:remote';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deploys repo to live server.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
            // Live deployment
            $commands = array(
                'echo "logged on"',
                'cd /var/www',
                'git clone git@bitbucket.org:hydroes/trender.git trender_latest',
                'cd trender_latest',
                'echo "updating composer.phar"',
                'php composer.phar selfupdate',
                'echo "running composer install"',
                'php composer.phar install',
                'echo "applying folder permissions"',
                'cd app/',
                'chown -R www-data storage',
                'chmod 0775 -R storage',
                'service supervisor stop',
                'echo "transferring trender_new to trender"',
                'cd /var/www',
                'mv trender "`date "+trender_%m_%d_%y__%H_%M_%S"`"',
                'mv trender_latest trender',
                'service supervisor start'
            );

            SSH::run($commands, function($line)
            {
                echo $line.PHP_EOL;
            });
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
