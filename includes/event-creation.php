

<!-- Modal Background -->
<div id="createEventModal" class="fixed inset-0 bg-gray-900 bg-opacity-80 flex justify-center items-center z-50 hidden">
  <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-lg">
    <div class="px-6 py-4">
      <h2 class="text-lg font-semibold mb-4 text-white">Create Event</h2>

        <form method="POST" action="event-creation-handler.php" enctype="multipart/form-data">

            <!-- Event Name -->
            <div class="mb-4">
                <label for="event_name" class="block text-gray-300">Event Name</label>
                <input type="text" id="event_name" name="event_name" class="w-full border border-gray-600 rounded-lg px-3 py-2 mt-1 bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <!-- Event Description -->
            <div class="mb-4">
                <label for="event_description" class="block text-gray-300">Event Description</label>
                <textarea id="event_description" name="event_description" class="w-full border border-gray-600 rounded-lg px-3 py-2 mt-1 bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-400"></textarea>
            </div>

            <!-- Event Date -->
            <div class="mb-4">
                <label for="event_datetime" class="block text-gray-300">Event Date</label>
                <input type="date" id="event_datetime" name="event_datetime" class="w-full border border-gray-600 rounded-lg px-3 py-2 mt-1 bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-400" style="color-scheme: dark;">
            </div>

            <!-- Event Location -->
            <div class="mb-4">
                <label for="event_location" class="block text-gray-300">Event Location</label>
                <input type="text" id="event_location" name="event_location" class="w-full border border-gray-600 rounded-lg px-3 py-2 mt-1 bg-gray-700 text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>

            <!-- Event Thumbnail -->
            <div id="drop-area" class="mb-4">
                <label for="event_thumbnail" class="block text-gray-300">Event Thumbnail</label>
                <div class="border-2 border-dashed border-gray-600 p-4 text-center bg-gray-700 text-gray-300 hover:border-indigo-400">
                <p id="thumbnailDropArea" class="text-gray-400">Drag & drop or click to upload</p>
                <p id="thumbnailText" class="text-gray-400">No file chosen</p>
                <input type="file" id="thumbnailInput" class="hidden" accept="image/*" name="event_thumbnail">
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-2">
                <button id="closeModal" type="reset" class="px-4 py-2 bg-gray-600 text-gray-300 rounded-lg">Cancel</button>
                <button id="submitEvent" type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded-lg">Create</button>
            </div>

        </form>
    </div>
  </div>
</div>

<!-- Trigger button -->
<button id="openModal" class="px-4 py-2 bg-indigo-500 text-white rounded-lg">Create Event</button>

<!-- JavaScript -->
<script>
  const openModalBtn = document.getElementById('openModal');
  const closeModalBtn = document.getElementById('closeModal');
  const modal = document.getElementById('createEventModal');
  const thumbnailDropArea = document.getElementById('thumbnailDropArea');
  const thumbnailInput = document.getElementById('thumbnailInput');
  const thumbnailText = document.getElementById('thumbnailText');

  openModalBtn.addEventListener('click', () => {
    modal.classList.remove('hidden');
  });

  closeModalBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
  });

  thumbnailDropArea.addEventListener('click', () => {
    thumbnailInput.click();
  });

  thumbnailInput.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
      thumbnailText.textContent = file.name;
    }
  });

  thumbnailDropArea.addEventListener('dragover', (event) => {
    event.preventDefault();
    thumbnailDropArea.classList.add('border-indigo-400');
  });

  thumbnailDropArea.addEventListener('dragleave', () => {
    thumbnailDropArea.classList.remove('border-indigo-400');
  });

  thumbnailDropArea.addEventListener('drop', (event) => {
    event.preventDefault();
    const file = event.dataTransfer.files[0];
    if (file) {
      thumbnailInput.files = event.dataTransfer.files;
      thumbnailText.textContent = file.name;
    }
  });
</script>