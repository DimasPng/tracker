<?php $title = (!empty($showThankYou)) ? "Thank You! - Buy A Cow" : "Buy A Cow - Page A"; ?>
<div class="bg-white">
    <div class="relative isolate px-6 pt-14 lg:px-8">
        <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
            <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"></div>
        </div>

        <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
            <div class="text-center">
                <div class="flex justify-center mb-8">
                    <div class="h-16 w-16 bg-indigo-600 rounded-2xl flex items-center justify-center">
                        <span class="text-white font-bold text-3xl">🐄</span>
                    </div>
                </div>

                <?php if (isset($showThankYou) && $showThankYou): ?>
                    <div class="animate-fade-in">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                            Thank You! 🎉
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-gray-600">
                            Your cow purchase request has been received! Our premium cow specialist will contact you soon to complete your bovine acquisition.
                        </p>

                        <div class="mt-8 flex justify-center">
                            <div class="animate-bounce">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 flex items-center justify-center gap-x-6">
                            <a href="/page-b" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-colors relative z-20">
                                📥 Download Resources
                            </a>
                            <a href="/page-a" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700 transition-colors relative z-20">
                                Buy Another Cow <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                        Premium Cow Experience
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Transform your agricultural ambitions with our exclusive cow acquisition service. Each cow comes with a lifetime guarantee and premium grass-fed happiness.
                    </p>

                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-600">
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-lg">🌱</span>
                            <span>Organic Feed</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-lg">🏆</span>
                            <span>Award Winning</span>
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="text-lg">💚</span>
                            <span>Eco Friendly</span>
                        </div>
                    </div>

                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <button 
                            type="button" 
                            class="group relative z-20 rounded-md bg-indigo-600 px-6 py-3 text-lg font-semibold text-white shadow-lg hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200 hover:scale-105 hover:shadow-xl"
                            id="buy-cow-btn"
                            onclick="buyCow()"
                        >
                            <span class="flex items-center space-x-2">
                                <span class="text-2xl">🐄</span>
                                <span>Buy a Cow</span>
                                <span class="text-xl">💰</span>
                            </span>

                            <div class="absolute inset-0 rounded-md bg-white opacity-0 group-hover:opacity-10 transition-opacity"></div>
                        </button>
                        
                        <a href="/page-b" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700 transition-colors relative z-20">
                            View Downloads <span aria-hidden="true">→</span>
                        </a>
                    </div>

                    <div class="mt-16 sm:mt-20">
                        <figure class="mx-auto max-w-lg">
                            <blockquote class="text-center text-xl font-semibold leading-8 text-gray-900 sm:text-2xl sm:leading-9">
                                <p>"Best cow purchase experience ever! The cow even came with its own personality."</p>
                            </blockquote>
                            <figcaption class="mt-4 text-center">
                                <div class="mt-2 text-sm text-gray-600">
                                    Happy Customer #1337
                                </div>
                            </figcaption>
                        </figure>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
            <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"></div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}
</style>

<?php if (!isset($showThankYou) || !$showThankYou): ?>
<script>
function buyCow() {
    const buyBtn = document.getElementById('buy-cow-btn');
    const buttonContainer = buyBtn.parentElement;
    
    buyBtn.style.display = 'none';
    
    const thankYouMessage = document.createElement('div');
    thankYouMessage.className = 'animate-fade-in text-center';
    thankYouMessage.innerHTML = `
        <div class="inline-flex items-center space-x-3 bg-green-100 text-green-800 px-6 py-3 rounded-lg font-semibold">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="text-lg">Thank You! 🎉</span>
        </div>
        <p class="mt-2 text-sm text-gray-600">Your cow purchase request has been received!</p>
    `;
    
    buttonContainer.appendChild(thankYouMessage);
    
    fetch('/buy-cow', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': window.BuyACow?.csrfToken || ''
        },
        body: '_csrf_token=' + encodeURIComponent(window.BuyACow?.csrfToken || ''),
        credentials: 'same-origin'
    })
    .catch(error => {
        console.error('Buy cow request failed:', error);
    });
}
</script>
<?php endif; ?>
