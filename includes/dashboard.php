<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
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

<body>
  <?php include "header.php" ?>
  <?php showSubheader(true); ?>
  <div class="flex items-start justify-center h-full bg-gradient-to-br from-gray-900 via-black to-gray-800">
    <div class="flex items-center justify-center w-full max-w-full">
    <div class="bg-gray-900 text-white p-6 rounded-lg shadow-lg flex flex-col md:flex-row items-center md:items-start my-4">
    <!-- Thumbnail -->
    <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
        <img src="image.png" alt="Event Thumbnail" class="w-32 h-32 object-cover rounded-lg">
    </div>
    <!-- Event Info -->
    <div class="flex-grow">
        <h2 class="text-2xl font-bold mb-2"><?php echo $eventName; ?></h2>
        <p class="text-sm text-gray-400 mb-4"><?php echo $location; ?></p>
        <p class="mb-4"><?php echo $description; ?></p>
        <div class="text-sm text-gray-500">
            <span class="inline-block bg-gray-700 py-1 px-3 rounded-full"><?php echo $eventDate; ?></span>
        </div>
    </div>
</div>

    </div>
  </div>
</body>