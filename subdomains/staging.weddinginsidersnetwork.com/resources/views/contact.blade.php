<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>The Network For Wedding Businesses + Couples - Join WIN</title>
    <meta name="description" content="A network of wedding businesses. Providing couples with exclusive access to top-rated wedding vendors, more savings and less emails to sift through."/>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
</head>
@include('layouts.guest_navigation')

<body class="antialiased overflow-x-hidden bg-white">
    
    <div class="min-w-screen" style="background-image:url('/assets/img/join-us/wedding-businesses-1.jpg'); background-size:cover; background-position: top;">
        <div class="bg-dark-grey-win bg-opacity-25 h-full mx-auto flex justify-center">
            <div class="text-center mx-auto lg:max-w-[50%] min-h-[80vh] flex inline-flex items-center justify-center">
                <div class="">
                <h1 class="subheading text-white my-auto">Wedding Businesses + Couples</h1>
                <p class="headline-large text-white mt-4">WIN IS Now Available in New England, New York + New Jersey</p>
                <p class="subheading text-white">Providing you with exclusive access to top-rated wedding vendors, more savings and less emails to sift through. All of the vendor recommendations, contact lists and reviews are here with the possibility of a cash savings of up to $250 per service. The doors are open in the Northeast and we’ll be expanding to your region soon. Featured by merit, founded to support artistry and true collaboration that benefits couples and vendors.
                </p>
                <div class="my-6 min-w-full flex items-center">
                    <a href="/user/register" class="mx-auto py-2 px-10 font-semibold button-text rounded-full bg-win-blue text-white disabled:opacity-50 disabled:pointer-events-none">
                        JOIN OUR NETWORK
                    </a>
                </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="min-w-[100vw] pt-24 pb-6 text-center relative z-20">
                <h2 class="headline-small text-win-red">The Right Vendors Are <span class="italic">Right This Way</span></h2>
                <p class="subheading">Free for couples, and just one flat monthly payment for vendors.
                </p>
            </div>
        <div class="min-w-[100vw] py-12 relative z-20">
            <div class="grid grid-cols-2 gap-16 lg:max-w-[50%] mx-auto">
                <div class="text-center">
                    <img class="rounded-tl-3xl mb-4 mx-auto lg:max-h-[40vh]" src="/assets/img/home/vendors-for-wedding(s)-2.jpg">
                    <div class="my-6 min-w-full flex items-center">
                        <a href="/user/register" class="mx-auto py-2 px-10 font-semibold rounded-full bg-win-purple text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                            I'M GETTING MARRIED
                        </a>
                    </div>
                </div>
                <div class="text-center">
                    <img class="rounded-tl-3xl mb-4 mx-auto lg:max-h-[40vh]" src="/assets/img/home/vendors-for-wedding(s)-3.jpg">
                    <div class="my-6 min-w-full flex items-center">
                        <a href="/vendor/register" class="mx-auto py-2 px-10 font-semibold rounded-full bg-win-blue text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                            I'M A WEDDING VENDOR
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative min-h-[2vh]"> </div>
        <!-- FAQ -->
        <div class="max-w-[85rem] lg:max-w-[50%] px-4 pt-6 pb-10 sm:px-6 lg:px-8 mx-auto bg-win-light rounded-3xl relative z-50 mb-12">
            <!-- Title -->
            <div class="max-w-5xl mx-auto text-center mb-10 lg:mb-14 mt-8">
                <h2 class="text-win-red headline-small">Frequently Asked Questions</h2>
            </div>
            <!-- End Title -->

            <div class="max-w-5xl mx-auto">
                <!-- Accordion -->
                <div class="hs-accordion-group">
                    <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6 active" id="hs-basic-with-title-and-arrow-stretched-heading-one">
                        <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-gray-800 rounded-lg transition hover:text-gray-500" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-one">
                        How is WIN different from other wedding vendor lists?
                            <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                            <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                        <div id="hs-basic-with-title-and-arrow-stretched-collapse-one" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-one">
                            <p class="text-gray-800">
                            Most importantly, WIN is merit-based. This means showcasing the top-rated and most engaged businesses, as opposed to the vendor who is disengaged, or simply paying the highest rate to be shown on page one. Truth be told, many wedding directories are designed to promote the businesses that pay the most. Even venues often use this as a way to create profits, featuring companies who are paying to be promoted. We’re flipping that system upside down to foster an algorithm of artistry and transparency. The optional cash incentives (more on that below) only serve to keep couples here, which keeps vendors in business. It’s a win-win.                            </p>
                        </div>
                    </div>

                    <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-gray-800 rounded-lg transition hover:text-gray-500" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                        The cash incentive — can you explain more about that?
                            <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                            <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                        <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <p class="text-gray-800">
                            Every vendor is encouraged to offer preferred pricing of up to $250 off. With these potential savings on each service, couples can maximize their wedding budget. This is not required, though, and is up to the discretion of each vendor. WIN vendors are typically willing to offer this because it means working with the right people — which fuels creativity, publication opportunities and future work potential. And because WIN only charges a small, flat-rate fee for wedding businesses, that extra cost for a sure booking still falls well below typical spending for other marketing platforms. In short, we suggest passing the savings onto you, but this is up to the discretion of each individual vendor and their business model.                            </p>
                        </div>
                    </div>

                    <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-gray-800 rounded-lg transition hover:text-gray-500" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                        Are there any kickbacks?
                            <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                            <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                        <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <p class="text-gray-800">
                            Vendors don’t make any money for referring others. Instead, they gain a boost in rankings as a thank you when they invite peers and add to their preferred vendor list. This simply promotes our core philosophy that a “rising tide lifts all boats.” WIN is better when vendors are willing to invite their peers, and increase the opportunities on this platform.                            </p>
                        </div>
                    </div>

                    <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-gray-800 rounded-lg transition hover:text-gray-500" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                        Who built the Wedding Insiders Network?
                            <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                            <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                        <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <p class="text-gray-800">
                            WIN was designed by vendors to eliminate the kickbacks, cliques and pay-to-play models to instead even out the playing field. The process of booking weddings for couples and vendors alike was unnecessarily antiquated — full of far too many emails, unclear pricing, ambiguous availability and big-box platforms that simply put the highest-paying business at the top of the site instead of the best-suited vendor for your vision. The process of finding wedding vendors on WIN is built around transparency and talent.                            </p>
                        </div>
                    </div>

                    <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-gray-800 rounded-lg transition hover:text-gray-500" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                        Is WIN a referral program too?
                            <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                            <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                        <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <p class="text-gray-800">
                            Yes! Because we help wedding vendors share and receive leads, you can easily describe WIN as a wedding vendor referral program. Keep in mind, though, that we’re less about who’s-who and more about the honed-in impact of a network that facilitates community, lead sharing and respect.                            </p>
                        </div>
                    </div>

                    <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-gray-800 rounded-lg transition hover:text-gray-500" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                        I’m a vendor. If I can’t pay to rank higher, what can I do to increase visibility?
                            <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                            <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                        <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <p class="text-gray-800">
                            We have a simple and transparent metric system designed to help couples connect with top-rated vendors. The main factors are displayed on your dashboard so you can see how you’re ranking and know what to do to improve. Key elements are response time, views from couples, reviews and the number of invites you’ve sent to couples and peers. Essentially, if you’re an active participant with strong reviews who communicates in a timely manner, you’ll benefit from better rankings on our directory.                             </p>
                        </div>
                    </div>
                </div>
                <!-- End Accordion -->
            </div>
        </div>
    <div class="max-w-screen overflow-hidden bg-win-light p-24 lg:pt-32">
        <div class="absolute h-64 w-64 right-[20vw] z-[0] hidden" style="background-image: url('/assets/img/shapes/confetti-small.PNG'); background-size:cover;">
        </div>
        <h2 class="headline-small text-center mb-8 relative z-[10]">Have A Question? Submit Yours Here.</h2>
        <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/embed/v2.js"></script>
