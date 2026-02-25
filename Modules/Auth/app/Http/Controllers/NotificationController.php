<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use App\Models\User;
use Pusher\Pusher;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class NotificationController extends Controller
{
    /**
     * Fungsi Helper Private untuk memicu Pusher
     */
    private function sendToPusher($channel, $event, $data)
    {
        $options = [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => false, // Bypass SSL untuk local dev
            'curl_options' => [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            ]
        ];

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $pusher->trigger($channel, $event, $data);
    }

    public function history(Request $request)
    {
        $items = UserNotification::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('sent_at')
            ->orderByDesc('id')
            ->limit(30)
            ->get([
                'id',
                'title',
                'body',
                'channel',
                'sent_by',
                'sent_at',
                'read_at',
            ]);

        return response()->json([
            'data' => $items,
        ]);
    }

    /**
     * 1. Kirim ke Spesifik User
     */
    public function sendPushFromExternal(Request $request) 
    {
        $user = \App\Models\User::find($request->user_id);
        if (!$user) return response()->json(['error' => 'User not found'], 404);

        $notif = \App\Models\UserNotification::create([
            'user_id' => $user->id,
            'title'   => $request->title,
            'body'    => $request->body,
            'sent_by' => $request->sender ?? 'System Admin',
            'sent_at' => now(),
        ]);

        $this->sendToPusher('user-channel-' . $user->id, 'new-notification', $notif);

        return response()->json(['success' => true, 'data' => $notif]);
    }

    /**
     * 2. Kirim ke Semua User (Broadcast)
     */
    public function sendPushToAll(Request $request) 
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'body'  => 'required|string',
        ]);
        
        $userIds = \App\Models\User::pluck('id');
        if ($userIds->isEmpty()) {
            return response()->json(['message' => 'Tidak ada user'], 404);
        }

        $now = now();
        $dataToInsert = $userIds->map(function ($id) use ($request, $now) {
            return [
                'user_id'    => $id,
                'title'      => $request->title,
                'body'       => $request->body,
                'sent_by'    => $request->sender ?? 'Broadcast System',
                'sent_at'    => $now,
                'created_at' => $now,
                'updated_at' => $now,
                'read_at'    => null,
            ];
        })->toArray();

        \App\Models\UserNotification::insert($dataToInsert);

        $this->sendToPusher('broadcast-channel', 'new-announcement', [
            'title'   => $request->title,
            'body'    => $request->body,
            'sent_at' => $now->toDateTimeString()
        ]);

        $users = \App\Models\User::all();
        \Illuminate\Support\Facades\Notification::send($users, new class($request->title, $request->body) extends \Illuminate\Notifications\Notification {
            private $t, $b;
            public function __construct($t, $b) { $this->t = $t; $this->b = $b; }
            public function via($notifiable) { return [WebPushChannel::class]; }
            public function toWebPush($notifiable, $notification) {
                return (new WebPushMessage)
                    ->title($this->t)
                    ->body($this->b)
                    ->icon('/pwa/icons/android-launchericon-192-192.png')
                    ->data(['url' => '/profil/notifikasi']);
            }
        });

        return response()->json([
            'success' => true, 
            'message' => 'Broadcast sukses ke ' . count($userIds) . ' user'
        ]);
    }

    /**
     * Untuk merubah status notif agar terbaca
     */
    public function markAsRead(Request $request)
    {
        $userId = $request->user()->id;

        \App\Models\UserNotification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sebagai terbaca'
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);

        $user = $request->user();
        $user->updatePushSubscription(
            $request->endpoint,
            $request->keys['p256dh'],
            $request->keys['auth']
        );

        return response()->json([
            'success' => true,
            'message' => 'Subscription stored successfully.'
        ]);
    }
}
