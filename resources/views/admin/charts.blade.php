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
        <p class="dashboard-subtitle">Click on any card to view detailed analytics</p>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Card 1: Task Status Overview -->
        <div class="chart-preview-card" data-chart="taskStatus">
            <div class="card-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 6v6l4 2"></path>
                </svg>
            </div>
            <h3 class="card-title">Task Status</h3>
            <p class="card-description">Overview of task completion status and progress tracking</p>
            <button class="view-chart-btn">
                <span>View Chart</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- Card 2: Priority Distribution -->
        <div class="chart-preview-card" data-chart="priority">
            <div class="card-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 6h18M3 12h18M3 18h18" />
                </svg>
            </div>
            <h3 class="card-title">Priority Distribution</h3>
            <p class="card-description">Tasks organized by priority levels and urgency</p>
            <button class="view-chart-btn">
                <span>View Chart</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- Card 3: Tasks by Project -->
        <div class="chart-preview-card" data-chart="projects">
            <div class="card-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                </svg>
            </div>
            <h3 class="card-title">Tasks by Project</h3>
            <p class="card-description">Distribution of tasks across different projects</p>
            <button class="view-chart-btn">
                <span>View Chart</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- Card 4: Tasks by User -->
        <div class="chart-preview-card" data-chart="users">
            <div class="card-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
            </div>
            <h3 class="card-title">Tasks by User</h3>
            <p class="card-description">Individual task assignments and workload distribution</p>
            <button class="view-chart-btn">
                <span>View Chart</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Chart Modal -->
<div class="chart-modal" id="chartModal">
    <div class="chart-modal-overlay"></div>
    <div class="chart-modal-content">
        <div class="chart-modal-header">
            <h3 class="chart-modal-title" id="modalChartTitle">Chart Title</h3>
            <button class="chart-modal-close" id="closeModal">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="chart-modal-controls" id="modalControls" style="display: none;">
            <button class="chart-toggle-btn" id="modalToggleBtn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 6v6l4 2"></path>
                </svg>
                <span>Switch to Pie</span>
            </button>
        </div>
        <div class="chart-modal-body">
            <canvas id="modalChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Chart configuration and state
    let currentChart = null;
    let currentChartType = 'doughnut';
    let currentChartData = null;

    // Modal elements
    const modal = document.getElementById('chartModal');
    const modalTitle = document.getElementById('modalChartTitle');
    const modalCanvas = document.getElementById('modalChart');
    const modalControls = document.getElementById('modalControls');
    const modalToggleBtn = document.getElementById('modalToggleBtn');
    const closeModalBtn = document.getElementById('closeModal');
    const modalOverlay = modal.querySelector('.chart-modal-overlay');

    // Chart titles mapping
    const chartTitles = {
        taskStatus: 'Task Status Overview',
        priority: 'Priority Distribution',
        projects: 'Tasks by Project',
        users: 'Tasks by User',
        monthly: 'Monthly Task Trends',
    };

    // Chart routes mapping
    const chartRoutes = {
        taskStatus: 'admin.charts.status',
        priority: 'admin.charts.priority',
        projects: 'admin.charts.projects',
        users: 'admin.charts.users',
        monthly: 'admin.charts.monthly',
    };

    // Open modal and render chart
    async function openChartModal(chartType) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';

        modalTitle.textContent = chartTitles[chartType];

        // Show/hide toggle button for pie/donut charts
        if (chartType === 'taskStatus') {
            modalControls.style.display = 'flex';
            currentChartType = 'doughnut';
            modalToggleBtn.querySelector('span').textContent = 'Switch to Pie';
        } else {
            modalControls.style.display = 'none';
        }

        await renderChart(chartType);
    }

    // Close modal
    function closeChartModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';

        if (currentChart) {
            currentChart.destroy();
            currentChart = null;
        }
    }

    // Fetch chart data
    async function fetchChartData(chartType) {
        try {
            const route = chartRoutes[chartType];
            const url = route.replace('admin.charts.', '/admin/charts/');
            const response = await fetch(url);
            return await response.json();
        } catch (error) {
            console.error('Error fetching chart data:', error);
            return {};
        }
    }

    // Render chart based on type
    async function renderChart(chartType) {
        const data = await fetchChartData(chartType);
        currentChartData = data;

        const labels = Object.keys(data);
        const values = Object.values(data);
        const ctx = modalCanvas.getContext('2d');

        if (currentChart) {
            currentChart.destroy();
        }

        let chartConfig = {};

        switch (chartType) {
            case 'taskStatus':
                chartConfig = getStatusChartConfig(labels, values);
                break;
            case 'priority':
                chartConfig = getPriorityChartConfig(labels, values);
                break;
            case 'projects':
                chartConfig = getProjectChartConfig(labels, values);
                break;
            case 'users':
                chartConfig = getUserChartConfig(labels, values);
                break;
            case 'monthly':
                chartConfig = getMonthlyChartConfig(labels, values);
                break;
            case 'completion':
                chartConfig = getCompletionChartConfig(labels, values);
                break;
        }

        currentChart = new Chart(ctx, chartConfig);
    }

    // Chart configurations
    function getStatusChartConfig(labels, values) {
        return {
            type: currentChartType,
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
                animation: {
                    duration: 800,
                    easing: 'easeInOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#ffffff',
                            font: {
                                size: 13,
                                family: "'Inter', sans-serif"
                            },
                            padding: 16
                        }
                    }
                }
            }
        };
    }

    function getPriorityChartConfig(labels, values) {
        return {
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
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 12
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
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        };
    }

    function getProjectChartConfig(labels, values) {
        return {
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
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 12
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
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        };
    }

    function getUserChartConfig(labels, values) {
        return {
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
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 12
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
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        };
    }

    function getMonthlyChartConfig(labels, values) {
        return {
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
                                size: 13,
                                family: "'Inter', sans-serif"
                            },
                            padding: 16
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 12
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
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)',
                            drawBorder: false
                        }
                    }
                }
            }
        };
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', () => {
        // Open chart on card click
        document.querySelectorAll('.chart-preview-card').forEach(card => {
            card.addEventListener('click', (e) => {
                const chartType = card.dataset.chart;
                openChartModal(chartType);
            });
        });

        // Close modal events
        closeModalBtn.addEventListener('click', closeChartModal);
        modalOverlay.addEventListener('click', closeChartModal);

        // Escape key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeChartModal();
            }
        });

        // Toggle chart type for task status
        modalToggleBtn.addEventListener('click', async () => {
            currentChartType = currentChartType === 'pie' ? 'doughnut' : 'pie';
            modalToggleBtn.querySelector('span').textContent =
                currentChartType === 'pie' ? 'Switch to Donut' : 'Switch to Pie';

            const labels = Object.keys(currentChartData);
            const values = Object.values(currentChartData);

            if (currentChart) {
                currentChart.destroy();
            }

            const ctx = modalCanvas.getContext('2d');
            currentChart = new Chart(ctx, getStatusChartConfig(labels, values));
        });
    });
</script>
@endpush