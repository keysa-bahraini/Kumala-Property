<?php

namespace App\Http\Controllers;

use App\Services\KumalaDataHub;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /**
     * GET /api/v1/properties
     * Multi-parameter filter & search
     */
    public function properties(Request $request): JsonResponse
    {
        $type = $request->query('type');
        $category = $request->query('category');
        $location = $request->query('location');
        $priceRange = $request->query('price_range');

        $properties = KumalaDataHub::filterProperties($type, $category, $location, $priceRange);

        // Optional search filter
        $search = $request->query('q') ?? $request->query('search');
        if (!empty($search)) {
            $properties = array_values(array_filter($properties, function ($p) use ($search) {
                return stripos($p['title'] ?? '', $search) !== false
                    || stripos($p['id'] ?? '', $search) !== false
                    || stripos($p['location_city'] ?? '', $search) !== false
                    || stripos($p['location_area'] ?? '', $search) !== false;
            }));
        }

        return response()->json([
            'success' => true,
            'total' => count($properties),
            'data' => $properties,
        ]);
    }

    /**
     * GET /api/v1/properties/{slug}
     * Property detail with related recommendations
     */
    public function propertyDetail(string $slug): JsonResponse
    {
        $property = KumalaDataHub::findPropertyBySlug($slug);
        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'Properti tidak ditemukan dalam portofolio aktif.',
            ], 404);
        }

        $all = KumalaDataHub::getAllProperties(true);
        $related = array_values(array_filter($all, fn($item) => ($item['id'] ?? '') !== ($property['id'] ?? '')));

        return response()->json([
            'success' => true,
            'data' => $property,
            'related' => array_slice($related, 0, 3),
        ]);
    }

    /**
     * POST /api/v1/titip-properti
     * Consignment submission from property owners
     */
    public function submitTitipProperti(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'owner_name' => 'required|string|max:100',
            'owner_phone' => 'required|string|max:30',
            'property_category' => 'required|string',
            'asking_price' => 'required',
            'city_region' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $submission = KumalaDataHub::createSubmission($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan titip properti berhasil diverifikasi dan disimpan ke database Kumala Property.',
            'submission_id' => $submission['id'],
            'property_id' => $submission['property_id'] ?? null,
            'data' => $submission,
        ], 201);
    }

    /**
     * POST /api/v1/inquiry
     * Customer VIP inquiry / survey request
     */
    public function submitInquiry(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $inquiry = KumalaDataHub::createInquiry($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Inquiry Anda telah diterima oleh Private Client Concierge Kumala.',
            'inquiry_id' => $inquiry['id'],
            'sla' => '< 15 Menit',
            'data' => $inquiry,
        ], 201);
    }

    /**
     * GET /api/v1/categories
     */
    public function categories(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => KumalaDataHub::getCategories(),
        ]);
    }

    /**
     * GET /api/v1/regions
     */
    public function regions(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => KumalaDataHub::getRegions(),
        ]);
    }

    /**
     * GET /api/v1/transactions
     */
    public function transactions(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => KumalaDataHub::getTransactions(),
        ]);
    }

    /**
     * GET /api/v1/stats
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => KumalaDataHub::getDashboardMetrics(),
        ]);
    }
}
