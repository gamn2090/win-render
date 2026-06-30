<div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <div class="px-4 pb-8 text-center">
        <p class="subheading">Select a consultation date:</p>
        <input id="reserve_date" name="reserve_date"
            class="hidden mx-auto"
            placeholder="Select a date" />
        <div id="consultation-calendar-display" class="flex justify-center">

        </div>
        
        <button id="request-consultation-btn" data-vendor-id="{{ $vendor->id }}" type="button" class="bg-win-blue uppercase text-white text-medium px-4 py-1 rounded-lg mb-1 mt-4 lg:mt-6 float-right">
            Request Consultation
        </button>
    </div>
    <script>
        $("#reserve_date").flatpickr({
            disable: [],
            dateFormat: "Y-m-d H:i",
            inline: true,
            enableTime: true,
            minDate: "{{ \Carbon\Carbon::today()->format('Y-m-d') }}",
            position: "below center",
            appendTo: document.getElementById("consultation-calendar-display")
        });
    </script>
</div>