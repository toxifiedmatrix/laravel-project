<?php

namespace App\Http\Livewire\Backend\UserManagement;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Invitation;
use App\Mail\InvitationCreated;


class InvitationController extends Component
{
    use WithPagination;

    public $showEditModal = false;
    public Invitation $invitation;

    protected $rules = [
        'invitation.email' => 'required | email | max:255 | unique:users,email',
    ];

    public function render()
    {
        $invitations = Invitation::where('registered_at', null)->paginate(5);
        return view('livewire.backend.user-management.invitation-controller', ['invitations' => $invitations]);
    }

    public function mount()
    {
        $this->invitation = new Invitation();
    }

    public function resetInvitationForm()
    {
        return Invitation::make();
    }

    public function create()
    {
        $this->resetValidation();
        $this->invitation = $this->resetInvitationForm();
        $this->showEditModal = true;
    }

    public function process()
    {
        $this->validate();
        $this->invitation->generateInvitationToken();
        $this->invitation->save();
        $this->showEditModal = false;

        Mail::to($this->invitation->email)->send(new InvitationCreated($this->invitation));
    }
}
