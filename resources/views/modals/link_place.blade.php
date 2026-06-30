<div>
    <section id="find-place-section">
        <h3 class="subheading text-center">Link your Google business: </h3>
        <p class="text-center mb-4">Enter your business name as it appears on Google</p>
        <input type="text" id="google_business_name" class="w-full p-3 bg-white rounded-lg border-win-lavender focus:border-win-purple focus:ring-purple mb-2" placeholder="Business Name">
        <button id="business-search-btn" type="button" class="bg-win-blue uppercase text-white text-medium px-4 py-1 rounded-lg mb-1 mt-4 lg:mt-6 float-right">
            Submit
        </button>
    </section>
    <section id="confirm-place-section" class="hidden text-center">
        <h3 class="subheading mb-4">Is this your business?</h3>
        <p class="font-semibold text-lg mb-2">Business Name:</p>
        <p id="place-name" class="mb-2"></p>
        <p class="font-semibold text-lg mb-2">Link:</p>
        <a id="place-link" href="#" target="_blank" class="underline text-win-blue">View Google Place</a>
        <div id="place-location-section" class="hidden mt-2">
            <p class="font-semibold text-lg mb-2">Location:</p>
            <p id="place-location" class="mb-4"></p>
        </div>
        <p id="g-place-id" class="hidden"></p>
        <div class="md:flex justify-center mt-6">
            <button type="button" aria-label="Close" data-hs-overlay="#link-google-place-modal" class="bg-win-red uppercase text-white text-medium px-4 py-1 rounded-lg md:mr-4 mx-auto">
                Close
            </button>
            <button id="confirm-place-btn" type="button" class="bg-win-blue uppercase text-white text-medium px-4 py-1 rounded-lg mx-auto">
                Confirm
            </button>
    </section>
</div>