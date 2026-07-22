<form method="POST" action="{{ route('assessment.phase1.store') }}" id="demographicForm">
    @csrf

    @if($errors->any())
        <div class="alert-box">
            @foreach($errors->all() as $err)
                <p>{{ $err }}</p>
            @endforeach
        </div>
    @endif

    <div class="assessment-input-group">
        <span class="input-icon"><i class="ti ti-user"></i></span>
        <input type="text" name="name" placeholder="Full Name" value="{{ old('name', $phase1['name'] ?? '') }}" required>
    </div>
    @error('name') <span class="assessment-error">{{ $message }}</span> @enderror

    <div class="assessment-input-group">
        <span class="input-icon"><i class="ti ti-mail"></i></span>
        <input type="email" name="email" placeholder="Email Address" value="{{ old('email', $phase1['email'] ?? '') }}" required>
    </div>
    @error('email') <span class="assessment-error">{{ $message }}</span> @enderror

    <div class="assessment-input-group">
        <span class="input-icon"><i class="ti ti-phone"></i></span>
        <input type="text" name="contact" placeholder="Contact No. (+63)" value="{{ old('contact', $phase1['contact'] ?? '') }}" maxlength="10" required>
    </div>
    @error('contact') <span class="assessment-error">{{ $message }}</span> @enderror

    <div class="assessment-input-group">
        <span class="input-icon"><i class="ti ti-cake"></i></span>
        <input type="number" name="age" placeholder="Age" value="{{ old('age', $phase1['age'] ?? '') }}" min="1" max="100" required>
    </div>
    @error('age') <span class="assessment-error">{{ $message }}</span> @enderror

    <span class="assessment-field-label">Gender</span>
    <div class="radio-pills">
        <div class="radio-pill">
            <input type="radio" name="gender" value="1" id="genderMale" {{ old('gender', $phase1['gender'] ?? '') == '1' ? 'checked' : '' }} required>
            <label for="genderMale">Male</label>
        </div>
        <div class="radio-pill">
            <input type="radio" name="gender" value="2" id="genderFemale" {{ old('gender', $phase1['gender'] ?? '') == '2' ? 'checked' : '' }}>
            <label for="genderFemale">Female</label>
        </div>
    </div>
    @error('gender') <span class="assessment-error">{{ $message }}</span> @enderror

    <span class="assessment-field-label">Status</span>
    <div class="radio-pills">
        <div class="radio-pill">
            <input type="radio" name="status" value="1" id="statusSingle" {{ old('status', $phase1['status'] ?? '') == '1' ? 'checked' : '' }} required>
            <label for="statusSingle">Single</label>
        </div>
        <div class="radio-pill">
            <input type="radio" name="status" value="2" id="statusMarried" {{ old('status', $phase1['status'] ?? '') == '2' ? 'checked' : '' }}>
            <label for="statusMarried">Married</label>
        </div>
    </div>
    @error('status') <span class="assessment-error">{{ $message }}</span> @enderror

    <span class="assessment-field-label">Baptized</span>
    <div class="radio-pills">
        <div class="radio-pill">
            <input type="radio" name="baptized" value="1" id="baptizedYes" {{ old('baptized', $phase1['baptized'] ?? '') == '1' ? 'checked' : '' }} required>
            <label for="baptizedYes">Yes</label>
        </div>
        <div class="radio-pill">
            <input type="radio" name="baptized" value="2" id="baptizedNo" {{ old('baptized', $phase1['baptized'] ?? '') == '2' ? 'checked' : '' }}>
            <label for="baptizedNo">No</label>
        </div>
    </div>
    @error('baptized') <span class="assessment-error">{{ $message }}</span> @enderror

    <span class="assessment-field-label">Time In Faith</span>
    <div class="radio-pills">
        <div class="radio-pill">
            <input type="radio" name="timeInFaith" value="1" id="faith1" {{ old('timeInFaith', $phase1['timeInFaith'] ?? '') == '1' ? 'checked' : '' }} required>
            <label for="faith1">1+ week</label>
        </div>
        <div class="radio-pill">
            <input type="radio" name="timeInFaith" value="2" id="faith2" {{ old('timeInFaith', $phase1['timeInFaith'] ?? '') == '2' ? 'checked' : '' }}>
            <label for="faith2">6+ months</label>
        </div>
        <div class="radio-pill">
            <input type="radio" name="timeInFaith" value="3" id="faith3" {{ old('timeInFaith', $phase1['timeInFaith'] ?? '') == '3' ? 'checked' : '' }}>
            <label for="faith3">1+ year</label>
        </div>
        <div class="radio-pill">
            <input type="radio" name="timeInFaith" value="4" id="faith4" {{ old('timeInFaith', $phase1['timeInFaith'] ?? '') == '4' ? 'checked' : '' }}>
            <label for="faith4">2+ years</label>
        </div>
    </div>
    @error('timeInFaith') <span class="assessment-error">{{ $message }}</span> @enderror
</form>
