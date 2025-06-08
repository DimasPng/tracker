<footer class="bg-white">
    <div class="mx-auto max-w-7xl px-6 py-12 md:flex md:items-center md:justify-between lg:px-8">
        <div class="flex justify-center space-x-6 md:order-2">
            <span class="text-sm leading-6 text-gray-600">
                Activity Tracker System
            </span>
            
            <?php if (auth()->check()): ?>
                <div class="border-l border-gray-300 pl-6 ml-6">
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
        <div class="mt-8 md:order-1 md:mt-0">
            <p class="text-center text-xs leading-5 text-gray-500">
                &copy; 2025 Buy A Cow. Made with ❤️ and PHP.
            </p>
        </div>
    </div>
</footer>
