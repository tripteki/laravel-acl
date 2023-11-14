<?php

namespace App\Http\Controllers\Admin\ACL;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Tripteki\ACL\Contracts\Repository\Admin\IACLPermissionRepository;
use App\Imports\ACLs\PermissionImport;
use App\Exports\ACLs\PermissionExport;
use App\Http\Requests\Admin\ACLs\Permissions\PermissionShowValidation;
use App\Http\Requests\Admin\ACLs\Permissions\PermissionStoreValidation;
use App\Http\Requests\Admin\ACLs\Permissions\PermissionDestroyValidation;
use Tripteki\Helpers\Http\Requests\FileImportValidation;
use Tripteki\Helpers\Http\Requests\FileExportValidation;
use Tripteki\Helpers\Http\Controllers\Controller;

class PermissionAdminController extends Controller
{
    /**
     * @var \Tripteki\ACL\Contracts\Repository\Admin\IACLPermissionRepository
     */
    protected $permissionAdminRepository;

    /**
     * @param \Tripteki\ACL\Contracts\Repository\Admin\IACLPermissionRepository $permissionAdminRepository
     * @return void
     */
    public function __construct(IACLPermissionRepository $permissionAdminRepository)
    {
        $this->permissionAdminRepository = $permissionAdminRepository;
    }

    /**
     * @OA\Get(
     *      path="/admin/acls/permissions",
     *      tags={"Admin ACL Permission"},
     *      summary="Index",
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="limit",
     *          description="ACL Permission's Pagination Limit."
     *      ),
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="current_page",
     *          description="ACL Permission's Pagination Current Page."
     *      ),
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="order",
     *          description="ACL Permission's Pagination Order."
     *      ),
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="filter[]",
     *          description="ACL Permission's Pagination Filter."
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success."
     *      )
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = [];
        $statecode = 200;

        $data = $this->permissionAdminRepository->all();

        return iresponse($data, $statecode);
    }

    /**
     * @OA\Get(
     *      path="/admin/acls/permissions/{permission}",
     *      tags={"Admin ACL Permission"},
     *      summary="Show",
     *      @OA\Parameter(
     *          required=true,
     *          in="path",
     *          name="permission",
     *          description="ACL Permission's Permission."
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success."
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found."
     *      )
     * )
     *
     * @param \App\Http\Requests\Admin\ACLs\Permissions\PermissionShowValidation $request
     * @param string $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(PermissionShowValidation $request, $permission)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 200;

        $data = $this->permissionAdminRepository->get($permission);

        return iresponse($data, $statecode);
    }

    /**
     * @OA\Post(
     *      path="/admin/acls/permissions",
     *      tags={"Admin ACL Permission"},
     *      summary="Store",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="permission",
     *                      type="string",
     *                      description="ACL Permission's Permission."
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Created."
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity."
     *      )
     * )
     *
     * @param \App\Http\Requests\Admin\ACLs\Permissions\PermissionStoreValidation $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PermissionStoreValidation $request)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 202;

        $data = $this->permissionAdminRepository->rule($form["permission"]);

        if ($data) {

            $statecode = 201;
        }

        return iresponse($data, $statecode);
    }

    /**
     * @OA\Delete(
     *      path="/admin/acls/permissions/{permission}",
     *      tags={"Admin ACL Permission"},
     *      summary="Destroy",
     *      @OA\Parameter(
     *          required=true,
     *          in="path",
     *          name="permission",
     *          description="ACL Permission's Permission."
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success."
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity."
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found."
     *      )
     * )
     *
     * @param \App\Http\Requests\Admin\ACLs\Permissions\PermissionDestroyValidation $request
     * @param string $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PermissionDestroyValidation $request, $permission)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 202;

        $data = $this->permissionAdminRepository->unrule($permission);

        if ($data) {

            $statecode = 200;
        }

        return iresponse($data, $statecode);
    }

    /**
     * @OA\Post(
     *      path="/admin/acls/permissions-import",
     *      tags={"Admin ACL Permission"},
     *      summary="Import",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="file",
     *                      type="file",
     *                      description="Permission's File."
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success."
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity."
     *      )
     * )
     *
     * @param \Tripteki\Helpers\Http\Requests\FileImportValidation $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(FileImportValidation $request)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 200;

        if ($form["file"]->getClientOriginalExtension() == "csv" || $form["file"]->getClientOriginalExtension() == "txt") {

            $data = Excel::import(new PermissionImport(), $form["file"], null, \Maatwebsite\Excel\Excel::CSV);

        } else if ($form["file"]->getClientOriginalExtension() == "xls") {

            $data = Excel::import(new PermissionImport(), $form["file"], null, \Maatwebsite\Excel\Excel::XLS);

        } else if ($form["file"]->getClientOriginalExtension() == "xlsx") {

            $data = Excel::import(new PermissionImport(), $form["file"], null, \Maatwebsite\Excel\Excel::XLSX);
        }

        return iresponse($data, $statecode);
    }

    /**
     * @OA\Get(
     *      path="/admin/acls/permissions-export",
     *      tags={"Admin ACL Permission"},
     *      summary="Export",
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="file",
     *          schema={"type": "string", "enum": {"csv", "xls", "xlsx"}},
     *          description="Permission's File."
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success."
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity."
     *      )
     * )
     *
     * @param \Tripteki\Helpers\Http\Requests\FileExportValidation $request
     * @return mixed
     */
    public function export(FileExportValidation $request)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 200;

        if ($form["file"] == "csv") {

            $data = Excel::download(new PermissionExport(), "Permission.csv", \Maatwebsite\Excel\Excel::CSV);

        } else if ($form["file"] == "xls") {

            $data = Excel::download(new PermissionExport(), "Permission.xls", \Maatwebsite\Excel\Excel::XLS);

        } else if ($form["file"] == "xlsx") {

            $data = Excel::download(new PermissionExport(), "Permission.xlsx", \Maatwebsite\Excel\Excel::XLSX);
        }

        return $data;
    }
};
