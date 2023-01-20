<?php

namespace App\Http\Controllers\Admin\ACL;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Tripteki\ACL\Contracts\Repository\Admin\IACLPermissionRepository;
use App\Http\Requests\Admin\ACLs\Permissions\PermissionShowValidation;
use App\Http\Requests\Admin\ACLs\Permissions\PermissionStoreValidation;
use App\Http\Requests\Admin\ACLs\Permissions\PermissionDestroyValidation;
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
};
