<?php

namespace App\Http\Controllers\Admin\ACL;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Tripteki\ACL\Contracts\Repository\Admin\IACLRoleRepository;
use App\Http\Requests\Admin\ACLs\Roles\RoleShowValidation;
use App\Http\Requests\Admin\ACLs\Roles\RoleStoreValidation;
use App\Http\Requests\Admin\ACLs\Roles\RoleDestroyValidation;
use Tripteki\Helpers\Http\Controllers\Controller;

class RoleAdminController extends Controller
{
    /**
     * @var \Tripteki\ACL\Contracts\Repository\Admin\IACLRoleRepository
     */
    protected $roleAdminRepository;

    /**
     * @param \Tripteki\ACL\Contracts\Repository\Admin\IACLRoleRepository $roleAdminRepository
     * @return void
     */
    public function __construct(IACLRoleRepository $roleAdminRepository)
    {
        $this->roleAdminRepository = $roleAdminRepository;
    }

    /**
     * @OA\Get(
     *      path="/admin/acls/roles",
     *      tags={"Admin ACL Role"},
     *      summary="Index",
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="limit",
     *          description="ACL Role's Pagination Limit."
     *      ),
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="current_page",
     *          description="ACL Role's Pagination Current Page."
     *      ),
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="order",
     *          description="ACL Role's Pagination Order."
     *      ),
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="filter[]",
     *          description="ACL Role's Pagination Filter."
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

        $data = $this->roleAdminRepository->all();

        return iresponse($data, $statecode);
    }

    /**
     * @OA\Get(
     *      path="/admin/acls/roles/{role}",
     *      tags={"Admin ACL Role"},
     *      summary="Show",
     *      @OA\Parameter(
     *          required=true,
     *          in="path",
     *          name="role",
     *          description="ACL Role's Role."
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
     * @param \App\Http\Requests\Admin\ACLs\Roles\RoleShowValidation $request
     * @param string $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(RoleShowValidation $request, $role)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 200;

        $data = $this->roleAdminRepository->get($role);

        return iresponse($data, $statecode);
    }

    /**
     * @OA\Post(
     *      path="/admin/acls/roles",
     *      tags={"Admin ACL Role"},
     *      summary="Store",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="role",
     *                      type="string",
     *                      description="ACL Role's Role."
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
     * @param \App\Http\Requests\Admin\ACLs\Roles\RoleStoreValidation $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RoleStoreValidation $request)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 202;

        $data = $this->roleAdminRepository->rule($form["role"]);

        if ($data) {

            $statecode = 201;
        }

        return iresponse($data, $statecode);
    }

    /**
     * @OA\Delete(
     *      path="/admin/acls/roles/{role}",
     *      tags={"Admin ACL Role"},
     *      summary="Destroy",
     *      @OA\Parameter(
     *          required=true,
     *          in="path",
     *          name="role",
     *          description="ACL Role's Role."
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
     * @param \App\Http\Requests\Admin\ACLs\Roles\RoleDestroyValidation $request
     * @param string $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(RoleDestroyValidation $request, $role)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 202;

        $data = $this->roleAdminRepository->unrule($role);

        if ($data) {

            $statecode = 200;
        }

        return iresponse($data, $statecode);
    }
};
