<?php

use Livewire\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $contact = '';
    public string $gender = '';
    public string $age = '';
    public string $status = '';
    public string $baptized = '';
    public string $timeInFaith = '';

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact' => 'required|string|max:10',
            'gender' => 'required|in:1,2',
            'age' => 'required|integer|min:1|max:100',
            'status' => 'required|in:1,2',
            'baptized' => 'required|in:1,2',
            'timeInFaith' => 'required|in:1,2,3,4',
        ]);

        $this->dispatch('demographics-completed', [
            'name' => $this->name,
            'email' => $this->email,
            'contact' => $this->contact,
            'gender' => $this->gender,
            'age' => $this->age,
            'status' => $this->status,
            'baptized' => $this->baptized,
            'timeInFaith' => $this->timeInFaith,
        ]);
    }
};
?>

<div class="personalDetail" id="demographicWizard">
    <div class="box" id="personalInfoBox">
        <span id="personalInfottl">Personal Details</span>
        <div class="ansDiv personalBox">
            <label>Name: <input type="text" wire:model="name" placeholder="Enter Name"></label>
            <label>Email: <input type="text" wire:model="email" placeholder="Enter Email"></label>
            <div class="contactInput">
                <label>Contact No.:
                    <div>
                        <p>+63</p><input type="number" wire:model="contact" oninput="if(this.value.length>10)this.value=this.value.slice(0,10)" placeholder="Enter Contact Number">
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div class="box" id="genderBox">
        <span id="genderBoxttl">Gender</span>
        <div class="ansDiv">
            <label><input type="radio" wire:model="gender" value="1"> Male</label>
            <label><input type="radio" wire:model="gender" value="2"> Female</label>
        </div>
    </div>

    <div class="box" id="ageBox">
        <span id="ageBoxttl">Age</span>
        <div class="ansDiv">
            <label><input type="number" wire:model="age" min="1" max="100" placeholder="Enter Age"> Years</label>
        </div>
    </div>

    <div class="box" id="statusBox">
        <span id="statusBoxttl">Status</span>
        <div class="ansDiv">
            <label><input type="radio" wire:model="status" value="1"> Single</label>
            <label><input type="radio" wire:model="status" value="2"> Married</label>
        </div>
    </div>

    <div class="box" id="baptizedBox">
        <span id="baptizedBoxttl">Baptized</span>
        <div class="ansDiv">
            <label><input type="radio" wire:model="baptized" value="1"> Yes</label>
            <label><input type="radio" wire:model="baptized" value="2"> No</label>
        </div>
    </div>

    <div class="box" id="timeInFaithBox">
        <span id="timeInFaithBoxttl">Time In Faith</span>
        <div class="ansDiv">
            <label><input type="radio" wire:model="timeInFaith" value="1"> 1+ week</label>
            <label><input type="radio" wire:model="timeInFaith" value="2"> 6+ months</label>
            <label><input type="radio" wire:model="timeInFaith" value="3"> 1+ year</label>
            <label><input type="radio" wire:model="timeInFaith" value="4"> 2+ years</label>
        </div>
    </div>

    @error('name') <span class="error">{{ $message }}</span> @enderror
    @error('email') <span class="error">{{ $message }}</span> @enderror
    @error('contact') <span class="error">{{ $message }}</span> @enderror
    @error('gender') <span class="error">{{ $message }}</span> @enderror
    @error('age') <span class="error">{{ $message }}</span> @enderror
    @error('status') <span class="error">{{ $message }}</span> @enderror
    @error('baptized') <span class="error">{{ $message }}</span> @enderror
    @error('timeInFaith') <span class="error">{{ $message }}</span> @enderror
</div>

