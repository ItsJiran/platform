<?php

namespace Monoland\Platform\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PlatformModuleClone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:clone
        {repository}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clone git repository to modules folder';

    /**
     * handle function
     *
     * @return void
     */
    public function handle()
    {
        $is_prod = env('PLATFORM_MODE', 'prod') == 'prod';

        if ($is_prod) {
            $this->info('Production Mode Detected!!');
            $this->info('Proceed to clone by tags');

            // if prod mode clone by examine the latest tags first
            // check for the latest tags
            $tags = $this->fetchTagsModule($this->argument('repository'));
            if (count($tags) > 0 && !is_null($tags)) {
                $select = $this->choice(
                    'Select Tag',
                    $tags,
                );
            }

            // proceed to clone the repository by the selected array
            if (isset($select) && in_array($select, $tags)) {
                $output = $this->cloneModuleByTags($select);

                // check if the folder exist after the module clone
                if ($this->isModuleDirExist($this->buildModuleName($this->argument('repository')))) {
                    $this->info('Clone successfull!!');
                } else {
                    $this->info($output);
                }
            }
        }

        if (!$is_prod || count($tags) <= 0) {
            $this->info("Proceed to clone by heads");
            $output = $this->cloneModeByHeads();

            // check if the folder exist after the module clone
            if ($this->isModuleDirExist($this->buildModuleName($this->argument('repository')))) {
                $this->info('Clone successfull!!');
            } else {
                $this->info($output);
            }
        }
    }

    /**
     * isModuleDirExist function
     *
     * @return string
     */
    protected function buildModuleName($repo_url): string
    {
        preg_match('/(?<=\/).+(?=\.git)/', $repo_url, $module_name);
        return $module_name[0];
    }

    /**
     * isModuleDirExist function
     *
     * @return bool
     */
    protected function isModuleDirExist($module_name): bool
    {
        return is_dir(base_path() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $module_name);
    }

    /**
     * cloneModuleByTags function
     *
     * @return string || null
     */
    protected function cloneModuleByTags($tags): string | null
    {
        $process = new Process([
            'git', 'clone', '--depth', '1', '--branch', $tags, $this->argument('repository')
        ]);
        $process->setWorkingDirectory(base_path() . DIRECTORY_SEPARATOR . 'modules');
        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            $this->info('Clone failed');
            return $exception->getMessage();
        }
        return $process->getOutput();
    }

    /**
     * cloneModeByHeads function
     *
     * @return string || null
     */
    protected function cloneModeByHeads(): string | null
    {
        $process = new Process([
            'git', 'clone', $this->argument('repository')
        ]);
        $process->setWorkingDirectory(base_path() . DIRECTORY_SEPARATOR . 'modules');
        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            $this->info('Clone failed');
            return $exception->getMessage();
        }
        return $process->getOutput();
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
