<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $user = Auth::user();
        
        $query = Notification::where('user_id', $user->id);
        
        if ($filter === 'unread') {
            $query->unread();
        } elseif ($filter === 'read') {
            $query->read();
        }
        
        $notifications = $query->latest()->paginate(10);
        
        $countsInfo = [
            'all' => Notification::where('user_id', $user->id)->count(),
            'unread' => Notification::where('user_id', $user->id)->unread()->count(),
            'read' => Notification::where('user_id', $user->id)->read()->count(),
        ];
        
        return view('notifications.index', compact('notifications', 'countsInfo', 'filter'));
    }

    /**
     * Display the specified notification.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\View\View
     */
    public function show(Notification $notification)
    {
        $this->authorize('view', $notification);
        
        // Mark the notification as read when viewed
        if (!$notification->read_at) {
            $notification->markAsRead();
        }
        
        // Get related notifications (same type, for the same user, excluding current one)
        $relatedNotifications = Notification::where('user_id', $notification->user_id)
            ->where('type', $notification->type)
            ->where('id', '!=', $notification->id)
            ->latest()
            ->take(5)
            ->get();
        
        return view('notifications.show', compact('notification', 'relatedNotifications'));
    }

    /**
     * Mark the specified notification as read.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Notification $notification)
    {
        $this->authorize('update', $notification);
        
        $notification->markAsRead();
        
        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read for the authenticated user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Remove the specified notification from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);
        
        $notification->delete();
        
        return redirect()->route('notifications.index')
            ->with('success', 'Notification deleted successfully.');
    }

    /**
     * Store a newly created notification in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|in:order,system,promotion',
            'data' => 'nullable|json',
            'read_at' => 'nullable|date',
        ]);
        
        Notification::create($validated);
        
        return redirect()->back()
            ->with('success', 'Notification sent successfully.');
    }

    /**
     * Clear all notifications for the authenticated user.
     */
    public function clearAll()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)->delete();
        
        return redirect()->route('notifications.index')
            ->with('success', 'All notifications cleared successfully.');
    }

    /**
     * Get unread notification count for the authenticated user.
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
            
        return response()->json([
            'count' => $count
        ]);
    }
} 