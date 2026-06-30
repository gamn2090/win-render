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

<body class="antialiased overflow-x-hidden w-screen bg-white">
    
    <div class="min-w-screen" style="background-image:url('/assets/img/join-us/wedding-businesses-1.jpg'); background-size:cover; background-position: top;">
        <div class="bg-dark-grey-win bg-opacity-25 h-full mx-auto flex justify-center">
            <div class="text-center mx-auto lg:max-w-[50%] min-h-[80vh] flex inline-flex items-center justify-center">
                <div class="">
                <h1 class="subheading text-white my-auto">Wedding Businesses + Couples</h1>
                <p class="headline-large text-white mt-4">WIN IS Now Available in New England, New York + New Jersey</p>
                <p class="subheading text-white">Providing you with exclusive access to top-rated wedding vendors, more savings and less emails to sift through. All of the vendor recommendations, contact lists and reviews are here with the possibility of a cash savings of up to $250 per service. The doors are open in the Northeast and we’ll be expanding to your region soon. Featured by merit, founded to support artistry and true collaboration that benefits couples and vendors.
                </p>
                <div class="my-6 min-w-full flex items-center">
                    <a href="/user/register" class="mx-auto py-2 px-10 font-semibold button-text rounded-lg bg-win-blue text-white disabled:opacity-50 disabled:pointer-events-none">
                        JOIN OUR NETWORK
                    </a>
                </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="min-w-[100vw] px-8 pt-24 pb-6 text-center relative z-20">
        <h2 class="headline-small text-win-red">The Right Vendors Are <span class="italic">Right This Way</span></h2>
        <p class="subheading">Free for couples, and just one flat monthly payment for vendors.</p>
    </div>
    <div class="min-w-[100vw] py-12 relative z-20">
        <div class="lg:max-w-[50%] mx-auto">
            <div class="text-center">
                <img class="rounded-tl-3xl mb-4 mx-auto lg:max-h-[40vh]" src="/assets/img/home/vendors-for-wedding(s)-2.jpg">
                <div class="my-6 min-w-full flex items-center">
                    <a href="/user/register" class="mx-auto py-2 px-10 button-text font-semibold rounded-lg bg-win-purple text-white">
                        I'M GETTING MARRIED
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="relative min-h-[2vh]"> </div>
    <div class="min-w-screen bg-white">
        <div class="bg-win-light h-full mx-4 md:mx-24 xl:mx-32 pb-8 lg:pb-16">
            <img src="/assets/img/about/wedding-network-7.jpg" class="max-h-[40vh] mx-auto">
            <div class="text-center my-auto mx-auto">
                <div class="lg:max-w-[50vw] mx-auto -mb-4 md:mb-0">
                    <div class="w-full bg-win-light rounded-tl-3xl rounded-br-3xl pt-16 lg:pt-24 px-8 lg:px-24">
                        <div class="text-center mb-12">
                            <h2 class="headline-small text-left mt-4 font-bold">The Difference? This Is A Refreshing, Forward-Thinking Way To Find Vendors For Your Wedding.</h2>
                        </div>
                        <div class="text-center mb-12">
                            <p class="body-copy text-left mt-4">Stirred up by a frustration, founded to foster something better because finding wedding vendors had become one of two norms — <span class="italic">antiquated or ambiguous.</span></p>
                            <p class="body-copy text-left mt-4"><span class="italic">Antiquated</span> when all you could do was rely on word of mouth, and those “Hey, here’s who I recommend” emails with a note to “Tell them I sent you.”</p>
                            <p class="body-copy text-left mt-4"><span class="italic">Ambiguous</span> when the only other fast and convenient option was to look through big-box directory websites with payment metrics that prioritize businesses that pay more.</p>
                            <p class="body-copy text-left mt-4">So, how do you find vendors based on true recommendations that are the right fit? You come to WIN where preferred vendor lists (from the businesses you’ve already booked) and suggested vendors are all in one place, and with a transparent ranking system that gives all vendors an equal footing.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative z-50">
        <div class="flex w-auto">
            <div class="max-w-[90%] px-16 pt-16 lg:px-32 xl:px-48 md:px-32 md:pt-32 mx-auto">
                <div class="container lg:max-w-[60%] mx-auto overflow-x-hidden">
                    <img src="/assets/img/shapes/confetti-small-transparent.png" class="absolute left-10 top-[-15%] hidden lg:block">
                        <h2 class="text-4xl headline-small font-semibold text-center">Time To Look Forward. We’re Eliminating Old-School Practices That Prioritize The Who’s-Who Rather Than The Who-Can.</h2>
                        <p class="subheading mx-auto max-w-[80%] text-center mt-4">Here’s how we can help you. We’ve taken a step back from the wedding industry status quo to refocus on trust and true expertise with a merit-based system that allows everyone to start with an even playing field.</p>
                </div>
                <div class="lg:grid lg:grid-cols-3 lg:gap-8 mt-16 lg:mx-16 relative z-50">
                    
                    <div class="rounded-tl-3xl" style="background-image: url('/assets/img/about/help-1.jpg'); background-size:cover; background-position: bottom;
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
                                    <a href="/user/register" class="mx-auto py-2 px-10 font-semibold rounded-full bg-win-blue text-white">
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
                                    <a href="/user/register" class="mx-auto py-2 px-10 font-semibold rounded-full bg-win-blue text-white">
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
                                    <a href="/user/register" class="mx-auto py-2 px-10 font-semibold rounded-full bg-win-blue text-white">
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
    
    <div class="min-w-screen bg-white" id="what-makes-us-different">
        <div class="bg-win-light h-full pb-8 sm:mx-16 md:mx-24 xl:mx-32">
            <div class="text-center my-auto mx-auto px-4 md:max-w-[65%] pt-16">
                <div class="md:grid md:gap-8 md:grid-cols-2 -mb-4 md:mb-0">
                    <div class="w-full mb-8 bg-win-light rounded-tl-3xl rounded-br-3xl">
                        <div class="text-center mb-12">
                            <img src="/assets/img/tagline/WIN-Tagline-RED-on-PINK.png" class="max-w-[50%] py-4">
                            <h2 class="subheading mb-2 text-left">Vendors for Weddings</h2>
                            <p class="headline-small text-left mt-4 font-bold">Choosing a WIN vendor supports the individual artists and creatives of the wedding industry — those who aren’t just talented, but have the experience to match your vision.</p>
                        </div>
                    </div>
                    <div class="w-full mb-8 bg-win-light rounded-tl-3xl rounded-br-3xl md:max-w-[50%] md:mx-auto">
                        <div class="text-center mb-12">
                            <div class="flex justify-center">
                                <div class="min-w-[100%]">
                                    <img src="/assets/img/home/vendors-for-wedding(s)-5.jpg" class="rounded-tl-3xl" alt="Two brides smiling with cheeks pressed together, both in cream lace gowns, as one holds a bouquet triumphantly. Behind them, a beluga whale appears to smile from inside an aquarium tank.">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:grid md:gap-8 md:grid-cols-2 -mb-4 md:mb-0">
                    <div class="w-full mb-8 bg-win-light rounded-tl-3xl rounded-br-3xl">
                        <div class="text-center mb-12">
                            <p class="body-copy text-left mt-4">We’re more than a referral program, we’re a destination for couples who care about the professionals they surround themselves with at their wedding. </p>
                            <p class="body-copy text-left mt-4">Together, we are breaking the status quo and orchestrating wow-factor weddings while sustaining small wedding businesses.</p>
                        </div>
                    </div>
                    <div class="w-full lg:px-8 mb-8 bg-win-light rounded-tl-3xl rounded-br-3xl">
                        <div class="text-center mb-12">
                            <p class="subheading text-left mt-4">Cue a sigh of relief. Wedding Insiders Network is a haven of pre-vetted vendors with the skills and character to help you plan and enjoy a statement wedding.</p>
                            <p class="body-copy text-left mt-8">With our community-based model, you’ll enjoy a ripple effect of savings — with many vendors offering preferred pricing to WIN couples. Once you’re in, you’re on a simple WINning streak.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-win-light p-4 md:p-12 lg:p-24">
        <h2 class="headline-small lg:headline-large text-center mb-4 md:mb-8">Have A Question? Submit Yours Here.</h2>
        <script charset="utf-8" type="text/javascript" src="https://js.hsforms.net/forms/embed/v2.js"></script>
        <script>
        hbspt.forms.create({
            portalId: "47914874",
            formId: "e1adba8f-81fa-4e63-b856-27d515e04895"
        });
        </script>
        <div class="w-full hidden">
            <div class="max-w-5xl mx-auto px-6 sm:px-6 lg:px-8 mb-12">
                <div class="w-full bg-white rounded-xl p-8 sm:p-12">
                    <form action="" method="post">
                        <div class="md:flex items-center mt-12">
                            <div class="w-full md:w-1/2 flex flex-col">
                                <label class="font-semibold leading-none">First Name</label>
                                <input type="text" class="leading-none text-gray-50 p-3 mt-4 border-0 bg-win-light rounded-lg" />
                            </div>
                            <div class="w-full md:w-1/2 flex flex-col md:ml-6 md:mt-0 mt-4">
                                <label class="font-semibold leading-none">Last Name</label>
                                <input type="text" class="leading-none text-gray-50 p-3 mt-4 border-0 bg-win-light rounded-lg" />
                            </div>
                        </div>
                        <div class="md:flex items-center mt-8">
                            <div class="w-full md:w-1/2 flex flex-col">
                                <label class="font-semibold leading-none">Email</label>
                                <input type="email" class="leading-none text-gray-50 p-3 mt-4 border-0 bg-win-light rounded-lg" />
                            </div>
                            <div class="w-full md:w-1/2 flex flex-col md:ml-6 md:mt-0 mt-4">
                                <label class="font-semibold leading-none">Subject</label>
                                <input type="text" class="leading-none text-gray-50 p-3 mt-4 border-0 bg-win-light rounded-lg" />
                            </div>
                        </div>
                        <div>
                            <div class="w-full flex flex-col mt-8">
                                <label class="font-semibold leading-none">Message</label>
                                <textarea type="text" class="h-40 text-base leading-none text-gray-50 p-3 mt-4 border-0 bg-win-light rounded-lg"></textarea>
                            </div>
                        </div>
                        <div class="my-6 w-full">
                            <button type="button" style="line-height:1; font-size:0.875rem;" class="button-text ml-auto mr-0 font-semibold inline flex py-3 px-10 gap-x-2 rounded-lg bg-win-blue text-white">
                                SEND IT
                            </button>
                        </div>
                    </form>
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