document.addEventListener('DOMContentLoaded', function () {
    const generateBtn = document.getElementById('generateReportBtn');
    if (!generateBtn) return;

    generateBtn.addEventListener('click', function () {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = generateBtn.dataset.reportUrl;

        // CSRF token
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
        form.appendChild(csrf);

        // Collect filter values
        const filterFields = {
            search: 'searchInput',
            startDate: 'startDate',
            endDate: 'endDate',
            gender: 'genderFilter',
            marital: 'maritalFilter',
            baptized: 'baptizedFilter',
            faith: 'faithFilter',
            age: 'ageFilter',
        };

        Object.entries(filterFields).forEach(([name, id]) => {
            const el = document.getElementById(id);
            if (el && el.value) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = el.value;
                form.appendChild(input);
            }
        });

        // Skills (checkboxes)
        const skills = Array.from(document.querySelectorAll('.skill-filter:checked')).map(cb => cb.value);
        if (skills.length) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'skills';
            input.value = skills.join(',');
            form.appendChild(input);
        }

        // Ministries (checkboxes)
        const ministries = Array.from(document.querySelectorAll('.ministry-filter:checked')).map(cb => cb.value);
        if (ministries.length) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ministries';
            input.value = ministries.join(',');
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
    });
});