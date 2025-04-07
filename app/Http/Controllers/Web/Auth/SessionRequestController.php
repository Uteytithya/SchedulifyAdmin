<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SessionRequest;

class SessionRequestController extends Controller
{
    public function index()
    {
        $sessionRequests = SessionRequest::orderBy('requested_date', 'desc')->get();
        return view('admin.requests.index', ['requests' => $sessionRequests]);
    }
    
    public function update(Request $request, $id)
    {
        $sessionRequest = SessionRequest::findOrFail($id);
        $oldStatus = $sessionRequest->status;
        $sessionRequest->status = $request->status;
    
        if ($request->status == 'approved') {
            $room = $sessionRequest->room;
            if ($room) {
                $room->is_active = 0;
                $room->save();
            }
        }
    
        if ($request->status == 'denied') {
            $room = $sessionRequest->room;
            if ($room) {
                $room->is_active = 1;
                $room->save();
            }
        }
        
        $sessionRequest->save();
    
        return redirect()->back()->with('success', 'Request ' . $request->status . ' successfully.');
    }
    
}
