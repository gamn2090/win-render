<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Vendor Insights</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.48.0/apexcharts.min.js" integrity="sha512-wqcdhB5VcHuNzKcjnxN9wI5tB3nNorVX7Zz9NtKBxmofNskRC29uaQDnv71I/zhCDLZsNrg75oG8cJHuBvKWGw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.48.0/apexcharts.min.css" integrity="sha512-qc0GepkUB5ugt8LevOF/K2h2lLGIloDBcWX8yawu/5V8FXSxZLn3NVMZskeEyOhlc6RxKiEj6QpSrlAoL1D3TA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <script src="https://preline.co/assets/js/hs-apexcharts-helpers.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
      integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
      window.series = [{{ round($data['ranking']['reviews']) }},
        {{ round($data['ranking']['badges']) }},
        {{ round($data['ranking']['endorsements']) }},
        {{ round($data['ranking']['vendor_community']) }},
        {{ round($data['ranking']['client_community']) }}];
      window.labels = ["Reviews", "Badges", "Endorsements", "Vendor Community", "Client Community"];
    </script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @vite('resources/js/insights.js')
    @include('components.fonts')
    <style>
      .button-text{
        font-size: 1rem;
      }
      .small-title{
        font-family: "NeulisNeue-Bold", sans-serif;
        font-size:1rem;
      }
    </style>
  </head>

  <body class="m-0 antialiased overflow-x-hidden">
    @include('layouts.vendor_navigation')
    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out rounded-xl">
      <div class="bg-[#EDE9F5] lg:mx-8 rounded-t-3xl">
        <div class="w-full px-6 py-6 container mx-auto">
          <div class="bg-white p-4 lg:p-8 rounded-3xl text-center">
            <h2 class="headline-small">&#127881; Your Insights, Your Path to WIN!</h2>
            <p class="mt-2"><i class="fa-regular fa-star text-win-purple text-lg relative top-[-.5rem]"></i><i class="fa-regular fa-star text-win-purple text-lg relative top-[.5rem]"></i> You're on a journey to earn points and improve your ranking as a wedding vendor. <i class="fa-regular fa-star text-win-purple text-lg relative top-[.5rem]"></i><i class="fa-regular fa-star text-win-purple text-lg relative top-[-.5rem]"></i></p>
          </div>
          <div class="bg-white rounded-3xl mt-4 md:mt-8">
            <div class="hs-accordion hs-accordion-active:bg-gray-100 rounded-3xl p-4 lg:p-8 hover:bg-grey-50" id="insights-help-dropdown">
              <button class="hs-accordion-toggle group inline-flex items-center justify-between gap-x-3 w-full headline-small text-start transition focus:outline-none focus:text-gray-500" aria-expanded="true" aria-controls="insights-help-dropdown">
                How it works:
                <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-gray-600 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-gray-600 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
              </button>
              <div id="insights-help-dropdown" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="insights-help-dropdown">
                <ul class="mt-4 list-disc space-y-4">
                  <li>
                    <i class="fa-solid fa-circle text-win-purple mr-1 my-auto"></i><span class="subheading"> Client Community:</span> <span>The number of clients you invite to WIN and collaborate with.</span>
                    <div class="relative md:ml-2 lg:ml-4">
                      <p><span class="font-semibold">PERFECT SCORE PRO TIP: </span> <span><i class="fa-solid fa-check mx-1 text-win-purple"></i> 3 new client invites & accounts established per month.</span></p>
                      <p><span class="font-semibold">Overall Score Weight & Cycle: </span> <span><i class="fa-solid fa-award mx-1 text-win-purple"></i> 30% Total - cycles monthly.</span></p>
                    </div>
                  </li>
                  <li>
                    <i class="fa-solid fa-circle text-win-blue mr-1 my-auto"></i><span class="subheading">Vendor Community:</span> <span>The growth of your vendor network, the number of vendors you invite to WIN resulting in increased community engagement & collaboration.</span>
                    <div class="relative md:ml-2 lg:ml-4">
                      <p><span class="font-semibold">PERFECT SCORE PRO TIP: </span> <span><i class="fa-solid fa-check mx-1 text-win-purple"></i> 3 new vendor invites & accounts established. 1 Preferred Vendor added to your storefront from each category (21 total categories)</span></p>
                      <p><span class="font-semibold">Overall Score Weight & Cycle: </span> <span><i class="fa-solid fa-award mx-1 text-win-purple"></i> 25% Total - cycles quarterly.</span></p>
                    </div>
                  </li>
                  <li>
                    <i class="fa-solid fa-circle text-win-orange mr-1 my-auto"></i><span class="subheading">Reviews:</span> <span>The quantity and overall rankings of your reviews.</span>
                    <div class="relative md:ml-2 lg:ml-4">
                      <p><span class="font-semibold">PERFECT SCORE PRO TIP: </span> <span><i class="fa-solid fa-check mx-1 text-win-purple"></i> Google reviews at 5 Stars</span></p>
                      <p><span class="font-semibold">Overall Score Weight & Cycle: </span> <span><i class="fa-solid fa-award mx-1 text-win-purple"></i> 20% Total</span></p>
                    </div>
                  </li>
                  <li>
                    <i class="fa-solid fa-circle text-win-lavender mr-1 my-auto"></i><span class="subheading">Peer Endorsements:</span> <span>The number of endorsements received from fellow vendors.</span>
                    <div class="relative md:ml-2 lg:ml-4">
                      <p><span class="font-semibold">PERFECT SCORE PRO TIP: </span> <span><i class="fa-solid fa-check mx-1 text-win-purple"></i> 4 unique peer endorsements achieved</span></p>
                      <p><span class="font-semibold">Overall Score Weight & Cycle: </span> <span><i class="fa-solid fa-award mx-1 text-win-purple"></i> 15% Total - cycles weekly.</span></p>
                    </div>
                  </li>
                  <li>
                    <i class="fa-solid fa-circle text-win-red mr-1 my-auto"></i><span class="subheading">Badges:</span> <span>Special achievements earned and displayed on your storefront.</span>
                    <div class="relative md:ml-2 lg:ml-4">
                      <p><span class="font-semibold">PERFECT SCORE PRO TIP: </span> <span><i class="fa-solid fa-check mx-1 text-win-purple"></i> Achievement of 4 total badges (Community Builder, Early Adopter, Trending, and Fast Responder)</span></p>
                      <p><span class="font-semibold">Overall Score Weight & Cycle: </span> <span><i class="fa-solid fa-award mx-1 text-win-purple"></i> 10% Total</span></p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="flex flex-wrap bg-white p-4 lg:p-8 rounded-3xl mt-4 md:mt-8">
            <div class="w-full mb-6">
              <h3 class="headline-small">WINfluence Status:</h3>
            </div>
            <div class="md:grid md:grid-cols-2">
              <div id="rankingRadialChart" class="h-[50vh] md:h-[30vh] lg:h-[50vh]"></div>
              <img src="{{ asset('/assets/img/insights/insights-bubble.PNG') }}">
            </div>
            <div class="flex w-full h-2 lg:h-4 bg-grey-100 rounded-full overflow-hidden">
              <div class="flex flex-col justify-center overflow-hidden bg-[#ED9A47] text-xs text-white text-center whitespace-nowrap" style="width: {{ round( $data['ranking']['reviews'] * .20)}}%" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
              <div class="flex flex-col justify-center overflow-hidden bg-[#E6632B] text-xs text-white text-center whitespace-nowrap" style="width: {{ round( $data['ranking']['badges'] * .10)}}%" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
              <div class="flex flex-col justify-center overflow-hidden bg-[#D2C6E4] text-xs text-white text-center whitespace-nowrap" style="width: {{ round( $data['ranking']['endorsements'] * .15)}}%" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
              <div class="flex flex-col justify-center overflow-hidden bg-[#627DF6] text-xs text-white text-center whitespace-nowrap" style="width: {{ round( $data['ranking']['vendor_community'] * .25)}}%" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100"></div>
              <div class="flex flex-col justify-center overflow-hidden bg-[#5E34C1] text-xs text-white text-center whitespace-nowrap" style="width: {{ round( $data['ranking']['client_community'] * .30)}}%" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="text-right w-full">
              <p class="subheading">Perfect Score</p>
            </div>
          </div>
          <div class="flex flex-wrap bg-white p-4 lg:p-8 rounded-3xl mt-4 md:mt-8">
            <div class="w-full mb-6">
              <h3 class="headline-small">WINfluence Status:</h3>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-5 w-full">
              <div class="mx-auto text-center">
                <p class="subheading mb-1 lg:mb-2">My Badges</p>
                <div class="relative size-40 text-win-purple mx-auto">
                  <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background Circle -->
                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-grey-100 text-grey-100" stroke-width="2"></circle>
                    <!-- Progress Circle -->
                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-win-purple text-win-purple" stroke-width="2" stroke-dasharray="100" stroke-dashoffset="{{ round(100 - $data['ranking']['badges']) }}" stroke-linecap="round"></circle>
                  </svg>

                  <!-- Percentage Text -->
                  <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2">
                    <span class="text-center text-2xl font-bold text-win-purple">{{ round($data['ranking']['badges']) }}%</span>
                  </div>
                </div>
              </div>
              <div class="mx-auto text-center">
                <p class="subheading mb-1 lg:mb-2">Endorsements</p>
                <div class="relative size-40 text-win-purple mx-auto">
                  <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background Circle -->
                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-grey-100 text-grey-100" stroke-width="2"></circle>
                    <!-- Progress Circle -->
                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-win-purple text-win-purple" stroke-width="2" stroke-dasharray="100" stroke-dashoffset="{{ round(100 - $data['ranking']['endorsements']) }}" stroke-linecap="round"></circle>
                  </svg>

                  <!-- Percentage Text -->
                  <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2">
                    <span class="text-center text-2xl font-bold text-win-purple">{{ round($data['ranking']['endorsements']) }}%</span>
                  </div>
                </div>
              </div>
              <div class="mx-auto text-center">
                <p class="subheading mb-1 lg:mb-2">Vendor Community</p>
                <div class="relative size-40 text-win-purple mx-auto">
                  <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background Circle -->
                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-grey-100 text-grey-100" stroke-width="2"></circle>
                    <!-- Progress Circle -->
                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-win-purple text-win-purple" stroke-width="2" stroke-dasharray="100" stroke-dashoffset="{{ round(100 - $data['ranking']['vendor_community']) }}" stroke-linecap="round"></circle>
                  </svg>

                  <!-- Percentage Text -->
                  <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2">
                    <span class="text-center text-2xl font-bold text-win-purple">{{ round($data['ranking']['vendor_community']) }}%</span>
                  </div>
                </div>
              </div>
              <div class="mx-auto text-center">
                <p class="subheading mb-1 lg:mb-2">Client Community</p>
                <div class="relative size-40 text-win-purple mx-auto">
                  <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background Circle -->
                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-grey-100 text-grey-100" stroke-width="2"></circle>
                    <!-- Progress Circle -->
                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-win-purple text-win-purple" stroke-width="2" stroke-dasharray="100" stroke-dashoffset="{{ round(100 - $data['ranking']['client_community']) }}" stroke-linecap="round"></circle>
                  </svg>

                  <!-- Percentage Text -->
                  <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2">
                    <span class="text-center text-2xl font-bold text-win-purple">{{ round($data['ranking']['client_community']) }}%</span>
                  </div>
                </div>
              </div>
              <div class="mx-auto text-center">
                <p class="subheading mb-1 lg:mb-2">Reviews</p>
                <div class="relative size-40 text-win-purple mx-auto">
                  <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background Circle -->
                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-grey-100 text-grey-100" stroke-width="2"></circle>
                    <!-- Progress Circle -->
                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-win-purple text-win-purple" stroke-width="2" stroke-dasharray="100" stroke-dashoffset="{{ round(100 - $data['ranking']['reviews']) }}" stroke-linecap="round"></circle>
                  </svg>

                  <!-- Percentage Text -->
                  <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2">
                    <span class="text-center text-2xl font-bold text-win-purple">{{ round($data['ranking']['reviews']) }}%</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="grid sm:grid-cols-2 mt-4 md:mt-8 sm:space-x-4 lg:space-x-8 max-sm:space-y-4">
            <div class="flex justify-around bg-white rounded-3xl">
              <div class="p-4 md:p-5 flex gap-x-3">
                <div class="shrink-0 flex justify-center items-center text-lg sm:text-xl lg:text-2xl bg-win-lavender text-win-purple rounded-full size-8 sm:size-12 lg:size-16">
                  <i class="fas fa-users"></i>
                </div>
                <div>
                  <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="subheading">
                    {{ Auth::user()->vendorReferrals() }}
                    </h3>
                  </div>
                  <p class="subheading">
                    Vendors Referred
                  </p>
                </div>
              </div>
              <div class="p-4 md:p-5 flex gap-x-3">
                <div class="shrink-0 flex justify-center items-center text-lg sm:text-xl lg:text-2xl bg-win-lavender text-win-purple rounded-full size-8 sm:size-12 lg:size-16">
                  <i class="fas fa-users"></i>
                </div>
                <div>
                  <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="subheading">
                    {{ Auth::user()->clientReferrals() }}
                    </h3>
                  </div>
                  <p class="subheading">
                    Clients Referred
                  </p>
                </div>
              </div>
            </div>
            
            <div class="flex justify-around bg-white rounded-3xl">
              <div class="p-4 md:p-5 flex gap-x-3">
                <div class="shrink-0 flex justify-center items-center text-lg sm:text-xl lg:text-2xl bg-win-lavender text-win-purple rounded-full size-8 sm:size-12 lg:size-16">
                  <i class="fas fa-users"></i>
                </div>
                <div>
                  <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="subheading">
                    {{ Auth::user()->storefront_views }}
                    </h3>
                  </div>
                  <p class="subheading">
                    Storefront Views
                  </p>
                </div>
              </div>
              <div class="p-4 md:p-5 flex gap-x-3">
                <div class="shrink-0 flex justify-center items-center text-lg sm:text-xl lg:text-2xl bg-win-lavender text-win-purple rounded-full size-8 sm:size-12 lg:size-16">
                  <i class="fas fa-users"></i>
                </div>
                <div>
                  <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="subheading">
                    {{ Auth::user()->timesFavorited() }}
                    </h3>
                  </div>
                  <p class="subheading">
                    Times Favorited
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    @include('layouts.footer')
    </main>
  </body>
</html>
