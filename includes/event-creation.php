<?php
session_start();
include_once 'dbh.inc.php'; // Database connection

if (isset($_SESSION['usersid'])) {
    $user_id = $_SESSION['usersid'];
} else {
    // Handle error or redirect to login
    header("Location: ../pages/login/login.php");
    exit();
}

$organizer_id = $_SESSION['usersid']; // Organizer ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_datetime = $_POST['event_datetime'];
    $event_location = $_POST['event_location'];
    $event_thumbnail = $_POST['event_thumbnail']; // Assuming a URL or path is provided

    $sql = "INSERT INTO Events (organizer_id, event_name, event_description, event_datetime, event_location, event_thumbnail) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "isssss", $organizer_id, $event_name, $event_description, $event_datetime, $event_location, $event_thumbnail);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: event-list.php"); // Redirect after successful event creation
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }

    mysqli_stmt_close($stmt);
}
?>

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
        <div x-data="{ modalOpen: false }" @keydown.escape.window="modalOpen = false" :class="{ 'z-40': modalOpen }"
            class="relative w-auto h-auto">
            <button @click="modalOpen=true"
                class="inline-flex items-center justify-center h-10 px-4 py-2 text-sm font-medium transition-colors bg-neutral-800 text-white border rounded-md hover:bg-neutral-700 active:bg-neutral-800 focus:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-neutral-600/60 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none">Create
                Event</button>
            <template x-teleport="body">
                <div x-show="modalOpen"
                    class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen" x-cloak>
                    <div x-show="modalOpen" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-300" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" @click="modalOpen=false"
                        class="absolute inset-0 w-full h-full bg-black bg-opacity-50 backdrop-blur-sm"></div>
                    <div x-show="modalOpen" x-trap.inert.noscroll="modalOpen" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-90"
                        class="relative w-full py-6 bg-neutral-900 text-neutral-100 shadow-md px-7 bg-opacity-90 drop-shadow-md backdrop-blur-sm sm:max-w-lg sm:rounded-lg">
                        <div class="flex items-center justify-between pb-3">
                            <h3 class="text-lg font-semibold">Create New Event</h3>
                            <button @click="modalOpen=false"
                                class="absolute top-0 right-0 flex items-center justify-center w-8 h-8 mt-5 mr-5 text-neutral-400 rounded-full hover:text-neutral-200 hover:bg-neutral-800">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative w-auto pb-8">
                            <form method="POST" action="event-creation-handler.php" enctype="multipart/form-data">

                                <label for="event_name" class="block mb-2 text-sm font-medium text-neutral-100">Event
                                    Name</label>
                                <input type="text" id="event_name" name="event_name"
                                    class="w-full px-4 py-2 mb-4 border border-neutral-700 bg-neutral-800 rounded-md focus:ring focus:outline-none text-neutral-100"
                                    required>

                                <label for="event_description"
                                    class="block mb-2 text-sm font-medium text-neutral-100">Event Description</label>
                                <textarea id="event_description" name="event_description" rows="4"
                                    class="w-full px-4 py-2 mb-4 border border-neutral-700 bg-neutral-800 rounded-md focus:ring focus:outline-none text-neutral-100"
                                    required></textarea>


                                <div x-data="{
