<div>
    <section id="find-place-section">
        <h3 class="subheading text-center">Link your Google business: </h3>
        <p class="text-center mb-4">Enter your business name as it appears on Google</p>
        <input type="text" id="google_business_name" class="w-full p-3 bg-white rounded-lg border-win-lavender focus:border-win-purple focus:ring-purple mb-2" placeholder="Business Name">
        <button id="business-search-btn" type="button" class="bg-win-blue uppercase text-white text-medium px-4 py-1 rounded-lg mb-1 mt-4 lg:mt-6 float-right">
            Submit
        </button>
    </section>

    <section id="results-place-section" class="hidden">
        <h3 class="subheading text-center mb-1">Which business is yours?</h3>
        <p class="text-center mb-4">Select the listing that matches your business</p>

        <div id="place-results-list" class="place-results-list"></div>
        <p id="no-results-msg" class="hidden text-center text-gray-500 my-4">No matching businesses found. Try a different search.</p>

        <div class="md:flex justify-center mt-6">
            <button type="button" id="back-to-search-btn" class="bg-gray-300 uppercase text-gray-800 text-medium px-4 py-1 rounded-lg md:mr-4 mx-auto mb-2 md:mb-0">
                Back
            </button>
            <button id="confirm-place-btn" type="button" disabled class="bg-win-blue uppercase text-white text-medium px-4 py-1 rounded-lg mx-auto opacity-50 cursor-not-allowed">
                Confirm Match
            </button>
        </div>
        <p id="g-place-id" class="hidden"></p>
    </section>
</div>
