// ---- Chart 1: Task Status Overview ----
let chartType1 = 'pie';
let taskStatusChart;
const ctx1 = document.getElementById('taskStatusChart').getContext('2d');

async function fetchStatusData() {
    const res = await fetch('{{ route("admin.charts.status") }}');
    return await res.json();
}

async function renderStatusChart() {
    const data = await fetchStatusData();
    const labels = Object.keys(data);
    const values = Object.values(data);

    if (taskStatusChart) taskStatusChart.destroy();

    taskStatusChart = new Chart(ctx1, {
        type: chartType1,
        data: {
            labels,
            datasets: [{
                label: 'Tasks',
                data: values,
                backgroundColor: ['#FFC107', '#2196F3', '#4CAF50', '#F44336', '#9C27B0']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                title: {
                    display: true,
                    text: 'Task Status Overview'
                }
            }
        }
    });
}

document.getElementById('toggleChartType1').addEventListener('click', () => {
    chartType1 = chartType1 === 'pie' ? 'doughnut' : 'pie';
    document.getElementById('toggleChartType1').innerText =
        chartType1 === 'pie' ? 'Switch to Donut' : 'Switch to Pie';
    renderStatusChart();
});

renderStatusChart();


// ---- Chart 2: Priority Distribution ----
let priorityChart;
const ctx2 = document.getElementById('priorityChart').getContext('2d');

async function fetchPriorityData() {
    const res = await fetch('{{ route("admin.charts.priority") }}');
    return await res.json();
}

async function renderPriorityChart() {
    const data = await fetchPriorityData();
    const labels = Object.keys(data);
    const values = Object.values(data);

    if (priorityChart) priorityChart.destroy();

    priorityChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Tasks by Priority',
                data: values,
                backgroundColor: ['#E91E63', '#FF9800', '#4CAF50']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Priority Distribution'
                }
            }
        }
    });
}
renderPriorityChart();


// ---- Chart 3: Tasks by Project ----
let projectChart;
const ctx3 = document.getElementById('projectChart').getContext('2d');

async function fetchProjectData() {
    const res = await fetch('{{ route("admin.charts.projects") }}');
    return await res.json();
}

async function renderProjectChart() {
    const data = await fetchProjectData();
    const labels = Object.keys(data);
    const values = Object.values(data);

    if (projectChart) projectChart.destroy();

    projectChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Tasks per Project',
                data: values,
                backgroundColor: '#2196F3'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Tasks by Project'
                }
            }
        }
    });
}
renderProjectChart();


// ---- Chart 4: Tasks by User ----
let userChart;
const ctx4 = document.getElementById('userChart').getContext('2d');

async function fetchUserData() {
    const res = await fetch('{{ route("admin.charts.users") }}');
    return await res.json();
}

async function renderUserChart() {
    const data = await fetchUserData();
    const labels = Object.keys(data);
    const values = Object.values(data);

    if (userChart) userChart.destroy();

    userChart = new Chart(ctx4, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Tasks per User',
                data: values,
                backgroundColor: '#673AB7'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Tasks by User'
                }
            }
        }
    });
}
renderUserChart();


// ---- Chart 5: Monthly Task Trends ----
let monthlyChart;
const ctx5 = document.getElementById('monthlyChart').getContext('2d');

async function fetchMonthlyData() {
    const res = await fetch('{{ route("admin.charts.monthly") }}');
    return await res.json();
}

async function renderMonthlyChart() {
    const data = await fetchMonthlyData();
    const labels = Object.keys(data);
    const values = Object.values(data);

    if (monthlyChart) monthlyChart.destroy();

    monthlyChart = new Chart(ctx5, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Tasks Created per Month',
                data: values,
                fill: false,
                borderColor: '#009688',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                title: {
                    display: true,
                    text: 'Monthly Task Trends'
                }
            }
        }
    });
}
renderMonthlyChart();
