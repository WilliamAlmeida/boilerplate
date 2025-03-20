<div>
    <div>
        @includeIf($beforeKanbanView)
    </div>

    <div class="grid sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3" x-data="" x-init="Sortable.create($el, {
        animation: 150,
        handle: '.cursor-move',
        onSort({ to }) {
            const groupsId = Array.from(to.children).map((column) => column.getAttribute('group-id'));
        }
    })">
        @foreach ($appointment_status as $status)
            @php($values = $events->where('status.value', $status['id']))

            @include($this->columnView, ['status' => $status, 'colors' => $colors, 'values' => $values])
        @endforeach
    </div>

    <div>
        @includeIf($afterKanbanView)
    </div>
</div>