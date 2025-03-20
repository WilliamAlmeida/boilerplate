<?php

namespace App\View\Components\Mary;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Markdown extends Component
{
    public string $uuid;

    public string $uploadUrl;

    public function __construct(
        public ?string $label = null,
        public ?string $disk = 'public',
        public ?string $folder = 'markdown',
        public ?array $config = [],
        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-red-500 label-text-alt p-1',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,
    ) {
        $this->uuid = "mary" . md5(serialize($this));
        $this->uploadUrl = route('mary.upload', absolute: false);
    }

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    public function errorFieldName(): ?string
    {
        return $this->errorField ?? $this->modelName();
    }

    public function setup(): string
    {
        $setup = array_merge([
            'spellChecker' => false,
            'autoSave' => false,
            'uploadImage' => true,
            'imageAccept' => 'image/png, image/jpeg, image/gif, image/avif',
            'toolbar' => [
                'heading', 'bold', 'italic', 'strikethrough', '|',
                'code', 'quote', 'unordered-list', 'ordered-list', 'horizontal-rule', '|',
                'link', 'upload-image', 'table', '|',
                'preview', 'side-by-side'
            ],
        ], $this->config);

        // Table default CSS class `.table` breaks the layout.
        // Here is a workaround
        $table = "{ 'title' : 'Table', 'name' : 'myTable', 'action' : EasyMDE.drawTable, 'className' : 'fa fa-table' }";

        return str(json_encode($setup))
            ->replace("\"", "'")
            ->trim('{}')
            ->replace("'table'", $table)
            ->toString();
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
            <div>
                <!-- STANDARD LABEL -->
                @if($label)
                    <label for="{{ $uuid }}" class="pt-0 label label-text font-semibold">
                        <span>
                            {{ $label }}

                            @if($attributes->get('required'))
                                <span class="text-error">*</span>
                            @endif
                        </span>
                    </label>
                @endif

                <!-- EDITOR -->
                <div
                    x-data="
                        {
                            editor: null,
                            value: @entangle($attributes->wire('model')),
                            uploadUrl: '{{ $uploadUrl }}?disk={{ $disk }}&folder={{ $folder }}&_token={{ csrf_token() }}',
                            uploading: false,
                            init() {
                                this.initEditor();

                                // Handles a case where people try to change contents on the fly from Livewire methods
                                this.$watch('value', (newValue) => {
                                    if (newValue !== this.editor.value()) {
                                        this.value = newValue || '';
                                        this.destroyEditor();
                                        this.initEditor();
                                    }
                                });
                            },
                            destroyEditor() {
                                this.editor.toTextArea();
                                this.editor = null;
                            },
                            initEditor() {
                                this.editor = new EasyMDE({
                                    spellChecker: false,
                                    autoSave: false,
                                    uploadImage: false,
                                    imageAccept: 'image/png, image/jpeg, image/gif, image/avif',
                                    toolbar: [
                                        'heading', 'bold', 'italic', 'strikethrough', '|',
                                        'code', 'quote', 'unordered-list', 'ordered-list', 'horizontal-rule', '|',
                                        'link', { 'title': 'Table', 'name': 'myTable', 'action': EasyMDE.drawTable, 'className': 'fa fa-table' },
                                        '|', 'preview', 'side-by-side', 'fullscreen',
                                        {'name': 'variables', 'action': (editor) => { this.insertAtCursor('[{company.name}]') }, 'className': 'fa fa-code', 'title': 'Wrap as Variable'}
                                    ],
                                    syncSideBySidePreviewScroll: true,
                                    element: $refs.markdown{{ $uuid }},
                                    initialValue: this.value ?? '',
                                });

                                this.editor.codemirror.on('change', () => this.value = this.editor.value());
                            },
                            insertAtCursor(value) {
                                const codemirror = this.editor.codemirror;
                                const doc = codemirror.getDoc();
                                const cursor = doc.getCursor();
                                doc.replaceRange(value, cursor);
                            },
                            replaceAtSelection(value) {
                                const codemirror = this.editor.codemirror;
                                const doc = codemirror.getDoc();
                                const selection = doc.getSelection();
                                doc.replaceSelection(value);
                            },
                            insertAtSelectionStartAndEnd(startValue, endValue) {
                                const codemirror = this.editor.codemirror;
                                const doc = codemirror.getDoc();
                                const selection = doc.getSelection();
                                const newValue = `${startValue}${selection}${endValue}`;
                                doc.replaceSelection(newValue);
                            },
                        }"
                    wire:ignore
                    @inserteditor.window="insertAtCursor($event.detail)"
                    @replaceeditor.window="replaceAtSelection($event.detail)"
                    @inserteditorstartandend.window="insertAtSelectionStartAndEnd($event.detail.start, $event.detail.end)"
                    x-on:livewire:navigating.window="destroyEditor()"
                >
                    <div class="relative disabled" :class="uploading && 'pointer-events-none opacity-50'">
                        <textarea id="{{ $uuid }}" x-ref="markdown{{ $uuid }}"></textarea>

                        <div class="absolute top-1/2 start-1/2 !opacity-100 text-center hidden" :class="uploading && '!block'">
                            <div>Uploading</div>
                            <div class="loading loading-dots"></div>
                        </div>
                    </div>

                </div>

                <!-- ERROR -->
                @if(!$omitError && $errors->has($errorFieldName()))
                    @foreach($errors->get($errorFieldName()) as $message)
                        @foreach(Arr::wrap($message) as $line)
                            <div class="{{ $errorClass }}" x-classes="text-red-500 label-text-alt p-1">{{ $line }}</div>
                            @break($firstErrorOnly)
                        @endforeach
                        @break($firstErrorOnly)
                    @endforeach
                @endif

            </div>
            HTML;
    }
}
