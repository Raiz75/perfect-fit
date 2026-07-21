import { Chart, registerables } from 'chart.js';
import ChartDataLabels from 'chartjs-plugin-datalabels';

Chart.register(...registerables);
Chart.register(ChartDataLabels);

let allReports = [];
const chartInstances = {};
const purple = '#8c52ff';

// Ministry list for filter checkboxes
const ministryList = [
    'Worship (Singing)', 'Worship (Dancing)', 'Worship (Instrument)', 'Prayer',
    'Preaching', 'Discipleship', 'Youth', 'Young Adults', "Men's", "Women's",
    'Family Or Couples', 'Ushering', 'Administrative', 'Finance', 'Marshal',
    'Facilities Maintenance', 'Evangelism', 'Missions', 'Community Service',
    'Visitation', 'Production Tech', 'Creative & Social Media', 'Counseling',
    'Healing & Deliverance', 'Funeral', 'Addiction Recovery', 'Special Needs',
    'Seniors', 'Single Adults'
];

document.addEventListener('DOMContentLoaded', function () {
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const today = new Date().toISOString().split('T')[0];

    startDateInput.setAttribute('max', today);
    endDateInput.setAttribute('max', today);

    startDateInput.addEventListener('change', () => {
        if (startDateInput.value) endDateInput.setAttribute('min', startDateInput.value);
    });
    endDateInput.addEventListener('change', () => {
        if (endDateInput.value) startDateInput.setAttribute('max', endDateInput.value);
    });

    // Populate ministry filters
    const container = document.getElementById('ministryFilterContainer');
    ministryList.forEach(m => {
        const div = document.createElement('div');
        div.className = 'form-check';
        div.innerHTML = `<input class="form-check-input ministry-filter" type="checkbox" value="${m}" id="min_${m.replace(/\s+/g, '_')}">
                         <label class="form-check-label small" for="min_${m.replace(/\s+/g, '_')}">${m}</label>`;
        container.appendChild(div);
    });

    // Load data
    loadDashboardData();

    // Apply filters
    document.getElementById('applyFilterBtn').addEventListener('click', loadDashboardData);
    document.getElementById('resetFilterBtn').addEventListener('click', () => {
        document.querySelectorAll('#searchInput, #startDate, #endDate, #ageFilter').forEach(el => el.value = '');
        document.querySelectorAll('select').forEach(el => el.value = '');
        document.querySelectorAll('input[type="checkbox"]').forEach(el => el.checked = false);
        loadDashboardData();
    });
});

function loadDashboardData() {
    const params = new URLSearchParams();

    const search = document.getElementById('searchInput').value.trim();
    if (search) params.set('search', search);

    const startDate = document.getElementById('startDate').value;
    if (startDate) params.set('startDate', startDate);

    const endDate = document.getElementById('endDate').value;
    if (endDate) params.set('endDate', endDate);

    const gender = document.getElementById('genderFilter').value;
    if (gender) params.set('gender', gender);

    const marital = document.getElementById('maritalFilter').value;
    if (marital) params.set('marital', marital);

    const baptized = document.getElementById('baptizedFilter').value;
    if (baptized) params.set('baptized', baptized);

    const faith = document.getElementById('faithFilter').value;
    if (faith) params.set('faith', faith);

    const age = document.getElementById('ageFilter').value.trim();
    if (age) params.set('age', age);

    const skills = Array.from(document.querySelectorAll('.skill-filter:checked')).map(cb => cb.value);
    if (skills.length) params.set('skills', skills.join(','));

    const ministries = Array.from(document.querySelectorAll('.ministry-filter:checked')).map(cb => cb.value);
    if (ministries.length) params.set('ministries', ministries.join(','));

    fetch(`/admin/dashboard/data?${params.toString()}`)
        .then(res => res.json())
        .then(data => {
            allReports = data.userReports.map(r => convertNumericFields(r));
            renderTable(allReports);
            buildCharts(allReports);
            document.getElementById('userTakeCount')?.parentElement?.previousElementSibling?.textContent
                ? (document.querySelector('.row.g-4.mb-4 .col-md-3:first-child h2').textContent = data.userTakeCount)
                : null;
        })
        .catch(err => console.error('Error loading dashboard data:', err));
}

