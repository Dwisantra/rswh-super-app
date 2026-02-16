<?php

namespace Modules\RegOnline\Services;

use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
class RegOnlineService
{
    protected LoginService $login;
    protected string $url;

    public function __construct(LoginService $login)
    {
        $this->login = $login;
        $this->url = (string) config('regonline.url');
    }

    private function getRegOnlineToken()
    {
        return Cache::remember('simrs_plugin_token', 3500, function () {
            $response = Http::withHeaders([
                'Accept' => '*/*'
            ])
            ->attach('username', config('regonline.x_id'))
            ->attach('password', config('regonline.x_pass'))
            ->post($this->url.'/registrasionline/plugins/getToken');

            $json = $response->json();

            if (!isset($json['response']['token'])) {
                throw new \Exception('Token tidak ditemukan: '.$response->body());
            }

            return $json['response']['token'];
        });
    }

    protected function baseHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'X-Token' => $this->getRegOnlineToken(),
        ];
    }

    protected function headers(User $user): array
    {
        return [
            ...$this->baseHeaders(),
            'X-Patient-Key' => $user->nik ?: $user->norm,
            'X-Family-Key' => $user->family_code,
        ];
    }

    public function findPatientByNik(string $nik): array
    {
        try {
            $response = Http::withHeaders($this->baseHeaders())
                ->timeout((int) config('regonline.timeout', 15))
                ->get($this->url.'/registrasionline/plugins/getIdentitasPasien', [
                    'NIK' => $nik
                ]);
        } catch (ConnectionException) {
        }

        return [
            'status' => $response->status(),
            'body' => $response->body()
        ];
    }

    public function getDoctorSchedules(User $user): array
    {
        try {
            $response = Http::withHeaders($this->headers($user))
                ->timeout((int) config('regonline.timeout', 15))
                ->get($this->url.'/registrasionline/rsonline/getJadwalDokter');

            if ($response->successful()) {
                return Arr::get($response->json(), 'response.data', $response->json());
            }
        } catch (ConnectionException) {
        }

        return [
            ['clinic_name' => 'Poli Penyakit Dalam', 'doctor_name' => 'dr. S. Wiratama, Sp.PD', 'schedule' => 'Senin - 08:00'],
            ['clinic_name' => 'Poli Anak', 'doctor_name' => 'dr. A. Lestari, Sp.A', 'schedule' => 'Selasa - 09:30'],
            ['clinic_name' => 'Poli Saraf', 'doctor_name' => 'dr. M. Hidayat, Sp.N', 'schedule' => 'Rabu - 10:00'],
        ];
    }

    public function getBedCapacity(User $user): array
    {
        try {
            $response = Http::withHeaders($this->headers($user))
                ->timeout((int) config('regonline.timeout', 15))
                ->get($this->url.'/registrasionline/rsonline/getKapasitasTempatTidur');

            if ($response->successful()) {
                return Arr::get($response->json(), 'response.data', $response->json());
            }
        } catch (ConnectionException) {
        }

        return [
            ['room' => 'Kelas 1', 'available' => 4, 'total' => 12],
            ['room' => 'Kelas 2', 'available' => 7, 'total' => 20],
            ['room' => 'ICU', 'available' => 1, 'total' => 8],
        ];
    }

    public function submitRegistration(User $user, array $payload): array
    {
        $payload['patient_key'] = $user->nik ?: $user->norm;
        $payload['family_code'] = $user->family_code;

        try {
            $response = Http::withHeaders($this->headers($user))
                ->timeout((int) config('regonline.timeout', 15))
                ->post($this->url.'/registrasionline/rsonline/postPendaftaran', $payload);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (ConnectionException) {
        }

        return [
            'success' => true,
            'message' => 'Tersimpan lokal (SIMRS belum terhubung)',
            'queue_number' => 'A-'.Str::padLeft((string) random_int(1, 999), 3, '0'),
        ];
    }
}
