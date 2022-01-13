<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function MongoDB\BSON\toJSON;

class NotificationController extends Controller
{
    public function show() {
        $user = Auth::user();
        $notifications = $user->notifications()->where('read', false)->get();
        $notifications_count = [];
        $ignore = [];
        for($i = 0, $iMax = count($notifications); $i < $iMax; $i++) {
            $count = 1;
            $ids = [];
            $ids[] = $notifications[$i]->id;
            if (in_array($i, $ignore)) {
                continue;
            }
            for($j = $i+1, $jMax = count($notifications); $j < $jMax; $j++) {
                if ($notifications[$i]->text == $notifications[$j]->text) {
                    $ignore[] = $j;
                    $ids[] = $notifications[$j]->id;
                    $count++;
                }
            }
            $count_arr['notification'] = $notifications[$i];
            $count_arr['count'] = $count;
            $count_arr['ids'] = implode(',', $ids);
            $notifications_count[] = $count_arr;
        }
        return view('notifications', ['notifications' => $notifications_count]);
    }

    public function read(int $id) {
        $notification = Notification::query()->where('id', $id)->first();
        $notification->read = true;
        $notification->save();
    }

    public function read_all(string $ids) {
        $ids_ = explode(',', $ids);
        foreach ($ids_ as $id) {
            $this->read($id);
        }
        return redirect()->route('notifications');
    }
}
