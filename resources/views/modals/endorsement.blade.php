<div>
    <div class="px-4 pb-8 text-center">
        <x-avatar :model="$vendor" class="rounded-full border-none w-16 h-16 md:w-32 md:h-32 mx-auto relative z-10 endorsement-modal-avatar" />
        <p class="text-center mb-2">What do you like about working with:</p>
        <p class="subheading text-center mb-4">{{ $vendor->business_name }}</p>
        @php($endorsements = Auth::guard('vendor')->user()->endorsementsFor($vendor)->get())
        <div class="grid lg:grid-cols-2 mx-auto lg:gap-2">
            <label class="flex p-3 w-full bg-win-lavender rounded-lg focus:border-win-lavender focus:ring-lavender mb-2">
                <span class="font-semibold text-black">Responsive</span>
                <input @checked($endorsements->contains('type', 1)) type="checkbox" name="endorsements[]" value="1" class="shrink-0 ms-auto mt-0.5 rounded text-win-lavender checked:bg-win-lavender focus:ring-lavender disabled:opacity-50 disabled:pointer-events-none">
            </label>
            <label class="flex p-3 w-full bg-win-lavender rounded-lg text-win-lavender focus:border-win-lavender focus:ring-lavender mb-2">
                <span class="font-semibold text-black">Professional</span>
                <input @checked($endorsements->contains('type', 2)) type="checkbox" name="endorsements[]" value="2" class="shrink-0 ms-auto mt-0.5 rounded text-win-lavender checked:bg-win-lavender focus:ring-lavender disabled:opacity-50 disabled:pointer-events-none">
            </label>
            <label class="flex p-3 w-full bg-win-lavender rounded-lg text-win-lavender focus:border-win-lavender focus:ring-lavender mb-2">
                <span class="font-semibold text-black">Communicative</span>
                <input @checked($endorsements->contains('type', 3)) type="checkbox" name="endorsements[]" value="3" class="shrink-0 ms-auto mt-0.5 rounded text-win-lavender checked:bg-win-lavender focus:ring-lavender disabled:opacity-50 disabled:pointer-events-none">
            </label>
            <label class="flex p-3 w-full bg-win-lavender rounded-lg text-win-lavender focus:border-win-lavender focus:ring-lavender mb-2">
                <span class="font-semibold text-black">Creative</span>
                <input @checked($endorsements->contains('type', 4)) type="checkbox" name="endorsements[]" value="4" class="shrink-0 ms-auto mt-0.5 rounded text-win-lavender checked:bg-win-lavender focus:ring-lavender disabled:opacity-50 disabled:pointer-events-none">
            </label>
            <label class="flex p-3 w-full bg-win-lavender rounded-lg text-win-lavender focus:border-win-lavender focus:ring-lavender mb-2">
                <span class="font-semibold text-black">Resourceful</span>
                <input @checked($endorsements->contains('type', 5)) type="checkbox" name="endorsements[]" value="5" class="shrink-0 ms-auto mt-0.5 rounded text-win-lavender checked:bg-win-lavender focus:ring-lavender disabled:opacity-50 disabled:pointer-events-none">
            </label>
            <label class="flex p-3 w-full bg-win-lavender rounded-lg text-win-lavender focus:border-win-lavender focus:ring-lavender mb-2">
                <span class="font-semibold text-black">Personable</span>
                <input @checked($endorsements->contains('type', 6)) type="checkbox" name="endorsements[]" value="6" class="shrink-0 ms-auto mt-0.5 rounded text-win-lavender checked:bg-win-lavender focus:ring-lavender disabled:opacity-50 disabled:pointer-events-none">
            </label>
        </div>
        
        <button id="endorse-btn" data-vendor-uuid="{{ $vendor->uuid }}" type="button" class="bg-win-blue uppercase text-white text-medium px-4 py-1 rounded-lg mb-1 mt-4 lg:mt-6 float-right">
            Submit
        </button>
    </div>
</div>