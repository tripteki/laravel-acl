<?php

namespace App\Imports\ACLs;

use Tripteki\ACL\Contracts\Repository\Admin\IACLRoleRepository;
use App\Http\Requests\Admin\ACLs\Roles\RoleStoreValidation;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class RoleImport implements ToCollection, WithStartRow
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
        $validator = (new RoleStoreValidation())->rules();

        Validator::make($rows->toArray(), [

            "*.0" => $validator["role"],

        ])->validate();
    }

    /**
     * @param \Illuminate\Support\Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        $this->validate($rows);

        $aclRoleAdminRepository = app(IACLRoleRepository::class);

        foreach ($rows as $row) {

            $aclRoleAdminRepository->rule($row[0]);
        }
    }
};
