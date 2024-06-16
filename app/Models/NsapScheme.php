<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="NsapScheme",
 *     type="object",
 *     title="NsapScheme",
 *     description="NSAP Scheme Model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="scheme_code", type="string", example="NSAP001"),
 *     @OA\Property(property="scheme_name", type="string", example="Scheme A"),
 *     @OA\Property(property="central_state_scheme", type="string", example="Central"),
 *     @OA\Property(property="fin_year", type="string", example="2023-2024"),
 *     @OA\Property(property="state_disbursement", type="number", format="float", example=10000.00),
 *     @OA\Property(property="central_disbursement", type="number", format="float", example=20000.00),
 *     @OA\Property(property="total_disbursement", type="number", format="float", example=30000.00),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class NsapScheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheme_code',
        'scheme_name',
        'central_state_scheme',
        'fin_year',
        'state_disbursement',
        'central_disbursement',
        'total_disbursement',
    ];
}
