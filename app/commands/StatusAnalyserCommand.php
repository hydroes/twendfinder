<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class StatusAnalyserCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'status:analyser';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Analyses statuses (tweets) and records relevent stats';

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
            // create zmq socket
            $socket = \App::make('zeroMqSubscriberSocket');

            $statAnalyser = \App::make('StatsAnalyser');

            // prepare keywords (remove i ...)
            $keywords = Config::get('twitter.keywords');
            foreach ($keywords as &$keyword)
            {
                $keyword = substr($keyword, 2);
            }

            while (true)
            {
                // Get message details
                $address = $socket->recv();
                $contents = $socket->recv();

                $data = json_decode($contents);

                // analyse status
                $statAnalyser->count($data, $keywords);

            }

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
