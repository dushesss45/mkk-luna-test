<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class BuildingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/buildings",
     *     summary="Получить список всех зданий",
     *     tags={"Здания"},
     *     security={{"apiKey": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Список зданий",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="buildings", type="array", @OA\Items(ref="#/components/schemas/Building"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $buildings = Building::with('organizations')->get();
        return response()->json(['buildings' => $buildings]);
    }

    /**
     * @OA\Get(
     *     path="/api/buildings/{id}",
     *     summary="Получить информацию о здании по ID",
     *     tags={"Здания"},
     *     security={{"apiKey": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID здания"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация о здании",
     *         @OA\JsonContent(ref="#/components/schemas/Building")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Здание не найдено"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $building = Building::find($id);

        if (!$building) {
            return response()->json(['error' => 'Здание не найдено'], 404);
        }

        return response()->json($building);
    }
}
