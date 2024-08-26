<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pines UI</title>
    <style>
    [x-cloak] {
        display: none
    }
    </style>
    <!-- Include the Alpine library on your page -->
    <script src="https://unpkg.com/alpinejs" defer></script>
    <!-- Include the TailwindCSS library on your page -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-start justify-center h-full bg-gray-50">
    <div class="flex items-center justify-center w-full max-w-full">
        <div x-data="{
        tabSelected: 1,
        tabId: $id('tabs'),
        tabButtonClicked(tabButton){
            this.tabSelected = tabButton.id.replace(this.tabId + '-', '');
            this.tabRepositionMarker(tabButton);
        },
        tabRepositionMarker(tabButton){
            this.$refs.tabMarker.style.width=tabButton.offsetWidth + 'px';
            this.$refs.tabMarker.style.height=tabButton.offsetHeight + 'px';
            this.$refs.tabMarker.style.left=tabButton.offsetLeft + 'px';
        },
        tabContentActive(tabContent){
            return this.tabSelected == tabContent.id.replace(this.tabId + '-content-', '');
        }
    }" x-init="tabRepositionMarker($refs.tabButtons.firstElementChild);" class="relative w-full max-w-sm">

            <div x-ref="tabButtons"
                class="relative inline-grid items-center justify-center w-full h-10 grid-cols-2 p-1 text-gray-500 bg-gray-100 rounded-lg select-none">
                <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button"
                    class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">Sign Up</button>
                <button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button"
                    class="relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap">Sign In</button>
                <div x-ref="tabMarker" class="absolute left-0 z-10 w-1/2 h-full duration-300 ease-out" x-cloak>
                    <div class="w-full h-full bg-white rounded-md shadow-sm"></div>
                </div>
            </div>
            <div class="relative w-full mt-2 content">
                <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative">
                    <!-- Tab Content 1 - Replace with your content -->
                    <section class="signup-form">
                        <form action="signup-handler.php" method="POST">

                            <div class="border rounded-lg shadow-sm bg-card text-neutral-900">
                                <div class="flex flex-col space-y-1.5 p-6">
                                    <h3 class="text-lg font-semibold leading-none tracking-tight">Sign Up</h3>
                                    <p class="text-sm text-neutral-500">Let's create a account to get started.</p>
                                </div>
                                <div class="p-6 pt-0 space-y-2">
                                    <div class="space-y-1"><label
                                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                            for="fullname">Name</label><input type="text" name="fullname"
                                            placeholder="Richard Hendricks" id="fullname" value=""
                                            class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md peer border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" />
                                    </div>
                                    <div class="space-y-1"><label
                                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                            for="username">Username</label><input type="text" name="username"
                                            placeholder="ecoholic" id="username" value=""
                                            class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md peer border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" />
                                    </div>
                                    <div class="space-y-1"><label
                                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                            for="email">Email</label><input type="text" name="email"
                                            placeholder="mail@example.com" id="email" value=""
                                            class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md peer border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" />
                                    </div>
                                    <div class="space-y-1"><label
                                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                            for="username">Password</label><input type="password" name="pwd"
                                            placeholder="50uR6O2rJMA64fF%r49Kze&I*@!oY66*" id="pwd" value=""
                                            class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md peer border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" />
                                    </div>
                                    <div class="space-y-1"><label
                                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                            for="username">Username</label><input type="text" name="pwdrepeat"
                                            placeholder="Repeat the password" id="pwdrepeat" value=""
                                            class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md peer border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" />
                                    </div>

                                </div>
                                <div class="flex items-center p-6 pt-0"><button type="button"
                                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-white transition-colors duration-200 rounded-md bg-neutral-950 hover:bg-neutral-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none">Create Account
                                        </button></div>
                            </div>
                        </form>
                        <!-- End Tab Content 1 -->
                </div>

                <div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" class="relative" x-cloak>
                    <!-- Tab Content 2 - Replace with your content -->
                    <div class="border rounded-lg shadow-sm bg-card text-neutral-900">
                        <div class="flex flex-col space-y-1.5 p-6">
                            <h3 class="text-lg font-semibold leading-none tracking-tight">Sign In</h3>
                            <p class="text-sm text-neutral-500">Login to your account.</p>
                        </div>

                        <section class="login-form">
                        <form action="login-handler.php" method="POST">

                        <div class="p-6 pt-0 space-y-2">
                            <div class="space-y-1"><label
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                    for="email">Username/Email</label><input type="text" name="email"
                                    placeholder="mail@example.com" id="email" value=""
                                    class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md peer border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" />
                            </div>
                            <div class="space-y-1"><label
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                    for="username">Password</label><input type="password" name="pwd"
                                    placeholder="50uR6O2rJMA64fF%r49Kze&I*@!oY66*" id="pwd" value=""
                                    class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md peer border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50" />
                            </div>
                        </div>
                        </form>





                        <div class="flex items-center p-6 pt-0"><button type="button"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-white transition-colors duration-200 rounded-md bg-neutral-950 hover:bg-neutral-900 focus:ring-2 focus:ring-offset-2 focus:ring-neutral-900 focus:shadow-outline focus:outline-none">Login
                                </button></div>
                    </div>
                    <!-- End Tab Content 2 -->
                </div>

            </div>
        </div>
    </div>
</body>

</html>