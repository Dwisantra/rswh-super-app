<?php

namespace Modules\Patient\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Patient\Models\Patient;
use Modules\RegOnline\Services\RegOnlineService;

class PatientController extends Controller
{
    public function __construct(protected RegOnlineService $regonline) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        return Patient::where('user_id', $request->user()->id)
            ->orderBy('is_default', 'desc')
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patient::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $user_id = $request->user()->id;
        $norm = $request->norm;

        $existingPatient = Patient::where('user_id', $user_id)
            ->where('norm', $norm)
            ->first();

        if ($existingPatient) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien dengan No. RM ' . $norm . ' sudah terdaftar di akun Anda. Tidak dapat menambah pasien yang sama.'
            ], 422);
        }

        if (strtoupper($request->relation) === 'KEPALA KELUARGA') {
            $kkExists = Patient::where('user_id', $user_id)
                ->where('relation', 'KEPALA KELUARGA')
                ->exists();

            if ($kkExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki Kepala Keluarga di akun ini.'
                ], 422);
            }
        }

        $isFirst = Patient::where('user_id', $user_id)->count() === 0;
        $isDefault = $isFirst || $request->is_default;

        if ($isDefault) Patient::where('user_id', $user_id)->update(['is_default' => false]);

        $patient = Patient::create([
            'user_id' => $user_id,
            'norm' => $request->norm,
            'nik' => $request->nik,
            'name'  => $request->name,
            'panggilan' => $request->panggilan,
            'gelar_depan' => $request->gelar_depan,
            'gelar_belakang' => $request->gelar_belakang,
            'birth_date' => $request->birth_date,
            'birth_place' => $request->birth_place,
            'gender' => $request->gender,
            'religion' => $request->religion,
            'address' => $request->address,
            'occupation' => $request->occupation,
            'marital_status' => $request->marital_status,
            'relation' => $request->relation,
            'is_default' => $isDefault
        ]);

        return response()->json(['success' => true, 'data' => $patient]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('patient::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('patient::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    /**
     * Set patient as default
     */
    public function setDefault(Request $request, $id) {
        Patient::where('user_id', $request->user()->id)->update(['is_default' => false]);
        Patient::where('id', $id)->update(['is_default' => true]);
        return response()->json(['success' => true]);
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
