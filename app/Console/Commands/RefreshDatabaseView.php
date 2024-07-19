<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Finder\Finder;

class RefreshDatabaseView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-db-view {--drop}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh database view definition';

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
        $this->createOrReplaceView(Finder::create()->files()->in(resource_path('sql/view/liquid')));
        $this->createOrReplaceView(Finder::create()->files()->in(resource_path('sql/view/materialized')), true);
        $this->createOrReplaceView(Finder::create()->files()->in(resource_path('sql/view/penilai-liquid')), true);
    }

    protected function createOrReplaceView(Finder $finder, $materialized = false)
    {
        foreach ($finder as $file) {
            $name = $file->getBasename('.sql');
            $query = $file->getContents();

            if ($query) {

                // Drop anything
                try {
                    DB::statement("DROP VIEW $name");
                } catch (QueryException $e) {
                    // $this->error($e->getMessage());
                }
                try {
                    DB::statement("DROP MATERIALIZED VIEW $name");
                } catch (QueryException $e) {
                    // $this->error($e->getMessage());
                }

                if ($this->option('drop') === false) {
                    if ($materialized) {
                        DB::statement("CREATE MATERIALIZED VIEW $name AS $query");
                    } else {
                        DB::statement("CREATE OR REPLACE VIEW $name AS $query");
                    }
                }
            }
        }
    }
}
