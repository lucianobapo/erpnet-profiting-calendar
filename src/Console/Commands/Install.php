<?php

namespace ErpNET\Profiting\Calendar\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


use App\Models\Auth\Role;
use App\Models\Auth\Permission;

class Install extends Command
{
    protected $progressBar;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'erpnet:profiting-calendar:install
                                {--timeout=300} : How many seconds to allow each process to run.
                                {--debug} : Show process output or not. Useful for debugging.';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ErpNET\Profiting\Calendar install and execute';

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
    public function handle()
    {
        //
        //$this->info(" Backpack\Base installation started. Please wait...");
        $this->progressBar = $this->output->createProgressBar(4);
        $this->progressBar->start();
        $this->info(" ErpNET\\Profiting\\Calendar installation started. Please wait...");
        $this->progressBar->advance();
        
        //step 1
        $this->line(' Creating Permissions......');
        $this->updatePermissions('full-calendar-controller');
        $this->progressBar->advance();
        
        //step 2
        $this->line(' Publishing Files...');
        $this->executeProcess('php artisan vendor:publish --force --provider="ErpNET\Profiting\Calendar\Providers\ErpnetProfitingCalendarServiceProvider"');
        $this->progressBar->advance();
        
        //step 3
        $this->line(' Migrate DB...');
        $this->executeProcess('php artisan migrate --force --step');
        $this->progressBar->advance();

        //step 4
        $this->progressBar->finish();
        $this->info(" ErpNET\\Profiting\\Calendar installation finished.");
    }

    /**
     * Run a SSH command.
     *
     * @param string $command      The SSH command that needs to be run
     * @param bool   $beforeNotice Information for the user before the command is run
     * @param bool   $afterNotice  Information for the user after the command is run
     *
     * @return mixed Command-line output
     */
    public function executeProcess($command, $beforeNotice = false, $afterNotice = false):void
    {
        $this->echo('info', $beforeNotice ? ' '.$beforeNotice : $command);

        $process = new Process($command, null, null, null, $this->option('timeout'), null);
        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                $this->echo('comment', $buffer);
            } else {
                $this->echo('line', $buffer);
            }
        });

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $this->executeProcess('php artisan migrate:reset');
            throw new ProcessFailedException($process);
        }

        if ($this->progressBar) {
            $this->progressBar->advance();
        }

        if ($afterNotice) {
            $this->echo('info', $afterNotice);
        }
    }

    /**
     * Write text to the screen for the user to see.
     *
     * @param [string] $type    line, info, comment, question, error
     * @param [string] $content
     */
    public function echo($type, $content)
    {
        if ($this->option('debug') == false) {
            return;
        }

        // skip empty lines
        if (trim($content)) {
            $this->{$type}($content);
        }
    }

    protected function updatePermissions($tag)
    {
        
        // Check if already exists
        if ($p = Permission::where('name', 'create-'.$tag)->value('id')) {
            dbg('Error: Permission create-productions already exists');
            return;
        }
        
        $permissions = [];
        
        // Item Groups
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-'.$tag,
            'display_name' => 'Create '.$tag,
            'description' => 'Create '.$tag,
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-'.$tag,
            'display_name' => 'Read '.$tag,
            'description' => 'Read '.$tag,
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-'.$tag,
            'display_name' => 'Update '.$tag,
            'description' => 'Update '.$tag,
        ]);
        
        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-'.$tag,
            'display_name' => 'Delete '.$tag,
            'description' => 'Delete '.$tag,
        ]);
        
        // Attach permission to roles
        $roles = Role::all();
        
        foreach ($roles as $role) {
            $allowed = ['admin', 'manager'];
            
            if (!in_array($role->name, $allowed)) {
                continue;
            }
            
            foreach ($permissions as $permission) {
                $role->attachPermission($permission);
            }
        }
    }
}
