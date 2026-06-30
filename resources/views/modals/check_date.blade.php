<div id="modal-check-date" class="hidden flex fixed m-auto inset-x-0 top-16">
    <script src="/assets/js/confetti-bundle.min.js"></script>
    <div class="bg-white shadow-md rounded-lg mx-auto w-[90vw] md:w-[60vw] lg:w-[25vw]">
        <div class="block bg-win-blue py-1 rounded-t-lg text-right"><button class="modal-check-date-toggle text-white mx-3"><i class="fa-solid fa-xmark"></i></button></div>
        <div class="px-8 pb-8 text-center">
            <div id="match-stepper" data-hs-stepper>
                <div class="hidden" data-hs-stepper-nav-item='{
                    "index": 1
                }'>
                    1 Step
                </div>
                <div class="hidden" data-hs-stepper-nav-item='{
                    "index": 2
                }'>
                    2 Step
                </div>
                <div class="hidden" data-hs-stepper-nav-item='{
                    "index": 3
                }'>
                    3 Step
                </div>

                <div data-hs-stepper-content-item='{
                    "index": 1
                }' style="display: none;">
                    <div class="w-full min-h-[30vh]">
                        <p class="subheading mt-2">Check your wedding date:</p>
                        @if(!$wedding_date_available)
                            <div class="top-[4vh] relative py-8 px-4 items-center text-center rounded-xl bg-grey-50">
                                <i class="fa-solid fa-triangle-exclamation text-yellow text-4xl"></i>
                                <p class="font-semibold"> This vendor is already booked on your wedding date. Would you like to continue anyway?</p>
                            </div>
                        @else
                            <div id="check_availability" class="top-[4vh] relative py-8 px-4 items-center text-center rounded-xl bg-grey-50">
                                <i class="fa-regular fa-circle-check text-win-purple text-4xl"></i>
                                <p class="font-semibold">Congratulations! This vendor is available on your wedding date.</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div data-hs-stepper-content-item='{
                    "index": 2
                }' style="display: none;">
                    <div class="w-full min-h-[30vh]">
                        <p class="subheading mt-2">Check vendor pricing:</p>
                        <div id="check_price" class="top-[4vh] relative py-8 px-4 items-center text-center rounded-xl bg-grey-50">
                            <p class="font-semibold">This vendor's average package price is</p>
                            <p class="font-semibold text-xl lg:text-3xl my-2">{{ $vendor->preferredPricing() }}</p>
                            <p class="font-semibold">Would you like to continue?</p>
                        </div>
                    </div>
                </div>
                <div data-hs-stepper-content-item='{
                    "index": 3
                }' style="display: none;">
                    <div class="w-full min-h-[30vh]">
                        <p class="subheading mt-2">Congratulations!</p>
                        <div class="top-[4vh] py-8 px-4 items-center text-center rounded-xl bg-grey-50 mt-4">
                            <p class="subheading">It's a match!</p>
                            <i class="fas fa-heart text-3xl" style="color: #df0c16;"></i>
                            <p class="font-semibold">Take the first step toward your ideal vendor match - send your profile!</p>
                            <p class="font-semibold">Ready to send your profile?</p>
                        </div>
                        <div class="mt-4 relative">
                            <button type="button" id="match-stepper-send" class="bg-win-blue uppercase text-white font-medium px-4 py-1 rounded-lg mx-1 outline-none focus:outline-none mb-1 ml-auto block">
                                <i class="fa-solid fa-user mr-1"></i> Send Profile 
                            </button>
                            <button type="button" id="match-stepper-request-consultation" class="modal-check-date-toggle bg-win-blue uppercase text-white font-medium mx-1 px-4 py-1 rounded-lg outline-none focus:outline-none mb-1 ml-auto block" aria-haspopup="dialog" aria-expanded="false" aria-controls="request-consultation-modal" data-hs-overlay="#request-consultation-modal">
                                <i class="fa-solid fa-calendar-check mr-1"></i> Request Consultation 
                            </button>

                        </div>
                    </div>
                </div>
                <div data-hs-stepper-content-item='{
                    "isFinal": true
                }' style="display: none;">
                    <div class="w-full min-h-[30vh]">
                        <p class="subheading mt-2">Congratulations!</p>
                        <div class="top-[4vh] relative py-8 px-4 items-center text-center rounded-xl bg-grey-50">
                            <p class="subheading">It's a match!</p>
                            <i class="fas fa-heart text-3xl" style="color: #df0c16;"></i>
                            <p class="font-semibold">Take the first step toward your ideal vendor match - send your profile!</p>
                            <p class="font-semibold">Ready to send your profile?</p>
                        </div>
                    </div>
                </div>

                <button class="hidden" type="button" data-hs-stepper-back-btn>
                    Back
                </button>
                <button type="button" data-hs-stepper-skip-btn style="display: none;">
                    Skip
                </button>
                <button type="button" id="match-stepper-next" class="bg-win-blue uppercase text-white font-medium px-4 py-1 rounded-lg outline-none focus:outline-none mb-1 ease-linear transition-all duration-150 mt-4 lg:mt-6 float-right" data-hs-stepper-next-btn>
                    Next
                </button>
            </div>
        </div>
    </div>
</div>