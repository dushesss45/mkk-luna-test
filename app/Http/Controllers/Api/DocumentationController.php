<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Generator;

/**
 * @OA\Info(
 *     title="REST API для справочника организаций",
 *     version="1.0.0",
 *     description="API для работы со справочником организаций, зданий и деятельности",
 *     @OA\Contact(
 *         email="begeka15@yandex.ru"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8100",
 *     description="Локальный сервер разработки"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="apiKey",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-Key"
 * )
 *
 * @OA\Tag(
 *     name="Здания",
 *     description="Операции с зданиями"
 * )
 *
 * @OA\Tag(
 *     name="Организации",
 *     description="Операции с организациями"
 * )
 *
 * @OA\Schema(
 *     schema="Building",
 *     type="object",
 *     @OA\Property(property="id", type="integer", description="ID здания"),
 *     @OA\Property(property="address", type="string", description="Адрес здания"),
 *     @OA\Property(property="latitude", type="number", format="decimal", description="Широта"),
 *     @OA\Property(property="longitude", type="number", format="decimal", description="Долгота"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Organization",
 *     type="object",
 *     @OA\Property(property="id", type="integer", description="ID организации"),
 *     @OA\Property(property="name", type="string", description="Название организации"),
 *     @OA\Property(property="building_id", type="integer", description="ID здания"),
 *     @OA\Property(property="building", ref="#/components/schemas/Building"),
 *     @OA\Property(
 *         property="phones",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="phone", type="string")
 *         )
 *     ),
 *     @OA\Property(
 *         property="activities",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="parent_id", type="integer", nullable=true),
 *             @OA\Property(property="level", type="integer")
 *         )
 *     )
 * )
 */
class DocumentationController extends Controller
{
    /**
     * Генерация OpenAPI документации
     */
    public function generateOpenApi(): JsonResponse
    {
        $openapi = Generator::scan([
            base_path('app/Http/Controllers/Api')
        ]);

        return response()->json($openapi);
    }
}
