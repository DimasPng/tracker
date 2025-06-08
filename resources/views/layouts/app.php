<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Buy A Cow') ?></title>

    <link href="/css/app.css" rel="stylesheet">
    <link href="/fonts/inter.css" rel="stylesheet">

    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🐄</text></svg>">
    <link rel="apple-touch-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🐄</text></svg>">

    <meta name="description" content="Buy A Cow - Premium cow management and tracking system">

    <meta property="og:title" content="<?= htmlspecialchars($title ?? 'Buy A Cow') ?>">
    <meta property="og:description" content="Premium cow management and tracking system">
    <meta property="og:type" content="website">
    <meta property="og:image" content="/images/cow-logo.png">

    <?php if (isset($additionalHead)): ?>
        <?= $additionalHead ?>
    <?php endif; ?>
</head>
<body class="h-full font-sans antialiased">
    <?php if (!isset($hideNavbar) || !$hideNavbar): ?>
        <?= \App\Core\View::component('navbar') ?>
    <?php endif; ?>

    <main class="<?= (!isset($hideNavbar) || !$hideNavbar) ? '' : 'min-h-screen' ?>">
        <?= $content ?>
    </main>

    <?php if (!isset($hideFooter) || !$hideFooter): ?>
        <footer class="bg-white border-t border-gray-200 relative z-30">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="h-8 w-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">🐄</span>
                            </div>
                            <span class="text-xl font-bold text-gray-900">Buy A Cow</span>
                        </div>
                        <p class="text-gray-600 text-sm max-w-md">
                            The world's most advanced cow management and tracking system. 
                            Revolutionizing agriculture through technology and innovation.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="/page-a" class="text-gray-600 hover:text-indigo-600 text-sm transition-colors">Buy A Cow</a></li>
                            <li><a href="/page-b" class="text-gray-600 hover:text-indigo-600 text-sm transition-colors">Download</a></li>
                            <?php if (auth()->check() && auth()->hasRole(\App\Enum\UserRole::ADMIN)): ?>
                                <li><a href="/admin/stats" class="text-gray-600 hover:text-indigo-600 text-sm transition-colors">Statistics</a></li>
                                <li><a href="/admin/reports" class="text-gray-600 hover:text-indigo-600 text-sm transition-colors">Reports</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">Support</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-600 hover:text-indigo-600 text-sm transition-colors">Documentation</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-indigo-600 text-sm transition-colors">Help Center</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-indigo-600 text-sm transition-colors">Contact Us</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-indigo-600 text-sm transition-colors">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4">
                            <p class="text-gray-500 text-sm">
                                © <?= date('Y') ?> Buy A Cow. All rights reserved.
                            </p>

                            <?php if (auth()->check()): ?>
                                <div class="border-l border-gray-300 pl-4 ml-4 hidden md:block">
                                    <form method="POST" action="/logout" class="inline">
                                        <button type="submit" 
                                                class="text-sm text-gray-500 hover:text-gray-700 transition-colors duration-200 flex items-center space-x-1"
                                                title="Logout from your account">
                                            <span>🚪</span>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex space-x-6 mt-4 md:mt-0">
                            <a href="#" class="text-gray-400 hover:text-gray-500 transition-colors">
                                <span class="sr-only">Twitter</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-500 transition-colors">
                                <span class="sr-only">GitHub</span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    <?php endif; ?>

    <script>
        window.BuyACow = {
            csrfToken: '<?= \App\Core\View::csrfToken() ?>',
            user: <?= auth()->check() ? json_encode(auth()->user()) : 'null' ?>,

            showNotification: function(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
                    type === 'success' ? 'bg-green-500 text-white' :
                    type === 'error' ? 'bg-red-500 text-white' :
                    type === 'warning' ? 'bg-yellow-500 text-white' :
                    'bg-blue-500 text-white'
                }`;
                notification.textContent = message;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            },

            formatDate: function(date) {
                return new Date(date).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        };

        window.handleLogout = function(event, button) {
            event.preventDefault();
            const form = button.closest('form');

            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '🔄 Logging out...';

            fetch('/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': window.BuyACow.csrfToken
                },
                body: '_csrf_token=' + encodeURIComponent(window.BuyACow.csrfToken),
                credentials: 'same-origin'
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else if (response.status === 302 || response.status === 301) {
                    return response.text().then(() => {
                        window.location.href = '/login';
                    });
                } else if (response.ok) {
                    window.location.href = '/login';
                } else {
                    throw new Error('Logout failed');
                }
            })
            .catch(error => {
                console.error('Logout error:', error);
                button.disabled = false;
                button.innerHTML = originalText;
                
                if (window.BuyACow && window.BuyACow.showNotification) {
                    window.BuyACow.showNotification('Logout failed, trying again...', 'warning');
                }
                
                setTimeout(() => {
                    form.submit();
                }, 1000);
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[data-auto-hide]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });

        window.toggleMobileMenu = function() {
            const mobileMenu = document.querySelector('.mobile-menu');
            const mobileButton = document.querySelector('.mobile-menu-button');
            
            if (mobileMenu) {
                mobileMenu.classList.toggle('hidden');
                

                if (mobileButton) {
                    if (mobileMenu.classList.contains('hidden')) {
                        mobileButton.style.backgroundColor = '';
                        mobileButton.setAttribute('aria-expanded', 'false');
                    } else {
                        mobileButton.style.backgroundColor = '#f3f4f6';
                        mobileButton.setAttribute('aria-expanded', 'true');
                    }
                }
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('nav a[href]');
            navLinks.forEach(link => {
                link.style.cursor = 'pointer';
                link.style.transition = 'all 0.2s ease';
                
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-1px)';
                    this.style.textDecoration = 'none';
                });
                
                link.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
                
                link.addEventListener('mousedown', function() {
                    this.style.transform = 'translateY(0) scale(0.98)';
                });
                
                link.addEventListener('mouseup', function() {
                    this.style.transform = 'translateY(-1px) scale(1)';
                });
            });
            
            const logoutButtons = document.querySelectorAll('button[type="submit"]');
            logoutButtons.forEach(button => {
                const form = button.closest('form');
                if (form && form.action && String(form.action).includes('/logout')) {
                    button.style.cursor = 'pointer';
                    button.style.transition = 'all 0.2s ease';
                    
                    button.addEventListener('mouseenter', function() {
                        this.style.backgroundColor = '#f3f4f6';
                        this.style.transform = 'translateY(-1px)';
                    });
                    
                    button.addEventListener('mouseleave', function() {
                        this.style.backgroundColor = '';
                        this.style.transform = 'translateY(0)';
                    });
                    
                    button.addEventListener('mousedown', function() {
                        this.style.transform = 'scale(0.95)';
                    });
                    
                    button.addEventListener('mouseup', function() {
                        this.style.transform = 'translateY(-1px)';
                    });
                }
            });

            const navbar = document.querySelector('nav');
            if (navbar) {
                navbar.style.position = 'sticky';
                navbar.style.top = '0';
                navbar.style.zIndex = '50';
                navbar.style.width = '100%';
                
                let lastScrollY = window.scrollY;
                
                const updateNavbar = () => {
                    const currentScrollY = window.scrollY;

                    if (currentScrollY > 10) {
                        navbar.classList.add('shadow-lg', 'backdrop-blur-sm');
                        navbar.classList.remove('shadow');
                        navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                        navbar.style.backdropFilter = 'blur(10px)';
                    } else {
                        navbar.classList.add('shadow');
                        navbar.classList.remove('shadow-lg', 'backdrop-blur-sm');
                        navbar.style.backgroundColor = 'rgb(255, 255, 255)';
                        navbar.style.backdropFilter = 'none';
                    }
                    
                    lastScrollY = currentScrollY;
                };
                
                let ticking = false;
                window.addEventListener('scroll', () => {
                    if (!ticking) {
                        requestAnimationFrame(() => {
                            updateNavbar();
                            ticking = false;
                        });
                        ticking = true;
                    }
                });
                
                updateNavbar();
            }
        });
    </script>

    <?php if (isset($additionalScripts)): ?>
        <?= $additionalScripts ?>
    <?php endif; ?>
</body>
</html>