datePickerOpen: false,
datePickerValue: '',
datePickerFormat: 'M d, Y',
datePickerMonth: '',
datePickerYear: '',
datePickerDay: '',
datePickerDaysInMonth: [],
datePickerBlankDaysInMonth: [],
datePickerMonthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
datePickerDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
datePickerDayClicked(day) {
let selectedDate = new Date(this.datePickerYear, this.datePickerMonth, day);
this.datePickerDay = day;
this.datePickerValue = this.datePickerFormatDate(selectedDate);
this.datePickerIsSelectedDate(day);
this.datePickerOpen = false;
},
datePickerPreviousMonth(){
if (this.datePickerMonth == 0) {
this.datePickerYear--;
this.datePickerMonth = 12;
}
this.datePickerMonth--;
this.datePickerCalculateDays();
},
datePickerNextMonth(){
if (this.datePickerMonth == 11) {
this.datePickerMonth = 0;
this.datePickerYear++;
} else {
this.datePickerMonth++;
}
this.datePickerCalculateDays();
},
datePickerIsSelectedDate(day) {
const d = new Date(this.datePickerYear, this.datePickerMonth, day);
return this.datePickerValue === this.datePickerFormatDate(d) ? true : false;
},
datePickerIsToday(day) {
const today = new Date();
const d = new Date(this.datePickerYear, this.datePickerMonth, day);
return today.toDateString() === d.toDateString() ? true : false;
},
datePickerCalculateDays() {
let daysInMonth = new Date(this.datePickerYear, this.datePickerMonth + 1, 0).getDate();
// find where to start calendar day of week
let dayOfWeek = new Date(this.datePickerYear, this.datePickerMonth).getDay();
let blankdaysArray = [];
for (var i = 1; i <= dayOfWeek; i++) {
blankdaysArray.push(i);
}
let daysArray = [];
for (var i = 1; i <= daysInMonth; i++) {
daysArray.push(i);
}
this.datePickerBlankDaysInMonth = blankdaysArray;
this.datePickerDaysInMonth = daysArray;
},
datePickerFormatDate(date) {
let formattedDay = this.datePickerDays[date.getDay()];
let formattedDate = ('0' + date.getDate()).slice(-2); // appends 0 (zero) in single digit date
let formattedMonth = this.datePickerMonthNames[date.getMonth()];
let formattedMonthShortName = this.datePickerMonthNames[date.getMonth()].substring(0, 3);
let formattedMonthInNumber = ('0' + (parseInt(date.getMonth()) + 1)).slice(-2);
let formattedYear = date.getFullYear();
if (this.datePickerFormat === 'M d, Y') {
return `${formattedMonthShortName} ${formattedDate}, ${formattedYear}`;
}
if (this.datePickerFormat === 'MM-DD-YYYY') {
return `${formattedMonthInNumber}-${formattedDate}-${formattedYear}`;
}
if (this.datePickerFormat === 'DD-MM-YYYY') {
return `${formattedDate}-${formattedMonthInNumber}-${formattedYear}`;
}
if (this.datePickerFormat === 'YYYY-MM-DD') {
return `${formattedYear}-${formattedMonthInNumber}-${formattedDate}`;
}
if (this.datePickerFormat === 'D d M, Y') {
return `${formattedDay} ${formattedDate} ${formattedMonthShortName} ${formattedYear}`;
}
return `${formattedMonth} ${formattedDate}, ${formattedYear}`;
},
}" x-init="
currentDate = new Date();
if (datePickerValue) {
currentDate = new Date(Date.parse(datePickerValue));
}
datePickerMonth = currentDate.getMonth();
datePickerYear = currentDate.getFullYear();
datePickerDay = currentDate.getDay();
datePickerValue = datePickerFormatDate( currentDate );
datePickerCalculateDays();
" x-cloak>
                                    <div class="container px-4 py-2 mx-auto md:py-10">
                                        <div class="w-full mb-5">
                                            <label for="event_datetime"
                                                class="block mb-1 text-sm font-medium text-neutral-500">Event Date &
                                                Time</label>
                                            <div class="relative w-[17rem]">
                                                <input x-ref="datePickerInput" type="text" id="event_datetime"
                                                    name="event_datetime" placeholderr="" required
                                                    @click="datePickerOpen=!datePickerOpen" x-model="datePickerValue"
                                                    x-on:keydown.escape="datePickerOpen=false"
                                                    class="flex w-full h-10 px-3 py-2 text-sm bg-white border rounded-md text-neutral-600 border-neutral-300 ring-offset-background placeholder:text-neutral-400 focus:border-neutral-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-400 disabled:cursor-not-allowed disabled:opacity-50"
                                                    placeholder="Select date" readonly />
                                                <div @click="datePickerOpen=!datePickerOpen; if(datePickerOpen){ $refs.datePickerInput.focus() }"
                                                    class="absolute top-0 right-0 px-3 py-2 cursor-pointer text-neutral-400 hover:text-neutral-500">
                                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <div x-show="datePickerOpen" x-transition
                                                    @click.away="datePickerOpen = false"
                                                    class="absolute top-0 left-0 max-w-lg p-4 mt-12 antialiased bg-white border rounded-lg shadow w-[17rem] border-neutral-200/70">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <div>
                                                            <span x-text="datePickerMonthNames[datePickerMonth]"
                                                                class="text-lg font-bold text-gray-800"></span>
                                                            <span x-text="datePickerYear"
                                                                class="ml-1 text-lg font-normal text-gray-600"></span>
                                                        </div>
                                                        <div>
                                                            <button @click="datePickerPreviousMonth()" type="button"
                                                                class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-gray-100">
                                                                <svg class="inline-flex w-6 h-6 text-gray-400"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M15 19l-7-7 7-7" />
                                                                </svg>
                                                            </button>
                                                            <button @click="datePickerNextMonth()" type="button"
                                                                class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer focus:outline-none focus:shadow-outline hover:bg-gray-100">
                                                                <svg class="inline-flex w-6 h-6 text-gray-400"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-7 mb-3">
                                                        <template x-for="(day, index) in datePickerDays" :key="index">
                                                            <div class="px-0.5">
                                                                <div x-text="day"
                                                                    class="text-xs font-medium text-center text-gray-800">
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                    <div class="grid grid-cols-7">
                                                        <template x-for="blankDay in datePickerBlankDaysInMonth">
                                                            <div
                                                                class="p-1 text-sm text-center border border-transparent">
                                                            </div>
                                                        </template>
                                                        <template x-for="(day, dayIndex) in datePickerDaysInMonth"
                                                            :key="dayIndex">
                                                            <div class="px-0.5 mb-1 aspect-square">
                                                                <div x-text="day" @click="datePickerDayClicked(day)"
                                                                    :class="{
