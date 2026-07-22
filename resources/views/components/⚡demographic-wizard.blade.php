<?php

use Livewire\Component;

new class extends Component
{
    public array $data;

    public string $name = '';
    public string $email = '';
    public string $contactNo = '';
    public string $gender = '';
    public string $age = '';
    public string $maritalStatus = '';
    public string $baptized = '';
    public string $timeInFaith = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contactNo' => 'required|string|max:10',
            'gender' => 'required|integer|in:1,2',
            'age' => 'required|integer|min:1|max:120',
            'maritalStatus' => 'required|integer|in:1,2',
            'baptized' => 'required|integer|in:1,2',
            'timeInFaith' => 'required|integer|in:1,2,3,4',
        ];
    }

    public function submit()
    {
        $this->validate();

        session()->put('assessment.phase1', [
            'name' => $this->name,
            'email' => $this->email,
            'contact' => $this->contactNo,
            'gender' => (int) $this->gender,
            'age' => (int) $this->age,
            'marital_status' => (int) $this->maritalStatus,
            'baptized' => (int) $this->baptized,
            'time_in_faith' => (int) $this->timeInFaith,
        ]);
        session()->put('assessment.step', 2);

        $this->dispatch('stepCompleted', step: 2)->to('assessment-wizard');
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            <div class="personalDetail move-up" id="personalForm" wire:key="demographic-form">
                <h2 translate="ph1">DEMOGRAPHICS</h2>
                <div class="box move-up" id="personalInfoBox">
                    <span id="personalInfottl">Personal Information</span><br>
                    <div class="ansDiv personalBox">
                        <label id="namettl">
                            <span>Name:</span>
                            <input type="text" wire:model.blur="name" placeholder="Enter Name">
                            @error('name') <span class="error" style="color:red;font-size:0.8rem;">{{ $message }}</span> @enderror
                        </label>
                        <label id="emailttl">
                            <span>Email:</span>
                            <input type="text" wire:model.blur="email" placeholder="Enter Email">
                            @error('email') <span class="error" style="color:red;font-size:0.8rem;">{{ $message }}</span> @enderror
                        </label>
                        <div class="contactInput">
                            <label id="contactttl">
                                <span>Contact No.:</span>
                                <div>
                                    <p>+63</p>
                                    <input type="text" wire:model.blur="contactNo" maxlength="10" placeholder="Enter Contact Number">
                                </div>
                                @error('contactNo') <span class="error" style="color:red;font-size:0.8rem;">{{ $message }}</span> @enderror
                            </label>
                        </div>
                    </div>
                </div>
                <div class="box move-up" id="genderBox">
                    <span id="genderBoxttl">Gender</span><br>
                    <div class="ansDiv">
                        <label><input type="radio" wire:model="gender" value="1"> Male</label>
                        <label><input type="radio" wire:model="gender" value="2"> Female</label>
                    </div>
                    @error('gender') <span class="error" style="color:red;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div class="box move-up" id="ageBox">
                    <span id="ageBoxttl">Age</span><br>
                    <div class="ansDiv">
                        <label>
                            <input type="number" wire:model.blur="age" min="1" max="120" placeholder="Enter Age">
                            <span>Years</span>
                        </label>
                    </div>
                    @error('age') <span class="error" style="color:red;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div class="box move-up" id="statusBox">
                    <span id="statusBoxttl">Status</span><br>
                    <div class="ansDiv">
                        <label><input type="radio" wire:model="maritalStatus" value="1"> Single</label>
                        <label><input type="radio" wire:model="maritalStatus" value="2"> Married</label>
                    </div>
                    @error('maritalStatus') <span class="error" style="color:red;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div class="box move-up" id="baptizedBox">
                    <span id="baptizedBoxttl">Baptized</span><br>
                    <div class="ansDiv">
                        <label><input type="radio" wire:model="baptized" value="1"> Yes</label>
                        <label><input type="radio" wire:model="baptized" value="2"> No</label>
                    </div>
                    @error('baptized') <span class="error" style="color:red;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div class="box move-up" id="timeInFaithBox">
                    <span id="timeInFaithBoxttl">Time In Faith</span><br>
                    <div class="ansDiv">
                        <label><input type="radio" wire:model="timeInFaith" value="1"> 1+ week</label>
                        <label><input type="radio" wire:model="timeInFaith" value="2"> 6+ months</label>
                        <label><input type="radio" wire:model="timeInFaith" value="3"> 1+ year</label>
                        <label><input type="radio" wire:model="timeInFaith" value="4"> 2+ years</label>
                    </div>
                    @error('timeInFaith') <span class="error" style="color:red;font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div class="ansDiv submitDiv">
                    <button type="button" wire:click="submit" class="nextBtn" id="step1" translate="b1">NEXT PHASE</button>
                </div>
            </div>
        </div>
        HTML;
    }
};
?>

<div>
</div>
