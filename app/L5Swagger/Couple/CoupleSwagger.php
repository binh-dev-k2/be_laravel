<?php

namespace App\L5Swagger\Couple;
use App\Http\Requests\Couple\CoupleRequest;

class CoupleSwagger
{
    /**
     * Lấy thông tin couple của user hiện tại
     *
     * @OA\Get(
     *      path="/couple",
     *      tags={"Couple"},
     *      summary="Lấy thông tin hiện tại của couple hiện tại",
     *      @OA\Response(
     *         response=400,
     *         description="Bạn đang độc thân",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="string",
     *                 example="{'data': [], 'code': 2}"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy thông tin thành công",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="string",
     *                example="{'data': [], 'code': 0}"
     *             )
     *         )
     *     ),
     *  security={{"sanctum":{}}}
     * )
     */
    public function getCurrentCouple() {

    }
    /**
     * Gửi lời mời ghép đôi
     *
     * @OA\Post(
     *      path="/couple/invite",
     *      tags={"Couple"},
     *      summary="Gửi lời mời ghép đôi",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="invited_email",
     *                      type="string",
     *                      format="email",
     *                      description="Địa chỉ email người nhận lời mời"
     *                  ),
     *                  example={"invited_email": "haitiger.al@gmail.com"}
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *         response=400,
     *         description="Bạn đã ghép đôi với người khác",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="string",
     *                 example="{'data': [], 'code': 2}"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Đã gửi lời mời trước đó, đang chờ",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="string",
     *                 example="{'data': [], 'code': 3}"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=402,
     *         description="Người nhận đã có người yêu, thử lại sau",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="string",
     *                 example="{'data': [], 'code': 5}"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gửi yêu cầu thành công",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="string",
     *                 example="{'data': [], 'code': 0}"
     *             )
     *         )
     *     ),
     *  security={{"sanctum":{}}}
     * )
     */
     public function invite(CoupleRequest $request) {

     }
    /**
     * Cập nhật trạng thái lời mời
     *
     * @OA\Post(
     *      path="/couple/update-invite",
     *      tags={"Couple"},
     *      summary="Cập nhật trạng thái lời mời",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="invitation_id",
     *                      type="string",
     *                      description="ID Lời mời"
     *                  ),
     *                  @OA\Property(
     *                      property="status",
     *                      type="string",
     *                      description="trạng thái lời mời (1: chấp nhận, 2: từ chối, 3: hủy lời mời)"
     *                  ),
     *                  example={"invitation_id": "1", "status": "1"}
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *         response=400,
     *         description="Không tìm thấy lời mời",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="string",
     *                 example="{'data': [], 'code': 2}"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Lời mời không phải của bạn => Bạn không thể từ chối",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="string",
     *                 example="{'data': [], 'code': 3}"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=402,
     *         description="Bạn không phải người gửi lời mời  => không thể hủy lời mời",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="string",
     *                 example="{'data': [], 'code': 4}"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Lời mời không phải của bạn   => không thể chấp nhận",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="string",
     *                 example="{'data': [], 'code': 5}"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Lỗi không xác định",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="string",
     *                 example="{'data': [], 'code': 6}"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật trạng thái thành công",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="string",
     *                 example="{'data': [], 'code': 0}"
     *             )
     *         )
     *     ),
     *  security={{"sanctum":{}}}
     * )
     */
      public function updateInvite(CoupleRequest $request) {

      }
}
