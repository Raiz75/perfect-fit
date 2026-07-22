<form id="demographicForm" method="POST" action="{{ route('assessment.store') }}">
    @csrf
    <div class="personalDetail" id="demographicWizard">
        <div class="box" id="personalInfoBox">
            <span id="personalInfottl">Personal Details</span>
            <div class="ansDiv personalBox">
                <label>Name: <input type="text" name="name" value="{{ old('name', $phase1['name'] ?? '') }}" placeholder="Enter Name"></label>
                <label>Email: <input type="text" name="email" value="{{ old('email', $phase1['email'] ?? '') }}" placeholder="Enter Email"></label>
                <div class="contactInput">
                    <label>Contact No.:
                        <div>
                            <p>+63</p><input type="number" name="contact" value="{{ old('contact', $phase1['contact'] ?? '') }}" oninput="if(this.value.length>10)this.value=this.value.slice(0,10)" placeholder="Enter Contact Number">
                        </div>
                    </label>
                </div>
            </div>
            @error('name') <span class="error">{{ $message }}</span> @enderror
            @error('email') <span class="error">{{ $message }}</span> @enderror
            @error('contact') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="box" id="genderBox">
            <span id="genderBoxttl">Gender</span>
            <div class="ansDiv">
                <label><input type="radio" name="gender" value="1" {{ old('gender', $phase1['gender'] ?? '') == '1' ? 'checked' : '' }}> Male</label>
                <label><input type="radio" name="gender" value="2" {{ old('gender', $phase1['gender'] ?? '') == '2' ? 'checked' : '' }}> Female</label>
            </div>
            @error('gender') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="box" id="ageBox">
            <span id="ageBoxttl">Age</span>
            <div class="ansDiv">
                <label><input type="number" name="age" value="{{ old('age', $phase1['age'] ?? '') }}" min="1" max="100" placeholder="Enter Age"> Years</label>
            </div>
            @error('age') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="box" id="statusBox">
            <span id="statusBoxttl">Status</span>
            <div class="ansDiv">
                <label><input type="radio" name="status" value="1" {{ old('status', $phase1['status'] ?? '') == '1' ? 'checked' : '' }}> Single</label>
                <label><input type="radio" name="status" value="2" {{ old('status', $phase1['status'] ?? '') == '2' ? 'checked' : '' }}> Married</label>
            </div>
            @error('status') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="box" id="baptizedBox">
            <span id="baptizedBoxttl">Baptized</span>
            <div class="ansDiv">
                <label><input type="radio" name="baptized" value="1" {{ old('baptized', $phase1['baptized'] ?? '') == '1' ? 'checked' : '' }}> Yes</label>
                <label><input type="radio" name="baptized" value="2" {{ old('baptized', $phase1['baptized'] ?? '') == '2' ? 'checked' : '' }}> No</label>
            </div>
            @error('baptized') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="box" id="timeInFaithBox">
            <span id="timeInFaithBoxttl">Time In Faith</span>
            <div class="ansDiv">
                <label><input type="radio" name="timeInFaith" value="1" {{ old('timeInFaith', $phase1['timeInFaith'] ?? '') == '1' ? 'checked' : '' }}> 1+ week</label>
                <label><input type="radio" name="timeInFaith" value="2" {{ old('timeInFaith', $phase1['timeInFaith'] ?? '') == '2' ? 'checked' : '' }}> 6+ months</label>
                <label><input type="radio" name="timeInFaith" value="3" {{ old('timeInFaith', $phase1['timeInFaith'] ?? '') == '3' ? 'checked' : '' }}> 1+ year</label>
                <label><input type="radio" name="timeInFaith" value="4" {{ old('timeInFaith', $phase1['timeInFaith'] ?? '') == '4' ? 'checked' : '' }}> 2+ years</label>
            </div>
            @error('timeInFaith') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div id="errorContainer" class="errorContainer"></div>

        @if(session('success'))
            <span class="success">{{ session('success') }}</span>
        @endif
    </div>
</form>
