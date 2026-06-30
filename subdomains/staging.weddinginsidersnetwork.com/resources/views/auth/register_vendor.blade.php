<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>Wedding Advertising For Vendors - Wedding Insider Network</title>
        <meta name="description" content="Wedding Insiders Network is a wedding advertising platform and community for vendors that promotes true community with authentic leads and transparent pricing."/>

        <script async
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJedaphwORrnJVSpwqHuYSzRrGSluQ8Ck&loading=async&callback=initMap">
        </script>
          
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        @include('components.fonts')
    </head>
    @include('layouts.guest_navigation')
    <!-- Announcement Banner -->
    <div class="sticky top-0 z-50 h-[5%] w-[85%] px-4 sm:px-6 lg:px-8 mx-auto hidden">
        <div class="bg-dark-purple-win p-2 rounded-lg text-center mt-2">
        <p class="me-2 inline-block text-white">
            Ready to get started?
        </p>
        <a id="jumpBtn" class="py-2 px-3 inline-flex items-center gap-x-2 md:text-sm font-semibold rounded-full border-2 border-white text-white hover:border-white/70 hover:text-white/70" href="#registrationSection">
            Jump to Sign Up
            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </a>
    </div>
    </div>
    <!-- End Announcement Banner -->
    <body class="bg-win-light overflow-x-hidden">
        <div class="max-w-screen overflow-hidden">
            <div class="flex flex-wrap -mx-4 -mb-4 md:mb-0 lg:min-h-[85vh] max-h-[85vh] items-stretch bg-win-light max-lg:py-8">
                <div class="w-full mb-4 md:mb-0 md:w-3/12 overflow-y-hidden max-h-[85vh]" style="background-image: url('/assets/img/welcome-left.PNG'); background-size:cover;">
                </div>
                <div class="w-full px-4 lg:px-16 xl:px-32 md:w-4/12 md:content-center overflow-y-hidden lg:min-h-[85vh]">
                    <div class="text-center my-auto mx-auto">
                        <img src="/assets/img/tagline/WIN-Tagline-RED-on-PINK.png" class="max-w-[65%] mx-auto py-4">
                        
                        <h1 class="headline-small my-auto">Wedding Advertising</h1>
                        <h2 class="headline-small xl:headline-large mb-2 text-center bg-win-light">Authentic Leads + True Community</h2>
                        <p class="mb-2 subheading">The innovative way to advertise your wedding business and gain a lead-sharing community. </p>
                        <div class="my-6 min-w-full flex items-center">
                            <a href="{{ route('vendor.register.form') }}" class="mx-auto py-2 px-10 font-semibold button-text rounded-full bg-win-blue text-white">
                                GET STARTED
                            </a>
                        </div>
                    </div>
                </div>
                <div class="w-full mb-4 md:mb-0 md:w-5/12 overflow-y-hidden max-h-[85vh]" style="background-image: url('/assets/img/welcome-right.PNG'); background-size:cover;">
                </div>
            </div>
        </div>
        <div class="bg-win-lavender w-screen py-4 text-center">
            <p class="text-win-red subheading">MERIT-BASED WEDDING VENDOR MATCHMAKING</p>
        </div>
        <section id="difference">
            <div class="container px-5 py-24 mx-auto flex flex-wrap">
                <div class="lg:w-1/2 w-full mb-10 lg:mb-0 rounded-br-3xl overflow-hidden">
                <img alt="feature" class="object-cover object-center h-full w-full" src="/assets/img/vendor-home/wedding-advertising-2.jpg">
                </div>
                <div class="flex flex-col flex-wrap lg:py-6 -mb-10 lg:w-1/2 lg:pl-24 text-left">
                <div class="lg:flex gap-x-4 mx-auto hidden p-8">
                    <div class="lg:w-1/2 rounded-tl-3xl overflow-hidden">
                    <img alt="feature" class="object-cover object-center h-full w-full" src="/assets/img/vendor-home/wedding-advertising-3.jpg">
                    </div>
                    <div class="lg:w-1/2 rounded-br-3xl overflow-hidden">
                    <img alt="feature" class="object-cover object-center h-full w-full" src="/assets/img/vendor-home/wedding-advertising-4.jpg">
                    </div>
                </div>
                <h2 class="headline-small text-left mt-4 font-bold">A <span class="italic">Different</span> Wedding Advertising Network That Works Wonders</h2>
                <div class="flex flex-col my-10 items-start">
                    <p class="body-copy">Get excited about a referral system that works for you. Thanks to our exclusive structure, you’ll get verified leads who are ready to book. Without the expensive pay-to-play systems, your listing will be based on real engagements and reviews, allowing the high quality of your work to speak for itself.<br><br></p>
                    <p class="body-copy">Here, the priority is to support local and state-of-the-art businesses like yours while connecting you with ready-to-book leads. Build your profile, invite fellow vendors, boost your rankings. Sharing WIN with your wedding industry friends increases your rankings, and supports our mission to build a better system shoulder to shoulder.</p>
                </div>
                </div>
            </div>
            </section>
        <!-- Features -->
        <section id="items-row" class="bg-win-charcoal text-white">
            <div class="container px-5 py-24 mx-auto">
                <div class="flex flex-wrap sm:-m-4 -mx-4 -mb-10 -mt-4 md:space-y-0 space-y-6">
                <div class="py-4 px-8 md:w-1/4 flex flex-col text-center items-center">
                    <div class="w-10 h-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red mb-5 flex-shrink-0">
                    <svg class="m-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    </div>
                    <div class="flex-grow">
                    <h2 class="subheading mb-3">Less Time + Fees</h2>
                    <p class="body-copy mt-3">Gone are the days of time and fund-consuming advertising that only results in subpar inquiries. Our low monthly fee gives you all-access to leads you want.</p>
                    </div>
                </div>
                <div class="py-4 px-8 md:w-1/4 flex flex-col text-center items-center">
                    <div class="w-10 h-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red mb-5 flex-shrink-0">
                    <svg class="m-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    </div>
                    <div class="flex-grow">
                    <h2 class="subheading mb-3">Vetted Leads</h2>
                    <p class="body-copy mt-3">No more lukewarm leads or ghosting inquiries. These couples are further in the booking process, making them more likely to book and not beat around the bush.</p>
                    </div>
                </div>
                <div class="py-4 px-8 md:w-1/4 flex flex-col text-center items-center">
                    <div class="w-10 h-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red mb-5 flex-shrink-0">
                    <svg class="m-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    </div>
                    <div class="flex-grow">
                    <h2 class="subheading mb-3">Merit-Based</h2>
                    <p class="body-copy mt-3">Your listing is completely determined by your interactions, reviews and engagement on our platform. You won’t find any unfair pay-to-play rankings here.</p>
                    </div>
                </div>
                <div class="py-4 px-8 md:w-1/4 flex flex-col text-center items-center">
                    <div class="w-10 h-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red mb-5 flex-shrink-0">
                    <svg class="m-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    </div>
                    <div class="flex-grow">
                    <h2 class="subheading mb-3">Community-Focused</h2>
                    <p class="body-copy mt-3">Take control of your referrals by using WIN to build your preferred vendor list — renewing old connections and making new ones to increase your leads, and the power of this community.</p>
                    </div>
                </div>
                </div>
            </div>
            </section>
            <section id="differences-table" class="bg-win-light">
                <div class="min-w-screen" style="background-image:url('/assets/img/vendor-home/wedding-advertising-5.jpg'); background-size:cover; background-position: center;">
                    <div class="bg-dark-grey-win bg-opacity-50 h-full py-32 lg:py-64">
                        <div class="text-center my-auto mx-auto md:max-w-[33%]">
                            <h1 class="headline-small text-white mb-2">This Is Where WIN Stands</h1>
                        </div>
                    </div>
                </div>
                <div class="container mt-[-4rem] lg:mt-[-8rem] px-5 py-12 mx-auto bg-white rounded-3xl mb-16">
                    <div class="lg:w-3/4 w-full mx-auto overflow-auto">
                    <table class="table-fixed w-full text-left whitespace-no-wrap">
                        <thead>
                        <tr>
                            <th class="px-4 py-2 text-center subheading text-win-red bg-win-light md:w-3/5">FEATURES</th>
                            <th class="px-4 py-2 text-center subheading text-win-red bg-win-pink md:w-1/5">OTHER PLATFORMS</th>
                            <th class="px-4 py-2 text-center subheading text-win-red bg-win-lavender md:w-1/5">WIN</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="px-2 md:px-6 lg:px-10 lg:py-8 text-center py-4 border-win-light border-x-2 border-b-2">An online storefront customized with your business’s photos, videos, reviews and offerings.</td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-pink text-win-red flex-shrink-0">
                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </div>
                            </td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2 md:px-6 lg:px-10 lg:py-8 text-center py-4 border-win-light border-x-2 border-b-2">Analytics to track how many couples have visited your storefront and how many leads you’ve received and booked.</td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-pink text-win-red flex-shrink-0">
                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </div>
                            </td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2 md:px-6 lg:px-10 lg:py-8 text-center py-4 border-win-light border-x-2 border-b-2">SEO backlinks (do-follow, which heightens your reputation and online credibility).</td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-pink text-win-red flex-shrink-0">
                                <svg class="m-1 inline" fill="currentColor" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>times</title> <path d="M17.769 16l9.016-9.016c0.226-0.226 0.366-0.539 0.366-0.884 0-0.691-0.56-1.251-1.251-1.251-0.346 0-0.658 0.14-0.885 0.367v0l-9.015 9.015-9.016-9.015c-0.226-0.226-0.539-0.366-0.884-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.345 0.14 0.658 0.366 0.884v0l9.015 9.016-9.015 9.015c-0.227 0.226-0.367 0.539-0.367 0.885 0 0.691 0.56 1.251 1.251 1.251 0.345 0 0.658-0.14 0.884-0.366v0l9.016-9.016 9.015 9.016c0.227 0.228 0.541 0.369 0.888 0.369 0.691 0 1.251-0.56 1.251-1.251 0-0.347-0.141-0.661-0.369-0.887l-0-0z"></path> </g></svg>
                                </div>
                            </td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2 md:px-6 lg:px-10 lg:py-8 text-center py-4 border-win-light border-x-2 border-b-2">Merit-based badge ranking system determined by your engagement, reviews and interactions.</td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-pink text-win-red flex-shrink-0">
                                <svg class="m-1 inline" fill="currentColor" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>times</title> <path d="M17.769 16l9.016-9.016c0.226-0.226 0.366-0.539 0.366-0.884 0-0.691-0.56-1.251-1.251-1.251-0.346 0-0.658 0.14-0.885 0.367v0l-9.015 9.015-9.016-9.015c-0.226-0.226-0.539-0.366-0.884-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.345 0.14 0.658 0.366 0.884v0l9.015 9.016-9.015 9.015c-0.227 0.226-0.367 0.539-0.367 0.885 0 0.691 0.56 1.251 1.251 1.251 0.345 0 0.658-0.14 0.884-0.366v0l9.016-9.016 9.015 9.016c0.227 0.228 0.541 0.369 0.888 0.369 0.691 0 1.251-0.56 1.251-1.251 0-0.347-0.141-0.661-0.369-0.887l-0-0z"></path> </g></svg>
                                </div>
                            </td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2 md:px-6 lg:px-10 lg:py-8 text-center py-4 border-win-light border-x-2 border-b-2">Vetted vendors and leads that have been approved through a transparent verification system.</td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-pink text-win-red flex-shrink-0">
                                <svg class="m-1 inline" fill="currentColor" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>times</title> <path d="M17.769 16l9.016-9.016c0.226-0.226 0.366-0.539 0.366-0.884 0-0.691-0.56-1.251-1.251-1.251-0.346 0-0.658 0.14-0.885 0.367v0l-9.015 9.015-9.016-9.015c-0.226-0.226-0.539-0.366-0.884-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.345 0.14 0.658 0.366 0.884v0l9.015 9.016-9.015 9.015c-0.227 0.226-0.367 0.539-0.367 0.885 0 0.691 0.56 1.251 1.251 1.251 0.345 0 0.658-0.14 0.884-0.366v0l9.016-9.016 9.015 9.016c0.227 0.228 0.541 0.369 0.888 0.369 0.691 0 1.251-0.56 1.251-1.251 0-0.347-0.141-0.661-0.369-0.887l-0-0z"></path> </g></svg>
                                </div>
                            </td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2 md:px-6 lg:px-10 lg:py-8 text-center py-4 border-win-light border-x-2 border-b-2">In-network benefits for couples, including an optional cash incentive to book, determined by you.</td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-pink text-win-red flex-shrink-0">
                                <svg class="m-1 inline" fill="currentColor" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>times</title> <path d="M17.769 16l9.016-9.016c0.226-0.226 0.366-0.539 0.366-0.884 0-0.691-0.56-1.251-1.251-1.251-0.346 0-0.658 0.14-0.885 0.367v0l-9.015 9.015-9.016-9.015c-0.226-0.226-0.539-0.366-0.884-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.345 0.14 0.658 0.366 0.884v0l9.015 9.016-9.015 9.015c-0.227 0.226-0.367 0.539-0.367 0.885 0 0.691 0.56 1.251 1.251 1.251 0.345 0 0.658-0.14 0.884-0.366v0l9.016-9.016 9.015 9.016c0.227 0.228 0.541 0.369 0.888 0.369 0.691 0 1.251-0.56 1.251-1.251 0-0.347-0.141-0.661-0.369-0.887l-0-0z"></path> </g></svg>
                                </div>
                            </td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2 md:px-6 lg:px-10 lg:py-8 text-center py-4 border-win-light border-x-2 border-b-2">Service-focused model that minimizes overhead costs and supports small businesses.</td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-pink text-win-red flex-shrink-0">
                                <svg class="m-1 inline" fill="currentColor" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>times</title> <path d="M17.769 16l9.016-9.016c0.226-0.226 0.366-0.539 0.366-0.884 0-0.691-0.56-1.251-1.251-1.251-0.346 0-0.658 0.14-0.885 0.367v0l-9.015 9.015-9.016-9.015c-0.226-0.226-0.539-0.366-0.884-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.345 0.14 0.658 0.366 0.884v0l9.015 9.016-9.015 9.015c-0.227 0.226-0.367 0.539-0.367 0.885 0 0.691 0.56 1.251 1.251 1.251 0.345 0 0.658-0.14 0.884-0.366v0l9.016-9.016 9.015 9.016c0.227 0.228 0.541 0.369 0.888 0.369 0.691 0 1.251-0.56 1.251-1.251 0-0.347-0.141-0.661-0.369-0.887l-0-0z"></path> </g></svg>
                                </div>
                            </td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2 md:px-6 lg:px-10 lg:py-8 text-center py-4 border-win-light border-x-2 border-b-2">Preferred vendor lists — easing the vendor-to-vendor referrals and eliminating outdated PDFs; your couples can shop directly from your WIN profile.</td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-pink text-win-red flex-shrink-0">
                                <svg class="m-1 inline" fill="currentColor" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>times</title> <path d="M17.769 16l9.016-9.016c0.226-0.226 0.366-0.539 0.366-0.884 0-0.691-0.56-1.251-1.251-1.251-0.346 0-0.658 0.14-0.885 0.367v0l-9.015 9.015-9.016-9.015c-0.226-0.226-0.539-0.366-0.884-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.345 0.14 0.658 0.366 0.884v0l9.015 9.016-9.015 9.015c-0.227 0.226-0.367 0.539-0.367 0.885 0 0.691 0.56 1.251 1.251 1.251 0.345 0 0.658-0.14 0.884-0.366v0l9.016-9.016 9.015 9.016c0.227 0.228 0.541 0.369 0.888 0.369 0.691 0 1.251-0.56 1.251-1.251 0-0.347-0.141-0.661-0.369-0.887l-0-0z"></path> </g></svg>
                                </div>
                            </td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-2 md:px-6 lg:px-10 lg:py-8 text-center py-4 border-win-light border-x-2 border-b-2">Direct, in-person leads from wedding expos, thanks to WIN representatives mingling with couples to bring them back to the platform where you’re listed.</td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-pink text-win-red flex-shrink-0">
                                <svg class="m-1 inline" fill="currentColor" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>times</title> <path d="M17.769 16l9.016-9.016c0.226-0.226 0.366-0.539 0.366-0.884 0-0.691-0.56-1.251-1.251-1.251-0.346 0-0.658 0.14-0.885 0.367v0l-9.015 9.015-9.016-9.015c-0.226-0.226-0.539-0.366-0.884-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.345 0.14 0.658 0.366 0.884v0l9.015 9.016-9.015 9.015c-0.227 0.226-0.367 0.539-0.367 0.885 0 0.691 0.56 1.251 1.251 1.251 0.345 0 0.658-0.14 0.884-0.366v0l9.016-9.016 9.015 9.016c0.227 0.228 0.541 0.369 0.888 0.369 0.691 0 1.251-0.56 1.251-1.251 0-0.347-0.141-0.661-0.369-0.887l-0-0z"></path> </g></svg>
                            </div>
                            </td>
                            <td class="p-6 border-win-light border-x-2 border-b-2">
                                <div class="w-10 h-10 mx-auto my-auto rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 lg:px-10 py-8 lg:py-16"></td>
                            <td class="p-6 bg-win-pink">
                                <h5 class="subheading text-center my-auto pb-0 text-win-red">$150-$500<br> / month</h5>
                            </td>
                            <td class="p-6 bg-win-lavender">
                                <h5 class="subheading text-center my-auto pb-0 text-win-red">$59<br> / month</h5>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="absolute">
                        <img src="/assets/img/shapes/confetti-small-transparent.png" class="w-[60%] h-[60%] mt-[-20%] hidden lg:block">
                    </div>
                    <div class="mt-6 min-w-full flex items-center">
                            <a href="{{ route('vendor.register.form') }}" class="mx-auto py-2 px-10 font-semibold rounded-lg bg-win-purple text-white button-text">
                                JOIN US
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <div class="bg-win-light text-center p-8 md:px-32 md:py-48" id="process">
                <h2 class="mb-4 headline-small text-win-red">Find Clients + A Community Too</h2>
                <div class="flex justify-center items-center my-8">
                    <img src="/assets/img/vendor-home/vendor-process.PNG" class="md:max-h-none">
                </div>
            </div>
            <div class="min-w-screen bg-win-lavender">
                <div class="bg-win-lavender h-full pb-16 mx-4 md:mx-24 md:pb-32 lg:pb-48 xl:mx-32 pt-8 lg:pt-16">
                    <div class="min-w-[100%] mx-auto">
                        <img src="/assets/img/vendor-home/wedding-advertising-6.jpg" class="rounded-tl-3xl max-w-[25%] mx-auto left-0 right-0 hidden lg:block">
                    </div>
                    <div class="text-center my-auto mx-auto md:max-w-[65%] pt-8">
                        <h1 class="headline-small text-win-red mb-2">Register + Join The Network</h1>
                        <p class="subheading mt-4">This is more than just wedding advertising, it’s a community where couples and vendors alike can finally make the authentic connections they’ve been waiting for.</p>
                    </div>
                    <div id="registrationSection" class="mt-4 lg:mt-12 hidden">
                        <div class="h-4/7 flex flex-row items-center justify-center">
                            <div class="container bg-white rounded-2xl p-4 md:p-8 lg:p-16">
                                <form id="vendorRegisterForm" method="POST" action="{{ route('vendor.register') }}">
                                    @csrf
                                    <div>
                                        <div class="md:grid md:grid-cols-2 gap-4 mt-4 mx-4 lg:mx-16">
                                            <div class="mt-4">
                                                <x-input-label class="font-semibold" for="first_name" :value="__('First Name')" />
                                                <input id="first_name" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="text" name="first_name" :value="old('first_name')" required autocomplete="first_name" />
                                                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                            </div>
                                            
                                            <div class="mt-4">
                                                <x-input-label class="font-semibold" for="last_name" :value="__('Last Name')" />
                                                <input id="last_name" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="text" name="last_name" :value="old('last_name')" required autocomplete="last_name" />
                                                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                            </div> 
                                            <div class="mt-4">
                                                <x-input-label class="font-semibold" for="vendor_email" :value="__('Email')" />
                                                <input id="vendor_email" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="email" name="email" :value="old('email')" required autocomplete="email" />
                                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <x-input-label class="font-semibold" for="role_select" :value="__('Select Role')" />
                                                <select id="role_select" name="role" class="py-3 px-4 pe-9 block w-full border-0 md:text-sm rounded-full bg-win-light text-win-charcoal">
                                                    <option selected>Choose your role</option>
                                                    @foreach($vendor_types as $type)
                                                    <option value="{{ $type->id }}">{{ $type->type }}</option>
                                                    @endforeach
                                                </select>
                                            </div> 
                                            <div class="mt-4">
                                                <x-input-label class="font-semibold inline" for="password" :value="__('Password')" />
                                                <button type="button"  data-hs-toggle-password='{
                                                    "target": "#password"
                                                    }' id="show-password-toggle" class="inline-flex inline items-center z-20 px-2 cursor-pointer text-gray-400 rounded-e-md focus:outline-none focus:text-blue-600 dark:text-neutral-600 dark:focus:text-blue-500">
                                                <svg class="shrink-0 size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                                    <path class="hs-password-active:hidden" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                                    <path class="hs-password-active:hidden" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                                    <line class="hs-password-active:hidden" x1="2" x2="22" y1="2" y2="22"></line>
                                                    <path class="hidden hs-password-active:block" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                    <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                </button>
                                                <input id="password" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0"
                                                                type="password"
                                                                name="password"
                                                                required autocomplete="new-password" />
                                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <x-input-label class="font-semibold inline" for="password_confirmation" :value="__('Confirm Password')" />
                                                <button type="button"  data-hs-toggle-password='{
                                                    "target": "#password_confirmation"
                                                    }' id="show-password-confirmation-toggle" class="inline-flex inline items-center z-20 px-2 cursor-pointer text-gray-400 rounded-e-md focus:outline-none focus:text-blue-600 dark:text-neutral-600 dark:focus:text-blue-500">
                                                <svg class="shrink-0 size-3.5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                                    <path class="hs-password-active:hidden" d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                                    <path class="hs-password-active:hidden" d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                                    <line class="hs-password-active:hidden" x1="2" x2="22" y1="2" y2="22"></line>
                                                    <path class="hidden hs-password-active:block" d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                                    <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                </button>
                    
                                                <input id="password_confirmation" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0"
                                                                type="password"
                                                                name="password_confirmation" required autocomplete="new-password" />
                    
                                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                            </div>
                                        </div>
                                        @isset($ref_id)
                                        <input name="ref_by" type="text" value="{{ $ref_id }}" hidden>
                                        @endisset
                                        <div>              
                                        </div>
                                    </div>
                                    <div class="md:grid md:grid-cols-2 gap-4 mx-4 lg:mx-16">
                                        <div>
                                            <div class="mt-4">
                                                <x-input-label class="font-semibold" for="business_name" :value="__('Business Name')" />
                                                <input id="business_name" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="text" name="business_name" :value="old('business_name')" required autocomplete="business_name" />
                                                <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <x-input-label class="font-semibold" for="weddings_num" :value="__('How many weddings do you service per year?')" />
                                                <input id="weddings_num" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="text" name="weddings_num" :value="old('weddings_num')" required/>
                                                <x-input-error :messages="$errors->get('weddings_num')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mt-4">
                                                <x-input-label class="font-semibold" for="business_website" :value="__('Business Website')" />
                                                <input id="business_website" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="text" name="business_website" :value="old('business_website')" required autocomplete="business_website" />
                                                <x-input-error :messages="$errors->get('business_website')" class="mt-2" />
                                            </div>
                                            <div class="mt-4">
                                                <x-input-label class="font-semibold" for="client_select" :value="__('What is your average client booking value?')" />
                                                <select id="client_select" name="booking_val" class="py-3 px-4 pe-9 block w-full border-0 md:text-sm rounded-full bg-win-light text-win-charcoal">
                                                    <option selected>Choose a value</option>
                                                    <option value="1">$500 or less</option>
                                                    <option value="2">$500-$2,000</option>
                                                    <option value="3">$2,000-$3,000</option>
                                                    <option value="4">$3,000-$5,000</option>
                                                    <option value="5">$5,000-$8,000</option>
                                                    <option value="6">$8,000-$10,000</option>
                                                    <option value="7">$12,000 or more</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <input id="offered_discount" name="offered_discount" type="number" hidden>
                                    <div class="mt-3 mx-4 lg:mx-16">
                                        <x-input-label class="font-semibold" :value="__('Preferred pricing')" />
                                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-2 mt-2">
                                            <label for="discount-0" class="flex p-3 w-full bg-win-light border-0 rounded-full md:text-sm focus:border-win-lavender focus:ring-win-lavender">
                                                <input type="radio" name="discount-val" value="0" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple focus:ring-win-purple" id="discount-0">
                                                <span class="md:text-sm text-win-charcoal ms-3">$0</span>
                                            </label>
                                            <label for="discount-50" class="flex p-3 w-full bg-win-light border-0 rounded-full md:text-sm focus:border-win-lavender focus:ring-win-lavender">
                                            <input type="radio" name="discount-val" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple focus:ring-win-purple" id="discount-50">
                                            <span class="md:text-sm text-win-charcoal ms-3">$50</span>
                                            </label>
                                            <label for="discount-75" class="flex p-3 w-full bg-win-light border-0 rounded-full md:text-sm focus:border-win-lavender focus:ring-win-lavender">
                                            <input type="radio" name="discount-val" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple focus:ring-win-purple" id="discount-75">
                                            <span class="md:text-sm text-win-charcoal ms-3">$75</span>
                                            </label>
                                            <label for="discount-100" class="flex p-3 w-full bg-win-light border-0 rounded-full md:text-sm focus:border-win-lavender focus:ring-win-lavender">
                                            <input type="radio" name="discount-val" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple focus:ring-win-purple" id="discount-100" >
                                            <span class="md:text-sm text-win-charcoal ms-3">$100</span>
                                            </label>
                                            <label for="discount-150" class="flex p-3 w-full bg-win-light border-0 rounded-full md:text-sm focus:border-win-lavender focus:ring-win-lavender">
                                            <input type="radio" name="discount-val" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple focus:ring-win-purple" id="discount-150" >
                                            <span class="md:text-sm text-win-charcoal ms-3">$150</span>
                                            </label>
                                            <label for="discount-200" class="flex p-3 w-full bg-win-light border-0 rounded-full md:text-sm focus:border-win-lavender focus:ring-win-lavender">
                                            <input type="radio" name="discount-val" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple focus:ring-win-purple" id="discount-200" >
                                            <span class="md:text-sm text-win-charcoal ms-3">$200</span>
                                            </label>
                                            <label for="discount-250" class="flex p-3 w-full bg-win-light border-0 rounded-full md:text-sm focus:border-win-lavender focus:ring-win-lavender">
                                            <input type="radio" name="discount-val" class="shrink-0 mt-0.5 border-0 rounded-full text-win-purple focus:ring-win-purple" id="discount-250" >
                                            <span class="md:text-sm text-win-charcoal ms-3">$250</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="md:grid md:grid-cols-2 gap-4 mx-4 lg:mx-16">
                                        <div>
                                            <div class="mt-4">
                                                <x-input-label class="font-semibold" for="location" :value="__('Location')" />
                                                <input id="location" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="text" name="location" :value="old('location')" required />
                                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mt-4">
                                                <x-input-label class="font-semibold" for="service_radius" :value="__('Service Radius (miles)')" />
                                                <input id="service_radius" class="block mt-1 w-full rounded-full bg-win-light text-win-charcoal border-0" type="text" name="service_radius" :value="old('service_radius', 50)" required/>
                                                <x-input-error :messages="$errors->get('service_radius')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full h-48 md:h-64 mt-4" id="map">

                                    </div>
                                    <x-input-error :messages="$errors->get('offered_discount')" class="mt-2" />
                                    <div class="flex items-center justify-end mt-4">
                                        <a class="underline text-win-charcoal font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                                            {{ __('Already registered?') }}
                                        </a>
                                        <button type="submit" class="ms-4 py-2 px-10 font-semibold rounded-lg bg-win-purple text-white">REGISTER NOW</button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="min-w-[100%] bg-win-charcoal pb-16 md:pb-48 lg:pb-64 hidden">
        <div class="mx-auto">
            <div class="bg-win-lavender py-8"></div>
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
    <div class="min-w-[100vw] relative z-0 hidden md:block">
        <div class="grid grid-cols-2 gap-16 lg:max-w-[60%] mx-auto">
            <div class="text-center mx-auto mb-[-75%] mt-[-25%]">
                <img class="rounded-tl-3xl mb-4" src="/assets/img/for-vendors.PNG">
            </div>
            <div class="text-center mx-auto mb-[-75%] mt-[-25%]">
                <img class="rounded-tl-3xl mb-4" src="/assets/img/for-couples.PNG">
            </div>
        </div>
    </div>
    <div class="min-w-screen pt-12 mb-[-5%] bg-win-light" style="background-image:url('/assets/img/WIN-Large-Confetti-Pattern.jpg');" id="faq">
        <div class="relative md:min-h-[20vh] text-win-light">s</div>
        <!-- FAQ -->
        <div class="max-w-[85rem] lg:max-w-[50%] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto bg-white rounded-3xl relative z-50">
            <!-- Title -->
            <div class="max-w-5xl mx-auto text-center mb-10 lg:mb-14 mt-8">
                <h2 class="text-win-red headline-small">Frequently Asked Questions</h2>
                <p class="body-copy">FAQS for how we advertise your wedding services</p>
            </div>
            <!-- End Title -->

            <div class="max-w-5xl mx-auto">
                <!-- Accordion -->
                <div class="hs-accordion-group">
                    <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6 active" id="hs-basic-with-title-and-arrow-stretched-heading-one">
                        <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full subheading text-start text-gray-800 rounded-lg" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-one">
                            What makes WIN different?
                            <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                            <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                        <div id="hs-basic-with-title-and-arrow-stretched-collapse-one" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-one">
                            <p class="text-gray-800 body-copy">
                            WIN was built by wedding vendors who, like you, grew tired of antiquated vendor lists: endless advertising costs, shady pricing structures, ghosted leads and pay-to-play ranking systems. Wedding Insiders Network is a merit-based system that ranks businesses based on real client and community engagement. With us, you won’t pay for your placement. You’ll earn your rankings. Plus, we’re streamlining the way you receive and share referrals.
                            </p>
                        </div>
                    </div>

                    <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full subheading text-start text-gray-800 rounded-lg" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                        Are there any additional fees to list my business?
                            <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                            <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                        <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <p class="text-gray-800 body-copy">
                            Unlike other vendor listing systems, our listing fees are straightforward. No tiered advertising packages or hidden premiums. For a single business listing, it’s a flat rate of $59 per month. Additional listings are just $29 per month, per listing. For example, if you want to be listed under the photography category, you’ll pay $59 per month. If you’re a photographer and videographer duo that wants to be listed under the photography and videography categories, you’ll pay the base $59 fee plus an additional listing fee of $29 per month. That’s it. Yes, really.
                            </p>
                        </div>
                    </div>

                    <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full subheading text-start text-gray-800 rounded-lg" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                        What do you mean by “vetted leads?”
                            <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                            <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                        <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <p class="text-gray-800 body-copy">
                            With other vendor listing services, anyone can open the website, click on a vendor and submit an inquiry. They could be months (or years) away from booking a vendor. Or let’s face it, they could be spam too. We have an in-network and out-of-network system that helps you identify the couples that have been vetted as ready-to-book and those that have not. When a couple is marked as in-network, it means they have been referred to WIN by a previously booked vendor. This means they are a real couple that is actively searching for their next vendor (and proven to be ready for that commitment).
                            </p>
                        </div>
                    </div>

                    <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full subheading text-start text-gray-800 rounded-lg" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                        Which regions is WIN available in?
                            <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                            <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                        <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <p class="text-gray-800 body-copy">
                            As our network builds its foundation, WIN includes vendors within the Northeastern United States. That’s New England, New York and New Jersey. We are excited to expand our network to other regions soon! Join the waitlist, and we’ll notify you when WIN is available in your area.
                            </p>
                        </div>
                    </div>

                    <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full subheading text-start text-gray-800 rounded-lg" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                        How can I track the performance of my listing on WIN?
                            <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                            <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                        <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <p class="text-gray-800 body-copy">
                            Each vendor profile includes a chart that tracks network engagement and how it contributes to your listing within the region. Badges, vendor referrals, client reviews, vendor community interactions, and client community interactions all contribute to the position of your ranking. This tool will help you identify what’s working for you and how to improve your ranking, giving you a say on your placement within the algorithm (without extra costs).
                            </p>
                        </div>
                    </div>

                    <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full subheading text-start text-gray-800 rounded-lg" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                        I’m excited to join! Is there a trial period for new WIN vendors?
                            <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                            <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                        <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                            <p class="text-gray-800 body-copy">
                            We’re excited to have you! We offer a 30-day free trial for vendors who are excited to try WIN, but want to test the waters first. This free version gives you access to list on our network, share a limited portfolio, and customize your storefront. If you love it, you can join the full version to get all of the benefits of our unique ranking system. But if it’s not the right fit, you can cancel the trial online. Start your free trial here!
                            </p>
                        </div>
                    </div>
                </div>
                <!-- End Accordion -->
            </div>
        </div>
        <!-- End FAQ -->
    </div>
    <div class="min-w-screen" style="background-image:url('/assets/img/call-to-action-bottom.jpg'); background-size:cover; background-position: center;">
        <div class="bg-dark-grey-win bg-opacity-50 h-full mx-auto flex justify-center">
            <div class="text-center mx-auto md:max-w-[75%] min-h-[80vh] flex inline-flex items-center justify-center">
                <div class="">
                <h2 class="headline-small text-white my-auto">The Synergy + Seamless System You’ve Been Looking For</h2>
                <div class="my-6 min-w-full flex items-center">
                    <a href="{{ route('vendor.register.form')}}" class="mx-auto py-2 px-10 font-semibold button-text rounded-lg bg-win-blue text-white">
                        GET STARTED
                    </a>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-win-blue w-screen py-4 text-center">
        <p class="text-white subheading">MERIT-BASED WEDDING VENDOR MATCHMAKING</p>
    </div>

        @include('layouts.footer')
        <div id="discount-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
            <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
              <div class="relative flex flex-col bg-white shadow-lg rounded-xl">
                <div class="absolute top-2 end-2">
                  <button type="button" class="flex justify-center items-center size-7 md:text-sm font-semibold rounded-lg border border-transparent text-gray-800 hover:bg-gray-100" data-hs-overlay="#hs-sign-out-alert">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                  </button>
                </div>
                <div class="p-4 sm:p-10 text-center overflow-y-auto">
                  <span class="mb-4 inline-flex justify-center items-center size-[62px] rounded-full border-4 border-yellow-50 bg-yellow-100 text-yellow-500">
                    <svg class="flex-shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                      <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                  </span>
                  <h3 class="mb-2 text-2xl font-bold text-gray-800">
                    Sign out
                  </h3>
                  <p class="text-gray-500">
                    Are you sure?
                  </p>
                  <div class="mt-6 flex justify-center gap-x-4">
                    <a class="py-2 px-3 inline-flex items-center gap-x-2 md:text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm" href="#">
                      Sign out
                    </a>
                    <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 md:text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700" data-hs-overlay="#hs-sign-out-alert">
                      Cancel
                    </button>
                  </div>
                </div>
              </div>
            </div>
        </div>
        @isset($ref_model)
        <script>
            $('#first_name').val("{{ $ref_model->first_name }}");
            $('#last_name').val("{{ $ref_model->last_name }}");
            $('#email').val("{{ $ref_model->email }}");
        </script>
        @endisset
        <script>
            $(document).ready(function() {
                $('#client_select').on('change', function() {
                    let discountVal = "X";
                    console.log($('#client_select').val());
                    switch($('#client_select').val()){
                        case "1":
                            $('#discount-50').prop("checked", true);
                            discountVal = 50;
                            break;
                        case "2":
                            $('#discount-50').prop("checked", true);
                            discountVal = 50;
                            break;
                        case "3":
                            $('#discount-75').prop("checked", true);
                            discountVal = 75;
                            break;
                        case "4":
                            $('#discount-100').prop("checked", true);
                            discountVal = 100;
                            break;
                        case "5":
                            $('#discount-150').prop("checked", true);
                            discountVal = 150;
                            break;
                        case "6":
                            $('#discount-200').prop("checked", true);
                            discountVal = 200;
                            break;
                        case "7":
                            $('#discount-250').prop("checked", true);
                            discountVal = 250;
                            break;
                        default:
                            break;
                    }
                    Swal.fire({
                        title: 'Nice!',
                        text: `Based on your average booking value and what other vendors in your role are offering, we 
                        recommend you offer a $` + discountVal + ` discount to our in-network clients.
                         In network discounts encourage clients to stay in-network, 
                         book more vendors, and provides vendors with merit badges to help with your on-site rankings.`,
                        icon: 'success',
                        confirmButtonText: 'Continue',
                        confirmButtonColor: '#6432C8'
                    });
                    $('#offered_discount').val(discountVal);
                });
                $(document).on("keypress", function(e){
                    if (e.which === 13) { 
                        $('#vendorRegisterForm').submit(); 
                        console.log('form submitted'); 
                    } 
                });
                @if($errors->any())
                $('html, body').animate({
                    scrollTop: $("#registrationSection").offset().top
                }, 100);
                console.log("errors");
                @endif
            });
        </script>
        <script>
            $(".ne-state").on('click', (e) => {
                console.log(e.target.id);
                $('#' + e.target.id).attr( 'fill', "#F9C7CE");
            });
            let map;
            async function initMap() {
                const { Map } = await google.maps.importLibrary("maps");
                map = new Map(document.getElementById("map"), {
                    center: { lat: 41.7658, lng: -72.6734 },
                    zoom: 6,
                    mapId: "6eaab94585b0841a"
                });
            }
            let circleRad = null;
            async function findPlaces(query, serviceRadius = 50) {
                const { Place } = await google.maps.importLibrary("places");
                //@ts-ignore
                const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
                const request = {
                    textQuery: query,
                    fields: ["displayName", "location", "rating"],
                    language: "en-US",
                    maxResultCount: 1,
                    region: "us",
                    useStrictTypeFiltering: false,
                };
                //@ts-ignore
                const { places } = await Place.searchByText(request);
                if (places.length) {
                    console.log(places);

                    const { LatLngBounds } = await google.maps.importLibrary("core");
                    const bounds = new LatLngBounds();

                    // Loop through and get all the results.
                    places.forEach((place) => {
                    const markerView = new AdvancedMarkerElement({
                        map,
                        position: place.location,
                        title: place.displayName,
                    });

                    bounds.extend(place.location);
                    console.log(place);
                    if (circleRad && circleRad.setMap) {
                        circleRad.setMap(null);
                    }
                    circleRad = new google.maps.Circle({
                        strokeColor: "#FF0000",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: "#FF0000",
                        fillOpacity: 0.35,
                        map,
                        center: place.location,
                        radius: serviceRadius * 1609.34,
                    });
                    });
                    map.setCenter(bounds.getCenter());
                } else {
                    console.log("No results");
                }
                }

            initMap();
            $('#location').on('change', function() {
                findPlaces($('#location').val(), $('#service_radius').val());
            });
            
            $('#service_radius').on('change', function() {
                findPlaces($('#location').val(), $('#service_radius').val());
            });
        </script>
        <script>
            const passwordToggle = document.querySelector('#show-password-toggle');

            passwordToggle.addEventListener('click', function() {
            const password = document.querySelector('#password');

            if (password.type === 'password') {
                password.type = 'text';
            } else {
                password.type = 'password';
            }

            password.focus();
            });
            
            const passwordCToggle = document.querySelector('#show-password-confirmation-toggle');

            passwordCToggle.addEventListener('click', function() {
            const passwordC = document.querySelector('#password_confirmation');

            if (passwordC.type === 'password') {
                passwordC.type = 'text';
            } else {
                passwordC.type = 'password';
            }

            passwordC.focus();
            });
        </script>
        
        <script>
            @isset($ref_user)
            $("#first_name").val("{!! $ref_user->first_name !!}");
            $("#last_name").val("{!! $ref_user->last_name !!}");
            $("#vendor_email").val("{!! $ref_user->email !!}");
            @endisset
        </script>
    </body>
</html>

