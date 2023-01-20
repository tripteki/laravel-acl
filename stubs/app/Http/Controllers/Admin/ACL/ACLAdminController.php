<?php

namespace App\Http\Controllers\Admin\ACL;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Tripteki\Helpers\Contracts\AuthModelContract;
use Tripteki\ACL\Contracts\Repository\Admin\IACLRoleRepository as IACLRuleRoleRepository;
use Tripteki\ACL\Contracts\Repository\IACLRepository as IACLRuleUserRepository;
use App\Http\Requests\Admin\ACLs\ACLValidation;
use Tripteki\Helpers\Http\Controllers\Controller;

class ACLAdminController extends Controller
{
    /**
     * @var \Tripteki\ACL\Contracts\Repository\Admin\IACLRoleRepository
     */
    protected $aclRoleAdminRepository;

    /**
     * @var \Tripteki\ACL\Contracts\Repository\IACLRepository
     */
    protected $aclUserAdminRepository;

    /**
     * @param \Tripteki\ACL\Contracts\Repository\Admin\IACLRoleRepository $aclRoleAdminRepository
     * @param \Tripteki\ACL\Contracts\Repository\IACLRepository $aclUserAdminRepository
     * @return void
     */
    public function __construct(IACLRuleRoleRepository $aclRoleAdminRepository, IACLRuleUserRepository $aclUserAdminRepository)
    {
        $this->aclRoleAdminRepository = $aclRoleAdminRepository;
        $this->aclUserAdminRepository = $aclUserAdminRepository;
    }

    /**
     * @OA\Put(
     *      path="/admin/acls/{context}/{object}",
     *      tags={"Admin ACL Rule"},
     *      summary="rule",
     *      @OA\Parameter(
     *          required=true,
     *          in="path",
     *          name="context",
     *          schema={"type": "string", "enum": {"grant_permissions_to_role", "revoke_permissions_from_role", "grant_roles_to_user", "revoke_roles_from_user"}},
     *          description="ACL's Context."
     *      ),
     *      @OA\Parameter(
     *          required=true,
     *          in="path",
     *          name="object",
     *          description="ACL's Object."
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="rules[]",
     *                      type="array",
     *                      collectionFormat="multi",
     *                      @OA\Items(type="string"),
     *                      description="ACL's Rules."
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
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found."
     *      )
     * )
     *
     * @param \App\Http\Requests\Admin\ACLs\ACLValidation $request
     * @param string $context
     * @param string $object
     * @return \Illuminate\Http\JsonResponse
     */
    public function rule(ACLValidation $request, $context, $object)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 202;

        if ($context == ACLValidation::GRANT_PERMISSIONS_TO_ROLE || $context == ACLValidation::REVOKE_PERMISSIONS_FROM_ROLE) {

            $this->aclRoleAdminRepository->forRole($object);

            foreach ($form["rules"] as $rule) {

                if ($context == ACLValidation::GRANT_PERMISSIONS_TO_ROLE) {

                    $data[] = $this->aclRoleAdminRepository->grant($rule);

                } else if ($context == ACLValidation::REVOKE_PERMISSIONS_FROM_ROLE) {

                    $data[] = $this->aclRoleAdminRepository->revoke($rule);
                }
            }

        } else if ($context == ACLValidation::GRANT_ROLES_TO_USER || $context == ACLValidation::REVOKE_ROLES_FROM_USER) {

            $this->aclUserAdminRepository->setUser(app(AuthModelContract::class)->findOrFail($object));

            foreach ($form["rules"] as $rule) {

                if ($context == ACLValidation::GRANT_ROLES_TO_USER) {

                    $data[] = $this->aclUserAdminRepository->grantAs($rule);

                } else if ($context == ACLValidation::REVOKE_ROLES_FROM_USER) {

                    $data[] = $this->aclUserAdminRepository->revokeAs($rule);
                }
            }
        }

        if ($data) {

            $statecode = 201;
        }

        return iresponse($data, $statecode);
    }
};
