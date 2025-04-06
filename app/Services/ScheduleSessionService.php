<?php

namespace App\Services;

use App\Models\ScheduleSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ScheduleSessionService
{
    /**
     * Create a new schedule session.
     */
    public function createSession(array $data): ScheduleSession
    {
        $this->validateTimeRange($data['start_time'], $data['end_time']);

        if ($this->checkForOverlap($data['room_id'], $data['day'], $data['start_time'], $data['end_time'])) {
            throw ValidationException::withMessages([
                'overlap' => 'The session overlaps with an existing session in the same room.',
            ]);
        }

        return ScheduleSession::create($data);
    }

    /**
     * Update an existing schedule session.
     */
    public function updateSession(string $id, array $data): ScheduleSession
    {
        $session = ScheduleSession::findOrFail($id);

        if (isset($data['start_time'], $data['end_time'])) {
            $this->validateTimeRange($data['start_time'], $data['end_time']);
        }

        if (
            isset($data['room_id'], $data['day'], $data['start_time'], $data['end_time']) &&
            $this->checkForOverlap($data['room_id'], $data['day'], $data['start_time'], $data['end_time'], $id)
        ) {
            throw ValidationException::withMessages([
                'overlap' => 'The session overlaps with an existing session in the same room.',
            ]);
        }

        $session->update($data);
        return $session;
    }

    /**
     * Retrieve sessions dynamically based on filters.
     */
    public function getSessions(array $filters)
    {
        return ScheduleSession::query()
            ->when(isset($filters['room_id']), fn($q) => $q->where('room_id', $filters['room_id']))
            ->when(isset($filters['day']), fn($q) => $q->where('day', $filters['day']))
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['start_time'], $filters['end_time']), function ($q) use ($filters) {
                $q->where('start_time', '>=', $filters['start_time'])
                    ->where('end_time', '<=', $filters['end_time']);
            })
            ->get();
    }

    /**
     * Delete a schedule session.
     */
    public function deleteSession(string $id): bool
    {
        $session = ScheduleSession::findOrFail($id);
        return $session->delete();
    }

    /**
     * Validate that the start time is before the end time.
     */
    private function validateTimeRange(string $start, string $end): void
    {
        if (strtotime($start) >= strtotime($end)) {
            throw ValidationException::withMessages([
                'time_range' => 'The start time must be earlier than the end time.',
            ]);
        }
    }

    /**
     * Check for overlapping sessions.
     */
    private function checkForOverlap(string $roomId, string $day, string $startTime, string $endTime, string $excludeId = null): bool
    {
        $query = ScheduleSession::where('room_id', $roomId)
            ->where('day', $day)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($query) use ($startTime, $endTime) {
                        $query->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
