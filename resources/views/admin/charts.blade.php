@extends('layouts.main')

@section('title', 'Analytics Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/charts.css') }}">
@endpush

@section('content')
<div class="analytics-wrapper">
    <!-- Header Section -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">Analytics Dashboard</h1>
        <p class="dashboard-subtitle">Comprehensive overview of task metrics and statistics</p>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Chart 1: Tasks by Project -->
        <div class="chart-card chart-card-half">
            <div class="chart-header">
                <h3 class="chart-title">Tasks by Project</h3>
            </div>
            <div class="chart-body">
                <canvas id="projectChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Tasks by User -->
        <div class="chart-card chart-card-half">
            <div class="chart-header">
                <h3 class="chart-title">Tasks by User</h3>
            </div>
            <div class="chart-body">
                <canvas id="userChart"></canvas>
            </div>
        </div>

        <!-- Chart 3: Task Status Overview -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Task Status Overview</h3>&nbsp; &nbsp;
                <button class="chart-toggle-btn" id="toggleChartType1">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 6v6l4 2"></path>
                    </svg>
                    <span>Switch to Donut</span>
                </button>
            </div>
            <div class="chart-body">
                <canvas id="taskStatusChart"></canvas>
            </div>
        </div>

        <!-- Chart 4: Priority Distribution -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Priority Distribution</h3>
            </div>
            <div class="chart-body">
                <canvas id="priorityChart"></canvas>
            </div>
        </div>

        <!-- Chart 5: Monthly Task Trends -->
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Monthly Task Trends</h3>
            </div>
            <div class="chart-body">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ---- Chart 1: Task Status Overview ----
    let chartType1 = 'doughnut';
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
                    backgroundColor: ['#FFC107', '#3b82f6', '#10b981', '#ef4444', '#a855f7'],
                    borderWidth: 2,
                    borderColor: 'rgba(255, 255, 255, 0.1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#ffffff',
                            font: {
                                size: 12,
                                family: "'Inter', sans-serif"
                            },
                            padding: 12
                        }
                    },
                    title: {
                        display: false
                    }
                }
            }
        });
    }

    document.getElementById('toggleChartType1').addEventListener('click', () => {
        chartType1 = chartType1 === 'pie' ? 'doughnut' : 'pie';
        const btn = document.getElementById('toggleChartType1');
        btn.querySelector('span').innerText = chartType1 === 'pie' ? 'Switch to Donut' : 'Switch to Pie';
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
                    backgroundColor: ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#a855f7'],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
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
                    backgroundColor: '#3b82f6',
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
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
                    backgroundColor: '#f59e0b',
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
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
                    fill: true,
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderColor: '#10b981',
                    borderWidth: 3,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#ffffff',
                            font: {
                                size: 12,
                                family: "'Inter', sans-serif"
                            },
                            padding: 12
                        }
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false
                        }
                    }
                }
            }
        });
    }
    renderMonthlyChart();
</script>
@endpush