<script>
  hbspt.forms.create({
    portalId: "47914874",
    formId: "e1adba8f-81fa-4e63-b856-27d515e04895"
  });
</script>
        <div class="w-full hidden">
            <div class="bg-gradient-to-b from-blue-800 to-blue-600 h-96"></div>
            <div class="max-w-5xl mx-auto px-6 sm:px-6 lg:px-8 mb-12">
                <div class="bg-gray-900 w-full shadow bg-white rounded-xl p-8 sm:p-12 -mt-72">
                    <img src="assets/img/tagline/WIN-Tagline-RED-on-PINK.png" class="w-60 absolute mt-[-5rem] mx-auto left-0 right-0">
                    <form action="" method="post">
                        <div class="md:flex items-center mt-12">
                            <div class="w-full md:w-1/2 flex flex-col">
                                <label class="font-semibold leading-none">First Name</label>
                                <input type="text" class="leading-none text-gray-50 p-3 focus:outline-none focus:border-blue-700 mt-4 border-0 bg-win-light rounded-full" />
                            </div>
                            <div class="w-full md:w-1/2 flex flex-col md:ml-6 md:mt-0 mt-4">
                                <label class="font-semibold leading-none">Last Name</label>
                                <input type="text" class="leading-none text-gray-50 p-3 focus:outline-none focus:border-blue-700 mt-4 border-0 bg-win-light rounded-full" />
                            </div>
                        </div>
                        <div class="md:flex items-center mt-8">
                            <div class="w-full md:w-1/2 flex flex-col">
                                <label class="font-semibold leading-none">Email</label>
                                <input type="email" class="leading-none text-gray-50 p-3 focus:outline-none focus:border-blue-700 mt-4 border-0 bg-win-light rounded-full" />
                            </div>
                            <div class="w-full md:w-1/2 flex flex-col md:ml-6 md:mt-0 mt-4">
                                <label class="font-semibold leading-none">Subject</label>
                                <input type="text" class="leading-none text-gray-50 p-3 focus:outline-none focus:border-blue-700 mt-4 border-0 bg-win-light rounded-full" />
                            </div>
                        </div>
                        <div>
                            <div class="w-full flex flex-col mt-8">
                                <label class="font-semibold leading-none">Message</label>
                                <textarea type="text" class="h-40 text-base leading-none text-gray-50 p-3 focus:outline-none focus:border-blue-700 mt-4 border-0 bg-win-light rounded-lg"></textarea>
                            </div>
                        </div>
                        <div class="my-6 w-full">
                            <button type="button" style="line-height:1; font-size:0.875rem;" class="body-copy ml-auto mr-0 font-semibold inline flex py-3 px-10 gap-x-2 rounded-full bg-win-blue text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                SEND IT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <div class="relative z-50">
    <div class="flex w-auto">
        <div class="max-w-[90%] px-16 pt-16 lg:px-32 xl:px-48 md:px-32 md:pt-32 mx-auto">
            <div class="container lg:max-w-[60%] mx-auto">
                <img src="/assets/img/shapes/confetti-small-transparent.png" class="absolute left-10 top-[-15%]">
                    <h2 class="text-4xl headline-small font-semibold text-center">Time To Look Forward. We’re Eliminating Old-School Practices That Prioritize The Who’s-Who Rather Than The Who-Can.</h2>
                    <p class="subheading mx-auto max-w-[80%] text-center mt-4">Here’s how we can help you. We’ve taken a step back from the wedding industry status quo to refocus on trust and true expertise with a merit-based system that allows everyone to start with an even playing field.</p>
            </div>
            <div class="lg:grid lg:grid-cols-3 lg:gap-8 mt-16 lg:mx-16 relative z-50">
                
                <div class="min-w-screen rounded-tl-3xl" style="background-image: url('/assets/img/about/help-1.jpg'); background-size:cover; background-position: bottom;
                    background-color: rgba(0,0,0,0.6);
                    background-blend-mode: darken;">
                    <div class="h-full w-full px-8 mb-[3.25rem]">
                        <div class="text-center mb-12">
                            <div class="flex justify-center mt-[-2.5rem]">
                                <div class="flex-shrink bg-win-light rounded-full">
                                    <p class="p-6 text-win-blue headline-small" style="line-height:1;">01</p>
                                </div>
                            </div>
                            <h5 class="headline-small text-white py-8 lg:mt-8">Search</h5>
                            <p class="subheading text-white px-8">Easily find the right vendors in your location and price range.</p>
                            <div class="my-6 min-w-full flex items-center">
                                <a href="/user/register" class="mx-auto py-2 px-10 font-semibold rounded-full bg-win-blue text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                    LEARN MORE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="min-w-screen" style="background-image: url('/assets/img/about/help-2.jpg'); background-size:cover; background-position: bottom;
                    background-color: rgba(0,0,0,0.6);
                    background-blend-mode: darken;">
                    <div class="h-full w-full px-8 mb-[3.25rem]">
                        <div class="text-center mb-12">
                            <div class="flex justify-center mt-[-2.5rem]">
                                <div class="flex-shrink bg-win-light rounded-full">
                                    <p class="p-6 text-win-blue headline-small" style="line-height:1;">02</p>
                                </div>
                            </div>
                            <h5 class="headline-small text-white py-8 lg:mt-8">Save</h5>
                            <p class="subheading text-white px-8">Bookmark your favorites, and have the opportunity to save up to $250 per booking.</p>
                            <div class="my-6 min-w-full flex items-center">
                                <a href="/user/register" class="mx-auto py-2 px-10 font-semibold rounded-full bg-win-blue text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                    LEARN MORE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="min-w-screen rounded-br-3xl" style="background-image: url('/assets/img/about/help-3.jpg'); background-size:cover; background-position: bottom;
                    background-color: rgba(0,0,0,0.6);
                    background-blend-mode: darken;">
                    <div class="h-full w-full px-8 mb-[3.25rem]">
                        <div class="text-center mb-12">
                            <div class="flex justify-center mt-[-2.5rem]">
                                <div class="flex-shrink bg-win-light rounded-full">
                                    <p class="p-6 text-win-blue headline-small" style="line-height:1;">03</p>
                                </div>
                            </div>
                            <h5 class="headline-small text-white py-8 lg:mt-8">Support</h5>
                            <p class="subheading text-white px-8">Small businesses and artists who use WIN for a bespoke experience.</p>
                            <div class="my-6 min-w-full flex items-center">
                                <a href="/user/register" class="mx-auto py-2 px-10 font-semibold rounded-full bg-win-blue text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                    LEARN MORE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="min-w-screen relative z-20 mt-[-4%]" style="background-image:url('/assets/img/join-us/wedding-businesses-2.jpg'); background-size:cover; background-position: top;">
        <div class="h-full mx-auto flex justify-center">
            <div class="text-center mx-auto md:max-w-[75%] min-h-[80vh] flex inline-flex items-center justify-center">
            </div>
        </div>
    </div>
    <div class="bg-win-blue w-screen py-4 text-center">
        <p class="text-white subheading">INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS.</p>
    </div>
    @include('layouts.footer')
</body>

</html>