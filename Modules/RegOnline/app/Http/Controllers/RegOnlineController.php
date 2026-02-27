<?php

namespace Modules\RegOnline\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RegOnline\Services\RegOnlineService;

class RegOnlineController extends Controller
{
    public function __construct(protected RegOnlineService $regonline) {}

    public function dashboard(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user,
            'doctor_schedule_count' => count($this->regonline->getDoctorSchedules($user)),
            'bed_room_count' => count($this->regonline->getBedCapacity($user)),
        ]);
    }

    public function doctorSchedules(Request $request)
    {
        return response()->json($this->regonline->getDoctorSchedules(
            $request->user(),
            $request->query('doctor_name')
        ));
    }

    public function bedCapacity(Request $request)
    {
        return response()->json($this->regonline->getBedCapacity($request->user()));
    }

    public function monitorClinicQueues(Request $request)
    {
        return response()->json([
            'queues' => $this->regonline->getAllClinicQueues($request->user()),
        ]);
    }

    public function calculateBmi(Request $request)
    {
        $validated = $request->validate([
            'height_cm' => 'required|numeric|min:80|max:250',
            'weight_kg' => 'required|numeric|min:20|max:300',
        ]);

        $heightM = $validated['height_cm'] / 100;
        $bmi = round($validated['weight_kg'] / ($heightM * $heightM), 1);

        $category = match (true) {
            $bmi < 18.5 => 'Berat badan kurang',
            $bmi < 25 => 'Normal',
            $bmi < 30 => 'Kelebihan berat badan',
            default => 'Obesitas',
        };

        return response()->json([
            'bmi' => $bmi,
            'category' => $category,
        ]);
    }
}