function convertNumericFields(r) {
    const genderMap = { 1: 'Male', 2: 'Female' };
    const maritalMap = { 1: 'Single', 2: 'Married' };
    const baptizedMap = { 1: 'Yes', 2: 'No' };
    const faithMap = { 1: '1+ Week', 2: '6+ Months', 3: '1+ Year', 4: '2+ Years' };
    return {
        ...r,
        gender: genderMap[r.gender] || '—',
        marital: maritalMap[r.marital_status] || '—',
        baptized: baptizedMap[r.baptized] || '—',
        timeInFaith: faithMap[r.time_in_faith] || '—',
    };
}

function renderTable(filtered) {
    const tbody = document.querySelector('#userReportTable tbody');
    tbody.innerHTML = '';

    if (!filtered.length) {
        tbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted py-4">No reports found.</td></tr>';
        return;
    }

    filtered.forEach(r => {
        const skills = [
            r.music == 1 ? 'Music' : null,
            r.technology == 1 ? 'Technology' : null,
            r.writing == 1 ? 'Writing' : null,
            r.technical == 1 ? 'Technical' : null,
            r.speaking == 1 ? 'Speaking' : null,
            r.accounting == 1 ? 'Accounting' : null,
            r.mentoring == 1 ? 'Mentoring' : null,
            r.bible_knowledge == 1 ? 'Bible Knowledge' : null,
        ].filter(Boolean);

        const skillsHtml = skills.length
            ? `<ul class="mb-0" style="padding-left:16px;">${skills.map(s => `<li>${s}</li>`).join('')}</ul>`
            : '—';

        let ministryHtml = '—';
        if (r.eligible_ministry && r.eligible_ministry.trim()) {
            const list = r.eligible_ministry.split(',').map(m => m.trim()).filter(Boolean);
            if (list.length) {
                ministryHtml = `<ul class="mb-0" style="padding-left:16px;">${list.map(m => `<li>${m}</li>`).join('')}</ul>`;
            }
        }

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${r.time_of_submission || '—'}</td>
            <td>${r.email || '—'}</td>
            <td>${r.name || '—'}</td>
            <td>${skillsHtml}</td>
            <td>${ministryHtml}</td>
            <td>${r.gender}</td>
            <td>${r.age || '—'}</td>
            <td>${r.marital}</td>
            <td>${r.baptized}</td>
            <td>${r.timeInFaith}</td>
        `;
        tbody.appendChild(tr);
    });
}

function buildCharts(filtered) {
    const newCharts = { gender: {}, timeInFaith: {}, skillStats: {}, eligibleMinistry: {}, age: {}, marital: {}, baptized: {} };
    const ageOrder = ['Under 18', '18-25', '26-35', '36-50', '51+'];
    const faithOrder = ['1+ Week', '6+ Months', '1+ Year', '2+ Years'];
    const baptizedOrder = ['Yes', 'No'];
    const skillOrder = ['music', 'technology', 'writing', 'technical', 'speaking', 'accounting', 'mentoring', 'bible_knowledge'];

    function bucketAge(ageRaw) {
        const n = parseInt(ageRaw);
        if (isNaN(n)) return 'Unknown';
        if (n < 18) return 'Under 18';
        if (n <= 25) return '18-25';
        if (n <= 35) return '26-35';
        if (n <= 50) return '36-50';
        return '51+';
    }

    filtered.forEach(r => {
        if (r.gender && r.gender !== '—') newCharts.gender[r.gender] = (newCharts.gender[r.gender] || 0) + 1;
        if (r.timeInFaith && r.timeInFaith !== '—') newCharts.timeInFaith[r.timeInFaith] = (newCharts.timeInFaith[r.timeInFaith] || 0) + 1;
        if (r.age) newCharts.age[bucketAge(r.age)] = (newCharts.age[bucketAge(r.age)] || 0) + 1;
        if (r.marital && r.marital !== '—') newCharts.marital[r.marital] = (newCharts.marital[r.marital] || 0) + 1;
        if (r.baptized && r.baptized !== '—') newCharts.baptized[r.baptized] = (newCharts.baptized[r.baptized] || 0) + 1;

        skillOrder.forEach(s => {
            if (r[s] == 1) newCharts.skillStats[s] = (newCharts.skillStats[s] || 0) + 1;
        });

        if (r.eligible_ministry) {
            r.eligible_ministry.split(',').forEach(min => {
                const m = min.trim();
                if (m) newCharts.eligibleMinistry[m] = (newCharts.eligibleMinistry[m] || 0) + 1;
            });
        }
    });

    const hasData = (obj) => obj && Object.values(obj).some(v => v > 0);
    function safeChart(id, config) {
        const canvas = document.getElementById(id);
        if (!canvas) return;
        if (chartInstances[id]) chartInstances[id].destroy();
        chartInstances[id] = new Chart(canvas.getContext('2d'), config);
    }

    // Colors
    const pieColors = ['#E6194B', '#F58231', '#FFE119', '#BFEF45', '#3CB44B', '#46F0F0', '#4363D8', '#911EB4'];

    // Gender Chart (pie)
    if (hasData(newCharts.gender)) {
        safeChart('genderChart', {
            type: 'pie',
            data: {
                labels: Object.keys(newCharts.gender),
                datasets: [{ data: Object.values(newCharts.gender), backgroundColor: ['#E6194B', '#F58231'], borderColor: '#fff', borderWidth: 2 }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: 'Gender Distribution', color: purple, font: { size: 16, weight: 'bold' } },
                    legend: { position: 'bottom', labels: { color: '#666' } },
                    datalabels: { color: '#fff', font: { weight: 'bold' }, formatter: (v, ctx) => { const t = ctx.dataset.data.reduce((a, b) => a + b, 0); return t ? ((v / t) * 100).toFixed(1) + '%' : ''; } }
                }
            },
            plugins: [ChartDataLabels]
        });
    }

    // Age Chart (pie)
    const ageData = ageOrder.map(a => newCharts.age[a] || 0);
    if (ageData.some(v => v > 0)) {
        safeChart('ageChart', {
            type: 'pie',
            data: { labels: ageOrder, datasets: [{ data: ageData, backgroundColor: ['#9A6324', '#FFD8B1', '#808000', '#AAFFC3', '#FF7F50'] }] },
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: 'Age Groups', color: purple, font: { size: 16, weight: 'bold' } },
                    legend: { position: 'bottom', labels: { color: '#666' } },
                    datalabels: { color: '#fff', font: { weight: 'bold' }, formatter: (v, ctx) => { const t = ctx.dataset.data.reduce((a, b) => a + b, 0); return t ? ((v / t) * 100).toFixed(1) + '%' : ''; } }
                }
            },
            plugins: [ChartDataLabels]
        });
    }

    // Baptized Chart (doughnut)
    const baptizedData = baptizedOrder.map(l => newCharts.baptized[l] || 0);
    if (baptizedData.some(v => v > 0)) {
        safeChart('baptizedChart', {
            type: 'doughnut',
            data: { labels: baptizedOrder, datasets: [{ data: baptizedData, backgroundColor: ['#3CB44B', '#ccc'] }] },
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: 'Baptized Status', color: purple, font: { size: 16, weight: 'bold' } },
                    legend: { position: 'bottom', labels: { color: '#666' } },
                    datalabels: { color: '#fff', font: { weight: 'bold' }, formatter: (v, ctx) => { const t = ctx.dataset.data.reduce((a, b) => a + b, 0); return t ? ((v / t) * 100).toFixed(1) + '%' : ''; } }
                }
            },
            plugins: [ChartDataLabels]
        });
    }

    // Faith Chart (bar)
    const faithData = faithOrder.map(l => newCharts.timeInFaith[l] || 0);
    if (faithData.some(v => v > 0)) {
        safeChart('faithChart', {
            type: 'bar',
            data: { labels: faithOrder, datasets: [{ label: 'Users', data: faithData, backgroundColor: ['#FFE119', '#BFEF45', '#3CB44B', '#46F0F0'], borderRadius: 6 }] },
            options: {
                responsive: true,
                plugins: { title: { display: true, text: 'Time in Faith', color: purple, font: { size: 16, weight: 'bold' } }, legend: { display: false }, datalabels: { anchor: 'end', align: 'end', color: '#666', font: { weight: 'bold' } } },
                scales: { x: { ticks: { color: '#666' } }, y: { beginAtZero: true, ticks: { color: '#666' } } }
            },
            plugins: [ChartDataLabels]
        });
    }

    // Skills Chart (bar)
    const skillData = skillOrder.map(k => newCharts.skillStats[k] || 0);
    if (skillData.some(v => v > 0)) {
        safeChart('skillsChart', {
            type: 'bar',
            data: { labels: ['Music', 'Technology', 'Writing', 'Technical', 'Speaking', 'Accounting', 'Mentoring', 'Bible Knowledge'], datasets: [{ label: 'Users with Skill', data: skillData, backgroundColor: pieColors, borderRadius: 6 }] },
            options: {
                responsive: true,
                plugins: { title: { display: true, text: 'Skills Breakdown', color: purple, font: { size: 16, weight: 'bold' } }, legend: { display: false }, datalabels: { anchor: 'end', align: 'end', color: '#666', font: { weight: 'bold' } } },
                scales: { x: { ticks: { color: '#666' } }, y: { beginAtZero: true, ticks: { color: '#666' } } }
            },
            plugins: [ChartDataLabels]
        });
    }

    // Ministry Chart (bar)
    if (hasData(newCharts.eligibleMinistry)) {
        const labels = Object.keys(newCharts.eligibleMinistry);
        const data = Object.values(newCharts.eligibleMinistry);
        safeChart('ministryChart', {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Eligible Ministries', data, backgroundColor: labels.map((_, i) => `hsl(${(i * 360) / labels.length}, 70%, 60%)`) }] },
            options: {
                responsive: true,
                plugins: { title: { display: true, text: 'Ministry Eligibility', color: purple, font: { size: 16, weight: 'bold' } }, legend: { display: false }, datalabels: { anchor: 'end', align: 'end', color: '#666', font: { weight: 'bold' } } },
                scales: { x: { ticks: { color: '#666' } }, y: { beginAtZero: true, ticks: { color: '#666' } } }
            },
            plugins: [ChartDataLabels]
        });
    }

    // Marital Chart (bar)
    if (hasData(newCharts.marital)) {
        safeChart('maritalChart', {
            type: 'bar',
            data: { labels: Object.keys(newCharts.marital), datasets: [{ label: 'Marital Status', data: Object.values(newCharts.marital), backgroundColor: ['#800000', '#808080'], borderRadius: 6 }] },
            options: {
                responsive: true,
                plugins: { title: { display: true, text: 'Marital Status', color: purple, font: { size: 16, weight: 'bold' } }, legend: { display: false }, datalabels: { anchor: 'end', align: 'end', color: '#666', font: { weight: 'bold' } } },
                scales: { x: { ticks: { color: '#666' } }, y: { beginAtZero: true, ticks: { color: '#666' } } }
            },
            plugins: [ChartDataLabels]
        });
    }
}