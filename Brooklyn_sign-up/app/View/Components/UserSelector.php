<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\User;

class UserSelector extends Component
{
    public $type;
    public $selectedUserId;
    public $users;

    /**
     * Create a new component instance.
     *
     * @param int $type
     * @param int|null $selectedUserId
     */
    public function __construct($type = 2, $selectedUserId = null)
    {
        $this->type = $type;
        $this->selectedUserId = $selectedUserId;
        // Load users of the given type
        $this->users = User::where('type', $type)->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.user-selector');
    }
}
