<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubjectResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : JsonResponse {
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
            $count_arr['ids'] = $ids;
            $notifications_count[] = $count_arr;
        }
        return response()->json($notifications_count);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification) : JsonResponse
    {
        return response()->json($notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification) : JsonResponse
    {
        $notification->read = true;
        $notification->save();
        return response()->json("successful");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
