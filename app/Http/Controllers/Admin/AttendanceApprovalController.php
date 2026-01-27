<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('isAdmin');
    }

    /**
     * Show pending attendance for approval
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = AttendanceRecord::with('user', 'approvedBy');

        if ($status) {
            $query->where('approval_status', $status);
        } else {
            $query->where('approval_status', 'pending');
        }

        $records = $query->orderBy('attendance_date', 'desc')
            ->paginate(15);

        $pendingCount = AttendanceRecord::where('approval_status', 'pending')->count();
        $approvedCount = AttendanceRecord::where('approval_status', 'approved')->count();
        $rejectedCount = AttendanceRecord::where('approval_status', 'rejected')->count();

        return view('admin.attendance.approvals', compact('records', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    /**
     * Approve attendance record
     */
    public function approve(Request $request, AttendanceRecord $record)
    {
        try {
            AttendanceService::approveAttendance($record->id, auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Presensi telah disetujui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Reject attendance record
     */
    public function reject(Request $request, AttendanceRecord $record)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            AttendanceService::rejectAttendance(
                $record->id,
                auth()->id(),
                $request->rejection_reason
            );

            return response()->json([
                'success' => true,
                'message' => 'Presensi telah ditolak.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get pending count (untuk widget)
     */
    public function getPendingCount()
    {
        $count = AttendanceRecord::where('approval_status', 'pending')->count();

        return response()->json(['count' => $count]);
    }
}
