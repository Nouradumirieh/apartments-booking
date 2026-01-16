<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification as NotificationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Fixed the 'Log' undefined type issue
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class NotificationController extends Controller
{
    /**
     * Test function to verify the notification setup
     */
    public function testNotification($userId)
    {
        $user = User::find($userId);

        if (!$user || empty($user->fcm_token)) {
            return response()->json(['error' => 'User not found or has no FCM token'], 404);
        }

        try {
            // Precise path based on your storage structure
            $credentialsPath = storage_path('app/Firebase/firebase_credentials.json');

            if (!file_exists($credentialsPath)) {
                return response()->json(['error' => 'JSON credentials file not found'], 500);
            }

            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $messaging = $factory->createMessaging();

            $notification = FirebaseNotification::create('Test Success!', 'Hello ' . $user->name . ', your notifications are working!');
            $message = CloudMessage::withTarget('token', $user->fcm_token)
                ->withNotification($notification);

            $messaging->send($message);

            return response()->json(['success' => 'Notification sent successfully to ' . $user->phone]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Firebase Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * General static function to be called from other controllers (e.g., BookingController)
     */
    public static function sendPushNotification($userId, $title, $body)
    {
        $user = User::find($userId);

        if (!$user || !$user->fcm_token) {
            Log::warning("Notification failed: User $userId not found or missing FCM token.");
            return false;
        }

        try {
            $credentialsPath = storage_path('app/Firebase/firebase_credentials.json');
            
            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $messaging = $factory->createMessaging();

            $notification = FirebaseNotification::create($title, $body);
            
            $message = CloudMessage::withTarget('token', $user->fcm_token)
                ->withNotification($notification);

            $messaging->send($message);

            // Save notification log in the database for the user's notification center
            NotificationModel::create([
                'user_id' => $user->id,
                'title' => $title,
                'body' => $body,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Firebase Error in sendPushNotification: " . $e->getMessage());
            return false;
        }
    }
}