<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Client's Profile</title>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJedaphwORrnJVSpwqHuYSzRrGSluQ8Ck&loading=async">
    </script>
    <!-- Main Styling -->
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>
  <body class="m-0 font-sans antialiased max-w-screen overflow-x-hidden">
    @if(Auth::guard('vendor')->check())
    @include('layouts.vendor_navigation')
    @else
    @include('layouts.navigation')
    @endif
    
    <div class="bg-[#EDE9F5] lg:mx-8 rounded-t-3xl pt-4">
      <section class="mx-4 mt-4 md:mx-8 md:mt-8 py-8 bg-white rounded-3xl">
        <div class="container mx-auto px-4">
          <div class="relative flex flex-col min-w-0 break-words w-full mb-6 rounded-lg">
            <div class="px-6">
              <div class="flex flex-wrap justify-center pt-4 md:pt-8 min-h-16 md:min-h-32 lg:min-h-64">
                <div class="w-full lg:w-5/12 px-4 lg:order-2 flex justify-center">
                  <x-avatar :model="$client" class="rounded-full border-none absolute w-32 h-32 lg:w-64 lg:h-64 pc-client-avatar" />
                </div>
                @php
                  $mainVendor = false;
                @endphp
                <div class="w-full lg:w-3/12 px-4 lg:order-3 lg:text-right lg:self-center">
                  <div class="py-3 md:py-6 px-3 mt-32 sm:mt-0">
                @if(!$client->isInNetwork())
                <div class="top-[1rem] bg-win-light text-black py-1 px-2 rounded-full items-center text-center">
                    <div class="inline-flex items-center gap-x-1 font-bold">
                    <svg class="flex-shrink-0 size-5 md:size-8 lg:size-16" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                    </div>
                    <div class="flex align-middle content-center justify-center">
                      <p class="subheading inline-flex">Out of network</p>
                      @if(Auth::guard('vendor')->check())
                      <span class="hs-tooltip items-center inline-flex self-center">
                        <div class="hs-tooltip-toggle items-center h-full align-middle -mt-1">
                          <svg class="align-middle ml-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                            <path d="M12 17h.01" />
                          </svg>
                          <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black font-medium text-white rounded shadow-sm max-w-2xl" role="tooltip">
                            On this website, &quot;Out of Network&quot; refers to couples who have not yet booked a vendor
                            through our platform. By being the first to work with them, you have the unique opportunity to
                            introduce them to the network and build a strong connection.
                            <br>
                            Additionally, as their first booked
                            vendor, you get to waive your discount, making it an even more rewarding collaboration. If
                            you're interested in bringing them into the network, consider reaching out to showcase your
                            services!
                          </span>
                        </div>
                      </span>
                      @elseif(Auth::guard('web')->check())
                      <span class="hs-tooltip items-center inline-flex self-center">
                        <div class="hs-tooltip-toggle items-center h-full align-middle -mt-1">
                          <svg class="align-middle ml-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                            <path d="M12 17h.01" />
                          </svg>
                          <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-black font-medium text-white rounded shadow-sm max-w-2xl" role="tooltip">
                            On this website, &quot;Out of Network&quot; means you haven’t yet booked a vendor through our
                            platform. By booking your first vendor here, you’ll unlock the opportunity to join the network
                            and start enjoying its benefits.
                            <br>
                            Additionally, your eligibility to access discounts from our vendors
                            becomes visible as soon as you book your first vendor.
                          </span>
                        </div>
                      </span>
                      @endif
                    </div>
                </div>
                @else
                <div class="top-[1rem] bg-win-purple py-1 px-2 rounded-full items-center text-center">
                    <div class="inline-flex items-center gap-x-1 font-bold text-white">
                      <svg class="flex-shrink-0 size-5 md:size-8 lg:size-16" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <p class="subheading text-white">In network</p>
                </div>
                @endif
                  </div>
                </div>
                <div class="w-full lg:w-3/12 px-4 lg:order-1 lg:self-center hidden sm:block">
                  <div class="flex justify-center py-4 lg:pt-4 pt-8">
                  </div>
                </div>
              </div>
              <div class="text-center mt-6 lg:mt-12">
                <h3 class="headline-large">
                  {{ $client->first_name }} & {{ $client->fiance_first_name }}
                </h3>
                <h3 class="body-copy">
                  {{ $client->wedding_date }} <br>
                  {{ $client->wedding_location }}
                </h3>
                @if($client->booking_date != null)
                <p class="body-copy font-semibold">We are looking to book our vendors within: </p>
                <p class="body-copy">{{ $client->booking_date }}</p>
                @endif
              </div>
              <div class="mt-4">
                <h3 class="headline-small">A little bit about {{ $client->first_name }} & {{ $client->fiance_first_name }}:</h3>
              </div>
              <div class="mt-4 py-6 border-t border-blueGray-200 text-center">
                <div class="flex flex-wrap justify-center">
                  <div class="w-full lg:w-9/12 px-4">
                    <p class="mb-4 text-lg">
                      @if($client->bio == null)
                      {{ $client->first_name }} & {{ $client->fiance_first_name }} haven't written a bio yet!
                      @else
                      {{ $client->bio }}
                      @endif
                    </p>
                  </div>
                </div>
              </div>
              @php($answers = json_decode($client->questions))
              @if($answers[0] != null)
              <div class="mt-4">
                <h3 class="headline-small">Describe your dream wedding in three words.</h3>
              </div>
              <div class="mt-4 py-6 border-t border-blueGray-200">
                <div class="flex flex-wrap justify-center">
                  <div class="w-full lg:w-9/12 px-4">
                    <p class="mb-4 text-lg">
                      {{ $answers[0] }}
                    </p>
                  </div>
                </div>
              </div>
              @endif
              @if($answers[1] != null)
              <div class="mt-4">
                <h3 class="headline-small">What are you most looking forward to about your wedding?</h3>
              </div>
              <div class="mt-4 py-6 border-t border-blueGray-200">
                <div class="flex flex-wrap justify-center">
                  <div class="w-full lg:w-9/12 px-4">
                    <p class="mb-4 text-lg">
                      {{ $answers[1] }}
                    </p>
                  </div>
                </div>
              </div>
              @endif
              @if($answers[2] != null)
              <div class="mt-4">
                <h3 class="headline-small">Are there any specific traditions that are important for you to include, or avoid?</h3>
              </div>
              <div class="mt-4 py-6 border-t border-blueGray-200">
                <div class="flex flex-wrap justify-center">
                  <div class="w-full lg:w-9/12 px-4">
                    <p class="mb-4 text-lg">
                      {{ $answers[2] }}
                    </p>
                  </div>
                </div>
              </div>
              @endif
              @if($answers[3] != null)
              <div class="mt-4">
                <h3 class="headline-small">Is there anything else you'd like your wedding vendors to know before working together?</h3>
              </div>
              <div class="mt-4 py-6 border-t border-blueGray-200">
                <div class="flex flex-wrap justify-center">
                  <div class="w-full lg:w-9/12 px-4">
                    <p class="mb-4 text-lg">
                      {{ $answers[3] }}
                    </p>
                  </div>
                </div>
              </div>
              @endif
            <div class="mt-4">
              <h3 class="headline-small">We are looking to connect with:</h3>
            </div>
            <div class="mt-4 py-6 border-t border-blueGray-200 text-center">
              <div class="flex flex-wrap justify-center">
                <div class="w-full lg:w-9/12 px-4 grid sm:grid-cols-2 sm:gap-4 lg:grid-cols-3">
                  @foreach($vendor_types as $vendorType)
                  @if($searching_for->contains($vendorType->id))
                  <div class="col-span-1">
                    <div class="rounded-full bg-win-light my-2 mx-4 p-4">
                      <label for="vt-{{ $vendorType->id }}" class="ms-3.5 block w-full subheading">
                        {{ $vendorType->type }} <span>
                          <img src="{{ $vendorType->icon }}" class="h-auto max-h-6 inline text-pink-win ml-1" alt="Icon">
                        </span>
                      </label>
                    </div>
                  </div>
                  @endif
                  @endforeach
                </div>
              </div>
          </div>
          <div class="mt-4">
            <h3 class="headline-small">Booked Vendors</h3>
          </div>
          <div class="mt-4 py-6 border-t border-blueGray-200 text-center">
            <div class="flex flex-wrap justify-center">
              
              <div class="w-full lg:w-9/12 px-4 grid sm:grid-cols-2 sm:gap-4 lg:grid-cols-3">
              @foreach($booked_vendors as $ven)
                <x-vendor-profile-card :vendor="$ven" />
              @endforeach
              </div>
            </div>
          </div>
          </div>
        </div>
      </section>
    </div>
    @include('layouts.footer')
    <script>
      $("#connectBtn").on("click", () => {
        $("#connectBtn").attr('disabled', true);
        let formData = {
          aff_id: null
        };
        $.ajax({
          type: "POST",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "/vendor/request/connection",
          data: formData,
          success: function (data) {
            console.log("connected user");
            if(data['status'] == false){
              Swal.fire({
                title: 'Oops!',
                text: data['msg'],
                icon:  'error',
                confirmButtonText: 'Ok'
              });
              $("#connectBtn").attr('disabled', false);
            } else{
                Swal.fire({
                  title: 'Nice!',
                  text: data['msg'],
                  icon:  'success',
                  confirmButtonText: 'Ok'
              });
              $("#connectBtn").html("Pending");
            }
          }
        });
      });
      </script>
    <!-- messages-->
  </body>
</html>
