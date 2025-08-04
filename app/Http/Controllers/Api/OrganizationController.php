<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class OrganizationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/organizations/{id}",
     *     summary="Получить информацию об организации по ID",
     *     tags={"Организации"},
     *     security={{"apiKey": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID организации"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация об организации",
     *         @OA\JsonContent(ref="#/components/schemas/Organization")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Организация не найдена"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $organization = Organization::with(['building', 'phones', 'activities'])->find($id);

        if (!$organization) {
            return response()->json(['error' => 'Организация не найдена'], 404);
        }

        return response()->json($organization);
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/building/{buildingId}",
     *     summary="Получить организации в конкретном здании",
     *     tags={"Организации"},
     *     security={{"apiKey": {}}},
     *     @OA\Parameter(
     *         name="buildingId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID здания"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций в здании",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="organizations", type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ"
     *     )
     * )
     */
    public function getByBuilding(int $buildingId): JsonResponse
    {
        $organizations = Organization::with(['building', 'phones', 'activities'])
            ->where('building_id', $buildingId)
            ->get();

        return response()->json(['organizations' => $organizations]);
    }

    /**
     * Получить организации по виду деятельности
     */
    public function getByActivity(int $activityId): JsonResponse
    {
        $organizations = Organization::with(['building', 'phones', 'activities'])
            ->whereHas('activities', function ($query) use ($activityId) {
                $query->where('activity_id', $activityId);
            })
            ->get();

        return response()->json(['organizations' => $organizations]);
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/radius",
     *     summary="Поиск организаций в радиусе от точки",
     *     tags={"Организации"},
     *     security={{"apiKey": {}}},
     *     @OA\Parameter(
     *         name="latitude",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="number", minimum=-90, maximum=90),
     *         description="Широта центральной точки"
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="number", minimum=-180, maximum=180),
     *         description="Долгота центральной точки"
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="number", minimum=0),
     *         description="Радиус поиска в километрах"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список организаций в радиусе",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="organizations", type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Некорректные параметры"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ"
     *     )
     * )
     */
    public function getByRadius(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $lat = $request->input('latitude');
        $lng = $request->input('longitude');
        $radius = $request->input('radius');

        // Формула гаверсинуса для расчета расстояния
        $organizations = Organization::with(['building', 'phones', 'activities'])
            ->whereHas('building', function ($query) use ($lat, $lng, $radius) {
                $query->whereRaw("
                    (6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) + sin(radians(?)) *
                    sin(radians(latitude)))) <= ?
                ", [$lat, $lng, $lat, $radius]);
            })
            ->get();

        return response()->json(['organizations' => $organizations]);
    }

    /**
     * Поиск организаций в прямоугольной области
     */
    public function getByArea(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'min_lat' => 'required|numeric|between:-90,90',
            'max_lat' => 'required|numeric|between:-90,90',
            'min_lng' => 'required|numeric|between:-180,180',
            'max_lng' => 'required|numeric|between:-180,180'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $organizations = Organization::with(['building', 'phones', 'activities'])
            ->whereHas('building', function ($query) use ($request) {
                $query->whereBetween('latitude', [$request->input('min_lat'), $request->input('max_lat')])
                      ->whereBetween('longitude', [$request->input('min_lng'), $request->input('max_lng')]);
            })
            ->get();

        return response()->json(['organizations' => $organizations]);
    }

    /**
     * Поиск организаций по деятельности (включая поддеятельности)
     */
    public function searchByActivity(int $activityId): JsonResponse
    {
        $activity = Activity::find($activityId);
        if (!$activity) {
            return response()->json(['error' => 'Деятельность не найдена'], 404);
        }

        $descendantIds = $activity->getAllDescendants()->pluck('id')->push($activityId);

        $organizations = Organization::with(['building', 'phones', 'activities'])
            ->whereHas('activities', function ($query) use ($descendantIds) {
                $query->whereIn('activity_id', $descendantIds);
            })
            ->get();

        return response()->json(['organizations' => $organizations]);
    }

    /**
     * @OA\Get(
     *     path="/api/organizations/search/name",
     *     summary="Поиск организаций по названию",
     *     tags={"Организации"},
     *     security={{"apiKey": {}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string", minLength=1),
     *         description="Название для поиска"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список найденных организаций",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="organizations", type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Некорректные параметры"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ"
     *     )
     * )
     */
    public function searchByName(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $name = $request->input('name');

        $organizations = Organization::with(['building', 'phones', 'activities'])
            ->where('name', 'LIKE', "%{$name}%")
            ->get();

        return response()->json(['organizations' => $organizations]);
    }
}
