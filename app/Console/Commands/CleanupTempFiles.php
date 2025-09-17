<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:temp-files {--hours=24 : Number of hours old files should be}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up temporary uploaded files older than specified hours';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $cutoffTime = Carbon::now()->subHours($hours);

        $this->info("Cleaning up temporary files older than {$hours} hours...");

        $deletedCount = 0;
        $tempDirectories = ['temp/images', 'temp/acts', 'temp/design_codes'];

        foreach ($tempDirectories as $directory) {
            $files = Storage::disk('public')->files($directory);

            foreach ($files as $file) {
                $lastModified = Carbon::createFromTimestamp(Storage::disk('public')->lastModified($file));

                if ($lastModified->lt($cutoffTime)) {
                    Storage::disk('public')->delete($file);
                    $deletedCount++;
                    $this->line("Deleted: {$file}");
                }
            }
        }

        $this->info("Cleanup completed. Deleted {$deletedCount} temporary files.");

        return Command::SUCCESS;
    }
}