'bg-neutral-200': datePickerIsToday(day) == true,
'text-gray-600 hover:bg-neutral-200': datePickerIsToday(day) == false && datePickerIsSelectedDate(day) == false,
'bg-neutral-800 text-white hover:bg-opacity-75': datePickerIsSelectedDate(day) == true
}" class="flex items-center justify-center text-sm leading-none text-center rounded-full cursor-pointer h-7 w-7"></div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label for="event_location"
                                    class="block mb-2 text-sm font-medium text-neutral-100">Event Location</label>
                                <input type="text" id="event_location" name="event_location"
                                    class="w-full px-4 py-2 mb-4 border border-neutral-700 bg-neutral-800 rounded-md focus:ring focus:outline-none text-neutral-100"
                                    required>

                                <label for="event_thumbnail"
                                    class="block mb-2 text-sm font-medium text-neutral-100">Event Thumbnail</label>
                                <input type="file" id="event_thumbnail" name="event_thumbnail"
                                    class="w-full px-4 py-2 mb-4 border border-neutral-700 bg-neutral-800 rounded-md focus:ring focus:outline-none text-neutral-100"
                                    accept="image/*">

                                <div class="flex flex-col-reverse sm:flex-row sm:justify-between sm:space-x-2">
                                    <button @click="modalOpen=false" type="button"
                                        class="inline-flex items-center justify-center h-10 px-4 py-2 text-sm font-medium transition-colors border rounded-md focus:outline-none focus:ring-2 focus:ring-neutral-800 focus:ring-offset-2 text-neutral-100 border-neutral-600">Cancel</button>
                                    <button type="submit"
                                        class="inline-flex items-center justify-center h-10 px-4 py-2 text-sm font-medium text-white transition-colors border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-neutral-900 focus:ring-offset-2 bg-neutral-950 hover:bg-neutral-900">Create
                                        Event</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</body>

</html>