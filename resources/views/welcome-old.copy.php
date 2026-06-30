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
</head>
@include('layouts.guest_navigation')

<body class="antialiased overflow-x-hidden bg-win-light w-screen">
    <div class="w-screen overflow-hidden">
        <div class="min-h-[85vh] max-h-[85vh] items-stretch">
            <div class="absolute -z-[30] overflow-hidden hidden sm:block">
                <img src="/assets/img/shapes/confetti-small-transparent.png" class="w-full mt-[20%] ml-[20%]">
            </div>
            <div class="w-full mx-auto px-4 lg:px-16 xl:px-32 md:w-1/2 md:content-center overflow-hidden min-h-[85vh] text-white">
                <div class="text-center my-auto mx-auto">
                    <img src="/assets/img/tagline/WIN-Tagline-RED-on-PINK.png" class="max-w-[50%] md:max-w-[30%] mx-auto py-4">
                    <h1 class="subheading mb-2 text-center">Vendors for Weddings</h1>
                    <h2 class="headline-large mb-2 text-center">Wedding Insiders Network</h2>
                    <p class="mb-2 headline-small">Welcome to WIN, the industry’s source for pre-vetted, curated vendors — and with a referral program that promotes true collaboration and artistry.</p>
                    <div class="my-6 min-w-full flex items-center">
                        <a href="/user/register" class="mx-auto py-2 px-10 font-semibold button-text rounded-lg bg-win-blue text-white disabled:opacity-50 disabled:pointer-events-none">
                            GET STARTED
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute w-screen sm:w-screen min-h-screen top-0 overflow-hidden -z-50 sm:max-w-screen sm:max-h-screen">
            <video id="splashVideo" autoplay loop muted playsinline="true" disablePictureInPicture="true" src="{{ url('/assets/wedding-home.mp4') }}"
                class="absolute min-w-[100%] min-h-screen overflow-hidden object-top max-sm:object-cover sm:top-[6rem]" type="video/mp4">
            </video>
            <div class="bg-black opacity-25 absolute min-w-[100%] min-h-screen overflow-hidden object-top"></div>
        </div>
    </div>
    <div class="bg-win-lavender w-screen py-4 text-left overflow-hidden">
        <p class="text-win-red subheading whitespace-nowrap -ml-[4rem] hidden xl:block">INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS. INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS. INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS. INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS. INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS. INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS. INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS.</p>
        <p class="text-win-red subheading xl:hidden text-center">INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS.</p>
    </div>
    <div class="min-w-screen" style="background-image:url('/assets/img/home/vendors-for-wedding(s)-1.jpg'); background-size:cover; background-position: center;">
        <div class="bg-dark-grey-win bg-opacity-50 h-full py-16 md:py-32">
            <div class="text-center my-auto mx-auto md:max-w-[33%] px-4">
                <h2 class="headline-small md:headline-large text-white mb-2">Modernizing How Couples Find Vendors,
                    And How Vendors Refer and Support Fellow Small Businesses</h2>
                <p class="mb-2 body-copy text-white font-semibold">We are Wedding Insiders Network, a virtual marketplace of wedding vendors designed to help couples find their ideal wedding team. Plan your wedding with local businesses featured for their merit and not for what they pay (unlike most platforms). Here, you’ll find an exclusive network of vendors ranked by their high levels of expertise, reliable communication styles and top-shelf client satisfaction. Everybody WINs.</p>
            </div>
        </div>
    </div>
    <div class="mb-24 max-w-screen overflow-hidden">
        <div class="min-h-[100vh]">
            <div class="absolute min-h-[60vh] w-[100%] bg-win-lavender z-10 overflow-hidden"></div>
            <div class="absolute min-h-[100%] w-[100%] bg-win-light overflow-hidden"></div>
            <div class="min-w-[100vw] pt-12 lg:pt-24 text-center relative z-20">
                <h2 class="headline-small text-win-red">Vendor Referral Program</h2>
            </div>
            <div class="min-w-[100vw] py-12 relative z-20">
                <div class="grid md:grid-cols-2 gap-x-16 lg:max-w-[50%] mx-auto">
                    <div class="text-center px-8">
                        <img class="rounded-tl-3xl mb-4 mx-auto" src="/assets/img/home/vendors-for-wedding(s)-2.jpg" alt="Smiling bride in a strapless white gown holding a bouquet, walking arm-in-arm with the groom in a black tuxedo outside a stone castle-like building on their wedding day.">
                        <h3 class="headline-small text-win-red">Free For Couples</h3>
                        <p class="subheading my-8 lg:my-10 lg:max-w-[75%] mx-auto">Easily filter through wedding vendors — featured for their reputation and not because they paid for a higher listing. The best way to support small businesses while tapping into a curated, transparent list of talented vendors that fit your needs.</p>
                        <div class="min-w-full mx-auto items-center md:hidden mt-4 mb-8">
                            <a href="/search" class="mx-auto button-text py-2 px-10 font-semibold rounded-lg bg-win-purple text-white">
                                SEARCH VENDORS
                            </a>
                        </div>
                    </div>
                    <div class="text-center px-8">
                        <img class="rounded-tl-3xl mb-4 mx-auto" src="/assets/img/home/vendors-for-wedding(s)-3.jpg" alt="Four-tier white wedding cake with floral detailing, displayed on a round table draped in a beige cloth, surrounded by colorful flower arrangements, set in front of a dark wooden ornate door.">
                        <h3 class="headline-small text-win-red">Revolutionary For Vendors</h3>
                        <p class="subheading my-8 lg:my-10 lg:max-w-[75%] mx-auto">Finally, experience reliable, cost-effective leads and inquiries with a platform built to streamline how you send and receive referrals while cutting out kickbacks, cliques and pay-to-play models.</p>
                        <div class="min-w-full mx-auto items-center md:hidden my-4">
                            <a href="{{ route('vendor.register')}}" class="mx-auto button-text py-2 px-10 font-semibold rounded-lg bg-win-blue text-white">
                                LEARN MORE
                            </a>
                        </div>
                    </div>
                    <div class="text-center hidden md:block">
                        <div class="min-w-full mx-auto items-center">
                            <a href="/search" class="mx-auto py-2 px-10 font-semibold rounded-lg bg-win-purple text-white">
                                SEARCH VENDORS
                            </a>
                        </div>
                    </div>
                    <div class="text-center hidden md:block">
                        <div class="min-w-full mx-auto items-center">
                            <a href="{{ route('vendor.register')}}" class="mx-auto py-2 px-10 font-semibold rounded-lg bg-win-blue text-white">
                                LEARN MORE
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="min-w-screen bg-white">
        <div class="h-full pb-16 px-4 sm:mx-16 md:pb-32 lg:pb-48 xl:mx-32" style="background-image:url('/assets/img/WIN-Large-Confetti-Pattern.jpg');">
            <img src="/assets/img/home/vendors-for-wedding(s)-4.jpg" class="rounded-tl-3xl max-w-[75%] sm:max-w-[50%] md:max-w-[25%] mx-auto" alt="Bride and groom stand in the center, laughing and surrounded by an excited wedding party, all dressed in black formalwear, holding white bouquets, celebrating outdoors in front of a stone building.">
            <div class="text-center my-auto mx-auto md:max-w-[65%] pt-8">
                <h2 class="headline-small text-win-red mb-2">More Vendors Who Care. Less Cliques.</h2>
                <div class="grid gap-8 md:grid-cols-2 -mb-4 md:mb-0 mt-32">
                    <div class="w-full sm:px-8 mb-[3.25rem] bg-win-light rounded-tl-3xl rounded-br-3xl">
                        <div class="text-center mb-12">
                            <div class="flex justify-center mt-[-2.5rem]">
                                <div class="flex-shrink bg-white rounded-full">
                                    <p class="p-6 text-win-red headline-small" style="line-height:1;">01</p>
                                </div>
                            </div>
                            <h3 class="subheading text-win-charcoal py-8 lg:mt-8">Merit-Based Listings</h3>
                            <p class="body-copy sm:px-8">Choose from the best, not from the highest-paying vendor. Our transparent vendor listings connect you with wedding businesses based on their reviews, response time and other clear-cut factors to eliminate unfair business practices while showing you the vendors who can understand and execute your vision. </p>
                        </div>
                    </div>
                    <div class="w-full sm:px-8 mb-[3.25rem] bg-win-light rounded-tl-3xl rounded-br-3xl">
                        <div class="text-center mb-12">
                            <div class="flex justify-center mt-[-2.5rem]">
                                <div class="flex-shrink bg-white rounded-full">
                                    <p class="p-6 text-win-red headline-small" style="line-height:1;">02</p>
                                </div>
                            </div>
                            <h3 class="subheading text-win-charcoal py-8 lg:mt-8">Savings On Time + Costs</h3>
                            <p class="body-copy sm:px-8">Maximize your savings by booking your entire vendor team through WIN with many vendors offering preferred pricing of up to $250 off per service (optional, but encouraged). As for vendors, cut your marketing spend while expanding your reach using our cost-effective platform to generate ready-to-book leads.</p>
                        </div>
                    </div>
                    <div class="w-full sm:px-8 mb-[3.25rem] bg-win-light rounded-tl-3xl rounded-br-3xl">
                        <div class="text-center mb-12">
                            <div class="flex justify-center mt-[-2.5rem]">
                                <div class="flex-shrink bg-white rounded-full">
                                    <p class="p-6 text-win-red headline-small" style="line-height:1;">03</p>
                                </div>
                            </div>
                            <h3 class="subheading text-win-charcoal py-8 lg:mt-8">Community-Focused Model</h3>
                            <p class="body-copy sm:px-8">WIN’s algorithm is designed to promote community over competition, boosting individual rankings when a vendor invites peers and adds to the network. We believe there’s enough work for everyone, but that there needs to be a better resource for connecting couples with the right people, and for vendors to share and refer business to each other. (That’s where we come in.)</p>
                        </div>
                    </div>
                    <div class="w-full sm:px-8 mb-[3.25rem] bg-win-light rounded-tl-3xl rounded-br-3xl">
                        <div class="text-center mb-12">
                            <div class="flex justify-center mt-[-2.5rem]">
                                <div class="flex-shrink bg-white rounded-full">
                                    <p class="p-6 text-win-red headline-small" style="line-height:1;">04</p>
                                </div>
                            </div>
                            <h3 class="subheading text-win-charcoal py-8 lg:mt-8">Nurturing Small Businesses</h3>
                            <p class="body-copy sm:px-8">Pairing couples with vendors who aren’t burned out, because with WIN, vendors can take back control of their marketing, minimize overhead costs and focus on their services — bringing you an even better experience as a couple. Plus, you’ll be supporting small businesses in the industry on a platform founded by solo entrepreneurs.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-6 min-w-full flex items-center">
                <a href="/user/register" class="mx-auto py-3 px-10 button-text font-semibold rounded-lg bg-win-purple text-white">
                    JOIN AND ENJOY
                </a>
            </div>
        </div>
    </div>
    <div class="min-w-screen bg-white" id="what-makes-us-different">
        <div class="bg-win-light h-full pb-16 sm:mx-16 md:mx-24 xl:mx-32">
            <div class="text-center my-auto mx-auto px-4 md:max-w-[65%] pt-16">
                <div class="md:grid md:gap-8 md:grid-cols-2 -mb-4 md:mb-0">
                    <div class="w-full mb-[3.25rem] bg-win-light rounded-tl-3xl rounded-br-3xl">
                        <div class="text-center mb-12">
                            <img src="/assets/img/tagline/WIN-Tagline-RED-on-PINK.png" class="max-w-[50%] py-4">
                            <h2 class="subheading mb-2 text-left">Vendors for Weddings</h2>
                            <p class="headline-small text-left mt-4 font-bold">Choosing a WIN vendor supports the individual artists and creatives of the wedding industry — those who aren’t just talented, but have the experience to match your vision.</p>
                        </div>
                    </div>
                    <div class="w-full mb-[3.25rem] bg-win-light rounded-tl-3xl rounded-br-3xl md:max-w-[50%] md:mx-auto">
                        <div class="text-center mb-12">
                            <div class="flex justify-center mt-[-2.5rem]">
                                <div class="min-w-[100%]">
                                    <img src="/assets/img/home/vendors-for-wedding(s)-5.jpg" class="rounded-tl-3xl md:mt-[-5rem]" alt="Two brides smiling with cheeks pressed together, both in cream lace gowns, as one holds a bouquet triumphantly. Behind them, a beluga whale appears to smile from inside an aquarium tank.">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:grid md:gap-8 md:grid-cols-2 -mb-4 md:mb-0">
                    <div class="w-full mb-[3.25rem] bg-win-light rounded-tl-3xl rounded-br-3xl">
                        <div class="text-center mb-12">
                            <p class="body-copy text-left mt-4">We’re more than a referral program, we’re a destination for couples who care about the professionals they surround themselves with at their wedding. </p>
                            <p class="body-copy text-left mt-4">Together, we are breaking the status quo and orchestrating wow-factor weddings while sustaining small wedding businesses.</p>
                        </div>
                    </div>
                    <div class="w-full lg:px-8 mb-[3.25rem] bg-win-light rounded-tl-3xl rounded-br-3xl">
                        <div class="text-center mb-12">
                            <p class="subheading text-left mt-4">Cue a sigh of relief. Wedding Insiders Network is a haven of pre-vetted vendors with the skills and character to help you plan and enjoy a statement wedding.</p>
                            <p class="body-copy text-left mt-8">With our community-based model, you’ll enjoy a ripple effect of savings — with many vendors offering preferred pricing to WIN couples. Once you’re in, you’re on a simple WINning streak.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="min-w-screen lg:w-[80vw] min-h-[50vh] max-h-[50vh] overflow-hidden hidden md:block px-8 rounded-tl-3xl mx-auto" style="background-image:url('/assets/img/home/wedding-network-11.jpg'); background-size:cover; background-position: bottom;">

    </div>
    <div class="bg-win-light text-center p-4 sm:p-16 md:px-32 md:pt-48 md:pb-32" id="process">
        <h2 class="mb-4 headline-small text-win-red">Booking Wedding Vendors Just Got Easier:</h2>
        <div class="flex justify-center items-center my-8">
            <img src="/assets/img/proccess.PNG" class="max-h-[70vh] md:max-h-none">
        </div>
        <div class="flex w-auto">
            <div class="bg-win-lavender py-8 sm:max-w-[70%] sm:py-16 md:py-32 mx-auto rounded-tl-3xl rounded-br-3xl">
                <h2 class="text-win-red headline-small font-semibold flex-shrink">Create Your Free Profile</h2>
                <p class="body-copy mx-auto mt-4 md:mt-8 max-w-[80%]">Always free for couples — use WIN to hand-select the vendors for your wedding. Tell us about your vision and the vendors you’re looking for. Unlock exclusive savings with preferred pricing available through many of our vendors. Take a look, and find the best for your wedding.</p>
                <div class="mt-8 md:mt-12 min-w-full flex items-center">
                    <a href="/user/register" class="mx-auto button-text py-3 px-10 font-semibold rounded-lg bg-win-purple text-white">
                        SIGN UP FOR FREE
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="min-w-[100%] bg-win-charcoal pb-16 md:pb-48 lg:pb-64 hidden">
        <div class="mx-auto">
            <div class="bg-win-light py-8"></div>
            <div class="md:max-w-[50%] mx-auto">
                <img src="/assets/img/profile.png" class="rounded-full mt-[-6rem] mx-auto w-48 h-48">
                <h3 class="text-center headline-small text-white mt-8 lg:max-w-[75%] mx-auto">“This is a spot for a great
                    testimonial. Here is where a 5 star review would go.”</h3>
                <p class="text-win-light body-copy text-center mt-8 lg:max-w-[75%] mx-auto">Vestibulum sem erat, maximus in pulvinar eu, vehicula eget massa. Donec
                    suscipit hendrerit lorem, eu aliquam justo sodales eget. Vestibulum dignissim
                    ex dui. Nunc libero enim, imperdiet at viverra tempor sodales. </p>
                <p class="subheading text-white mx-auto text-center my-4">SARAH SMITH</p>
            </div>
        </div>
    </div>
    <div class="min-w-screen bg-win-light py-16">
        <h3 class="headline-small text-center underline">Search By Vendor</h3>
        <div class="flex-none text-center button-text mt-4 space-y-4">
            <div class="text-center">
                <a href="/search?type=12">Planner →</a>
            </div>
            <div class="text-center">
                <a href="/search?type=2">Venue →</a>
            </div>
            <div class="text-center">
                <a href="/search?type=9">Photographer →</a>
            </div>
            <div class="text-center">
                <a href="/search?type=13">Videographer →</a>
            </div>
            <div class="text-center">
                <a href="/search?type=7">Hair and makeup artist →</a>
            </div>
            <div class="text-center">
                <a href="/search?type=5">DJ →</a>
            </div>
            <div class="text-center">
                <a href="/search?type=4">Catering →</a>
            </div>
            <div class="text-center">
                <a href="/search">View all listings →</a>
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
    <div class="min-w-screen bg-win-light border-t pt-8 lg:pt-12">
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
    <div class="min-w-screen bg-win-light border-t px-4 pt-8 lg:pt-12">
        <h3 class="subheading text-center">Find Vendors for Your Wedding</h3>
        <h3 class="headline-small text-center">Now Available in New England, New York + New Jersey</h3>

        <div class="px-4 mx-8 mt-4 mb-8 lg:max-w-[70%] mx-auto">
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
</body>

</html>