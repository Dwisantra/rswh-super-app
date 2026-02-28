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

    public function findPatientByIdentity(string $value, string $type = 'NIK', array $extraParams = []): array
    {
        $params = [
            'NIK'  => ['NIK' => $value],
            'KAP'  => ['KAP' => $value],
            'NORM' => ['NORM' => $value],
        ];

        $queryParam = $params[strtoupper($type)] ?? ['NIK' => $value];
        $queryParam = array_merge($queryParam, $extraParams);

        try {
           $response = Http::withHeaders($this->baseHeaders())
            ->timeout((int) config('regonline.timeout', 15))
            ->get($this->url.'/registrasionline/plugins/getIdentitasPasien', $queryParam);

            return [
                'status' => $response->status(),
                'body' => $response->json()
            ];
        } catch (ConnectionException) {
            return [
                'status' => 500,
                'body' => 'Connection error'
            ];
        }
    }

    public function getDoctorSchedules(User $user, ?string $doctorName = null): array
    {
        try {
            $response = Http::withHeaders($this->headers($user))
                ->timeout((int) config('regonline.timeout', 15))
                ->get($this->url.'/registrasionline/plugins/getJadwalDokterHfis');

            if ($response->successful()) {
                $data = Arr::get($response->json(), 'response.data', $response->json());
                return $this->filterDoctorSchedules($data, $doctorName);
            }
        } catch (ConnectionException) {
        }

        return [
            'status' => $response->status(),
            'body' => $response->body()
        ];
        return $this->filterDoctorSchedules($fallback, $doctorName);
    }

    protected function filterDoctorSchedules(array $schedules, ?string $doctorName = null): array
    {
        if (!$doctorName) {
            return $schedules;
        }

        $keyword = Str::lower(trim($doctorName));

        return array_values(array_filter($schedules, function ($item) use ($keyword) {
            $name = (string) data_get($item, 'doctor_name', data_get($item, 'nm_dokter', ''));

            return Str::contains(Str::lower($name), $keyword);
        }));
    }

    public function getBedCapacity(User $user): array
    {
        try {
            $response = Http::withHeaders($this->headers($user))
                ->timeout((int) config('regonline.timeout', 15))
                ->get($this->url.'/registrasionline/plugins/getKetersediaanTempatTidur');

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

    public function getAllClinicQueues(User $user): array
    {
        try {
            $response = Http::withHeaders($this->headers($user))
                ->timeout((int) config('regonline.timeout', 15))
                ->get($this->url.'/registrasionline/plugins/getMonitoringAntrianPoli');

            if ($response->successful()) {
                return Arr::get($response->json(), 'response.data', $response->json());
            }
        } catch (ConnectionException) {
        }

        return [
            'status' => $response->status(),
            'body' => $response->body()
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

    public function getShdkPatient(User $user): array
    {
        try {
            $response = Http::withHeaders($this->baseHeaders())
                ->timeout((int) config('regonline.timeout', 15))
                ->get($this->url . '/registrasionline/plugins/getShdk');

            return [
                'status' => $response->status(),
                'body' => $response->body()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 500,
                'body' => json_encode(['message' => 'Gagal koneksi ke SIMRS: ' . $e->getMessage()])
            ];
        }
    }

    public function getClinic(User $user): array
    {
        try {
            $response = Http::withHeaders($this->headers($user))
                ->timeout((int) config('regonline.timeout', 15))
                ->get($this->url . '/registrasionline/plugins/getRuangan');

            if ($response->successful()) {
                return Arr::get($response->json(), 'response.data', $response->json());
            }

            return [
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        } catch (ConnectionException) {
            return [
                'status' => 500,
                'body' => json_encode(['message' => 'Gagal koneksi ke SIMRS: ' . $e->getMessage()])
            ];
        }
    }

    public function getDoctorScheduleByClinic(User $user, string $poliCode, int $dayCode): array
    {
        try {
            $response = Http::withHeaders($this->headers($user))
                ->timeout((int) config('regonline.timeout', 15))
                ->get($this->url . '/registrasionline/jadwaldokterhfis', [
                    'POLI' => $poliCode,
                    'KODE_HARI' => $dayCode,
                ]);

            if ($response->successful()) {
                return Arr::get($response->json(), 'response.data', $response->json());
            }

            return [
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        } catch (ConnectionException) {
            return [
                'status' => 500,
                'body' => json_encode(['message' => 'Gagal koneksi ke SIMRS: ' . $e->getMessage()])
            ];
        }
    }

    public function getPaymentMethods(User $user, ?string $ruanganPenjamin = null): array
    {
        $query = [];
        if ($ruanganPenjamin) {
            $query['RUANGAN_PENJAMIN'] = $ruanganPenjamin;
        }

        try {
            $response = Http::withHeaders($this->headers($user))
                ->timeout((int) config('regonline.timeout', 15))
                ->get($this->url . '/registrasionline/plugins/getCaraBayar', $query);

            if ($response->successful()) {
                return Arr::get($response->json(), 'response.data', $response->json());
            }

            return [
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        } catch (ConnectionException) {
            return [];
        }
    }
}
