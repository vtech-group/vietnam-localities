<?php

namespace Vtech\VietnamLocalities\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Vtech\VietnamLocalities\Importers\LocalitiesImporter as Importer;
use Vtech\VietnamLocalities\Models\District;
use Vtech\VietnamLocalities\Models\Province;
use Vtech\VietnamLocalities\Models\Ward;

/**
 * The VietnamLocalitiesImport class.
 *
 * @package vtech/vietnam-localities
 *
 * @author  Jackie Do <anhvudo@gmail.com>
 */
class VietnamLocalitiesImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vietnam-localities:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Viet Nam localities into database';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (file_exists(storage_path('vietnam-localities.xls'))) {
            $tmpFile = storage_path('vietnam-localities.xls');
        } else {
            $tmpFile = realpath(__DIR__.'/../../../database/vietnam-localities.xls');
        }

        $this->line('');
        $this->line('----------');
        $this->info('Before import');
        $this->line('- Total provinces: ' . Province::count(['id']));
        $this->line('- Total districts: ' . District::count(['id']));
        $this->line('- Total wards    : ' . Ward::count(['id']));

        $this->line('');
        $this->line('----------');
        $this->info('Importing...');
        $this->info('Source: ' . $tmpFile);

        Excel::import(new Importer(), $tmpFile);

        $this->line('');
        $this->line('----------');
        $this->info('After import');
        $this->line('- Total provinces: ' . Province::count(['id']));
        $this->line('- Total districts: ' . District::count(['id']));
        $this->line('- Total wards    : ' . Ward::count(['id']));

        $this->line('');
        $this->line('----------');
        $this->info('Completed');
    }
}
