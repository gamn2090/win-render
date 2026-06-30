<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Wedding Network - Wedding Insiders Network</title>
    <meta name="description" content="Introducing Wedding Insiders Network, a wedding network of couples and vendors who care about relationships, reputations + remarkable experiences."/>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
    <style>
        body {
            background-color: 222323;
        }
        .text-overflow-center {
            margin-left: -10%;
            margin-right: -10%;
            text-align: center;
        }
    </style>
</head>
@include('layouts.guest_navigation')

<body class="antialiased overflow-x-hidden bg-white">
    <div class="min-w-screen" style="background-image:url('/assets/img/about/wedding-network-1.jpg'); background-size:cover; background-position: top;">
        <div class="bg-dark-grey-win bg-opacity-25 h-full mx-auto flex justify-center">
            <div class="text-center mx-auto lg:max-w-[50%] min-h-[80vh] flex inline-flex items-center justify-center">
                <div class="">
                <h1 class="headline-large text-white my-auto">Wedding Network</h1>
                <p class="headline-small text-white mt-4">WIN – The Wedding Insiders Network For Couples + Vendors Who Care About Relationships, Reputations + Remarkable Experiences</p>
                <div class="my-6 min-w-full flex items-center">
                    <a href="/user/register" class="mx-auto py-2 px-10 font-semibold button-text rounded-lg bg-win-blue text-white disabled:opacity-50 disabled:pointer-events-none">
                        LEARN MORE
                    </a>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="min-w-screen pt-16 relative z-50 bg-win-light" id="what-makes-us-different">
        <div class="bg-win-light h-full pb-16 mx-8 sm:mx-16 md:mx-24 xl:mx-32">
            <div class="text-center my-auto mx-auto md:max-w-[65%] md:pt-16">
                <div class="md:grid md:gap-8 lg:gap-16 md:grid-cols-2 -mb-4 md:mb-0">
                    <div class="w-full mb-[3.25rem] bg-win-light rounded-tl-3xl rounded-br-3xl">
                        <div class="text-center mb-12">
                            <h2 class="headline-small text-left mt-4 font-bold">It’s Time To Re-Think Wedding Planning</h2>
                        </div>
                        <div class="text-center mb-12">
                            <p class="body-copy text-left mt-4">WIN is an exclusive wedding network created by vendors to help couples eliminate decision fatigue and more effortlessly find the small businesses that are best suited for their wedding.</p>
                            <p class="body-copy text-left mt-4">Above all, we don’t ask vendors to pay more to rank higher (unlike most platforms). Instead, rankings are earned through engagement, timely communication and client satisfaction. Plus, there’s a boost in rankings when a vendor invites their peers —  creating a collective responsibility for who is here and an equal starting line for who can rank at the top. We’re building WIN together, and with good taste.</p>
                        </div>
                    </div>
                    <div class="w-full mb-[3.25rem] bg-win-light">
                        <div class="text-center mb-12">
                            <div class="flex justify-center">
                                <div class="min-w-[100%]">
                                    <img src="/assets/img/about/wedding-network-2.jpg" class="rounded-tr-3xl min-h-full">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex w-auto text-center max-sm:mx-8">
            <div class="bg-win-lavender lg:max-w-[70%] p-8 md:p-16 lg:px-32 xl:px-48 md:py-32 mx-auto rounded-3xl">
                <h2 class="text-win-red headline-small font-semibold">Diligently Disrupting The Wedding Industry With A New Approach To Vendor-Client Connections — Founded On Transparency, Artistry + Community</h2>
                <p class="subheading mx-auto mt-4 md:mt-8 md:max-w-[80%]">Our mission is to create a network of couples and vendors who value community and transparency (not to mention incredible experiences). With this, connections are made that foster accountability and artistry at every turn. Don’t book just anyone from anywhere — book on a platform that promotes collaboration rather than cliques and kickbacks.</p>
            </div>
        </div>
    </div>
    <div class="min-w-screen bg-win-light pt-16 overflow-hidden">
        <img src="/assets/img/about/wedding-network-31.jpg" class="min-w-[100%] max-h-[80vh] object-cover object-center">
    </div>
    <div class="relative z-10">
    <div class="flex w-auto bg-white">
        <div class="lg:max-w-[90%] px-8 pt-8 md:px-32 md:pt-32 lg:px-32 xl:px-48 mx-auto">
            <div class="lg:grid lg:grid-cols-3 lg:gap-8 mt-16 lg:mx-16">
                <div class="min-w-screen rounded-tl-3xl" style="background-image: url('/assets/img/about/wedding-network-4.jpg'); background-size:cover; background-position: bottom;
                    background-color: rgba(0,0,0,0.6);
                    background-blend-mode: darken;">
                    <div class="h-full w-full px-8 mb-[3.25rem]">
                        <div class="text-center mb-12">
                            <div class="flex justify-center mt-[-2.5rem]">
                                <div class="flex-shrink bg-win-light rounded-full">
                                    <p class="p-6 text-win-blue headline-small" style="line-height:1;">01</p>
                                </div>
                            </div>
                            <h3 class="headline-small text-white py-8 lg:mt-8">Find Vendors For Your Wedding</h3>
                            <p class="subheading text-white px-8">Book vendors who care about building relationships and creating a seamless experience.</p>
                            <div class="my-6 min-w-full flex items-center">
                                <a href="/user/register" class="mx-auto mb-4 py-2 px-10 font-semibold rounded-lg bg-win-blue text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                    SEARCH
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="min-w-screen" style="background-image: url('/assets/img/about/wedding-network-5.jpg'); background-size:cover; background-position: bottom;
                    background-color: rgba(0,0,0,0.6);
                    background-blend-mode: darken;">
                    <div class="h-full w-full px-8 mb-[3.25rem]">
                        <div class="text-center mb-12">
                            <div class="flex justify-center mt-[-2.5rem]">
                                <div class="flex-shrink bg-win-light rounded-full">
                                    <p class="p-6 text-win-blue headline-small" style="line-height:1;">02</p>
                                </div>
                            </div>
                            <h3 class="headline-small text-white py-8 lg:mt-8">Easily Share Referrals</h3>
                            <p class="subheading text-white px-8">Refer your couples to fellow vendors you know will deliver the same care and attention to detail you do.</p>
                            <div class="my-6 min-w-full flex items-center">
                                <a href="/vendor/register" class="mx-auto mb-4 py-2 px-10 font-semibold rounded-lg bg-win-blue text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                    REFER
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="min-w-screen rounded-br-3xl" style="background-image: url('/assets/img/about/wedding-network-6.jpg'); background-size:cover; background-position: bottom;
                    background-color: rgba(0,0,0,0.6);
                    background-blend-mode: darken;">
                    <div class="h-full w-full px-8 mb-[3.25rem]">
                        <div class="text-center mb-12">
                            <div class="flex justify-center mt-[-2.5rem]">
                                <div class="flex-shrink bg-win-light rounded-full">
                                    <p class="p-6 text-win-blue headline-small" style="line-height:1;">03</p>
                                </div>
                            </div>
                            <h3 class="headline-small text-white py-8 lg:mt-8">Get Qualified Leads</h3>
                            <p class="subheading text-white px-8">Connect with couples who are actively searching for vendors. Our in-network system lets you know when a couple has booked their first vendor and is ready to book their next.</p>
                            <div class="my-6 min-w-full flex items-center">
                                <a href="/vendor/register" class="mx-auto mb-4 py-2 px-10 font-semibold rounded-lg bg-win-blue text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                    CONNECT
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="min-w-screen bg-white">
        <div class="bg-win-light h-full mx-8 md:mx-24 xl:mx-32">
            <img src="/assets/img/about/wedding-network-7.jpg" class="px-4 pt-4 lg:pt-8 rounded-tl-3xl mx-auto md:mb-4 lg:mb-8">
            <div class="text-center my-auto mx-auto md:max-w-[100%]">
                <div class="md:grid md:gap-8 md:grid-cols-2 -mb-4 md:mb-0">
                    <div class="w-full bg-win-light rounded-tl-3xl rounded-br-3xl pt-8 px-4 md:pt-32 md:px-16 lg:px-24">
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
                    <div class="w-full bg-win-charcoal">
                        <div class="text-center mb-12">
                            <div class="flex justify-center">
                                <div class="min-w-[100%]">
                                <section class="text-white">
                                    <div class="container pt-8 md:py-16 lg:py-24 mx-auto max-sm:px-4">
                                        <div class="flex items-center lg:w-3/4 mx-auto border-b-2 pb-10 mb-10 border-gray-200 sm:flex-row flex-col">
                                            <div class="h-10 w-10 sm:mr-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                            <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                            </div>
                                            <div class="flex-grow sm:text-left text-center mt-6 sm:mt-0">
                                                <p class="subheading">A merit-based system that champions the care and quality of small, local businesses</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center lg:w-3/4 mx-auto border-b-2 pb-10 mb-10 border-gray-200 sm:flex-row flex-col">
                                            <div class="h-10 w-10 sm:mr-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                            <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                            </div>
                                            <div class="flex-grow sm:text-left text-center mt-6 sm:mt-0">
                                                <p class="subheading">Transparent pricing and ranking structure without hidden fees or pay-to-play schemes</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center lg:w-3/4 mx-auto border-b-2 pb-10 mb-10 border-gray-200 sm:flex-row flex-col">
                                            <div class="h-10 w-10 sm:mr-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                            <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                            </div>
                                            <div class="flex-grow sm:text-left text-center mt-6 sm:mt-0">
                                                <p class="subheading">Promoting community and collaboration between vendors and couples with engagement-based rankings</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center lg:w-3/4 mx-auto pb-10 border-gray-200 sm:flex-row flex-col">
                                            <div class="h-10 w-10 sm:mr-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                            <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                            </div>
                                            <div class="flex-grow sm:text-left text-center mt-6 sm:mt-0">
                                                <p class="subheading">Top-quality leads that are ready to book, and fewer window shoppers</p>
                                            </div>
                                        </div>
                                    </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="min-w-screen relative z-50" id="what-makes-us-different">
        <div class="bg-win-light h-full pb-16 px-8 md:mx-24 xl:mx-32 rounded-b-3xl">
            <div class="my-auto mx-auto md:max-w-[75%] pt-8 md:pt-16">
                <div class="md:flex md:justify-around -mb-4 md:mb-0">
                    <div class="md:container md:p-8 lg:px-16">
                        <div class="mb-12">
                            <img src="/assets/img/about/wedding-vendors-h1.jpg" class="rounded-tl-3xl min-h-full mb-8 sm:hidden">
                            <h2 class="headline-small text-left mt-4 font-bold">Melissa Lewis, WIN Founder/President + Wedding Photographer</h2>
                            <p class="body-copy mt-4">Melissa is the founder of a photography and videography studio in New England and has worked in over 250 weddings. She saw the benefits of a tight-knit community, but noticed the unfair practices of certain wedding platforms. These directories inherently prevented peers from getting noticed unless they pay a higher price. WIN was created to address those antiquated directories and frustrating referral systems with a passion and commitment to be better for couples and vendors.</p>
                            <div class="text-center mb-12">
                                <div class="flex justify-center">
                                    <div class="min-w-[100%]">
                                    <section>
                                        <div class="container pt-24 mx-auto">
                                            <div class="flex items-center mx-auto mb-10 sm:flex-row flex-col">
                                                <div class="h-10 w-10 sm:mr-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                                </div>
                                                <div class="flex-grow sm:text-left text-center mt-6 sm:mt-0">
                                                    <p class="body-copy">Holds nearly a decade of experience as a wedding photographer.</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mx-auto mb-10 sm:flex-row flex-col">
                                                <div class="h-10 w-10 sm:mr-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                                </div>
                                                <div class="flex-grow sm:text-left text-center mt-6 sm:mt-0">
                                                    <p class="body-copy">Known for her unmatched work ethic and knack for helping people.</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mx-auto mb-10 sm:flex-row flex-col">
                                                <div class="h-10 w-10 sm:mr-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                                </div>
                                                <div class="flex-grow sm:text-left text-center mt-6 sm:mt-0">
                                                    <p class="body-copy">Can be found traveling the Northeast with her 3 mini Dachshunds and husband, Sean.</p>
                                                </div>
                                            </div>
                                        </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-hidden mx-auto bg-win-light rounded-tl-3xl bg-top hidden sm:block" style="">
                        <img src="/assets/img/about/wedding-vendors-h1.jpg" class="rounded-tl-3xl min-h-full object-contain">
                    </div>
                </div>
            </div>
            <div class="my-auto mx-auto md:max-w-[75%] pt-8 md:pt-16">
                <div class="md:flex md:justify-around -mb-4 md:mb-0">
                    <div class="overflow-hidden mx-auto bg-win-light rounded-tl-3xl bg-top hidden sm:block" style="">
                        <img src="/assets/img/about/wedding-network-8.jpg" class="rounded-tl-3xl min-h-full object-contain">
                    </div>
                    <div class="md:container md:p-8 lg:px-16">
                        <div class="mb-12">
                            <img src="/assets/img/about/wedding-network-8.jpg" class="rounded-tl-3xl min-h-full mb-8 sm:hidden">
                            <h2 class="headline-small text-left mt-4 font-bold">Sean Lewis, WIN Co-Founder + Engineer</h2>
                            <p class="body-copy mt-4">Engineer by trade, inventor by instinct, Sean has spent years identifying systematic issues and solving them with impressive efficiency. That includes the discrepancy Melissa and Sean saw in the wedding industry. Melissa brings the ideas, and Sean has the eye for functionality along with the project management experience to make things happen. He’s the one behind the WIN algorithm — inspired by a late-night conversation about the industry cliques and the know-how to stir up effective solutions without stirring the pot (at least too much).</p>
                            <div class="text-center mb-12">
                                <div class="flex justify-center">
                                    <div class="min-w-[100%]">
                                    <section>
                                        <div class="container pt-24 mx-auto">
                                            <div class="flex items-center mx-auto mb-10 sm:flex-row flex-col">
                                                <div class="h-10 w-10 sm:mr-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                                </div>
                                                <div class="flex-grow sm:text-left text-center mt-6 sm:mt-0">
                                                    <p class="body-copy">A natural problem solver and inventor of numerous patented products.</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mx-auto mb-10 sm:flex-row flex-col">
                                                <div class="h-10 w-10 sm:mr-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                                </div>
                                                <div class="flex-grow sm:text-left text-center mt-6 sm:mt-0">
                                                    <p class="body-copy">Actively translating his attention to detail into learning photography.</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center mx-auto mb-10 sm:flex-row flex-col">
                                                <div class="h-10 w-10 sm:mr-10 inline-flex items-center justify-center rounded-full bg-win-lavender text-win-red flex-shrink-0">
                                                <svg class="m-1 inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>

                                                </div>
                                                <div class="flex-grow sm:text-left text-center mt-6 sm:mt-0">
                                                    <p class="body-copy">Enjoys regular evening motorcycle rides with Melissa.</p>
                                                </div>
                                            </div>
                                        </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="min-w-[100%] bg-win-lavender pb-16 md:pb-48 lg:pb-64 hidden">
        <div class="mx-auto">
            <div class="py-8"></div>
            <div class="md:max-w-[50%] mx-auto">
                <img src="/assets/img/profile.png" class="rounded-full mx-auto w-48 h-48">
                <h3 class="text-center headline-small text-win-red mt-8 lg:max-w-[75%] mx-auto">“This is a spot for a great
                    testimonial. Here is where a 5 star review would go.”</h3>
                <p class="text-win-charcoal body-copy text-center mt-8 lg:max-w-[75%] mx-auto">Vestibulum sem erat, maximus in pulvinar eu, vehicula eget massa. Donec
                    suscipit hendrerit lorem, eu aliquam justo sodales eget. Vestibulum dignissim
                    ex dui. Nunc libero enim, imperdiet at viverra tempor sodales. </p>
                <p class="subheading mx-auto text-center my-4">SARAH SMITH</p>
            </div>
        </div>
    </div>
    <!-- FAQ -->
    <div class="max-w-[85rem] lg:max-w-[50%] px-4 pt-6 pb-10 sm:px-6 lg:px-8 mx-auto bg-win-light rounded-3xl relative z-50 my-12">
        <!-- Title -->
        <div class="max-w-5xl mx-auto text-center mb-10 lg:mb-14 mt-8">
            <h2 class="text-win-red headline-small">Frequently Asked Questions</h2>
        </div>
        <!-- End Title -->

        <div class="max-w-5xl mx-auto">
            <!-- Accordion -->
            <div class="hs-accordion-group">
                <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6 active" id="hs-basic-with-title-and-arrow-stretched-heading-one">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full subheading text-start text-gray-800 rounded-lg" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-one">
                    How is WIN different from other wedding vendor lists?
                        <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                        <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>
                    </button>
                    <div id="hs-basic-with-title-and-arrow-stretched-collapse-one" class="hs-accordion-content w-full overflow-hidden" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-one">
                        <p class="text-gray-800 body-copy">
                        Most importantly, WIN is merit-based. This means showcasing the top-rated and most engaged businesses, as opposed to the vendor who is disengaged, or simply paying the highest rate to be shown on page one. Truth be told, many wedding directories are designed to promote the businesses that pay the most. Even venues often use this as a way to create profits, featuring companies who are paying to be promoted. We’re flipping that system upside down to foster an algorithm of artistry and transparency. The optional cash incentives (more on that below) only serve to keep couples here, which keeps vendors in business. It’s a win-win.                            </p>
                    </div>
                </div>

                <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full subheading text-start text-gray-800 rounded-lg" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                    The cash incentive — can you explain more about that?
                        <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                        <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>
                    </button>
                    <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <p class="text-gray-800 body-copy">
                        Every vendor is encouraged to offer preferred pricing of up to $250 off. With these potential savings on each service, couples can maximize their wedding budget. This is not required, though, and is up to the discretion of each vendor. WIN vendors are typically willing to offer this because it means working with the right people — which fuels creativity, publication opportunities and future work potential. And because WIN only charges a small, flat-rate fee for wedding businesses, that extra cost for a sure booking still falls well below typical spending for other marketing platforms. In short, we suggest passing the savings onto you, but this is up to the discretion of each individual vendor and their business model.                            </p>
                    </div>
                </div>

                <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full subheading text-start text-gray-800 rounded-lg" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                    Are there any kickbacks?
                        <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                        <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>
                    </button>
                    <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <p class="text-gray-800 body-copy">
                        Vendors don’t make any money for referring others. Instead, they gain a boost in rankings as a thank you when they invite peers and add to their preferred vendor list. This simply promotes our core philosophy that a “rising tide lifts all boats.” WIN is better when vendors are willing to invite their peers, and increase the opportunities on this platform.                            </p>
                    </div>
                </div>

                <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full subheading text-start text-gray-800 rounded-lg" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                    Who built the Wedding Insiders Network?
                        <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                        <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>
                    </button>
                    <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <p class="text-gray-800 body-copy">
                        WIN was designed by vendors to eliminate the kickbacks, cliques and pay-to-play models to instead even out the playing field. The process of booking weddings for couples and vendors alike was unnecessarily antiquated — full of far too many emails, unclear pricing, ambiguous availability and big-box platforms that simply put the highest-paying business at the top of the site instead of the best-suited vendor for your vision. The process of finding wedding vendors on WIN is built around transparency and talent.                            </p>
                    </div>
                </div>

                <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full subheading text-start text-gray-800 rounded-lg" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                    Is WIN a referral program too?
                        <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                        <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>
                    </button>
                    <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <p class="text-gray-800 body-copy">
                        Yes! Because we help wedding vendors share and receive leads, you can easily describe WIN as a wedding vendor referral program. Keep in mind, though, that we’re less about who’s-who and more about the honed-in impact of a network that facilitates community, lead sharing and respect.                            </p>
                    </div>
                </div>

                <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-xl p-6" id="hs-basic-with-title-and-arrow-stretched-heading-two">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full subheading text-start text-gray-800 rounded-lg" aria-controls="hs-basic-with-title-and-arrow-stretched-collapse-two">
                    I’m a vendor. If I can’t pay to rank higher, what can I do to increase visibility?
                        <svg class="hs-accordion-active:hidden block flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                        <svg class="hs-accordion-active:block hidden flex-shrink-0 size-5 text-win-red group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>
                    </button>
                    <div id="hs-basic-with-title-and-arrow-stretched-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden" aria-labelledby="hs-basic-with-title-and-arrow-stretched-heading-two">
                        <p class="text-gray-800 body-copy">
                        We have a simple and transparent metric system designed to help couples connect with top-rated vendors. The main factors are displayed on your dashboard so you can see how you’re ranking and know what to do to improve. Key elements are response time, views from couples, reviews and the number of invites you’ve sent to couples and peers. Essentially, if you’re an active participant with strong reviews who communicates in a timely manner, you’ll benefit from better rankings on our directory.                             </p>
                    </div>
                </div>
            </div>
            <!-- End Accordion -->
        </div>
    </div>
    <div class="min-w-screen relative mt-[-5%]" style="background-image:url('/assets/img/about/about-footer.jpg'); background-size:cover; background-position: top;">
        <div class="bg-dark-grey-win bg-opacity-50 h-full mx-auto flex justify-center">
            <div class="text-center mx-auto md:max-w-[75%] min-h-[80vh] flex inline-flex items-center justify-center">
                <div class="">
                <h1 class="headline-small text-white my-auto">More Ease, Equality + Better Experiences 
                For Vendors + Couples Who Care About Relationships.</h1>
                <div class="my-6 min-w-full flex items-center">
                    <a href="/user/register" class="mx-auto py-2 px-10 font-semibold button-text rounded-lg bg-win-blue text-white disabled:opacity-50 disabled:pointer-events-none">
                        JOIN US
                    </a>
                </div>
                </div>
            </div>
        </div>
    </div> 
    <div class="bg-win-blue w-screen py-4 text-center">
        <p class="text-white subheading">INSPIRING MARKETPLACE FOR VENDORS AND CLIENTS.</p>
    </div>
    @include('layouts.footer')
</body>

</html>