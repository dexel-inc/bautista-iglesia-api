<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Visit;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class VisitController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $ip = $request->ip();

        Visit::create([
            'ip' => $ip,
            'user_agent' => $request->header('User-Agent'),
            'url' => $request->input('url'),
        ]);

        return response()->json([
            'status' => ['status' => Status::OK,],
            'data' => "Visita registrada"
        ]);
    }

    public function stats(): JsonResponse
    {
        $monthly = Cache::remember('visits', 86400, function () {
            return Visit::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        });

        return response()->json([
           'status' => ['status' => Status::OK,],
           'data' => [
               'monthly' => $monthly,
           ]
        ]);
    }
}

