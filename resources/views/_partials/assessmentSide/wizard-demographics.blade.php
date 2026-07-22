<form method="POST" action="{{ route('assessment.phase1.store') }}" id="demographicForm">
    @csrf

    <fieldset>
        <legend>Personal Details</legend>

        <div>
            <label>Name:</label>
            <input type="text" name="name" value="{{ old('name', $phase1['name'] ?? '') }}" required>
            @error('name') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email', $phase1['email'] ?? '') }}" required>
            @error('email') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Contact No. (+63):</label>
            <input type="text" name="contact" value="{{ old('contact', $phase1['contact'] ?? '') }}" maxlength="10" required>
            @error('contact') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Gender:</label>
            <label><input type="radio" name="gender" value="1" {{ old('gender', $phase1['gender'] ?? '') == '1' ? 'checked' : '' }} required> Male</label>
            <label><input type="radio" name="gender" value="2" {{ old('gender', $phase1['gender'] ?? '') == '2' ? 'checked' : '' }}> Female</label>
            @error('gender') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Age:</label>
            <input type="number" name="age" value="{{ old('age', $phase1['age'] ?? '') }}" min="1" max="100" required>
            @error('age') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Status:</label>
            <label><input type="radio" name="status" value="1" {{ old('status', $phase1['status'] ?? '') == '1' ? 'checked' : '' }} required> Single</label>
            <label><input type="radio" name="status" value="2" {{ old('status', $phase1['status'] ?? '') == '2' ? 'checked' : '' }}> Married</label>
            @error('status') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Baptized:</label>
            <label><input type="radio" name="baptized" value="1" {{ old('baptized', $phase1['baptized'] ?? '') == '1' ? 'checked' : '' }} required> Yes</label>
            <label><input type="radio" name="baptized" value="2" {{ old('baptized', $phase1['baptized'] ?? '') == '2' ? 'checked' : '' }}> No</label>
            @error('baptized') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Time In Faith:</label>
            <label><input type="radio" name="timeInFaith" value="1" {{ old('timeInFaith', $phase1['timeInFaith'] ?? '') == '1' ? 'checked' : '' }} required> 1+ week</label>
            <label><input type="radio" name="timeInFaith" value="2" {{ old('timeInFaith', $phase1['timeInFaith'] ?? '') == '2' ? 'checked' : '' }}> 6+ months</label>
            <label><input type="radio" name="timeInFaith" value="3" {{ old('timeInFaith', $phase1['timeInFaith'] ?? '') == '3' ? 'checked' : '' }}> 1+ year</label>
            <label><input type="radio" name="timeInFaith" value="4" {{ old('timeInFaith', $phase1['timeInFaith'] ?? '') == '4' ? 'checked' : '' }}> 2+ years</label>
            @error('timeInFaith') <span style="color:red;">{{ $message }}</span> @enderror
        </div>
    </fieldset>
</form>
