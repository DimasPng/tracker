<?php $title = 'Reports - Admin Panel'; ?>
<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg p-6 mb-8">
            <h1 class="text-3xl font-bold text-white">📈 Reports & Analytics</h1>
            <p class="text-purple-100 mt-2">Comprehensive insights into user behavior and system performance</p>
        </div>


        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Report Period</h2>
                
                <form method="GET" action="/admin/reports" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input 
                            type="date" 
                            id="date_from" 
                            name="date_from" 
                            value="<?= htmlspecialchars($dateFrom) ?>"
                            class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>
                    
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input 
                            type="date" 
                            id="date_to" 
                            name="date_to" 
                            value="<?= htmlspecialchars($dateTo) ?>"
                            class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>
                    
                    <button 
                        type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                    >
                        📊 Generate Report
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Total Users</p>
                        <p class="text-3xl font-bold"><?= $reportData['total_users'] ?? 0 ?></p>
                    </div>
                    <div class="text-3xl opacity-80">👥</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Total Activities</p>
                        <p class="text-3xl font-bold"><?= $reportData['total_activities'] ?? 0 ?></p>
                    </div>
                    <div class="text-3xl opacity-80">📊</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Cows Purchased</p>
                        <p class="text-3xl font-bold"><?= $reportData['cow_purchases'] ?? 0 ?></p>
                    </div>
                    <div class="text-3xl opacity-80">🐄</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm">Downloads</p>
                        <p class="text-3xl font-bold"><?= $reportData['downloads'] ?? 0 ?></p>
                    </div>
                    <div class="text-3xl opacity-80">📥</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Action Distribution</h3>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="actionChart" width="400" height="200"></canvas>
                </div>
            </div>
            

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Activities</h3>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="dailyChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>


        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Activity Trends</h3>
            <div class="h-80 flex items-center justify-center">
                <canvas id="trendsChart" width="800" height="300"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">📊 Activity Trends Table</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                👁️ Page View A
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                👀 Page View B
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                🐄 Buy Cow Clicks
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                📥 Download Clicks
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($reportData['activity_trends'])): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No activity trends data available for the selected period.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reportData['activity_trends'] as $trend): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= \App\Util\TimeHelper::formatForCurrentUser($trend['date'], 'M j, Y') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?= $trend['page_view_a'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                            <?= $trend['page_view_b'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <?= $trend['click_buy_cow'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <?= $trend['click_download'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Action Statistics</h3>
            </div>
            <div class="p-6">
                                        <?php if (empty($reportData['action_stats'])): ?>
                    <div class="text-center py-8">
                        <div class="text-4xl mb-4">📊</div>
                        <p class="text-gray-500">No activity data available for the selected period.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($reportData['action_stats'] as $stat): ?>
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-900">
                                        <?= ucfirst(str_replace('-', ' ', htmlspecialchars($stat['action']))) ?>
                                    </h4>
                                    <span class="text-2xl">
                                        <?php 
                                        switch($stat['action']) {
                                            case 'login': echo '🔐'; break;
                                            case 'logout': echo '🚪'; break;
                                            case 'registration': echo '👤'; break;
                                            case 'click-buy-cow': echo '🐄'; break;
                                            case 'click-download': echo '📥'; break;
                                            case 'page-view-a': echo '👁️'; break;
                                            case 'page-view-b': echo '👀'; break;
                                            default: echo '📊';
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Total Count:</span>
                                        <span class="font-semibold text-gray-900"><?= $stat['count'] ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Unique Users:</span>
                                        <span class="font-semibold text-gray-900"><?= $stat['unique_users'] ?? 'N/A' ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Last Activity:</span>
                                        <span class="text-sm text-gray-500">
                                            <?= $stat['last_activity'] ? \App\Util\TimeHelper::formatForCurrentUser($stat['last_activity'], 'M j, H:i') : 'N/A' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Activity Timeline</h3>
            </div>
            <div class="p-6">
                <?php if (empty($reportData['recent_activities'])): ?>
                    <div class="text-center py-8">
                        <div class="text-4xl mb-4">⏰</div>
                        <p class="text-gray-500">No recent activities to display.</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach (array_slice($reportData['recent_activities'] ?? [], 0, 10) as $activity): ?>
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-indigo-600">
                                            <?php 
                                            switch($activity['action']) {
                                                case 'login': echo '🔐'; break;
                                                case 'logout': echo '🚪'; break;
                                                case 'registration': echo '👤'; break;
                                                case 'click-buy-cow': echo '🐄'; break;
                                                case 'click-download': echo '📥'; break;
                                                case 'page-view-a': echo '👀'; break;
                                                case 'page-view-b': echo '👀'; break;
                                                default: echo '📊';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($activity['user_email'] ?? 'Guest') ?>
                                        <span class="text-gray-500">
                                            <?= strtolower(str_replace('-', ' ', $activity['action'])) ?>
                                        </span>
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <?= $activity['page'] ?? 'Unknown page' ?> • 
                                        <?= \App\Util\TimeHelper::formatForCurrentUser($activity['created_at'], 'M j, Y H:i') ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4">
            <a 
                href="/admin/stats" 
                class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
            >
                📈 View Statistics
            </a>
        </div>
    </div>
</div>

<script src="/js/chart.min.js"></script>

<script>
const actionData = <?= json_encode($reportData['action_stats']) ?>;
const actionLabels = actionData.map(item => item.action.replace('-', ' ').toUpperCase());
const actionCounts = actionData.map(item => item.count);

const actionChart = new Chart(document.getElementById('actionChart'), {
    type: 'doughnut',
    data: {
        labels: actionLabels,
        datasets: [{
            data: actionCounts,
            backgroundColor: [
                '#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', 
                '#EF4444', '#06B6D4', '#84CC16', '#F97316'
            ],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20
                }
            }
        }
    }
});

const dailyData = <?= json_encode($reportData['daily_activities'] ?? []) ?>;
const dailyLabels = dailyData.map(item => item.date);
const dailyCounts = dailyData.map(item => item.count);

const dailyChart = new Chart(document.getElementById('dailyChart'), {
    type: 'line',
    data: {
        labels: dailyLabels,
        datasets: [{
            label: 'Daily Activities',
            data: dailyCounts,
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

const trendsData = <?= json_encode($reportData['activity_trends']) ?>;
const trendsLabels = trendsData.map(item => item.date);

const trendsChart = new Chart(document.getElementById('trendsChart'), {
    type: 'line',
    data: {
        labels: trendsLabels,
        datasets: [
            {
                label: '👁️ Page View A',
                data: trendsData.map(item => parseInt(item.page_view_a)),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: false,
                tension: 0.4
            },
            {
                label: '👀 Page View B',
                data: trendsData.map(item => parseInt(item.page_view_b)),
                borderColor: '#06B6D4',
                backgroundColor: 'rgba(6, 182, 212, 0.1)',
                borderWidth: 3,
                fill: false,
                tension: 0.4
            },
            {
                label: '🐄 Buy Cow Clicks',
                data: trendsData.map(item => parseInt(item.click_buy_cow)),
                borderColor: '#8B5CF6',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                borderWidth: 3,
                fill: false,
                tension: 0.4
            },
            {
                label: '📥 Download Clicks',
                data: trendsData.map(item => parseInt(item.click_download)),
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                borderWidth: 3,
                fill: false,
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: 'Date'
                }
            },
            y: {
                beginAtZero: true,
                display: true,
                title: {
                    display: true,
                    text: 'Count'
                },
                ticks: {
                    stepSize: 1
                }
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        }
    }
});

setInterval(function() {
    actionChart.update();
    dailyChart.update();
    trendsChart.update();
}, 300000);
</script> 