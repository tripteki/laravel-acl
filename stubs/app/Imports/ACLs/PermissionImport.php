<?php

namespace App\Imports\ACLs;

use Tripteki\ACL\Contracts\Repository\Admin\IACLPermissionRepository;
use App\Http\Requests\Admin\ACLs\Permissions\PermissionStoreValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class PermissionImport implements ToCollection, WithStartRow
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * @param \Illuminate\Support\Collection $rows
     * @return void
     */
    protected function validate(Collection $rows)
    {
        $validator = (new PermissionStoreValidation())->rules();

        Validator::make($rows->toArray(), [

            "*.0" => $validator["permission"],

        ])->validate();
    }

    /**
     * @param \Illuminate\Support\Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        $this->validate($rows);

        $aclPermissionAdminRepository = app(IACLPermissionRepository::class);

        foreach ($rows as $row) {

            $aclPermissionAdminRepository->rule($row[0]);
        }
    }
};
