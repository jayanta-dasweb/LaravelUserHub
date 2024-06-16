<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NsapScheme;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;


class NSAPController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/nsap-schemes",
     *     operationId="getNsapSchemesList",
     *     tags={"NSAP Schemes"},
     *     summary="Get list of NSAP schemes",
     *     description="Returns list of NSAP schemes",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/NsapScheme")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function index()
    {
        $schemes = NsapScheme::all();
        return response()->json([
            'status' => 'success',
            'data' => $schemes,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/nsap-schemes/{id}",
     *     operationId="getNsapSchemeById",
     *     tags={"NSAP Schemes"},
     *     summary="Get specific NSAP scheme by ID",
     *     description="Returns a single NSAP scheme by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="NSAP Scheme ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/NsapScheme")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Scheme not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function show($id)
    {
        $scheme = NsapScheme::find($id);
        if (!$scheme) {
            return response()->json([
                'status' => 'error',
                'message' => 'Scheme not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $scheme,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/nsap-schemes/code/{scheme_code}",
     *     operationId="getNsapSchemesByCode",
     *     tags={"NSAP Schemes"},
     *     summary="Get NSAP schemes by Scheme Code",
     *     description="Returns all NSAP schemes by Scheme Code",
     *     @OA\Parameter(
     *         name="scheme_code",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="NSAP Scheme Code"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/NsapScheme"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Schemes not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function getBySchemeCode($scheme_code)
    {
        $schemes = NsapScheme::where('scheme_code', $scheme_code)->get();
        if ($schemes->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Schemes not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $schemes,
        ], 200);
    }

}
