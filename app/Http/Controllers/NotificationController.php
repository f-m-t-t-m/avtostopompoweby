<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function show() {
        $user = Auth::user();
        $notifications = $user->notifications()->where('read', false)->get();
        $notifications_count = [];
        $ignore = [];
        for($i = 0, $iMax = count($notifications); $i < $iMax; $i++) {
            $count = 1;
            if (in_array($i, $ignore)) {
                continue;
            }
            for($j = $i+1, $jMax = count($notifications); $j < $jMax; $j++) {
                if ($notifications[$i]->text == $notifications[$j]->text) {
                    $ignore[] = $j;
                    $count++;
                }
            }
            $count_arr['notification'] = $notifications[$i];
            $count_arr['count'] = $count;
            $notifications_count[] = $count_arr;
        }
        return view('notifications', ['notifications' => $notifications_count]);
    }
}
