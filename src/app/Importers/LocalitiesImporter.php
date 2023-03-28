<?php

namespace Vtech\VietnamLocalities\Importers;

use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Vtech\VietnamLocalities\Models\District;
use Vtech\VietnamLocalities\Models\Province;
use Vtech\VietnamLocalities\Models\Ward;

class LocalitiesImporter implements WithHeadingRow, SkipsOnFailure, ToArray, WithChunkReading
{
    /**
     * Store ids of localities exsisting in database.
     *
     * @var array
     */
    protected $currentIds = [
        'province' => [],
        'district' => [],
        'ward'     => [],
    ];

    /**
     * Store ids of localities imported (update or create).
     *
     * @var array
     */
    protected $importedIds = [
        'province' => [],
        'district' => [],
        'ward'     => [],
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fillCurrentIds();
    }

    public function onFailure(Failure ...$failures)
    {
        //
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function array(array $records)
    {
        foreach ($records as $row) {
            $this->ensureWardExists($row);
        }
    }

    /**
     * Import provinces.
     *
     * @param  array $row
     *
     * @return bool
     */
    protected function ensureProvinceExists(array $row): bool
    {
        if (!empty($row['ma_tp']) && !empty($row['tinh_thanh_pho'])) {
            if (in_array($row['ma_tp'], $this->currentIds['province'])) {
                if (!in_array($row['ma_tp'], $this->importedIds['province'])) {
                    Province::whereId($row['ma_tp'])->update([
                        'name' => $row['tinh_thanh_pho'],
                    ]);
                }
            } else {
                Province::create([
                    'id'   => $row['ma_tp'],
                    'name' => $row['tinh_thanh_pho'],
                ]);

                array_push($this->currentIds['province'], $row['ma_tp']);
            }

            array_push($this->importedIds['province'], $row['ma_tp']);

            return true;
        }

        return false;
    }

    /**
     * Import districts.
     *
     * @param  array $row
     *
     * @return bool
     */
    protected function ensureDistrictExists(array $row): bool
    {
        $safe = $this->ensureProvinceExists($row);

        if ($safe && !empty($row['ma_qh']) && !empty($row['quan_huyen'])) {
            if (in_array($row['ma_qh'], $this->currentIds['district'])) {
                if (!in_array($row['ma_qh'], $this->importedIds['district'])) {
                    District::whereId($row['ma_qh'])->update([
                        'name'        => $row['quan_huyen'],
                        'province_id' => $row['ma_tp'],
                    ]);
                }
            } else {
                District::create([
                    'id'          => $row['ma_qh'],
                    'name'        => $row['quan_huyen'],
                    'province_id' => $row['ma_tp'],
                ]);

                array_push($this->currentIds['district'], $row['ma_qh']);
            }

            array_push($this->importedIds['district'], $row['ma_qh']);

            return true;
        }

        return false;
    }

    /**
     * Import wards.
     *
     * @param  array $row
     *
     * @return bool
     */
    protected function ensureWardExists(array $row): bool
    {
        $safe = $this->ensureDistrictExists($row);

        if ($safe && !empty($row['ma_px']) && !empty($row['phuong_xa'])) {
            if (in_array($row['ma_px'], $this->currentIds['ward'])) {
                if (!in_array($row['ma_px'], $this->importedIds['ward'])) {
                    Ward::whereId($row['ma_px'])->update([
                        'name'        => $row['phuong_xa'],
                        'district_id' => $row['ma_qh']
                    ]);
                }
            } else {
                Ward::create([
                    'id'          => $row['ma_px'],
                    'name'        => $row['phuong_xa'],
                    'district_id' => $row['ma_qh'],
                ]);

                array_push($this->currentIds['ward'], $row['ma_px']);
            }

            array_push($this->importedIds['ward'], $row['ma_px']);

            return true;
        }

        return false;
    }

    /**
     * Load data from database and fill currentId list.
     *
     * @return void
     */
    protected function fillCurrentIds()
    {
        $this->currentIds['province'] = Province::get(['id'])->pluck('id')->toArray();
        $this->currentIds['district'] = District::get(['id'])->pluck('id')->toArray();
        $this->currentIds['ward']     = Ward::get(['id'])->pluck('id')->toArray();
    }
}
