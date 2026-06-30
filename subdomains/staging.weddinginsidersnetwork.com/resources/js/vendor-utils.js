$( document ).ready(function() {
  //find couples page inquire with client
  
  $('body').on('click', '.inquireClientButton', function(event) {
    Swal.fire({
      title: "Are you sure?",
      text: "Would you like to contact this client? This will use 1 contact credit.",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Confirm"
    }).then((result) => {
      if (result.isConfirmed) {
        let formData = {
          client_uuid: $(this).data("client-uuid")
        };
        console.log($(this).data("client-uuid"));
        $.ajax({
          type: "POST",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "/vendor/message/client",
          data: formData,
          success: function (data) {
            if(!data['status']){
              Swal.fire({
                title: 'Oops!',
                text: data["message"],
                icon:  'error',
                confirmButtonText: 'Ok',
                confirmButtonColor: '#6432C8'
              });
            } else{
              window.location = '/inbox/conversation/' + data["c_id"];
            }
          }
        });
      } else{
        $(this).attr('disabled', false);
      }
    });
  });

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

  $('body').on('click', '#business-search-btn', function(event) {
    let formData = {
      search: $('#google_business_name').val(),
    };
    $.ajax({
      type: "POST",
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/vendor/business/search",
      data: formData,
      success: function (data) {
        $("#find-place-section").toggleClass("hidden");
        $("#confirm-place-section").toggleClass("hidden");
        $("#place-name").text(data["places"][0]["displayName"]["text"]);
        $("#place-link").attr("href", data["places"][0]["googleMapsUri"]);
        $("#g-place-id").text(data["places"][0]["id"]);
        if((data["places"][0]["formattedAddress"] != null) && (data["places"][0]["formattedAddress"] != "")){
          $("#place-location-section").toggleClass("hidden");
          $("#place-location").text(data["places"][0]["formattedAddress"]);
        }
      }
    });
  });

  
  $('body').on('click', '.rm-pairing', function(event) {
    $(this).attr('disabled', true);
    let formData = {
      client_uuid: $(this).data("vendor-id")
    };
    $.ajax({
      type: "POST",
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/vendor/remove/client",
      data: formData,
      success: function (data) {
        if(!data['status']){
          Swal.fire({
            title: 'Oops!',
            text: "There was a problem removing this client, please try again!",
            icon:  'error',
            confirmButtonText: 'Ok',
            confirmButtonColor: '#6432C8'
          });
        } else{
          let card = $(event.target).closest( ".current-client-row" );
          card.fadeOut(250, function (){
            card.remove();
          });
        }
      }
    });
  });

  $('body').on('click', '#confirm-place-btn', function(event) {
    let formData = {
      place_id: $('#g-place-id').text(),
    };
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
        });
      }
    });
  });

  $('body').on('click', '#unlink-place-btn', function(event) {
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

