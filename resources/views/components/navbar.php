<?php 
$user = App\Core\View::auth();
$isAdmin = App\Core\View::hasRole(\App\Enum\UserRole::ADMIN);
$currentUri = getCurrentUri();
?>

<nav class="bg-white shadow sticky top-0 z-50 w-full sticky-navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center space-x-2 relative z-20">
                        <div class="h-8 w-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">🐄</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">Buy A Cow</span>
                    </a>
                </div>

                <?php if (auth()->check()): ?>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="/page-a" 
                           class="<?= $currentUri === '/page-a' ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors relative z-20">
                            🛒 Buy A Cow
                        </a>
                        <a href="/page-b" 
                           class="<?= $currentUri === '/page-b' ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors relative z-20">
                            📥 Download
                        </a>
                        
                        <?php if (auth()->hasRole(\App\Enum\UserRole::ADMIN)): ?>
                            <a href="/admin/stats" 
                               class="<?= $currentUri === '/admin/stats' ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors relative z-20">
                                📊 Statistics
                            </a>
                            <a href="/admin/reports" 
                               class="<?= $currentUri === '/admin/reports' ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors relative z-20">
                                📈 Reports
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex items-center">
                <?php if (auth()->check()): ?>
                    <div class="relative ml-3">
                        <div class="flex items-center space-x-4">
                            <div class="hidden md:flex md:items-center md:space-x-2">
                                <div class="h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-indigo-600">
                                        <?= strtoupper(substr(auth()->user()['email'] ?? 'U', 0, 1)) ?>
                                    </span>
                                </div>
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900"><?= htmlspecialchars(auth()->user()['email'] ?? '') ?></div>
                                    <div class="text-gray-500 text-xs">
                                        <?= auth()->hasRole(\App\Enum\UserRole::ADMIN) ? '👑 Administrator' : '👤 User' ?>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="/logout" class="inline">
                                <?= \App\Core\View::csrfField() ?>
                                <button type="submit" 
                                        class="bg-white border border-gray-300 rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors relative z-20"
                                        onclick="handleLogout(event, this)">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center space-x-4">
                        <a href="/login" 
                           class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium transition-colors relative z-20">
                            🔐 Login
                        </a>
                        <a href="/register" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 relative z-20">
                            ✨ Sign Up
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex items-center sm:hidden">
                <button type="button" 
                        class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 relative z-20"
                        onclick="toggleMobileMenu()">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <?php if (auth()->check()): ?>
        <div class="mobile-menu hidden sm:hidden relative z-40 bg-white border-t border-gray-200">
            <div class="pt-2 pb-3 space-y-1">
                <a href="/page-a" 
                   class="<?= $currentUri === '/page-a' ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors">
                    🛒 Buy A Cow
                </a>
                <a href="/page-b" 
                   class="<?= $currentUri === '/page-b' ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors">
                    📥 Download
                </a>
                
                                        <?php if (auth()->hasRole(\App\Enum\UserRole::ADMIN)): ?>
                    <a href="/admin/stats" 
                       class="<?= $currentUri === '/admin/stats' ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors">
                        📊 Statistics
                    </a>
                    <a href="/admin/reports" 
                       class="<?= $currentUri === '/admin/reports' ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors">
                        📈 Reports
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="font-medium text-indigo-600">
                            <?= strtoupper(substr(auth()->user()['email'] ?? 'U', 0, 1)) ?>
                        </span>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800"><?= htmlspecialchars(auth()->user()['email'] ?? '') ?></div>
                        <div class="text-sm text-gray-500">
                            <?= auth()->hasRole(\App\Enum\UserRole::ADMIN) ? '👑 Administrator' : '👤 User' ?>
                        </div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <form method="POST" action="/logout" class="block px-4">
                        <?= \App\Core\View::csrfField() ?>
                        <button type="submit" 
                                class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-md transition-colors"
                                onclick="handleLogout(event, this)">
                            🚪 Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</nav>
