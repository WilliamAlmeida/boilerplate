<div>
    <x-header title="Components Mary UI" subtitle="Use this list to test Mary UI components" separator />

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 bg-base-100 p-2 gap-2">
        @php
            $users = collect([
                [
                    'id' => 1,
                    'name' => 'John Doe',
                    'city' => ['name' => 'New York'],
                    'avatar' => 'https://picsum.photos/200?=1',
                    'other_avatar' => 'https://picsum.photos/200?=2',
                ],
                [
                    'id' => 2,
                    'name' => 'Jane Doe',
                    'city' => ['name' => 'Los Angeles'],
                    'avatar' => 'https://picsum.photos/200?=3',
                    'other_avatar' => 'https://picsum.photos/200?=4',
                ],
                [
                    'id' => 3,
                    'name' => 'John Smith',
                    'city' => ['name' => 'Chicago'],
                    'avatar' => 'https://picsum.photos/200?=5',
                    'other_avatar' => 'https://picsum.photos/200?=6',
                ]
            ]);
        @endphp

        <div>
            @foreach($users as $user)
                <x-list-item :item="$user" link="/docs/installation" />
            @endforeach
        </div>

        @php
            $user1 = $users->random();
            $user2 = $users->random();
        @endphp
        
        <div>
            {{-- Notice `city.name`. It supports nested properties --}}
            <x-list-item :item="$user1" value="other_name" sub-value="city.name" avatar="other_avatar" />
        
            {{-- All slots --}}
            <x-list-item :item="$user2" no-separator no-hover>
                <x-slot:avatar>
                    <x-badge value="top user" class="badge-primary" />
                </x-slot:avatar>
                <x-slot:value>
                    Custom value
                </x-slot:value>
                <x-slot:sub-value>
                    Custom sub-value
                </x-slot:sub-value>
                <x-slot:actions>
                    <x-button icon="o-trash" class="text-red-500" wire:click="delete(1)" spinner />
                </x-slot:actions>
            </x-list-item>
        </div>

        @php 
            $headers = [
                ['key' => 'id', 'label' => '#'],
                ['key' => 'name', 'label' => 'Nice Name'],
                ['key' => 'city.name', 'label' => 'City'] # <---- nested attributes
            ];
        @endphp
        
        {{-- You can use any `$wire.METHOD` on `@row-click` --}}
        <x-table :headers="$headers" :rows="$users" striped @row-click="alert($event.detail.name)" />

        <x-menu class="border border-dashed w-64">
            <x-menu-item title="Hello" />
            <x-menu-item title="There" />
        
            {{-- Simple separator --}}
            <x-menu-separator />
        
            {{-- Submenu --}}
            <x-menu-sub title="Settings" icon="o-cog-6-tooth">
                <x-menu-item title="Wifi" icon="o-wifi" />
                <x-menu-item title="Archives" icon="o-archive-box" />
            </x-menu-sub>
        
            {{-- Separator with title and icon --}}
            <x-menu-separator title="Magic" icon="o-sparkles" />
        
            <x-menu-item title="Wifi" icon="o-wifi" />
        </x-menu>

        <x-dropdown label="Settings" class="btn-outline">
            {{-- By default any click closes dropdown --}}
            <x-menu-item title="Close after click" />
        
            <x-menu-separator />
        
            {{-- Use `@click.STOP` to stop event propagation --}}
            <x-menu-item title="Keep open after click" @click.stop="alert('Keep open')" />
        
            {{-- Or `wire:click.stop`  --}}
            <x-menu-item title="Call wire:click" wire:click.stop="delete" />
        
            <x-menu-separator />
        
            <x-menu-item @click.stop="">
                <x-checkbox label="Activate" />
            </x-menu-item>
        
            <x-menu-item @click.stop="">
                <x-toggle label="Sleep mode" right />
            </x-menu-item>
        </x-dropdown>

        <div>
            Press <x-kbd>F</x-kbd> to pay respects.
        </div>

        <div x-data="{ pin2: $wire.entangle('pin2') }">
            <x-pin wire:model="pin2" size="4" />

            <template x-if="pin2.length == 4">
                <x-alert icon="o-check-circle" class="mt-5">
                    You have typed : <span x-text="pin2"></span>
                </x-alert>
            </template>
        </div>

        <x-drawer
            wire:model="showDrawer3"
            title="Hello"
            subtitle="Livewire"
            separator
            with-close-button
            close-on-escape
            class="w-11/12 lg:w-1/3"
        >
            <div>Hey!</div>
        
            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.showDrawer3 = false" />
                <x-button label="Confirm" class="btn-primary" icon="o-check" />
            </x-slot:actions>
        </x-drawer>

        <x-modal wire:model="myModal2" title="Hello" subtitle="Livewire example" separator>
            <div>Hey!</div>
        
            <x-slot:actions>
                <x-button label="Cancelar" @click="$wire.myModal2 = false" />
                <x-button label="Confirm" class="btn-primary" />
            </x-slot:actions>
        </x-modal>

        <div class="col-span-full flex flex-wrap gap-3">
            <x-button label="Open Drawer" @click="$wire.showDrawer3 = true" />
            <x-button label="Open Modal" @click="$wire.myModal2 = true" />
            <x-button label="Default" class="btn-success" wire:click="triggerToast('success')" spinner />
            
            <x-button label="Quick" class="btn-error" wire:click="triggerToast('error')" spinner />
            
            <x-button label="Save and redirect" class="btn-warning" wire:click="triggerToast('warning_redirect')" spinner />

            <x-button label="Like" wire:click="triggerToast('custom_warning')" icon="o-heart" spinner />
        </div>

        <div class="col-span-full grid md:grid-cols-3 gap-3">
            <x-alert title="You have 10 messages" icon="o-exclamation-triangle" />
        
            <x-alert title="Hey!" description="Ho!" icon="o-home" class="alert-warning" />
            
            <x-alert icon="o-exclamation-triangle" class="alert-success">
                I am using the <strong>default slot.</strong>
            </x-alert>
            
            <x-alert title="With actions" description="Hi" icon="o-envelope" class="alert-info">
                <x-slot:actions>
                    <x-button label="See" />
                </x-slot:actions>
            </x-alert>
            
            <x-alert title="I have a shadow" icon="o-exclamation-triangle" shadow />
            
            <x-alert title="Dismissible" description="Click the close icon" icon="o-exclamation-triangle" dismissible />
        </div>

        <div class="col-span-full flex gap-3 items-center flex-wrap p-2">
            <x-avatar :image="$user['avatar']" class="!w-24">
                <x-slot:title class="text-3xl pl-2">
                    {{ $user['name'] }}
                </x-slot:title>
             
                <x-slot:subtitle class="text-gray-500 flex flex-col gap-1 mt-2 pl-2">
                    <x-icon name="o-paper-airplane" label="12 posts" />
                    <x-icon name="o-chat-bubble-left" label="45 comments" />
                </x-slot:subtitle>
            </x-avatar>
    
            <x-password label="Toggle" hint="It toggles visibility" wire:model="password" clearable />
        </div>

        <div class="col-span-full flex gap-3 items-center flex-wrap p-2">
            {{--  COLOR AND STYLE --}}
            <x-button label="Hi!" class="btn-outline" />
            <x-button label="How" class="btn-warning" />
            <x-button label="Are" class="btn-success" />
            <x-button label="You?" class="btn-error btn-sm" />
            
            {{-- SLOT--}}
            <x-button class="btn-primary">
                With default slot 😃
            </x-button>
            
            {{-- CIRCLE --}}
            <x-button icon="o-user" class="btn-circle" />
            <x-button icon="o-user" class="btn-circle btn-outline" />
            
            {{-- SQUARE --}}
            <x-button icon="o-user" class="btn-circle btn-ghost" />
            <x-button icon="o-user" class="btn-square" />
        </div>

        <div class="col-span-full flex gap-3 items-center flex-wrap p-2">
            <x-badge value="Hello" />
            
            <x-badge value="Hello" class="badge-primary" />
            
            <x-badge value="Hello" class="badge-warning" />
            
            <x-badge value="Hello" class="bg-purple-500/10 " />

            <x-button>
                Inbox
                <x-badge value="+99" class="badge-neutral" />
            </x-button>
             
            <x-button class="indicator">
                Inbox
                <x-badge value="7" class="badge-secondary indicator-item" />
            </x-button>
             
            <x-button icon="o-bell" class="btn-circle relative">
                <x-badge value="2" class="badge-error absolute -right-2 -top-2" />
            </x-button>
        </div>
    </div>

    <div class="col-span-full grid sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 bg-base-200 p-2">
        <x-card title="Your stats" subtitle="Our findings about you" shadow separator>
            I have title, subtitle, separator and shadow.
            <br/>
            <br/>
            <x-button label="Save" wire:click="save" />
        </x-card>
         
        <x-card title="Nice things">
            I am using slots here.
         
            <x-slot:figure>
                <img src="https://picsum.photos/500/200" />
            </x-slot:figure>
            <x-slot:menu>
                <x-button icon="o-share" class="btn-circle btn-sm" />
                <x-icon name="o-heart" class="cursor-pointer" />
            </x-slot:menu>
            <x-slot:actions>
                <x-button label="Ok" class="btn-primary" />
            </x-slot:actions>
        </x-card>

        <div class="p-4 bg-base-100">
            <x-steps wire:model="step" class="border my-5 p-5">
                <x-step step="1" text="Register">
                    Register step
                </x-step>
                <x-step step="2" text="Payment">
                    Payment step
                </x-step>
                <x-step step="3" text="Receive Product" class="bg-orange-500/20">
                    Receive Product
                </x-step>
            </x-steps>
        </div>

        <div class="p-4 bg-base-100">
            <x-timeline-item title="Register" first />

            <x-timeline-item title="Payment" subtitle="10/23/2023" />
    
            <x-timeline-item title="Analysis" subtitle="10/23/2023" description="Just checking" />
    
            {{-- Notice `pending` --}}
            <x-timeline-item title="Prepare" pending description="Prepare to ship" />
    
            {{-- Cut bottom edge with `last` --}}
            <x-timeline-item title="Shipment" pending last description="It is shiped :)" />
        </div>
    </div>

    <div class="col-span-full flex flex-col md:flex-row gap-3 bg-base-100 p-2">
        @php
            $slides = [
                [
                    'image' => 'https://mary-ui.com/photos/photo-1559703248-dcaaec9fab78.jpg',
                    'title' => 'Front end developers',
                    'description' => 'We love last week frameworks.',
                    'url' => route('home'),
                    'urlText' => 'Get started',
                ],
                [
                    'image' => 'https://mary-ui.com/photos/photo-1565098772267-60af42b81ef2.jpg',
                    'title' => 'Full stack developers',
                    'description' => 'Where burnout is just a fancy term for Tuesday.',
                ],
                [
                    'image' => 'https://mary-ui.com/photos/photo-1494253109108-2e30c049369b.jpg',
                    'url' => route('home'),
                    'urlText' => 'Let`s go!',
                ],
                [
                    'image' => 'https://mary-ui.com/photos/photo-1572635148818-ef6fd45eb394.jpg',
                    'url' => route('home'),
                ],
            ];
        @endphp
 
        <x-carousel :slides="$slides" />

        <x-accordion>
            <x-collapse name="group1">
                <x-slot:heading>Group 1</x-slot:heading>
                <x-slot:content>Hello 1</x-slot:content>
            </x-collapse>
            <x-collapse name="group2">
                <x-slot:heading>Group 2</x-slot:heading>
                <x-slot:content>Hello 2</x-slot:content>
            </x-collapse>
            <x-collapse name="group3">
                <x-slot:heading>Group 3</x-slot:heading>
                <x-slot:content>Hello 3</x-slot:content>
            </x-collapse>
        </x-accordion>
    </div>

    <div class="col-span-full flex flex-col md:flex-row gap-3 bg-base-100 p-2">
        <x-tabs wire:model="selectedTab">
            <x-tab name="users-tab" label="Users" icon="o-users">
                <div>Users</div>
            </x-tab>
            <x-tab name="tricks-tab" label="Tricks" icon="o-sparkles">
                <div>Tricks</div>
            </x-tab>
            <x-tab name="musics-tab" label="Musics" icon="o-musical-note">
                <div>Musics</div>
            </x-tab>
        </x-tabs>

        <x-tabs wire:model="selectedTab">
            <x-tab name="users-tab">
                <x-slot:label>  
                    Users
                    <x-badge value="3" class="badge-primary" />
                </x-slot:label>
         
                <div>Users</div>
            </x-tab>
            <x-tab name="tricks-tab" label="Tricks">
                <div>Tricks</div>
            </x-tab>
            <x-tab name="musics-tab" label="Musics">
                <div>Musics</div>
            </x-tab>
        </x-tabs>

        <x-tabs
            wire:model="selectedTab"
            active-class="bg-primary rounded text-white"
            label-class="font-semibold"
            label-div-class="bg-primary/5 p-2 rounded"
        >
            <x-tab name="users-tab" label="Users">
                <div>All</div>
            </x-tab>
            <x-tab name="tricks-tab" label="Tricks">
                <div>Tricks</div>
            </x-tab>
            <x-tab name="musics-tab" label="Musics">
                <div>Musics</div>
            </x-tab>
        </x-tabs>
    </div>
</div>