<?php

namespace App\Livewire\Components\Kanban;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Class LivewireKanban
 * @package WilliamAlmeida\LivewireKanban
 * @property Carbon $startsAt
 * @property Carbon $endsAt
 * @property string $kanbanView
 * @property string $columnView
 * @property string $eventView
 * @property string $beforeKanbanWeekView
 * @property string $afterKanbanWeekView
 * @property string $dragAndDropClasses
 * @property int $pollMillis
 * @property string $pollAction
 * @property boolean $dragAndDropEnabled
 * @property boolean $columnClickEnabled
 * @property boolean $eventClickEnabled
 */
class LivewireKanban extends Component
{
    public $startsAt;
    public $endsAt;

    public $kanbanView;
    public $columnView;
    public $eventView;

    public $dragAndDropClasses;

    public $beforeKanbanView;
    public $afterKanbanView;

    public $pollMillis;
    public $pollAction;

    public $dragAndDropEnabled;
    public $columnClickEnabled;
    public $eventClickEnabled;

    protected $casts = [
        'startsAt' => 'date',
        'endsAt' => 'date',
    ];

    public function mount($initialYear = null,
                          $initialMonth = null,
                          $initialDay = null,
                          $kanbanView = null,
                          $columnView = null,
                          $eventView = null,
                          $dragAndDropClasses = null,
                          $beforeKanbanView = null,
                          $afterKanbanView = null,
                          $pollMillis = null,
                          $pollAction = null,
                          $dragAndDropEnabled = true,
                          $columnClickEnabled = true,
                          $eventClickEnabled = true,
                          $extras = [])
    {
        $initialYear = $initialYear ?? Carbon::today()->year;
        $initialMonth = $initialMonth ?? Carbon::today()->month;
        $initialDay = $initialDay ?? Carbon::today()->day;

        $this->startsAt = Carbon::createFromDate($initialYear, $initialMonth, $initialDay)->startOfWeek()->startOfDay();
        $this->endsAt = $this->startsAt->clone()->endOfWeek()->endOfDay();

        $this->setupViews($kanbanView, $columnView, $eventView, $beforeKanbanView, $afterKanbanView);

        $this->setupPoll($pollMillis, $pollAction);

        $this->dragAndDropEnabled = $dragAndDropEnabled;
        $this->dragAndDropClasses = $dragAndDropClasses ?? 'border border-blue-400 border-4';

        $this->columnClickEnabled = $columnClickEnabled;
        $this->eventClickEnabled = $eventClickEnabled;

        $this->afterMount($extras);
    }

    public function afterMount($extras = [])
    {
        //
    }

    public function setupViews($kanbanView = null,
                               $columnView = null,
                               $eventView = null,
                               $beforeKanbanView = null,
                               $afterKanbanView = null)
    {
        $this->kanbanView = $kanbanView ?? 'vendor.livewire-kanban.kanban';
        $this->columnView = $columnView ?? 'vendor.livewire-kanban.column';
        $this->eventView = $eventView ?? 'vendor.livewire-kanban.event';

        $this->beforeKanbanView = $beforeKanbanView ?? null;
        $this->afterKanbanView = $afterKanbanView ?? null;
    }

    public function setupPoll($pollMillis, $pollAction)
    {
        $this->pollMillis = $pollMillis;
        $this->pollAction = $pollAction;
    }

    public function goToPreviousMonth()
    {
        $this->startsAt->subMonthNoOverflow();
        $this->endsAt->subMonthNoOverflow();
    }

    public function goToNextMonth()
    {
        $this->startsAt->addMonthNoOverflow();
        $this->endsAt->addMonthNoOverflow();
    }

    public function goToCurrentMonth()
    {
        $this->startsAt = Carbon::today()->startOfMonth()->startOfDay();
        $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();
    }

    public function goToPreviousWeek()
    {
        $this->startsAt->subWeek();
        $this->endsAt->subWeek();
    }

    public function goToNextWeek()
    {
        $this->startsAt->addWeek();
        $this->endsAt->addWeek();
    }

    public function goToCurrentWeek()
    {
        $this->startsAt = Carbon::today()->startOfWeek()->startOfDay();
        $this->endsAt = $this->startsAt->clone()->endOfWeek()->endOfDay();
    }

    public function events() : Collection
    {
        return collect();
    }

    // public function getEventsForDay($day, Collection $events) : Collection
    // {
    //     return $events
    //         ->filter(function ($event) use ($day) {
    //             return Carbon::parse($event['date'])->isSameDay($day);
    //         });
    // }

    // public function onEventClick($eventId)
    // {
    //     //
    // }

    public function onEventDropped($eventId, $status)
    {
        //
    }

    public function getId()
    {
        if (!empty($this->__id)) {
            $id = $this->__id;
        } else if (!empty($this->id)) {
            $id = $this->id;
        } else {
            $id = 'livewire-kanban-' . uniqid();
        }
        return $id;
    }

    /**
     * @return Factory|View
     * @throws Exception
     */
    public function render()
    {
        $events = $this->events();

        return view($this->kanbanView)
            ->with([
                'componentId' => $this->getId(),
                'events' => $events
            ]);
    }
}
