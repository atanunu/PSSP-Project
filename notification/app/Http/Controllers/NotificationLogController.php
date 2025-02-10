<?php
// app/Http/Controllers/NotificationLogController.php
// Last updated: 2025-02-06
// Controller for managing NotificationLog records.

namespace App\Http\Controllers;

use App\Models\NotificationLog;
use Illuminate\Http\Request;

class NotificationLogController extends Controller
{
    // Retrieves all notification logs.
    public function index()
    {
        $logs = NotificationLog::orderBy('created_at', 'desc')->get();
        return response()->json($logs);
    }
    
    // Retrieves a single notification log by ID.
    public function show($id)
    {
        $log = NotificationLog::find($id);
        if (!$log) {
            return response()->json(['error' => 'Log not found'], 404);
        }
        return response()->json($log);
    }
    
    // Deletes a notification log by ID.
    public function destroy($id)
    {
        $log = NotificationLog::find($id);
        if (!$log) {
            return response()->json(['error' => 'Log not found'], 404);
        }
        $log->delete();
        return response()->json(['status' => 'Log deleted']);
    }
    
    // Empties the notification logs table.
    public function empty()
    {
        NotificationLog::truncate();
        return response()->json(['status' => 'Notification logs table emptied']);
    }
}
