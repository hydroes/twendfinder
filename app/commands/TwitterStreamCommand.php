<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TwitterStreamCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'twitter:stream';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Streams tweets from twitter';

        protected $_twitterConsumer;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

                // put this into the registry
                define("TWITTER_CONSUMER_KEY", Config::get('twitter.consumer_key'));
                define("TWITTER_CONSUMER_SECRET", Config::get('twitter.consumer_secret'));
                define("OAUTH_TOKEN", Config::get('twitter.access_token'));
                define("OAUTH_SECRET", Config::get('twitter.access_token_secret'));
                $this->_twitterConsumer = new FilterTrackConsumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
            $this->_twitterConsumer->consume();

            // log errors
            Log::info('Phirehose', array(
                'consumer err' => $this->_twitterConsumer->getLastErrorMsg())
            );
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