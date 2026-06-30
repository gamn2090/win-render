<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Blog</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
</head>
@include('layouts.guest_navigation')

<body class="antialiased overflow-x-hidden bg-white w-screen">
    <div class="max-w-screen bg-win-light overflow-x-hidden">
        <div class="min-h-[60vh] md:min-h-[80vh] mx-auto flex justify-center">
            <img class="absolute z-0 left-[20%] top-[20%] hidden lg:block" src="/assets/img/blog/blog-confetti.PNG">
            <div class="text-center mx-auto lg:max-w-[30%] min-h-[60vh] md:min-h-[80vh] flex inline-flex items-center justify-center">
                <div class="relative z-50">
                <img src="/assets/img/blog/blog-tagline.PNG" class="py-4 mx-auto">
                <h1 class="headline-large my-auto">An important
                heading goes here</h1>
                <p class="subheading mt-4"> Duis urna metus, tristique non tristique convallis, sagittis quis ligula. Suspendisse potenti. Proin vitae sapien vitae.</p>
                </div>
            </div>
        </div>
    </div>
    <section class="bg-win-lavender py-6">
        <div class="flex flex-wrap p-5 flex-col md:flex-row items-center">
            <nav class="flex lg:w-2/5 flex-wrap items-center text-base md:ml-auto">
            <a class="ml-5 md:ml-10 mr-5 md:mr-10 body-copy font-bold">FEATURED</a>
            <a class="mr-5 md:mr-10 body-copy font-bold">VENDORS</a>
            <a class="mr-5 md:mr-10 body-copy font-bold">COUPLES</a>
            <a class="md:mr-10 body-copy font-bold">TRENDING</a>
            </nav>
            <a class="flex order-first lg:order-none lg:w-1/5 title-font font-medium items-center text-gray-900 lg:items-center lg:justify-center mb-4 md:mb-0">
            </a>
            <div class="lg:w-2/5 inline-flex lg:justify-end ml-5 lg:ml-0">
            <div class="w-full md:w-1/2 inline-flex items-center">
                <input type="text" placeholder="SEARCH" class="leading-none text-gray-50 p-3 focus:outline-none border-0 bg-win-light rounded-full" />
            </div>
            </div>
        </div>
    </section>
    <div class="min-w-screen py-12 lg:py-32 lg:min-h-[80vh] bg-win-light" style="background-image:url('/assets/img/WIN-Large-Confetti-Pattern.jpg');" id="faq">
        <!-- FAQ -->
        <div class="max-w-[85rem] lg:max-w-[60%] md:grid md:grid-cols-5 mx-auto bg-white relative z-50 rounded-tl-3xl">
            <!-- Title -->
            <div class="col-span-2 rounded-tl-3xl" style="background-image:url('assets/img/blog/card-1.PNG');background-size:cover;"></div>
            <div class="col-span-3 mx-auto mt-8 px-4 py-10 sm:px-6 lg:px-16 lg:py-16">
                <h2 class="headline-small">This is where your blog
                    heading will go. Right
                    here in this spot.</h2>
                <p class="subheading my-4 md:my-8">Excerpt Spot. Lorem ipsum dolor sit amet,
                    consectetur adipiscing elit. Pellentesque
                    semper rhoncus urna, non laoreet metus
                    malesuada ac.</p>
                <div class="my-6 min-w-full flex items-center">
                    <a href="#" class="py-3 px-10 font-semibold rounded-full bg-win-purple text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                        READ MORE
                    </a>
                </div>
            </div>
        </div>
        <!-- End FAQ -->
    </div>
    <div class="min-h-screen py-16 lg:py-32 lg:mb-24">
        <div class="md:grid md:grid-cols-3 md:gap-16 lg:max-w-[85%] mx-auto">
            <div class="text-center">
                <img class="rounded-tl-3xl mb-4 mx-auto h-[50vh]" src="/assets/img/blog/blog-1.png">
                <h3 class="body-copy font-bold text-win-red mt-8">VENDORS</h3>
                <p class="headline-small my-8 lg:my-10 max-w-[75%] mx-auto">This is where your
                    blog heading will
                    go. Right here in
                    this spot.</p>
                <div class="my-6 min-w-full flex items-center">
                    <a href="/vendor/register" class="mx-auto py-2 px-10 font-semibold rounded-full bg-win-blue text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                        READ MORE
                    </a>
                </div>
            </div>
            <div class="text-center">
                <img class="rounded-tl-3xl mb-4 mx-auto h-[50vh] object-none" src="/assets/img/blog/blog-2.png">
                <h3 class="body-copy font-bold text-win-red mt-8">COUPLES</h3>
                <p class="headline-small my-8 lg:my-10 max-w-[75%] mx-auto">This is where your
                    blog heading will
                    go. Right here in
                    this spot.</p>
                <div class="my-6 min-w-full flex items-center">
                    <a href="/vendor/register" class="mx-auto py-2 px-10 font-semibold rounded-full bg-win-blue text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                        READ MORE
                    </a>
                </div>
            </div>
            <div class="text-center">
                <img class="rounded-tl-3xl mb-4 mx-auto h-[50vh]" src="/assets/img/blog/blog-3.png">
                <h3 class="body-copy font-bold text-win-red mt-8">TRENDING</h3>
                <p class="headline-small my-8 lg:my-10 max-w-[75%] mx-auto">This is where your
                    blog heading will
                    go. Right here in
                    this spot.</p>
                <div class="my-6 min-w-full flex items-center">
                    <a href="/vendor/register" class="mx-auto py-2 px-10 font-semibold rounded-full bg-win-blue text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                        READ MORE
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="md:mx-16 bg-win-light min-h-[50vh] py-16 lg:py-32">
        <div class="md:grid md:grid-cols-2 md:gap-8 lg:max-w-[65%] mx-auto mb-12 lg:mb-24">
            @foreach($posts as $post)
            <div class="text-center relative z-50">
                <img class="rounded-tl-3xl mb-4 mx-auto h-[24rem]" src="@isset($post->featured_image) {{ $post->featured_image }} @endisset">
                @foreach($post->tags as $tag)
                <div class="bg-win-red text-white rounded-full py-1 px-4 inline-block text-xs mr-2">{{ $tag }}</div>
                @endforeach
                <p class="subheading my-4 lg:my-6 md:max-w-[35%] mx-auto">{{ $post->title->rendered }}</p>
                <div class="my-6 min-w-full flex items-center">
                    <a href="/blog/post/{{ $post->id }}" class="mx-auto py-1 px-4 font-semibold rounded-lg bg-win-purple text-white">
                        READ MORE
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
        <div class="bg-win-light h-full">
            <div class="text-center my-auto mx-auto md:max-w-[100%]">
                <div class="md:grid md:grid-cols-2 -mb-4 md:mb-0">
                    <div class="w-full bg-win-light pt-32 px-16 lg:px-24" style="background-image: url('/assets/img/blog/about-us-l.png'); background-size:cover; background-position:center;">
                    </div>
                    <div class="w-full bg-win-charcoal p-8 md:p-16 lg:p-24 text-white">
                        <div class="text-center mb-12">
                        <img src="/assets/img/blog/about-us-tagline.PNG" class="py-4">
                            <h2 class="headline-small text-left mt-4 font-bold">The Wedding
                            Insiders Network</h2>
                        </div>
                        <div class="text-center mb-12">
                            <p class="body-copy text-left mt-4">Quisque at malesuada risus. Pellentesque velit lorem,
                                ullamcorper quis accumsan nec, imperdiet tempor ligula.
                                Phasellus nisi enim, sollicitudin in mattis in, blandit vitae
                                leo. Orci varius natoque penatibus et magnis dis parturient
                                montes, nascetur ridiculus mus. </p>
                            <p class="body-copy text-left mt-4">Duis rutrum viverra turpis, laoreet blandit felis sodales id.
                                Nulla facilisis, ipsum id molestie tincidunt, sem velit
                                ullamcorper enim, non varius erat nisi elementum dolor.</p>
                            <div class="my-6 lg:my-10 min-w-full flex items-center">
                                <a href="#" class="py-3 px-10 font-semibold rounded-full bg-win-blue text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                    LEARN MORE
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    <div class="mb-24 bg-win-light hidden">
        <div class="min-h-[100vh]">
            <div class="absolute min-h-[60vh] min-w-[100%] bg-win-lavender z-10"></div>
            <div class="absolute min-h-[100%] min-w-[100%] bg-win-light"></div>
            <div class="min-w-[100vw] pt-24 pb-12 text-center relative z-20">
                <h3 class="headline-small text-win-red">Choose your adventure</h3>
                <p class="subheading py-4">To optimise your experience, select a service that best fits your goals</p>
            </div>
            <div class="min-w-[100vw] py-12 relative z-20">
                <div class="grid grid-cols-2 gap-16 lg:max-w-[50%] mx-auto">
                    <div class="text-center">
                        <img class="rounded-tl-3xl mb-4" src="/assets/img/for-vendors.png">
                        <h3 class="headline-small text-win-red">For Vendors</h3>
                        <p class="subheading my-8 lg:my-10 max-w-[75%] mx-auto">Lorem ipsum dolor sit amet,
                            consectetur adipiscing elit.
                            Quisque sed ornare ipsum,</p>
                        <div class="my-6 min-w-full flex items-center">
                            <a href="/vendor/register" class="mx-auto py-2 px-10 font-semibold rounded-full bg-win-purple text-white hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                                LEARN MORE
                            </a>
                        </div>
                    </div>
                    <div class="text-center">
                        <img class="rounded-tl-3xl mb-4" src="/assets/img/for-couples.png">
                        <h3 class="headline-small text-win-red">For Couples</h3>
                        <p class="subheading my-8 lg:my-10 max-w-[75%] mx-auto">Lorem ipsum dolor sit amet,
                            consectetur adipiscing elit.
                            Quisque sed ornare ipsum,</p>
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
    <div class="min-w-screen relative mt-[-5%]" style="background-image:url('/assets/img/call-to-action-bottom.jpg'); background-size:cover; background-position: center;">
        <div class="bg-dark-grey-win bg-opacity-50 h-full mx-auto flex justify-center">
            <div class="text-center mx-auto md:max-w-[75%] min-h-[80vh] flex inline-flex items-center justify-center">
                <div class="">
                <h1 class="headline-small text-white my-auto">This is a spot for your final call to action</h1>
                <div class="my-6 min-w-full flex items-center">
                    <a href="/user/register" class="mx-auto py-2 px-10 font-semibold button-text rounded-full bg-win-blue text-white disabled:opacity-50 disabled:pointer-events-none">
                        GET STARTED
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