<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-5xl px-6 lg:px-8">
            <div class="flex-col space-y-4">

                <div class="flex justify-between">
                    <div class="w-2/4 flex space-x-4">
                        <x-input.text wire:model="search" placeholder="Search Users..." />
                        <x-button.link wire:click="toggleShowFilters">@if ($showFilters) Hide @endif Advanced Search</x-button.link>
                    </div>
                    <div class="space-x-2">
                        <x-dropdown label="Actions">
                            <x-dropdown.item wire:click="exportSelected" class="flex items-center space-x-2" type="button">
                                <x-icon.download class="text-cool-gray-400" /><span>Export</span>
                            </x-dropdown.item>
                            <x-dropdown.item wire:click="deleteSelected" class="flex items-center space-x-2" type="button">
                                <x-icon.trash class="text-cool-gray-400" /><span>Delete</span>
                            </x-dropdown.item>
                        </x-dropdown>
                        <x-button.primary wire:click="create">Create</x-button.primary>
                    </div>
                </div>

                <!-- Advanced Search -->
                <div>
                    @if ($showFilters)
                    <div class="bg-cool-gray-200 p-6 rounded shadow-inner flex relative">
                        <div class="w-1/2 pr-2 space-y-4">
                            <x-input.group inline for="filter-email" label="Email">
                                <x-input.text wire:model.lazy="filters.email" id="filter-email" placeholder="Enter Email"/>
                            </x-input.group>
                        </div>
                        
                        <div class="w-1/2 pl-2 space-y-4">
                            <x-input.group inline for="filter-role" label="Role">
                                <x-input.select wire:model.lazy="filters.role" id="filter-role" placeholder="Select Role">
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </x-input.select>
                            </x-input.group>
                        </div>
                    </div>
                    @endif
                </div>

                <x-table>
                    <x-slot name="head">
                        <x-table.heading class="pr-0 w-8">
                            <x-input.checkbox />
                        </x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('id')" :direction="$sortField === 'id' ? $sortDirection : null">ID</x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">Name</x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('email')" :direction="$sortField === 'email' ? $sortDirection : null">Email</x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('role')" :direction="$sortField === 'role' ? $sortDirection : null">Role</x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('created_at')" :direction="$sortField === 'created_at' ? $sortDirection : null">Creation</x-table.heading>
                        <x-table.heading></x-table.heading>
                    </x-slot>

                    <x-slot name="body">
                        @forelse($users as $key => $user)
                            <x-table.row wire:loading.class.delay="opacity-50">
                                <x-table.cell wire:key="row-{{ $user->id }}" class="pr-0">
                                    <x-input.checkbox wire:model="selected" value="{{ $user->id }}" />
                                </x-table.cell>
                                <x-table.cell>{{ $users->firstitem() + $key }}</x-table.cell>
                                <x-table.cell>{{ $user->name }}</x-table.cell>
                                <x-table.cell>{{ $user->email }}</x-table.cell> 
                                <x-table.cell>
                                    @foreach($user->roles as $role)
                                        {{ $role->name }}
                                    @endforeach
                                </x-table.cell>
                                <x-table.cell>{{ $user->date_for_humans }}</x-table.cell>
                                <x-table.cell>
                                    <x-button.link wire:click="edit({{ $user->id }})">Edit</x-button.link>
                                </x-table.cell>
                            </x-table.row>
                        @empty
                            <x-table.row>
                                <x-table.cell colspan="7">
                                    <div class="flex justify-center items-center">
                                        <span class="font-medium py-8 text-cool-gray-400 text-xl">No Results Found</span>
                                    </div>
                                </x-table.cell>
                            </x-table.row>
                        @endforelse
                    </x-slot>
                </x-table>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="showEditModal">
            <x-slot name="title">Edit User</x-slot>

            <x-slot name="content">
                <x-input.group for="name" label="Name" :error="$errors->first('user.name')">
                    <x-input.text wire:model="user.name" id="name" placeholder="Enter Name" />
                </x-input.group>

                <x-input.group for="email" label="Email" :error="$errors->first('user.email')">
                    <x-input.text wire:model="user.email" id="email" placeholder="Enter Email" />
                </x-input.group>

                <x-input.group for="role" label="Role" :error="$errors->first('role')">
                    <x-input.select wire:model="role" id="role">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>
                <x-button.primary wire:click.prevent="save">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>

</div>