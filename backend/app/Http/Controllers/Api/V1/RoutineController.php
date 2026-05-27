<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\RoutineLog;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class RoutineController extends Controller
{
    private static array $amSteps = [
        ['id' => 'am_1', 'label' => 'Double Cleanse (oil + foam)',  'pts' => 1],
        ['id' => 'am_2', 'label' => 'BHA / Exfoliant Toner',        'pts' => 1],
        ['id' => 'am_3', 'label' => 'Niacinamide Serum',            'pts' => 1],
        ['id' => 'am_4', 'label' => 'Hyaluronic Acid',              'pts' => 1],
        ['id' => 'am_5', 'label' => 'Lightweight Moisturiser',      'pts' => 1],
        ['id' => 'am_6', 'label' => 'SPF 50+ Sunscreen',            'pts' => 2],
    ];

    private static array $pmSteps = [
        ['id' => 'pm_1', 'label' => 'Oil Cleanser',               'pts' => 1],
        ['id' => 'pm_2', 'label' => 'Foam Cleanser',              'pts' => 1],
        ['id' => 'pm_3', 'label' => 'BHA Treatment (2–3×/week)',  'pts' => 1],
        ['id' => 'pm_4', 'label' => 'Snail Mucin Essence',        'pts' => 1],
        ['id' => 'pm_5', 'label' => 'Night Moisturiser',          'pts' => 1],
        ['id' => 'pm_6', 'label' => 'Laneige Lip Sleeping Mask',  'pts' => 1],
    ];

    /** GET /api/v1/routine/steps */
    public function steps(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => ['am' => self::$amSteps, 'pm' => self::$pmSteps],
        ]);
    }

    /** GET /api/v1/routine/logs */
    public function logs(Request $request): JsonResponse
    {
        $user  = $request->user();
        $today = now()->toDateString();

        $weekStart = now()->startOfWeek(Carbon::MONDAY);
        $weekEnd   = now()->endOfWeek(Carbon::SUNDAY);

        $weekLogs = RoutineLog::where('user_id', $user->id)
            ->whereBetween('log_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->get()
            ->keyBy(fn($l) => $l->log_date->toDateString());

        $todayLog = $weekLogs[$today] ?? null;

        $streak  = $this->calculateStreak($user->id);
        $weekPts = $weekLogs->sum('pts_earned');

        // Build week grid (Mon–Sun)
        $weekGrid = [];
        for ($i = 0; $i < 7; $i++) {
            $date    = $weekStart->copy()->addDays($i);
            $dateStr = $date->toDateString();
            $log     = $weekLogs[$dateStr] ?? null;
            $isToday = $dateStr === $today;
            $isPast  = $date->lt(now()->startOfDay()) && !$isToday;

            $weekGrid[] = [
                'date'      => $dateStr,
                'day'       => $date->format('D'),
                'num'       => (int) $date->format('j'),
                'is_today'  => $isToday,
                'is_past'   => $isPast,
                'is_done'   => $log && ($log->am_done || $log->pm_done),
                'is_missed' => $isPast && !$log,
            ];
        }

        // Monthly stats
        $monthStart    = now()->startOfMonth()->toDateString();
        $monthEnd      = now()->endOfMonth()->toDateString();
        $monthLogs     = RoutineLog::where('user_id', $user->id)
            ->whereBetween('log_date', [$monthStart, $monthEnd])
            ->get();

        $daysLogged      = $monthLogs->count();
        $daysElapsed     = (int) now()->format('j');
        $doneSessions    = $monthLogs->filter(fn($l) => $l->am_done || $l->pm_done)->count();
        $totalSessions   = $daysElapsed * 2; // potential AM + PM each day
        $completionRate  = $totalSessions > 0 ? (int) round($doneSessions / $totalSessions * 100) : 0;
        $monthPts        = $monthLogs->sum('pts_earned');

        return response()->json([
            'success' => true,
            'data'    => [
                'today'    => $todayLog ? [
                    'am_steps' => $todayLog->am_steps ?? [],
                    'pm_steps' => $todayLog->pm_steps ?? [],
                    'am_done'  => $todayLog->am_done,
                    'pm_done'  => $todayLog->pm_done,
                ] : null,
                'streak'    => $streak,
                'week_pts'  => $weekPts,
                'week_grid' => $weekGrid,
                'month'     => [
                    'name'            => now()->format('F Y'),
                    'days_logged'     => $daysLogged,
                    'days_elapsed'    => $daysElapsed,
                    'completion_rate' => $completionRate,
                    'pts_earned'      => $monthPts,
                ],
            ],
        ]);
    }

    /** POST /api/v1/routine/log */
    public function log(Request $request): JsonResponse
    {
        $data = $request->validate([
            'date'      => 'nullable|date_format:Y-m-d',
            'tab'       => 'required|in:am,pm',
            'steps'     => 'nullable|array',
            'steps.*'   => 'string',
            'mark_done' => 'nullable|boolean',
        ]);
        $data['steps'] = $data['steps'] ?? [];

        $user    = $request->user();
        $date    = $data['date'] ?? now()->toDateString();
        $tab     = $data['tab'];
        $stepKey = $tab === 'am' ? 'am_steps' : 'pm_steps';
        $doneKey = $tab === 'am' ? 'am_done'  : 'pm_done';

        $routineLog = RoutineLog::firstOrNew(['user_id' => $user->id, 'log_date' => $date]);
        $routineLog->$stepKey = $data['steps'];

        $ptsEarned   = 0;
        $alreadyDone = (bool) $routineLog->$doneKey;

        if (!empty($data['mark_done']) && !$alreadyDone) {
            $stepDefs    = $tab === 'am' ? self::$amSteps : self::$pmSteps;
            $checkedIds  = $data['steps'];

            foreach ($stepDefs as $step) {
                if (in_array($step['id'], $checkedIds)) {
                    $ptsEarned += $step['pts'];
                }
            }
            // Bonus for completing all steps
            if (count($checkedIds) >= count($stepDefs)) {
                $ptsEarned += 5;
            }

            $routineLog->$doneKey    = true;
            $routineLog->pts_earned  = ($routineLog->pts_earned ?? 0) + $ptsEarned;
            $routineLog->save();

            LoyaltyService::award(
                $user,
                'routine_complete',
                $ptsEarned,
                "Daily {$tab} routine completed"
            );
        } else {
            $routineLog->save();
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'pts_earned'  => $ptsEarned,
                'already_done' => $alreadyDone,
            ],
        ]);
    }

    /** GET /api/v1/routine/admin/stats  (admin only) */
    public function adminStats(): JsonResponse
    {
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd   = now()->endOfMonth()->toDateString();

        $totalLogs      = RoutineLog::count();
        $usersLogging   = RoutineLog::distinct('user_id')->count('user_id');
        $monthLogsCount = RoutineLog::whereBetween('log_date', [$monthStart, $monthEnd])->count();

        // Completion rate: rows where at least am_done or pm_done is true
        $doneLogs  = RoutineLog::where(fn($q) => $q->where('am_done', true)->orWhere('pm_done', true))->count();
        $avgCompletion = $totalLogs > 0 ? (int) round($doneLogs / $totalLogs * 100) : 0;

        // Per-user monthly stats for leaderboard
        $leaderboard = RoutineLog::selectRaw('user_id, COUNT(*) as days_logged, SUM(pts_earned) as total_pts')
            ->whereBetween('log_date', [$monthStart, $monthEnd])
            ->groupBy('user_id')
            ->orderByDesc('days_logged')
            ->with('user:id,name,email')
            ->limit(20)
            ->get()
            ->map(fn($s) => [
                'user_id'    => $s->user_id,
                'name'       => $s->user->name  ?? 'Unknown',
                'email'      => $s->user->email ?? '',
                'days_logged' => (int) $s->days_logged,
                'total_pts'  => (int) $s->total_pts,
            ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'total_logs'     => $totalLogs,
                'users_logging'  => $usersLogging,
                'month_logs'     => $monthLogsCount,
                'avg_completion' => $avgCompletion,
                'leaderboard'    => $leaderboard,
            ],
        ]);
    }

    /** GET /api/v1/routine/admin/user/{userId}/logs  (admin only) */
    public function adminUserLogs(Request $request, int $userId): JsonResponse
    {
        $user = \App\Models\User::findOrFail($userId);

        // Return last 14 days of logs so the editor can show two weeks
        $from = now()->subDays(13)->toDateString();
        $to   = now()->toDateString();

        $logs = RoutineLog::where('user_id', $userId)
            ->whereBetween('log_date', [$from, $to])
            ->orderBy('log_date')
            ->get()
            ->keyBy(fn($l) => $l->log_date->toDateString());

        // Build a grid of the 14 days with log state for each
        $days = [];
        for ($i = 13; $i >= 0; $i--) {
            $date    = now()->subDays($i)->toDateString();
            $log     = $logs[$date] ?? null;
            $days[]  = [
                'date'     => $date,
                'am_steps' => $log?->am_steps ?? [],
                'pm_steps' => $log?->pm_steps ?? [],
                'am_done'  => (bool) ($log?->am_done ?? false),
                'pm_done'  => (bool) ($log?->pm_done ?? false),
                'pts_earned' => (int) ($log?->pts_earned ?? 0),
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'user'     => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
                'am_steps' => self::$amSteps,
                'pm_steps' => self::$pmSteps,
                'days'     => $days,
            ],
        ]);
    }

    /** PUT /api/v1/routine/admin/user/{userId}/log/{date}  (admin only) */
    public function adminUpdateLog(Request $request, int $userId, string $date): JsonResponse
    {
        \App\Models\User::findOrFail($userId);

        $data = $request->validate([
            'am_steps' => 'nullable|array',
            'am_steps.*' => 'string',
            'pm_steps' => 'nullable|array',
            'pm_steps.*' => 'string',
            'am_done'  => 'nullable|boolean',
            'pm_done'  => 'nullable|boolean',
        ]);

        $log = RoutineLog::firstOrNew(['user_id' => $userId, 'log_date' => $date]);

        if (array_key_exists('am_steps', $data)) $log->am_steps = $data['am_steps'] ?? [];
        if (array_key_exists('pm_steps', $data)) $log->pm_steps = $data['pm_steps'] ?? [];
        if (array_key_exists('am_done',  $data)) $log->am_done  = (bool) $data['am_done'];
        if (array_key_exists('pm_done',  $data)) $log->pm_done  = (bool) $data['pm_done'];

        // Recalculate pts from checked steps
        $pts = 0;
        foreach (self::$amSteps as $step) {
            if (in_array($step['id'], $log->am_steps ?? [])) $pts += $step['pts'];
        }
        foreach (self::$pmSteps as $step) {
            if (in_array($step['id'], $log->pm_steps ?? [])) $pts += $step['pts'];
        }
        // Bonus for full AM
        if (count($log->am_steps ?? []) >= count(self::$amSteps) && $log->am_done) $pts += 5;
        // Bonus for full PM
        if (count($log->pm_steps ?? []) >= count(self::$pmSteps) && $log->pm_done) $pts += 5;

        $log->pts_earned = $pts;
        $log->save();

        return response()->json([
            'success' => true,
            'data'    => [
                'date'      => $date,
                'am_done'   => $log->am_done,
                'pm_done'   => $log->pm_done,
                'am_steps'  => $log->am_steps,
                'pm_steps'  => $log->pm_steps,
                'pts_earned' => $log->pts_earned,
            ],
        ]);
    }

    private function calculateStreak(int $userId): int
    {
        $streak = 0;
        $date   = now()->startOfDay();

        // If today isn't logged yet, start counting from yesterday
        $todayLogged = RoutineLog::where('user_id', $userId)
            ->where('log_date', $date->toDateString())
            ->where(fn($q) => $q->where('am_done', true)->orWhere('pm_done', true))
            ->exists();

        if (!$todayLogged) {
            $date->subDay();
        }

        while (true) {
            $exists = RoutineLog::where('user_id', $userId)
                ->where('log_date', $date->toDateString())
                ->where(fn($q) => $q->where('am_done', true)->orWhere('pm_done', true))
                ->exists();

            if (!$exists) break;
            $streak++;
            $date->subDay();
        }

        return $streak;
    }
}
