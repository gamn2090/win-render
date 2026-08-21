$( document ).ready(function() {
  // Contact confirm for Find Couples lives in find-couples.js (fc-contact-confirm-modal).

  $('body').on('click', '#endorse-btn', function(event) {
    let endorsements = new Array();
    $.each($("input[name='endorsements[]']:checked"), function() {
      endorsements.push($(this).val());
    });
    let formData = {
      vendor_uuid: $(this).data("vendor-uuid"),
      endorsements: endorsements,
    };
    $.ajax({
      type: "POST",
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/vendor/endorse",
      data: formData,
      success: function (data) {
        $("#endorse-vendor-modal-close-btn").trigger("click");
        Swal.fire({
          title: 'Success!',
          text: "You have submitted an endorsement for this vendor!",
          icon:  'success',
          confirmButtonText: 'Ok',
          confirmButtonColor: '#6432C8'
        });
      }
    });
  });

  // Resets the modal back to the search step whenever it's opened or closed
  // (the trigger button and the modal's close button both carry this
  // attribute), so a vendor who closes it mid-pick always starts fresh.
  $('body').on('click', '[data-hs-overlay="#link-google-place-modal"]', function () {
    $('#results-place-section').addClass('hidden');
    $('#find-place-section').removeClass('hidden');
    $('#place-results-list').empty();
    $('#no-results-msg').addClass('hidden');
    $('#g-place-id').text('');
    $('#confirm-place-btn').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
  });

  $('body').on('click', '#business-search-btn', function(event) {
    let formData = {
      search: $('#google_business_name').val(),
    };
    $('#business-search-btn').prop('disabled', true);
    $.ajax({
      type: "POST",
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/vendor/business/search",
      data: formData,
      success: function (data) {
        $('#business-search-btn').prop('disabled', false);

        const places = (data && data["places"]) || [];
        const $list = $('#place-results-list').empty();
        $('#g-place-id').text('');
        $('#confirm-place-btn').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');

        if (!places.length) {
          $('#no-results-msg').removeClass('hidden');
        } else {
          $('#no-results-msg').addClass('hidden');

          places.forEach(function (place) {
            const name = (place.displayName && place.displayName.text) || 'Unnamed business';
            const address = place.formattedAddress || '';
            const category = place.primaryTypeDisplayName ? place.primaryTypeDisplayName.text : '';
            const rating = place.rating;
            const ratingCount = place.userRatingCount;

            const $card = $('<div>')
              .addClass('place-result-card')
              .attr('data-place-id', place.id);

            const $info = $('<div>').addClass('place-result-card__info');
            $('<p>').addClass('place-result-card__name').text(name).appendTo($info);
            if (category) {
              $('<p>').addClass('place-result-card__meta').text(category).appendTo($info);
            }
            if (rating) {
              const filled = Math.round(rating);
              const stars = '★'.repeat(filled) + '☆'.repeat(5 - filled);
              $('<p>').addClass('place-result-card__meta').text(stars + ' ' + rating + (ratingCount ? ' (' + ratingCount + ' reviews)' : '')).appendTo($info);
            }
            if (address) {
              $('<p>').addClass('place-result-card__address').text(address).appendTo($info);
            }

            $card.append($info);
            $('<span>').addClass('place-result-card__check').text('✓').appendTo($card);
            $list.append($card);
          });
        }

        $("#find-place-section").addClass("hidden");
        $("#results-place-section").removeClass("hidden");
      },
      error: function () {
        $('#business-search-btn').prop('disabled', false);
        Swal.fire({
          title: 'Error',
          text: 'Something went wrong searching for your business, please try again.',
          icon: 'error',
          confirmButtonColor: '#6432C8'
        });
      }
    });
  });

  $('body').on('click', '.place-result-card', function () {
    $('.place-result-card').removeClass('place-result-card--selected');
    $(this).addClass('place-result-card--selected');
    $('#g-place-id').text($(this).data('placeId'));
    $('#confirm-place-btn').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
  });

  $('body').on('click', '#back-to-search-btn', function () {
    $('#results-place-section').addClass('hidden');
    $('#find-place-section').removeClass('hidden');
  });

  $('body').on('click', '#confirm-place-btn', function(event) {
    const placeId = $('#g-place-id').text();
    if (!placeId) {
      return;
    }
    let formData = {
      place_id: placeId,
    };
    $('#confirm-place-btn').prop('disabled', true);
    $.ajax({
      type: "POST",
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/vendor/business/link",
      data: formData,
      success: function (data) {
        $("#link-google-place-modal-close-btn").trigger("click");
        Swal.fire({
          title: 'Success!',
          text: "You linked this Google business to your account!",
          icon:  'success',
          confirmButtonText: 'Ok',
          confirmButtonColor: '#6432C8'
        }).then(function () {
          window.location.reload();
        });
      },
      error: function () {
        $('#confirm-place-btn').prop('disabled', false);
        Swal.fire({
          title: 'Error',
          text: 'Could not link this business, please try again.',
          icon: 'error',
          confirmButtonColor: '#6432C8'
        });
      }
    });
  });

  $('body').on('click', '#unlink-place-btn', function(event) {
    Swal.fire({
      title: 'Unlink this business?',
      text: "You'll stop showing its Google reviews until you link a business again.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Unlink',
      confirmButtonColor: '#6432C8',
      cancelButtonColor: '#d33'
    }).then(function (result) {
      if (!result.isConfirmed) {
        return;
      }
      $.ajax({
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/vendor/business/unlink",
        success: function (data) {
          window.location.reload();
        }
      });
    });
  });

  function dashboardTutorial(){
    var intro = introJs();
    return intro.setOptions({
      steps: [
        {
          title: 'Welcome to WIN: Dashboard Tutorial',
          intro: "Your hub for client &amp; vendor connections, communication, insights, and rankings. Follow these steps to get started and make the most of your WIN dashboard."
        },
        {
          element: document.querySelector('#hs-dropdown-hover-refer'),
          title: 'Task #1: Build Community, Refer, Boost &amp; WIN',
          intro: `<ul>
            <li>Invite your preferred vendors and clients to WIN to boost your rankings.</li>
            <li>Help grow your network and strengthen your profile visibility.</li>
          </ul>`
        },
        {
          element: document.querySelector('#profile-hover'),
          title: 'Task #2: Edit Your Profile',
          intro: `<ul>
            <li>Link your Google Business page.</li>
            <li>Upload your portfolio.</li>
            <li>Update pricing, location, and your bio to showcase your expertise.</li>
          </ul>`
        },
        {
          element: document.querySelector('#notification-bar'),
          title: 'Task #3: Stay Updated with the Notification Bar',
          intro: `<ul>
            <li>View your current category rankings, store front views, &amp; messages.</li>
            <li>Track your client credits for the “Find Couples” feature.</li>
          </ul>`
        },
        {
          element: document.querySelector('#current-clients-card'),
          title: 'Task #4: Expand Your Network',
          intro: `<ul>
            <li>View all your current connections:</li>
            <li>Active Clients: Couples you’re currently working with.</li>
          </ul>`
        },
        {
          element: document.querySelector('#vendor-network-card'),
          title: 'Task #4: Expand Your Network',
          intro: `<ul>
            <li>View all your current connections:</li>
            <li>Preferred Vendors: Vendors showcased on your storefront.</li>
          </ul>`
        },
        {
          element: document.querySelector('#badges-section'),
          title: 'Task #5: Unlock Badges for Ranking Points',
          intro: `<ul>
            <li>Check your progress on badges:</li>
            <li>View current badges earned.</li>
            <li>See locked badges and work towards unlocking them.</li>
          </ul>`
        },
        {
          element: document.querySelector('#search-vendors-tab'),
          title: 'Task #6: Browse Our Vendor Network',
          intro: `<ul>
            <li>Build valuable connections with other vendors.</li>
            <li>Send and receive referrals to boost rankings and WIN big!</li>
          </ul>`
        },
        {
          element: document.querySelector('#find-couples-tab'),
          title: 'Quick Access Features',
          intro: `Find Couples: Use credits to view couple profiles, including their status and bio.
Inquire with confidence!`
        },
        {
          element: document.querySelector('#inbox-tab'),
          title: 'Quick Access Features',
          intro: `Inbox: Your central communication hub for vendors and couples.`
        },
        {
          element: document.querySelector('#storefront-tab'),
          title: 'Quick Access Features',
          intro: `Storefront: See your live storefront. (Use “Edit Profile” to make updates)`
        },
        {
          element: document.querySelector('#insights-tab'),
          title: 'Quick Access Features',
          intro: `Insights: Track your merit-based ranking.
          <ul>
            <li>Learn how the ranking system works.</li>
            <li>Get actionable tips to boost visibility.</li>
            <li>Click "How it works" in the insights tab to learn more.</li>
          </ul>`
        },
      ],
      buttonClass: 'rounded-lg bg-win-purple text-white py-1 px-3'
      });
  }

  $("#tutorial-btn").on("click", function(){
    dashboardTutorial().start();
  });

  if(window.newUser){
    dashboardTutorial().setOption("dontShowAgain", true).start();
  }

});

