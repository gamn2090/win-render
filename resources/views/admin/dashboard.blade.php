<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>WIN: Admin Dashboard</title>
    
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('components.fonts')
  </head>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.48.0/apexcharts.min.js" integrity="sha512-wqcdhB5VcHuNzKcjnxN9wI5tB3nNorVX7Zz9NtKBxmofNskRC29uaQDnv71I/zhCDLZsNrg75oG8cJHuBvKWGw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.48.0/apexcharts.min.css" integrity="sha512-qc0GepkUB5ugt8LevOF/K2h2lLGIloDBcWX8yawu/5V8FXSxZLn3NVMZskeEyOhlc6RxKiEj6QpSrlAoL1D3TA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <style type="text/css">
    .apexcharts-tooltip.apexcharts-theme-light {
      background-color: transparent !important;
      border: none !important;
      box-shadow: none !important;
    }
  </style>
  <body class="m-0 font-sans text-base antialiased font-normal leading-default text-black overflow-x-hidden min-w-screen p-8">
    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out rounded-xl hidden">
      <!-- Breadcrumb -->
      <div class="sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 md:px-8 lg:hidden">
        <div class="flex justify-between items-center py-2">
          <!-- Breadcrumb -->
          <ol class="ms-3 flex items-center whitespace-nowrap">
            <li class="flex items-center text-sm text-gray-800">
              Application Layout
              <svg class="flex-shrink-0 mx-3 overflow-visible size-2.5 text-gray-400" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </li>
            <li class="text-sm font-semibold text-gray-800 truncate" aria-current="page">
              Dashboard
            </li>
          </ol>
          <!-- End Breadcrumb -->
      
          <!-- Sidebar -->
          <button type="button" class="py-2 px-3 flex justify-center items-center gap-x-1.5 text-xs rounded-lg border border-gray-200 text-gray-500 hover:text-gray-600" data-hs-overlay="#application-sidebar" aria-controls="application-sidebar" aria-label="Sidebar">
            <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8L21 12L17 16M3 12H13M3 6H13M3 18H13"/></svg>
            <span class="sr-only">Sidebar</span>
          </button>
          <!-- End Sidebar -->
        </div>
      </div>
      <!-- End Breadcrumb -->
      
      <!-- Content -->
      <div class="w-full">
        <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
          <!-- Grid -->
          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Card -->
            <div class="flex flex-col bg-white border shadow-sm rounded-xl">
              <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                  <p class="text-sm uppercase tracking-wide text-gray-500">
                    Total users
                  </p>
                </div>
      
                <div class="mt-1 flex items-center gap-x-2">
                  <h3 class="text-xl sm:text-2xl font-medium text-gray-800">
                    {{ App\Models\Vendor::count() + App\Models\User::count()}}
                  </h3>
                </div>
              </div>
            </div>
            <!-- End Card -->
      
            <!-- Card -->
            <div class="flex flex-col bg-white border shadow-sm rounded-xl">
              <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                  <p class="text-sm uppercase tracking-wide text-gray-500">
                    Total Vendors
                  </p>
                </div>
      
                <div class="mt-1 flex items-center gap-x-2">
                  <h3 class="text-xl sm:text-2xl font-medium text-gray-800">
                    {{ App\Models\Vendor::count() }}
                  </h3>
                </div>
              </div>
            </div>
            <!-- End Card -->
      
            <!-- Card -->
            <div class="flex flex-col bg-white border shadow-sm rounded-xl">
              <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                  <p class="text-sm uppercase tracking-wide text-gray-500">
                    Total Clients
                  </p>
                </div>
      
                <div class="mt-1 flex items-center gap-x-2">
                  <h3 class="text-xl sm:text-2xl font-medium text-gray-800">
                    {{ App\Models\User::count() }}
                  </h3>
                </div>
              </div>
            </div>
            <!-- End Card -->
      
            <!-- Card -->
            <div class="flex flex-col bg-white border shadow-sm rounded-xl">
              <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                  <p class="text-sm uppercase tracking-wide text-black">
                    Total Money Saved
                  </p>
                </div>
      
                <div class="mt-1 flex items-center gap-x-2">
                  <h3 class="text-xl sm:text-2xl font-medium text-gray-800">
                  </h3>
                </div>
              </div>
            </div>
            <!-- End Card -->
          </div>
          <!-- End Grid -->
      
          <div class="grid lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Card -->
            <div class="p-4 md:p-5 min-h-[410px] flex flex-col bg-white border shadow-sm rounded-xl">
              <!-- Header -->
              <div class="flex justify-between items-center">
                <div>
                  <h2 class="text-black font-semibold">
                    Subscriptions
                  </h2>
                </div>
              </div>
              <!-- End Header -->
      
              <div id="chart"></div>
            </div>
            <!-- End Card -->
      
            <!-- Card -->
            <div class="p-4 md:p-5 min-h-[410px] flex flex-col bg-white border shadow-sm rounded-xl">
              <!-- Header -->
              <div class="flex justify-between items-center">
                <div>
                  <h2 class="text-black font-semibold">
                    Vendor Types
                  </h2>
                </div>
              </div>
              <div id="types-chart"></div>
            </div>
            <!-- End Card -->
          </div>
          <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-sm max-w-[64rem] mx-auto">
            <div class="p-4 sm:p-7">
              <div class="text-center">
                <h1 class="block text-2xl font-bold text-gray-800">Generate Vendors CSV</h1>
              </div>

              <div class="mt-5">
                <form method="GET" action="{{ route('admin.csv.vendors') }}">
                  @csrf
                  <div class="grid gap-y-4">
                    <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-dark-purple-win text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">Submit</button>
                  </div>
                </form>
                <!-- End Form -->
              </div>
            </div>
          </div>
          <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-sm max-w-[64rem] mx-auto">
            <div class="p-4 sm:p-7">
              <div class="text-center">
                <h1 class="block text-2xl font-bold text-gray-800">Add Vendor Suscription</h1>
                <p>Input vendor email address and specify the number of months to add.</p>
              </div>

              <div class="mt-5">
                <!-- Form -->
                @if(session('res'))
                  @if(session('res')["status"] == false)
                  <p class="text-sm text-red mt-2">{{ session('res')["msg"] }}</p>
                  @endif
                  @if(session('res')["status"] == true)
                  <p class="text-sm text-green mt-2">Successfully increased subscription!</p>
                  @endif
                @endif
                <form method="POST" action="{{ route('admin.add.months') }}">
                  @csrf
                  <div class="grid gap-y-4">
                    <!-- Form Group -->
                    <div>
                      <label for="email" class="block text-sm mb-2">Email address</label>
                      <div class="relative">
                        <input type="email" id="email" name="email" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" required aria-describedby="email-error">
                        <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                          <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                          </svg>
                        </div>
                      </div>
                      <p class="hidden text-xs text-red-600 mt-2" id="email-error">Please include a valid email address so we can get back to you</p>
                    </div>
                    <div>
                      <label for="months" class="block text-sm mb-2">Months</label>
                      <div class="relative">
                        <input type="number" id="months" name="months" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" required aria-describedby="months-error">
                        <div class="hidden absolute inset-y-0 end-0 pointer-events-none pe-3">
                          <svg class="size-5 text-red-500" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                          </svg>
                        </div>
                      </div>
                      <p class="hidden text-xs text-red-600 mt-2" id="months-error">Please include a valid number!</p>
                    </div>
                    <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-dark-purple-win text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">Submit</button>
                  </div>
                </form>
                <!-- End Form -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Content -->
      <!-- ========== END MAIN CONTENT ========== -->
    </main>
    <div class="mx-auto px-8 my-8">
      <h3 class="headline-small">Conversations</h3>
      @php
      $messages = \Musonza\Chat\Models\Message::orderBy('created_at', 'desc')->paginate(40);
      @endphp
      @foreach($messages as $message)
        <div class="border p-2">
          <p>{{ $message->body }}</p>
          <p>Sent by:</p>
          <p class="font-semibold">
          @php
          $sender = \Musonza\Chat\Models\Participation::where('id', $message->participation_id)->first()
          @endphp
          @if($sender->messageable_type == 'App\Models\User')
            Client:
            {{ $sender->messageable->first_name }} {{ $sender->messageable->last_name }}
            @else
            Vendor:
            {{ $sender->messageable ? $sender->messageable->business_name : 'unknown' }}
            @endif
          </p>
        </div>
      @endforeach
      
      <div class="flex justify-end items-end my-4 space-x-4">
            @if(!$messages->onFirstPage())
            <a href="{{ $messages->previousPageUrl() }}" class="py-1 px-4 bg-win-blue rounded-lg text-white">Previous</a>
            @endif
            @if($messages->hasMorePages())
            <a href="{{ $messages->nextPageUrl() }}" class="py-1 px-4 bg-win-blue rounded-lg text-white">Next</a>
            @endif
          </div>
    </div>
  </body>
  <script src="https://preline.co/assets/js/hs-apexcharts-helpers.js"></script>
  <script>
    window.addEventListener('load', () => {
      (function () {
        var options = {
          series: [{{ App\Models\Payment::where('confirmed', true)->count() }}, {{ App\Models\Vendor::count() - App\Models\Payment::where('confirmed', true)->count()}}],
          chart: {
          width: '100%',
          type: 'pie',
        },
        labels: ['Subscribed', 'Not Subscribed'],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: '100%'
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
      })();
    });
  </script>

<script>
  window.addEventListener('load', () => {
    (function () {
      var options = {
        series: [@foreach($data["vendor_types_count"] as $type_count){{ $type_count }}, @endforeach],
        chart: {
        width: '100%',
        type: 'pie',
      },
      labels: [@foreach($data["vendor_types"] as $type)'{{ $type->type }}', @endforeach],
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: '100%'
          },
          legend: {
            position: 'bottom'
          }
        }
      }]
      };

      var chart = new ApexCharts(document.querySelector("#types-chart"), options);
      chart.render();
    })();
  });
