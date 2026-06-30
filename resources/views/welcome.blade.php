<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Wedding Insiders Network: Find Vendors For Your Wedding</title>
    <meta name="description" content="Start booking your wedding vendors with an exclusive list — we’ve cut out paid placements to give you top-shelf wedding vendors. Create your free profile!" />

    <!-- Styles -->
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
    <style>
        body {
            min-width: fit-content;
        }

        .text-overflow-center {
            margin-left: -10%;
            margin-right: -10%;
            text-align: center;
        }
    </style>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
@include('layouts.guest_navigation')

<body class="antialiased overflow-x-hidden bg-win-light w-screen">
        <div class="absolute w-screen overflow-hidden -z-50 sm:max-w-screen h-screen top-0">
            <video id="splashVideo" autoplay loop muted playsinline="true" disablePictureInPicture="true" src="{{ url('/assets/home-video-2.mp4') }}"
                class="absolute min-w-[100%] min-h-full overflow-hidden object-top max-sm:object-cover" type="video/mp4">
            </video>
            <div class="bg-black opacity-[10%] absolute min-w-[100%] min-h-screen overflow-hidden object-top"></div>
        </div>
    <div class="w-screen overflow-hidden">
        <div class="min-h-[85vh] max-h-[85vh] items-stretch">
            <div class="w-full mx-auto px-4 lg:px-16 xl:px-32 md:w-3/4 md:content-center overflow-hidden min-h-[85vh] text-white">
                <div class="text-center my-auto mx-auto">
                    <img src="/assets/img/tagline/WIN-Tagline-RED-on-PINK.png" class="max-w-[50%] md:max-w-[30%] mx-auto py-4">
                    <h1 class="subheading mb-2 text-center">Vendors for Weddings</h1>
                    <h2 class="headline-large mb-2 text-center">Wedding Insiders Network</h2>
                    <p class="mb-2 headline-small">Find Your Dream Wedding Team</p>
                    <div class="mx-auto rounded-lg bg-win-light p-4 md:w-3/4 2xl:w-1/2 my-4 border-black border-4">
                        <div class="grid grid-cols-7">
                            <div class="col-span-3 block p-2 lg:p-4 content-center">
                                <img src="/assets/img/home/win-welcome-1.png" class="rounded-tl-3xl rounded-br-3xl h-auto max-h-[15rem] mx-auto my-auto">
                            </div>
                            <div class="col-span-4 text-center p-2 lg:p-4 text-black content-center">
                                <h3 class="subheading">Wedding Planning with Purpose</h3>
                                <p>Merit-based vendor <span class="text-nowrap">matchmaking ❤️</span> The industry's source for pre-vetted, curated vendors—match with vendors based on your vision and budget while supporting local businesses.</p>
                                <div class="mt-6 min-w-full flex items-center">
                                    <a href="/user/register" class="mx-auto py-1 px-6 button-text font-medium rounded-lg bg-win-orange text-white">
                                        Get Started
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-win-lavender w-screen py-4 text-left overflow-hidden">
        <p class="text-win-red subheading whitespace-nowrap -ml-[4rem] hidden xl:block">INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS. INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS. INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS. INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS. INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS. INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS. INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS.</p>
        <p class="text-win-red subheading xl:hidden text-center">INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS.</p>
    </div>
    <div class="w-screen mb-16" style="background-image:url('/assets/img/WIN-Large-Confetti-Pattern.jpg');">
    <div class="min-w-screen">
        <div class="py-16">
            <div class="text-center my-auto mx-auto md:max-w-[65%] px-4 bg-win-light">
                <h2 class="headline-large text-win-red mb-2">Modernizing How Couples Find Vendors,
                    And How Vendors Refer and Support Fellow Small Businesses</h2>
                    <div class="border-dotted border-b-2" data-aos="fade-right"></div>
                <p class="mb-2 mt-4 body-copy">We are Wedding Insiders Network, a virtual marketplace of wedding vendors designed to help couples find their ideal wedding team. Plan your wedding with local businesses featured for their merit and not for what they pay (unlike most platforms). Here, you’ll find an exclusive network of vendors ranked by their high levels of expertise, reliable communication styles and top-shelf client satisfaction. Everybody WINs.</p>
            </div>
        </div>
    </div>
    <div class="max-w-screen overflow-hidden" style="background-image:url('/assets/img/WIN-Large-Confetti-Pattern.jpg');">
            <div class="min-w-[100vw] py-12 relative z-20 px-4">
                <div class="grid md:grid-cols-2 gap-x-16 lg:max-w-[75%] mx-auto">
                    <div class="text-center p-8 md:px-12 md:pb-0 bg-win-lavender rounded-xl border-4 md:border-b-0 md:rounded-b-none">
                        <i class="fa-solid fa-gem text-win-red text-5xl mb-4"></i>
                        <h3 class="headline-small">Free For Couples</h3>
                        <p class="subheading mt-4 lg:max-w-[85%] mx-auto">Easily filter through wedding vendors — featured for their reputation and not because they paid for a higher listing. The best way to support small businesses while tapping into a curated, transparent list of talented vendors that fit your needs.</p>
                        
                        <div class="min-w-full mx-auto items-center md:hidden mt-4">
                            <a href="/search" class="mx-auto button-text py-2 px-10 font-semibold rounded-lg bg-win-red text-white">
                                Explore our Community
                            </a>
                        </div>
                        <img src="assets/img/home/laptop-2-resized.png" class="md:hidden mb-8">
                    </div>
                    <div class="text-center p-8 md:px-12 md:pb-0 bg-win-lavender rounded-xl border-4 md:border-b-0 md:rounded-b-none mt-4 sm:mt-0">
                        <i class="fa-solid fa-camera text-win-red text-5xl mb-4"></i>
                        <h3 class="headline-small">Revolutionary For Vendors</h3>
                        <p class="subheading mt-6 lg:max-w-[85%] mx-auto">Finally, experience reliable, cost-effective leads and inquiries with a platform built to streamline how you send and receive referrals while cutting out kickbacks, cliques and pay-to-play models.</p>
                        <div class="min-w-full mx-auto items-center md:hidden my-4">
                            <a href="{{ route('vendor.register')}}" class="mx-auto button-text py-2 px-10 font-semibold rounded-lg bg-win-red text-white">
                                Learn More
                            </a>
                        </div>
                        <img src="assets/img/home/laptop-1-resized.png" class="md:hidden mb-4">
                    </div>
                    <div class="text-center hidden md:block bg-win-lavender border-4 rounded-xl border-t-0 rounded-t-none pb-8">
                        <div class="min-w-full mx-auto items-center mt-4">
                            <a href="/search" class="mx-auto py-2 px-10 font-semibold rounded-lg bg-win-red text-white">
                                Explore our Community
                            </a>
                        </div>
                        <img src="assets/img/home/laptop-2-resized.png" class="mt-1">
                    </div>
                    <div class="text-center hidden md:block bg-win-lavender border-4 rounded-xl border-t-0 rounded-t-none pb-8">
                        <div class="min-w-full mx-auto items-center mt-4">
                            <a href="{{ route('vendor.register')}}" class="mx-auto py-2 px-10 font-semibold rounded-lg bg-win-red text-white">
                            Learn More
                            </a>
                        </div>
                        <img src="assets/img/home/laptop-1-resized.png" class="">
                    </div>
                </div>
            </div>
    </div>
    </div>

    <div class="min-w-screen bg-win-light">
        <div class="h-full pb-0 md:pb-8 px-4">
            <div class="text-center my-auto mx-auto md:max-w-[85%] pt-8">
                <h2 class="headline-large mb-2" data-aos="fade-up">More Vendors Who Care. Less Cliques.</h2>
                <h3 class="headline-small mb-4" data-aos="fade-up">We offer a range of benefits that set us apart:</h3>
                <div class="border-dotted border-b-2" data-aos="fade-right"></div>
                <div class="lg:grid gap-8 lg:grid-cols-7 mt-4">
                    <div class="col-span-3 hidden lg:block content-center">
                        <img src="/assets/img/join-us/wedding-businesses-1.jpg" class="hidden lg:block">
                    </div>
                    <div class="grid gap-x-4 md:grid-cols-2 -mb-4 md:mb-0 text-left col-span-4">
                        <div class="w-full px-8 mb-4 bg-win-light rounded-3xl border-4">
                            <div class="mb-12">
                                <h3 class="subheading text-win-charcoal py-4 lg:pt-8"><span><i class="fa-solid fa-diagram-project bg-win-purple text-white rounded-lg mr-2 p-2 lg:p-4"></i></span>Merit-Based Listings</h3>
                                <p class="font-regular" style="line-height: 2rem;">Choose from the best, not from the highest-paying vendor. Our transparent vendor listings connect you with wedding businesses based on their reviews, response time and other clear-cut factors to eliminate unfair business practices while showing you the vendors who can understand and execute your vision. </p>
                            </div>
                        </div>
                        <div class="w-full px-8 mb-4 bg-win-light rounded-3xl border-4">
                            <div class="mb-12">
                                <h3 class="subheading text-win-charcoal py-4 lg:pt-8"><span><i class="fa-solid fa-dollar-sign bg-win-purple text-white rounded-lg mr-2 px-4 py-2 lg:py-4 lg:px-6"></i></span>Savings On Time + Costs</h3>
                                <p class="font-regular" style="line-height: 2rem;">Maximize your savings by booking your entire vendor team through WIN with many vendors offering preferred pricing of up to $250 off per service (optional, but encouraged). As for vendors, cut your marketing spend while expanding your reach using our cost-effective platform to generate ready-to-book leads.</p>
                            </div>
                        </div>
                        <div class="w-full px-8 mb-4 bg-win-light rounded-3xl border-4">
                            <div class="mb-12">
                                <h3 class="subheading text-win-charcoal py-4 lg:pt-8"><span><i class="fa-solid fa-users bg-win-purple text-white rounded-lg mr-2 p-2 lg:p-4"></i></span>Community-Focused Model</h3>
                                <p class="font-regular" style="line-height: 2rem;">WIN’s algorithm is designed to promote community over competition, boosting individual rankings when a vendor invites peers and adds to the network. We believe there’s enough work for everyone, but that there needs to be a better resource for connecting couples with the right people, and for vendors to share and refer business to each other. (That’s where we come in.)</p>
                            </div>
                        </div>
                        <div class="w-full px-8 mb-4 bg-win-light rounded-3xl border-4">
                            <div class="mb-12">
                                <h3 class="subheading text-win-charcoal py-4 lg:pt-8"><span><i class="fa-solid fa-business-time bg-win-purple text-white rounded-lg mr-2 p-2 lg:p-4"></i></span>Nurturing Small Businesses<</h3>
                                <p class="font-regular" style="line-height: 2rem;">Pairing couples with vendors who aren’t burned out, because with WIN, vendors can take back control of their marketing, minimize overhead costs and focus on their services — bringing you an even better experience as a couple. Plus, you’ll be supporting small businesses in the industry on a platform founded by solo entrepreneurs.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-6 min-w-full flex items-center">
                <a href="/user/register" class="mx-auto py-2 px-8 button-text font-medium rounded-lg bg-win-orange text-white">
                    Join & Enjoy
                </a>
            </div>
        </div>
    </div>
    <div class="container -mt-12 sm:mt-0">
        <div class="bg-win-light box border-circle"></div>
    </div>
    <div class="min-w-screen bg-white" id="what-makes-us-different">
        <div class="bg-[#FFD3FB] h-full pb-16">
            <div class="my-auto mx-auto px-4 max-w-[85%] lg:max-w-[65%] pt-16">
                <h3 class="text-center headline-large" data-aos="slide-up">Features</h3>
                <h3 class="text-center headline-small my-4" data-aos="slide-up">Discover the Future of Wedding Planning: choosing a WIN vendor supports the individual artists and creatives of the wedding industry — those who aren’t just talented, but have the experience to match your vision.</h3>
                <div class="border-dotted border-b-2" data-aos="fade-right"></div>
                <div class="lg:grid grid-cols-2 gap-8 my-4 lg:my-8">
                    <img src="/assets/img/home/features-1.avif" class="rounded-xl h-48 lg:h-64 mx-auto border-4" data-aos="flip-left">
                    <div class="mt-4 sm:mt-0" data-aos="slide-up">
                        <p class="headline-small mb-4">Easily Search + Match With Vendors</p>
                        <p class="body-copy">Create your free profile: browse & match with top-rated vendors ranked by their reputation, expertise, and real engagement — not just paid listings. It’s time to rethink wedding planning and build meaningful connections with the vendors who will bring your vision to life! </p>
                    </div>
                </div>
                <div class="border-dotted border-b-2" data-aos="fade-right"></div>
                <div class="lg:grid grid-cols-2 gap-8 my-4 lg:my-8">
                    <img src="/assets/img/home/features-2.avif" class="rounded-xl h-48 lg:h-64 mx-auto border-4 sm:hidden" data-aos="flip-right">
                    <div class="mt-4 sm:mt-0" data-aos="slide-up">
                        <p class="headline-small mb-4">Personal Messaging + Consultation Requests</p>
                        <p class="body-copy">No more scattered emails or planning chaos—just a seamless, easy-to-use platform designed to help you find, connect + book your dream team with confidence.</p>
                    </div>
                    <img src="/assets/img/home/features-2.avif" class="rounded-xl h-48 lg:h-64 mx-auto border-4 hidden sm:block" data-aos="flip-right">
                </div>
                <div class="border-dotted border-b-2" data-aos="fade-right"></div>
                <div class="lg:grid grid-cols-2 gap-8 my-4 lg:my-8">
                    <img src="/assets/img/home/features-3.avif" class="rounded-xl h-48 lg:h-64 mx-auto border-4" data-aos="flip-left">
                    <div class="mt-4 sm:mt-0" data-aos="slide-up">
                        <p class="headline-small mb-4">Planning Tools Made Easy</p>
                        <p class="body-copy">Easily track your vendor bookings, schedule and manage appointments & find other helpful planning tools through your dashboard & be sure to check out our blog for more planning tips & advice.</p>
                    </div>
                </div>
                <div class="border-dotted border-b-2" data-aos="fade-right"></div>
            </div>
        </div>
    </div>
    <div class="w-screen lg:max-w-[85%] bg-win-light p-16 my-16 mx-auto rounded-xl bg-[url('/assets/img/home/vendors-for-wedding(s)-8.jpg')] bg-no-repeat bg-cover">
        <div class="lg:grid grid-cols-2">
            <div class="rounded-xl bg-white/50 border-4 w-auto py-12">
                <h3 class="headline-small text-center">Find Your Wedding Dream Team</h3>
                <h3 class="subheading text-center ">Search By Vendor Type:</h3>
                <div class="text-center button-text mt-4 font-medium">
                    <a href="/search?type=12" class="transition duration-150 hover:scale-105">
                        <div class="text-center w-[60%] md:w-[40%] px-6 py-2 bg-win-lavender rounded-lg mx-auto">
                            Planner →
                        </div>
                    </a>
                    <a href="/search?type=2" class="my-4 block">
                    <div class="text-center w-[60%] md:w-[40%] px-6 py-2 bg-win-lavender rounded-lg mx-auto">
                        Venue →
                    </div>
                    </a>
                    <a href="/search?type=9" class="my-4 block">
                    <div class="text-center w-[60%] md:w-[40%] px-6 py-2 bg-win-lavender rounded-lg mx-auto">
                        Photographer →
                    </div>
                    </a>
                    <a href="/search?type=13" class="my-4 block">
                    <div class="text-center w-[60%] md:w-[40%] px-6 py-2 bg-win-lavender rounded-lg mx-auto">
                        Videographer →
                    </div>
                    </a>
                    <a href="/search?type=7" class="my-4 block">
                    <div class="text-center w-[60%] md:w-[40%] px-6 py-2 bg-win-lavender rounded-lg mx-auto">
                        Hair and makeup →
                    </div>
                    </a>
                    <a href="/search?type=5" class="my-4 block">
                    <div class="text-center w-[60%] md:w-[40%] px-6 py-2 bg-win-lavender rounded-lg mx-auto">
                        DJ →
                    </div>
                    </a>
                    <a href="/search?type=4" class="my-4 block">
                    <div class="text-center w-[60%] md:w-[40%] px-6 py-2 bg-win-lavender rounded-lg mx-auto">
                        Catering →
                    </div>
                    </a>
                    <a href="/search" class="my-4 block">
                    <div class="text-center w-[70%] md:w-[50%] px-6 py-2 bg-win-lavender rounded-lg mx-auto">
                        View all →
                    </div>
                    </a>
                </div>
            </div>
            <div class="hidden lg:block mx-16">
                <div class="my-16">
                    <p class="bg-win-purple rounded-t-xl rounded-br-xl p-4 text-white inline headline-small" data-aos="fade-up">Hi there! 😄</p>
                </div>
                <div class="my-16 text-right">
                    <p class="bg-win-blue rounded-t-xl rounded-bl-xl p-4 text-white inline headline-small" data-aos="fade-up" data-aos-delay="500">Welcome to WIN! 🎉</p>
                </div>
                <div class="my-16">
                    <p class="bg-win-purple rounded-t-xl rounded-br-xl p-4 text-white inline headline-small" data-aos="fade-up" data-aos-delay="1000">Thank you! ❤️</p>
                </div>
            </div>
        </div>

    </div>
    <div class="min-w-screen bg-win-light border-t pt-8 lg:pt-12 hidden">
        <h3 class="headline-small text-center">On The Blog: Wedding Industry Inspo + Insights</h3>

        <div class="p-4 mx-8 mt-4 mb-8 flex justify-center items-center border border-dashed border-black rounded-xl col-span-2">
            <h3 class="text-black">
                (Blog post placeholder)
            </h3>
        </div>
    </div>
    <div class="min-w-screen bg-win-light border-t pt-8 lg:pt-12 hidden">
        <h3 class="subheading text-center">ON THE HOUSE</h3>
        <h3 class="headline-small text-center">Wedding Vendor Checklist</h3>

        <div class="px-4 mx-8 mt-4 mb-8 lg:max-w-[70%] mx-auto">
            <p class="body-copy text-black">
                A printable PDF and Google Doc of the vendors you need for your wedding — who to book and when, with a checklist and notes section for you to stay organized. Best paired with WIN, which keeps track of who you’ve contacted, booked and who is left to confirm.
            </p>

            <div class="text-center underline mt-4 button-text">
                <a href="/assets/Wedding Vendor Checklist.pdf" target="_blank">Download →</a>
            </div>
        </div>
    </div>
    <div class="box border-circle-b"></div>
    <div class="min-w-screen bg-[#FFD3FB] px-4 pt-4">
        <h3 class="subheading text-center">Find Vendors for Your Wedding</h3>
        <h3 class="headline-small text-center">Now Available in New England, New York + New Jersey</h3>

        <div class="px-4 mx-8 mt-4 pb-8 lg:max-w-[70%] mx-auto">
            <div class="my-6 mx-auto text-center">
                <a href="/user/register" class="py-2 px-8 button-text font-semibold rounded-lg bg-win-blue text-white">
                    For Clients
                </a>
            </div>
            <div class="my-6 mx-auto text-center">
                <a href="{{ route('vendor.register.form')}}" class="py-2 px-8 button-text font-semibold rounded-lg bg-white text-win-blue">
                    For Vendors
                </a>
            </div>
        </div>
    </div>
    @include('layouts.footer')
    <button id="adventureTrigger" type="button" class="hidden" data-hs-overlay="#hs-full-screen-adventure-modal">
        Full screen
    </button>
    <div id="hs-full-screen-adventure-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="min-h-[100vh]">
            <div class="absolute min-h-[60vh] min-w-[100%] bg-win-lavender z-10"></div>
            <div class="absolute min-h-[100%] min-w-[100%] bg-win-light"></div>
            <div class="min-w-[100vw] pt-24 pb-12 text-center relative z-20">
                <h3 class="headline-small text-win-red">Choose your adventure</h3>
                <p class="subheading py-4">To optimise your experience, select a service that best fits your goals</p>
            </div>
            <div class="min-w-[100vw] max-h-[90vh] py-12 relative z-20">
                <div class="grid grid-cols-2 gap-16 lg:max-w-[50%] mx-auto">
                    <div class="text-center">
                        <img class="rounded-tl-3xl mb-4 max-h-[35vh] mx-auto" src="/assets/img/for-vendors.PNG">
                        <h3 class="headline-small text-win-red">For Vendors</h3>
                        <p class="subheading my-8 lg:my-10 max-w-[75%] mx-auto">Lorem ipsum dolor sit amet,
                            consectetur adipiscing elit.
                            Quisque sed ornare ipsum,</p>
                        <div class="my-6 min-w-full flex items-center">
                            <a href="{{ route('vendor.register.form')}}" class="mx-auto py-2 px-10 font-semibold rounded-lg bg-win-purple text-white">
                                CONTINUE
                            </a>
                        </div>
                    </div>
                    <div class="text-center">
                        <img class="rounded-tl-3xl mb-4 max-h-[35vh] mx-auto" src="/assets/img/for-couples.PNG">
                        <h3 class="headline-small text-win-red">For Couples</h3>
                        <p class="subheading my-8 lg:my-10 max-w-[75%] mx-auto">Lorem ipsum dolor sit amet,
                            consectetur adipiscing elit.
                            Quisque sed ornare ipsum,</p>
                        <div class="my-6 min-w-full flex items-center">
                            <a id="closeAdventure" class="mx-auto py-2 px-10 font-semibold rounded-lg bg-win-blue text-white">
                                CONTINUE
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        if (window.matchMedia("(max-width: 767px)").matches){ 
            $("#splashVideo").attr('poster', '/assets/img/home/vendors-for-wedding(s)-1.jpg');
        }
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    AOS.init();
    </script>
</body>

</html>