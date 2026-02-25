<?php

namespace Modules\RegOnline\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\RegOnline\Models\PatientRegistration;
use Modules\RegOnline\Services\RegOnlineService;

class RegOnlineController extends Controller
{
    public function __construct(protected RegOnlineService $regonline) {}

    public function dashboard(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user,
            'registration_count' => PatientRegistration::where('user_id', $user->id)->count(),
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

    public function listRegistrations(Request $request)
    {
        $user = $request->user();
        $query = PatientRegistration::query()->latest('schedule_at');

        if ($request->boolean('family') && $user->family_code) {
            $query->whereIn('user_id', function ($sub) use ($user) {
                $sub->select('id')->from('users')->where('family_code', $user->family_code);
            });
        } else {
            $query->where('user_id', $user->id);
        }

        return response()->json($query->get());
    }

    public function monitorClinicQueues(Request $request)
    {
        return response()->json([
            'queues' => $this->regonline->getAllClinicQueues($request->user()),
        ]);
    }

    public function createRegistration(Request $request)
    {
        $validated = $request->validate([
            'clinic_name' => 'required|string|max:120',
            'doctor_name' => 'required|string|max:120',
            'schedule_at' => 'required|date',
            'complaint' => 'nullable|string|max:255',
        ]);

        $simrs = $this->regonline->submitRegistration($request->user(), $validated);

        $registration = PatientRegistration::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'queue_number' => data_get($simrs, 'queue_number'),
            'status' => data_get($simrs, 'success') ? 'submitted' : 'pending',
            'simrs_response' => $simrs,
        ]);

        return response()->json($registration, 201);
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

    public function getPatient(Request $request)
    {
        try {
            $type = null;
            $value = null;
            $extraParams = [];

            if ($request->has('NIK')) {
                $type = 'NIK';
                $value = $request->query('NIK');
            } elseif ($request->has('KAP')) {
                $type = 'KAP';
                $value = $request->query('KAP');
            } elseif ($request->has('NORM')) {
                $type = 'NORM';
                $value = $request->query('NORM');

                if ($request->has('TANGGAL_LAHIR')) {
                    $extraParams['TANGGAL_LAHIR'] = $request->query('TANGGAL_LAHIR');
                } else {
                    return $this->apiResponse(['message' => 'TANGGAL_LAHIR wajib diisi untuk pencarian NORM'], 422, false);
                }
            }

            if (!$value) {
                return $this->apiResponse(['message' => 'Parameter NIK, KAP, atau NORM diperlukan'], 400, false);
            }

            $result = $this->regonline->findPatientByIdentity($value, $type, $extraParams);
            $body = is_string($result['body']) ? json_decode($result['body'], true) : $result['body'];
            $isSuccess = ($result['status'] >= 200 && $result['status'] < 300);

            return $this->apiResponse($body, $result['status'], $isSuccess);            
        } catch (\Exception $e) {
            return $this->apiResponse(null, 500, false);
        }
    }

    public function shdk(Request $request)
    {
        try {
            $result = $this->regonline->getShdkPatient($request->user());
            $isSuccess = ($result['status'] >= 200 && $result['status'] < 300);
            $body = is_string($result['body']) ? json_decode($result['body'], true) : $result['body'];

            return $this->apiResponse($body, $result['status'], $isSuccess);

        } catch (\Exception $e) {
            return $this->apiResponse(['message' => $e->getMessage()], 500, false);
        }
    }
}
