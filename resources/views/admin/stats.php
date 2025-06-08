<?php $title = 'Statistics - Admin Panel'; ?>
<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 mb-8">
            <h1 class="text-3xl font-bold text-white">📊 Statistics Dashboard</h1>
            <p class="text-indigo-100 mt-2">Monitor user activity and system performance</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Filters</h2>
                
                <form method="GET" action="/admin/stats" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input 
                            type="date" 
                            id="date_from" 
                            name="date_from" 
                            value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input 
                            type="date" 
                            id="date_to" 
                            name="date_to" 
                            value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                        <select 
                            id="user_id" 
                            name="user_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">All Users</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>" <?= ($filters['user_id'] ?? '') == $user['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($user['email']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                        <select 
                            id="action" 
                            name="action"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">All Actions</option>
                            <?php foreach ($availableActions as $action): ?>
                                <option value="<?= htmlspecialchars($action) ?>" <?= ($filters['action'] ?? '') === $action ? 'selected' : '' ?>>
                                    <?= ucfirst(str_replace('-', ' ', $action)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="md:col-span-2 lg:col-span-4 flex gap-3">
                        <button 
                            type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                        >
                            🔍 Apply Filters
                        </button>
                        <a 
                            href="/admin/stats"
                            class="px-6 py-2 bg-gray-100 text-gray-700 font-medium rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                        >
                            🔄 Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Activity Log</h2>
                    <div class="text-sm text-gray-500">
                        <?php if ($pagination['total_records'] > 0): ?>
                            Showing <?= $pagination['start_record'] ?> to <?= $pagination['end_record'] ?> of <?= $pagination['total_records'] ?> entries
                        <?php else: ?>
                            No entries found
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php if (empty($activities)): ?>
                <div class="p-12 text-center">
                    <div class="text-4xl mb-4">📊</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No activity found</h3>
                    <p class="text-gray-500">Try adjusting your filters or check back later.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date & Time
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Page
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    IP Address
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($activities as $activity): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="font-medium">
                                            <?= \App\Util\TimeHelper::formatForCurrentUser($activity['created_at'], 'M j, Y') ?>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <?= \App\Util\TimeHelper::formatForCurrentUser($activity['created_at'], 'H:i:s') ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php if ($activity['user_email']): ?>
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-xs font-medium text-indigo-600">
                                                        <?= strtoupper(substr($activity['user_email'], 0, 1)) ?>
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="font-medium"><?= htmlspecialchars($activity['user_email']) ?></div>
                                                    <div class="text-xs text-gray-500">ID: <?= $activity['user_id'] ?></div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-gray-400 italic">Guest</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            <?php 
                                            switch($activity['action']) {
                                                case 'login': echo 'bg-green-100 text-green-800'; break;
                                                case 'logout': echo 'bg-yellow-100 text-yellow-800'; break;
                                                case 'registration': echo 'bg-blue-100 text-blue-800'; break;
                                                case 'click-buy-cow': echo 'bg-purple-100 text-purple-800'; break;
                                                case 'click-download': echo 'bg-orange-100 text-orange-800'; break;
                                                case 'page-view-a': echo 'bg-cyan-100 text-indigo-800'; break;
                                                case 'page-view-b': echo 'bg-cyan-100 text-cyan-800'; break;
                
                                                default: echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?= ucfirst(str_replace('-', ' ', htmlspecialchars($activity['action']))) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <code class="bg-gray-100 px-2 py-1 rounded text-xs">
                                            <?= htmlspecialchars($activity['page']) ?>
                                        </code>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                        <?php echo htmlspecialchars($activity['ip_address']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            
            <?php if ($pagination['total_records'] > 0): ?>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-700">Show</span>
                        <form method="GET" action="/admin/stats" class="inline">
                            <?php if (!empty($filters['date_from'])): ?>
                                <input type="hidden" name="date_from" value="<?= htmlspecialchars($filters['date_from']) ?>">
                            <?php endif; ?>
                            <?php if (!empty($filters['date_to'])): ?>
                                <input type="hidden" name="date_to" value="<?= htmlspecialchars($filters['date_to']) ?>">
                            <?php endif; ?>
                            <?php if (!empty($filters['user_id'])): ?>
                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($filters['user_id']) ?>">
                            <?php endif; ?>
                            <?php if (!empty($filters['action'])): ?>
                                <input type="hidden" name="action" value="<?= htmlspecialchars($filters['action']) ?>">
                            <?php endif; ?>
                            
                            <select 
                                name="limit" 
                                onchange="this.form.submit()"
                                class="px-4 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="10" <?= $pagination['limit'] == 10 ? 'selected' : '' ?>>10</option>
                                <option value="20" <?= $pagination['limit'] == 20 ? 'selected' : '' ?>>20</option>
                                <option value="50" <?= $pagination['limit'] == 50 ? 'selected' : '' ?>>50</option>
                                <option value="100" <?= $pagination['limit'] == 100 ? 'selected' : '' ?>>100</option>
                            </select>
                        </form>
                        <span class="text-sm text-gray-700">entries</span>
                    </div>

                    <?php if ($pagination['total_pages'] > 1): ?>
                        <div class="flex items-center gap-1">
                            <?php if ($pagination['has_previous']): ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['previous_page']])) ?>" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    ← Previous
                                </a>
                            <?php else: ?>
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-l-md cursor-not-allowed">
                                    ← Previous
                                </span>
                            <?php endif; ?>

                            <?php
                            $start = max(1, $pagination['current_page'] - 2);
                            $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
                            
                            if ($start > 1): ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    1
                                </a>
                                <?php if ($start > 2): ?>
                                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border-t border-b border-gray-300">...</span>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $start; $i <= $end; $i++): ?>
                                <?php if ($i == $pagination['current_page']): ?>
                                    <span class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 border-t border-b border-indigo-600">
                                        <?= $i ?>
                                    </span>
                                <?php else: ?>
                                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                                       class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <?= $i ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($end < $pagination['total_pages']): ?>
                                <?php if ($end < $pagination['total_pages'] - 1): ?>
                                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border-t border-b border-gray-300">...</span>
                                <?php endif; ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['total_pages']])) ?>" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <?= $pagination['total_pages'] ?>
                                </a>
                            <?php endif; ?>

                            <?php if ($pagination['has_next']): ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['next_page']])) ?>" 
                                   class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    Next →
                                </a>
                            <?php else: ?>
                                <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-r-md cursor-not-allowed">
                                    Next →
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <a 
                href="/admin/reports" 
                class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
            >
                📈 View Reports
            </a>
        </div>
    </div>
</div>
 