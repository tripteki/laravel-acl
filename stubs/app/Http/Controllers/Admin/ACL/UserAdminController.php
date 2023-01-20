<?php

namespace App\Http\Controllers\Admin\ACL;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Tripteki\Helpers\Contracts\AuthModelContract;
use Tripteki\ACL\Contracts\Repository\IACLRepository as IACLUserRepository;
use App\Http\Requests\Admin\ACLs\Users\UserShowValidation;
use Tripteki\Helpers\Http\Controllers\Controller;

class UserAdminController extends Controller
{
    /**
     * @var \Tripteki\ACL\Contracts\Repository\IACLRepository
     */
    protected $userAdminRepository;

    /**
     * @param \Tripteki\ACL\Contracts\Repository\IACLRepository $userAdminRepository
     * @return void
     */
    public function __construct(IACLUserRepository $userAdminRepository)
    {
        $this->userAdminRepository = $userAdminRepository;
    }

    /**
     * @OA\Get(
     *      path="/admin/acls/users/{user}",
     *      tags={"Admin ACL User"},
     *      summary="Show",
     *      @OA\Parameter(
     *          required=true,
     *          in="path",
     *          name="user",
     *          description="ACL User's User."
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
     * @param \App\Http\Requests\Admin\ACLs\Users\UserShowValidation $request
     * @param string $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(UserShowValidation $request, $user)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 200;

        $user = app(AuthModelContract::class)->findOrFail($user);

        $this->userAdminRepository->setUser($user);

        $data = $this->userAdminRepository->all();

        return iresponse($data, $statecode);
    }
};
