<?php
// app/Http/Controllers/NotificationMessageController.php
// Last updated: 2025-02-06
// Controller for managing NotificationMessage records, filtering, and retrying failed messages.

namespace App\Http\Controllers;

use App\Models\NotificationMessage;
use Illuminate\Http\Request;

class NotificationMessageController extends Controller
{
    // Lists all notification messages.
    public function index()
    {
        $messages = NotificationMessage::orderBy('created_at', 'desc')->get();
        return response()->json($messages);
    }
    
    // Retrieves a single notification message by ID.
    public function show($id)
    {
        $message = NotificationMessage::find($id);
        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }
        return response()->json($message);
    }
    
    // Deletes a notification message by ID.
    public function destroy($id)
    {
        $message = NotificationMessage::find($id);
        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }
        $message->delete();
        return response()->json(['status' => 'Message deleted']);
    }
    
    // Empties the notification messages table.
    public function empty()
    {
        NotificationMessage::truncate();
        return response()->json(['status' => 'Notification messages table emptied']);
    }
    
    // Filters notification messages based on query parameters.
    public function filter(Request $request)
    {
        $query = NotificationMessage::query();
        if ($request->has('gateway')) {
            $query->where('gateway', $request->gateway);
        }
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        if ($request->has('recipient')) {
            $query->where('recipient', $request->recipient);
        }
        if ($request->has('status')) {
            $query->where('response_status', $request->status);
        }
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }
        $messages = $query->orderBy('created_at', 'desc')->get();
        return response()->json($messages);
    }
    
    // Cron job endpoint to retry failed messages.
    public function retryFailed()
    {
        $failed = NotificationMessage::where('http_status_code', '<>', 200)
            ->orWhere('response_status', '<>', 'success')
            ->get();
        foreach ($failed as $message) {
            // For demonstration, mark as retried.
            $message->update(['response_status' => 'retried']);
        }
        return response()->json(['status' => 'Failed messages retried', 'count' => $failed->count()]);
    }
}
