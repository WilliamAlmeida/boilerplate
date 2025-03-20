<?php

namespace App\Livewire\Components;

use Mary\Traits\Toast;
use Livewire\Attributes\On;
use App\Models\Appointments;
use Illuminate\Support\Collection;
use App\Enums\EnumAppointmentStatus;
use App\Livewire\Components\Kanban\LivewireKanban;

class AppointmentsKanban extends LivewireKanban
{
    use Toast;

    public $drawer = false;

    public $type;

    public $exams = [];
    public $exam_id;

    public $specialists = [];
    public $specialist_id;

    public $appointment_status = [];
    public $status;

    public $colors = [
        'pending' => 'yellow-400',
        'confirmed' => 'green-400',
        'cancelled' => 'red-400',
        'completed' => 'blue-400',
    ];

    public $beforeKanbanView = 'livewire-kanban.before';
    public $afterKanbanView = 'livewire-kanban.after';

    public function clear(): void
    {
        $this->reset('drawer', 'type', 'exam_id', 'specialist_id', 'status');
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    public function afterMount($extras = [])
    {
        $this->listExamsSpecialists();

        $this->appointment_status = collect(EnumAppointmentStatus::cases())->map(function($item) {
            return [
                'id' => $item->value,
                'name' => __($item->name)
            ];
        })->values();
    }

    public function listExamsSpecialists(): void
    {
        $this->exams = \App\Models\Exams::select('id', 'title', 'event_id')->toBase()->get()->toArray();
        $this->specialists = \App\Models\Specialists::select('id', 'title', 'event_id')->toBase()->get()->toArray();
    }

    #[On('calendar:refresh')]
    public function events() : Collection
    {
        return Appointments::with('reference','cancelledBy','rescheduledFrom')
        ->when($this->status, function ($query) {
            $query->where('status', $this->status);
        })
        // ->when(!$this->status, function ($query) {
        //     $query->where('status', '!=', EnumAppointmentStatus::Completed);
        // })
        ->whereBetween('datetime_init', [$this->startsAt, $this->endsAt])
        ->when($this->type, function ($query) {
            if($this->type == 'c' && $this->exam_id) {
                $query->where('reference_type', \App\Models\Exams::class);
                $query->where('reference_id', $this->exam_id);

            } elseif($this->type == 'p' && $this->specialist_id) {
                $query->where('reference_type', \App\Models\Specialists::class);
                $query->where('reference_id', $this->specialist_id);
            }
        })
        ->orderBy('datetime_init')->get()->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->title,
                'status' => $appointment->status,
                'description' => $appointment->description,
                'date' => $appointment->datetime_init,
                'date_end' => $appointment->datetime_end,
                'reference' => $appointment->reference,
                'color' => $this->colors[$appointment->status->value],
                'rescheduled_from' => $appointment->rescheduledFrom?->id,
                'cancelled_by' => $appointment->cancelledBy?->id,
            ];
        });
    }

    public function onEventDropped($eventId, $status)
    {
        // This event will fire when an event is dragged and dropped into another status column
        // You will get the event id and the new status

        try {
            $appointment = Appointments::withTrashed()->find($eventId);

            if(!$appointment) return;
            if($appointment->status->value == $status) return;

            $appointment->update(['status' => $status]);

            $this->success('Agendamento atualizado com sucesso!', position: 'bottom-right');

        } catch (\Throwable $th) {
            throw $th;

            $this->error('Erro ao atualizar agendamento!');
        }
    }
}