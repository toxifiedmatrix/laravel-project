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
                        <x-input.text wire:model="search" placeholder="Search Invitations..." />
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

                <x-table>
                    <x-slot name="head">
                        <x-table.heading sortable wire:click="sortBy('id')">ID</x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('email')">Email</x-table.heading>
                        <x-table.heading sortable wire:click="sortBy('invitation_link')">Invitation Link</x-table.heading>
                    </x-slot>

                    <x-slot name="body">
                        @forelse($invitations as $key => $invitation)
                            <x-table.row wire:loading.class.delay="opacity-50">
                                <x-table.cell>{{ $invitations->firstitem() + $key }}</x-table.cell>
                                <x-table.cell>{{ $invitation->email }}</x-table.cell>
                                <x-table.cell>{{ $invitation->getLink() }}</x-table.cell>
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
                    {{ $invitations->links() }}
                </div>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="process">
        <x-modal.dialog wire:model.defer="showEditModal">
            <x-slot name="title">Edit User</x-slot>

            <x-slot name="content">
                <x-input.group for="email" label="Email" :error="$errors->first('invitation.email')">
                    <x-input.text wire:model="invitation.email" id="email" placeholder="Enter Email" />
                </x-input.group>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.secondary>
                <x-button.primary wire:click.prevent="process">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>

</div>