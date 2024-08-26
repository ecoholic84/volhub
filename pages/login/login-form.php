<div class="flex items-center justify-center w-full max-w-full">
    <div x-data="{ fullscreenModal: false }" x-init="
    $watch('fullscreenModal', function(value){
            if(value === true){
                document.body.classList.add('overflow-hidden');
            }else{
                document.body.classList.remove('overflow-hidden');
            }
        })
    " @keydown.escape="fullscreenModal=false">
        <div @click="fullscreenModal=true"
            class="w-full py-5 mr-0 text-center text-neutral-400 md:py-3 md:w-auto hover:text-white md:pl-0 md:mr-3 lg:mr-5">
            Sign In</div>

        <template x-teleport="body">
            <div x-show="fullscreenModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full" class="flex fixed inset-0 z-[99] w-screen h-screen bg-black">
                <button @click="fullscreenModal=false"
                    class="absolute top-0 right-0 z-30 flex items-center justify-center px-3 py-2 mt-3 mr-3 space-x-1 text-xs font-medium uppercase border rounded-md border-neutral-500 lg:border-neutral-700 lg:bg-neutral-800 hover:lg:bg-neutral-700 text-white hover:bg-neutral-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Close</span>
                </button>

                <div class="relative flex flex-wrap items-center w-full h-full px-8">

                    <div class="relative w-full max-w-sm mx-auto lg:mb-0">
                        <div class="relative text-center">

                            <div class="flex flex-col mb-6 space-y-2">
                                <h1 class="text-2xl font-semibold tracking-tight text-white">Create an account</h1>
                                <p class="text-sm text-neutral-400">Enter your email below to create your account
                                </p>
                            </div>
                            <form onsubmit="event.preventDefault();" class="space-y-2">
                                <input type="text" placeholder="name@example.com"
                                    class="flex w-full h-10 px-3 py-2 text-sm bg-neutral-900 border rounded-md border-neutral-700 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 text-white">
                                <button type="button"
                                    class="inline-flex items-center justify-center w-full h-10 px-4 py-2 text-sm font-medium tracking-wide text-black transition-colors duration-200 rounded-md bg-white hover:bg-neutral-100 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 focus:shadow-outline focus:outline-none">
                                    Sign up with Email
                                </button>
                                <div class="relative py-6">
                                    <div class="absolute inset-0 flex items-center"><span
                                            class="w-full border-t border-neutral-600"></span></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
