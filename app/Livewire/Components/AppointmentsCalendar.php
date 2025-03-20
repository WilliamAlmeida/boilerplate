<?php

namespace App\Livewire\Components;

use App\Enums\EnumAppointmentStatus;
use Mary\Traits\Toast;
use Livewire\Attributes\On;
use App\Models\Appointments;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Omnia\LivewireCalendar\LivewireCalendar;

class AppointmentsCalendar extends LivewireCalendar
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

    private $colors = [
        'pending' => 'yellow-400',
        'confirmed' => 'green-400',
        'cancelled' => 'red-400',
        'completed' => 'blue-400',
    ];

    public function clear(): void
    {
        $this->reset('drawer', 'type', 'exam_id', 'specialist_id');
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
        ->when(!$this->status, function ($query) {
            $query->where('status', '!=', EnumAppointmentStatus::Completed);
        })
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

    public function onDayClick($year, $month, $day)
    {
        // This event is triggered when a day is clicked
        // You will be given the $year, $month and $day for that day

        $now = now(auth()->user()->timezone);

        $datetime = [
            'init' => Carbon::create($year, $month, $day, $now->hour, $now->minute, $now->second)->format('Y-m-d H:i:s'),
            'end' => Carbon::create($year, $month, $day, $now->addHour()->hour, $now->minute, $now->second)->format('Y-m-d H:i:s'),
        ];

        $this->dispatch('create', $datetime);
    }

    public function onEventClick($eventId)
    {
        // This event is triggered when an event card is clicked
        // You will be given the event id that was clicked
        
        $this->dispatch('edit', $eventId);
    }

    public function onEventDropped($eventId, $year, $month, $day)
    {
        // This event will fire when an event is dragged and dropped into another calendar day
        // You will get the event id, year, month and day where it was dragged to

        $appointment = Appointments::find($eventId);

        if(!$appointment || $appointment->datetime_init->isSameDay(Carbon::create($year, $month, $day))) return;

        $datetime_init = $appointment->datetime_init->setDate($year, $month, $day);
        $datetime_end = $appointment->datetime_end->setDate($year, $month, $day);

        $appointment->update([
            'datetime_init' => $datetime_init,
            'datetime_end' => $datetime_end,
        ]);
    }
}