<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateTables extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'db:truncate';
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Truncate database tables.';
	/**
	 * Database tables
	 * @var array
	 */
	protected $tables;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        parent::__construct();
        $tables = DB::select('SHOW TABLES');
        $this->tables = array_map('current', $tables);
		// $this->tables = [
		// 	'users',
		// ];
	}
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		foreach( $this->tables as $table )
			DB::table($table)->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		return true;
	}
}