</script>
  
  <script>
    window.addEventListener('load', () => {
      (function () {
        buildChart('#hs-single-area-chart', (mode) => ({
          chart: {
            height: 300,
            type: 'area',
            toolbar: {
              show: false
            },
            zoom: {
              enabled: false
            }
          },
          series: [
            {
              name: 'Visitors',
              data: [180, 51, 60, 38, 88, 50, 40, 52, 88, 80, 60, 70]
            }
          ],
          legend: {
            show: false
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            curve: 'straight',
            width: 2
          },
          grid: {
            strokeDashArray: 2
          },
          fill: {
            type: 'gradient',
            gradient: {
              type: 'vertical',
              shadeIntensity: 1,
              opacityFrom: 0.1,
              opacityTo: 0.8
            }
          },
          xaxis: {
            type: 'category',
            tickPlacement: 'on',
            categories: [
              '25 January 2023',
              '26 January 2023',
              '27 January 2023',
              '28 January 2023',
              '29 January 2023',
              '30 January 2023',
              '31 January 2023',
              '1 February 2023',
              '2 February 2023',
              '3 February 2023',
              '4 February 2023',
              '5 February 2023'
            ],
            axisBorder: {
              show: false
            },
            axisTicks: {
              show: false
            },
            crosshairs: {
              stroke: {
                dashArray: 0
              },
              dropShadow: {
                show: false
              }
            },
            tooltip: {
              enabled: false
            },
            labels: {
              style: {
                colors: '#9ca3af',
                fontSize: '13px',
                fontFamily: 'Inter, ui-sans-serif',
                fontWeight: 400
              },
              formatter: (title) => {
                let t = title;
  
                if (t) {
                  const newT = t.split(' ');
                  t = `${newT[0]} ${newT[1].slice(0, 3)}`;
                }
  
                return t;
              }
            }
          },
          yaxis: {
            labels: {
              align: 'left',
              minWidth: 0,
              maxWidth: 140,
              style: {
                colors: '#9ca3af',
                fontSize: '13px',
                fontFamily: 'Inter, ui-sans-serif',
                fontWeight: 400
              },
              formatter: (value) => value >= 1000 ? `${value / 1000}k` : value
            }
          },
          tooltip: {
            x: {
              format: 'MMMM yyyy'
            },
            y: {
              formatter: (value) => `${value >= 1000 ? `${value / 1000}k` : value}`
            },
            custom: function (props) {
              const { categories } = props.ctx.opts.xaxis;
              const { dataPointIndex } = props;
              const title = categories[dataPointIndex].split(' ');
              const newTitle = `${title[0]} ${title[1]}`;
  
              return buildTooltip(props, {
                title: newTitle,
                mode,
                valuePrefix: '',
                hasTextLabel: true,
                markerExtClasses: '!rounded-sm',
                wrapperExtClasses: 'min-w-28'
              });
            }
          },
          responsive: [{
            breakpoint: 568,
            options: {
              chart: {
                height: 300
              },
              labels: {
                style: {
                  colors: '#9ca3af',
                  fontSize: '11px',
                  fontFamily: 'Inter, ui-sans-serif',
                  fontWeight: 400
                },
                offsetX: -2,
                formatter: (title) => title.slice(0, 3)
              },
              yaxis: {
                labels: {
                  align: 'left',
                  minWidth: 0,
                  maxWidth: 140,
                  style: {
                    colors: '#9ca3af',
                    fontSize: '11px',
                    fontFamily: 'Inter, ui-sans-serif',
                    fontWeight: 400
                  },
                  formatter: (value) => value >= 1000 ? `${value / 1000}k` : value
                }
              },
            },
          }]
        }), {
          colors: ['#2563eb', '#9333ea'],
          fill: {
            gradient: {
              stops: [0, 90, 100]
            }
          },
          grid: {
            borderColor: '#e5e7eb'
          }
        }, {
          colors: ['#3b82f6', '#a855f7'],
          fill: {
            gradient: {
              stops: [100, 90, 0]
            }
          },
          grid: {
            borderColor: '#374151'
          }
        });
      })();
    });
  </script>
</html>
