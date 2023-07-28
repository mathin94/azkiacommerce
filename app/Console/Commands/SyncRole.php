<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class SyncRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Master Role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $roles = DB::connection('mysql_pos')->table('roles')
            ->where('deleted_at', null)
            ->where('ecommerce_enabled', true)
            ->orderBy('id', 'asc')
            ->get();

        $role_ids = [];

        foreach ($roles as $_role) {
            $role = Role::updateOrCreate(['name' => $_role->name]);
            $this->info("$_role->name created.");

            $role_ids[] = $role->id;
        }

        Role::whereNotIn('id', $role_ids)->delete();
    }
}
