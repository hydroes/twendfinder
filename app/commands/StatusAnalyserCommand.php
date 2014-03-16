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
            
            while (true) 
            {
                // Get message details
                $address = $socket->recv();
                $contents = $socket->recv();
//                printf ("[%s] %s%s", $address, $contents, PHP_EOL);
                
                // count statuses per minute
                $key = date('d_m_Y_H_i_s');
                \Cache::increment('key');
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
