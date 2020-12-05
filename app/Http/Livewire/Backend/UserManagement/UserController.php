<?php

namespace App\Http\Livewire\Backend\UserManagement;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\User;
use App\Models\Role;

class UserController extends Component
{
    use WithPagination;

    public $sortField;
    public $sortDirection = 'asc';
    public $showEditModal = false;
    public $showFilters = false;
    public $selected = [];
    public $filters = [
        'search' => "",
        'email' => null,
        'role' => ''
    ];
    public $role;
    public User $user;

    protected $rules = [
        'user.name' => 'required | string | max:255',
        'user.email' => 'required | string | email | max:255 | unique:users,email',
        'role' => 'required'
    ];

    protected $queryString = ['sortField', 'sortDirection'];

    public function mount()
    {
        $this->user = $this->resetUserForm();
    }

    public function toggleShowFilters()
    {
        $this->showFilters = ! $this->showFilters;
    }

    public function updatedFilters() 
    { 
        $this->resetPage();
    }

    public function resetFilters() 
    { 
        $this->reset('filters'); 
    }

    public function resetUserForm()
    {
        return User::make();
    }

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field
            ? $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortField;
    }

    public function create()
    {
        $this->resetValidation();
        $this->user = $this->resetUserForm();
        $this->role = '3';
        $this->showEditModal = true;
    }

    public function edit(User $user)
    {
        $this->resetValidation();
        $this->user = $user;
        $this->role = $user->roles->pluck('id');
        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate();
        $this->user->roles()->sync($this->role);
        $this->user->save();
        $this->showEditModal = false;
    }

    public function deleteSelected()
    {
        $user = User::whereKey($this->selected)->first();
        $user->roles()->detach();
        $user->delete();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->filters['email'], fn($query, $email) => $query->where('email', 'like', '%'.$email.'%'))
            ->when($this->filters['role'], fn($query, $role) => $query->whereHas('roles', fn ($query) => $query->where('id', $role)))
            ->when($this->filters['search'], fn($query, $search) => $query->where('name', 'like', '%'.$search.'%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(5);
        $roles = Role::all();
        return view('livewire.backend.user-management.user-controller', ['users' => $users, 'roles' => $roles]);
    }
}
