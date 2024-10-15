<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/lucide-static@0.321.0/font/lucide.min.css" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'dark': '#1a1a1a',
                    'dark-light': '#2a2a2a',
                }
            }
        }
    }
    </script>
</head>

<body class="min-h-screen bg-gray-900 text-gray-100 flex flex-col">
    <header class="bg-gray-800 py-4">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl font-bold">Sample Event</h1>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="mb-4 overflow-x-auto">
            <div class="flex space-x-2 md:space-x-4">
                <button
                    class="tab-button py-2 px-3 md:px-4 text-sm font-medium text-center whitespace-nowrap text-blue-500 border-b-2 border-blue-500"
                    data-tab="event-details">Event Details</button>
                <button
                    class="tab-button py-2 px-3 md:px-4 text-sm font-medium text-center whitespace-nowrap text-gray-400 hover:text-gray-200"
                    data-tab="applied-users">Applied Users</button>
                <button
                    class="tab-button py-2 px-3 md:px-4 text-sm font-medium text-center whitespace-nowrap text-gray-400 hover:text-gray-200"
                    data-tab="approved-users">Approved Users</button>
                <button
                    class="tab-button py-2 px-3 md:px-4 text-sm font-medium text-center whitespace-nowrap text-gray-400 hover:text-gray-200"
                    data-tab="declined-users">Declined Users</button>
            </div>
        </div>

        <div class="mt-6">
            <div id="event-details" class="tab-content">
                <div class="space-y-6">
                    <div
                        class="aspect-video bg-gray-800 rounded-lg flex items-center justify-center mx-auto w-full md:w-3/4 lg:w-1/2">
                        <i class="lucide-calendar h-16 w-16 md:h-14 md:w-14 lg:h-12 lg:w-12 text-gray-600"></i>
                    </div>

                    


                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold">About the Event</h2>
                        <p class="text-gray-400">This is a sample event description.</p>
                    </div>
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold">Event Details</h2>
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2 text-gray-400">
                                <i class="lucide-calendar h-5 w-5"></i>
                                <span>October 15, 2024, 2:00 PM</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-400">
                                <i class="lucide-map-pin h-5 w-5"></i>
                                <span>Sample Location</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-400">
                                <i class="lucide-user h-5 w-5"></i>
                                <span>Organizer ID: 123</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button id="register-btn"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Register for the Event
                        </button>
                        <p id="registration-message" class="text-green-400 mt-2 hidden"></p>
                    </div>
                </div>
            </div>

            <div id="applied-users" class="tab-content hidden">
                <h2 class="text-xl font-semibold mb-4">Applied Users</h2>
                <p class="text-gray-400">List of users who have applied for this event will be displayed here.</p>
            </div>

            <div id="approved-users" class="tab-content hidden">
                <h2 class="text-xl font-semibold mb-4">Approved Users</h2>
                <p class="text-gray-400">List of users approved for this event will be displayed here.</p>
            </div>

            <div id="declined-users" class="tab-content hidden">
                <h2 class="text-xl font-semibold mb-4">Declined Users</h2>
                <p class="text-gray-400">List of users declined for this event will be displayed here.</p>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 py-4 mt-8">
        <div class="container mx-auto px-4 text-center text-gray-400">
            <p>&copy; <span id="current-year"></span> Volhub. All rights reserved.</p>
        </div>
    </footer>

    <script>
    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');

            tabButtons.forEach(btn => btn.classList.remove('text-blue-500', 'border-b-2',
                'border-blue-500'));
            tabContents.forEach(content => content.classList.add('hidden'));

            button.classList.add('text-blue-500', 'border-b-2', 'border-blue-500');
            document.getElementById(tabId).classList.remove('hidden');
        });
    });

    // Registration functionality
    const registerBtn = document.getElementById('register-btn');
    const registrationMessage = document.getElementById('registration-message');

    registerBtn.addEventListener('click', () => {
        registerBtn.disabled = true;
        registerBtn.textContent = 'Registering...';

        setTimeout(() => {
            registerBtn.classList.add('hidden');
            registrationMessage.textContent =
                "Thank you for registering! We'll be in touch soon with more details.";
            registrationMessage.classList.remove('hidden');
        }, 1500);
    });

    // Set current year in footer
    document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>
</body>

</html>