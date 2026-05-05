<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Index
     */
    public function index()
    {
        // 
        return view('notifications.list');
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        // Update status
        if($id == 'all') {
            // Read all
            Auth::user()->unReadNotifications->markAsRead();
        }
        else {
            $notification = Auth::user()->notifications->find($id);
            $notification->markAsRead();
        }
    }

    /**
     * Destroy
     */
    public function destroy(Request $request, $id)
    {
        // Delete
        if($id == 'all') {
            // Delete all
            Auth::user()->notifications()->delete();
        }
    }
}