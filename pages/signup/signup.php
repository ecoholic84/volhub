<div class="flex items-center justify-center w-full max-w-full">
    <div x-data="{ 
        fullscreenModal: false,
        errorMessage: '',
        submitForm(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '../profile/profile-index.php';
                } else {
                    this.errorMessage = data.error;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.errorMessage = 'An unexpected error occurred. Please try again.';
            });
        }
    }" x-init="
    $watch('fullscreenModal', function(value){
            if(value === true){
                document.body.classList.add('overflow-hidden');
            }else{
                document.body.classList.remove('overflow-hidden');
            }
        })
    " @keydown.escape="fullscreenModal=false">
        <div @click="fullscreenModal=true"
            class="inline-flex items-center justify-center px-4 py-2 font-medium leading-6 text-center whitespace-nowrap transition duration-150 ease-in-out border border-transparent md:mr-1 text-white md:w-auto bg-neutral-800 rounded-lg md:rounded-full hover:bg-neutral-700 focus:outline-none focus:border-neutral-500 focus:shadow-outline-gray active:bg-neutral-600">
            Sign Up
        </div>

        <template x-teleport="body">
            <div x-show="fullscreenModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full" class="flex fixed inset-0 z-[99] w-screen h-screen bg-black">
                <button @click="fullscreenModal=false"
                    class="absolute top-0 right-0 z-30 flex items-center justify-center px-3 py-2 mt-3 mr-3 space-x-1 text-xs font-medium uppercase border rounded-md border-neutral-200 lg:border-neutral-600 lg:bg-neutral-800 hover:lg:bg-neutral-700 text-white hover:bg-neutral-100">
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
                                <h1 class="text-white text-2xl font-semibold tracking-tight">Create an account</h1>
                                <p class="text-sm text-neutral-400">Enter your details below to create your account
                                </p>
                            </div>
                            <form @submit="submitForm" action="pages\signup\signup-handler.php" method="POST" class="space-y-2">
                                <label for="fullname" class="sr-only">Full Name</label>
                                <input type="text" name="fullname" id="fullname" placeholder="Richard Hendricks" required
                                    class="flex w-full h-10 px-3 py-2 text-sm bg-neutral-900 border rounded-md border-neutral-700 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 text-white">

                                <label for="username" class="sr-only">Username</label>
                                <input type="text" name="username" id="username" placeholder="ecoholic" required
                                    class="flex w-full h-10 px-3 py-2 text-sm bg-neutral-900 border rounded-md border-neutral-700 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 text-white">

                                <label for="email" class="sr-only">Email</label>
                                <input type="email" name="email" id="email" placeholder="name@example.com" required
                                    class="flex w-full h-10 px-3 py-2 text-sm bg-neutral-900 border rounded-md border-neutral-700 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 text-white">

                                <label for="pwd" class="sr-only">Password</label>
                                <input type="password" name="pwd" id="pwd" placeholder="Enter a strong password" required
                                    class="flex w-full h-10 px-3 py-2 text-sm bg-neutral-900 border rounded-md border-neutral-700 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 text-white">

                                <label for="pwdrepeat" class="sr-only">Repeat Password</label>
                                <input type="password" name="pwdrepeat" id="pwdrepeat" placeholder="Repeat the password" required
                                    class="flex w-full h-10 px-3 py-2 text-sm bg-neutral-900 border rounded-md border-neutral-700 ring-offset-background placeholder:text-neutral-500 focus:border-neutral-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 text-white">

                                    <button type="submit"
                                    class="inline-flex items-center justify-center w-full h-10 px-4 py-2 text-sm font-medium tracking-wide text-black transition-colors duration-200 rounded-md bg-white focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 focus:shadow-outline focus:outline-none hover:bg-neutral-100">
                                    Sign Up
                                </button>

                                <div x-show="errorMessage" x-text="errorMessage" class="mt-2 text-red-500 text-sm"></div>
                            </form>

                                <div class="relative py-6">
                                    <div class="absolute inset-0 flex items-center">
                                        <span class="w-full border-t border-neutral-600"></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- <p class="mt-6 text-sm text-center text-neutral-500">Already have an account? <a href="#_"
                                class="relative font-medium text-yellow-500 group"><span>Login here</span><span
                                    class="absolute bottom-0 left-0 w-0 group-hover:w-full ease-out duration-300 h-0.5 bg-yellow-500"></span></a>
                        </p> -->
                        <p class="px-8 mt-1 text-sm text-center text-neutral-500">By continuing, you agree to our Terms and Policy.
                        </p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>