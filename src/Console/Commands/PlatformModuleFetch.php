<?php

namespace Monoland\Platform\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PlatformModuleFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Check if there's a new commit or new tags for each modules repositroy";

    /**
     * handle function
     *
     * @return void
     */
    public function handle()
    {
        $modules = [];

        // git ls-remote --heads | repo_url
        // git ls-remote --tags  | repo_url

        // 1. masukkin current tags / heads ke setiap module yang terdetect di module list 
        // 2. coba fetch masing-masing repo_url untuk check apakah ada tags / heads yang berbeda

        foreach (Cache::get('modules') as $module) {
            array_push($modules, [
                $module->namespace,
                $module->name,
                $module->repo_url,
                $module->repo_path,
            ]);
        }

        array_multisort(array_column($modules, 3), SORT_ASC, $modules);

        $this->table(
            ['Namespace', 'Name', 'Repo Url', 'Repo Path'],
            $modules
        );
    }

    /**
     * fetchTagsModule function
     *
     * @return array
     */
    protected function fetchTagsModule($repository): array | null
    {
        $this->info('Trying to fetch tags from the repository');

        $process = new Process(["git", "ls-remote", "--tags", $repository]);
        $tags = [];

        try {
            $process->mustRun();
            // try to query the tags that has been fetched
            preg_match_all('/(?<=refs\/tags\/).*/', $process->getOutput(), $tags);
        } catch (ProcessFailedException $exception) {
            $this->info('Fetch failed');
            echo $exception->getMessage();
        }

        if (count($tags) > 0 && count($tags[0]) > 0) {
            return $tags[0];
        } else if ($process->getOutput() != "") {
            $this->info('==========================================');
            $this->info('Tags not found, displaying process result incase of error : ');
            $this->info($process->getOutput());
            $this->info('=========================================');
        } else {
            $this->info('==========================================');
            $this->info('Tags not found ');
        }

        return $tags[0];
    }
}
