$( document ).ready(function() {
  //favorite btn
  $('body').on('click', '.heart', function() {
    // favorites function
    $(this).toggleClass("is-active");
    let active = $(this).hasClass("is-active");
    $(this).attr('disabled', true);
    if(active){
        $(this).html('<i class="fas fa-heart text-3xl" style="color: #6432C8;"></i>');
    } else {
        $(this).html('<i class="far fa-heart text-3xl"></i>');
    }
    let formData = {
      vendor_uuid: $(this).data("vendor-id"),
      active: active
    };
    $.ajax({
      type: "POST",
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/toggle/favorite",
      data: formData,
      success: function (data) {
        if(!data['status']){
          Swal.fire({
            title: 'Oops!',
            text: "There was a problem changing your favorites list, please try again!",
            icon:  'error',
            confirmButtonText: 'Ok',
            confirmButtonColor: '#6432C8'
          });
        }
        setTimeout(() => {
            $(this).attr('disabled', false);
        }, "1000");
      }
    });
  });
  // remove vendor button
  $('body').on('click', '.rm-pairing', function(event) {
    $(this).attr('disabled', true);
    let formData = {
      vendor_uuid: $(this).data("vendor-id")
    };
    $.ajax({
      type: "POST",
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/client/remove/vendor",
      data: formData,
      success: function (data) {
        if(!data['status']){
          Swal.fire({
            title: 'Oops!',
            text: "There was a problem removing this vendor, please try again!",
            icon:  'error',
            confirmButtonText: 'Ok',
            confirmButtonColor: '#6432C8'
          });
        } else{
          let card = $(event.target).closest( ".vendor-status-card" );
          card.fadeOut(250, function (){
            card.remove();
          });
        }
      }
    });
  });
  //move to booked btn
  
  $('body').on('click', '.mv-booked', function(event) {
    $(this).attr('disabled', true);
    if($(this).data("status") > 2){
      Swal.fire({
        title: "Are you sure?",
        text: "Would you like to unbook this vendor?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm"
      }).then((result) => {
        if (result.isConfirmed) {
          let formData = {
            vendor_uuid: $(this).data("vendor-id")
          };
          $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/client/book/vendor",
            data: formData,
            success: function (data) {
              if(!data['status']){
                Swal.fire({
                  title: 'Oops!',
                  text: "There was a problem moving this vendor, please try again!",
                  icon:  'error',
                  confirmButtonText: 'Ok',
                  confirmButtonColor: '#6432C8'
                });
              } else{
                Swal.fire({
                  title: "Success!",
                  text: "You have unbooked this vendor!",
                  icon: "success",
                  confirmButtonText: 'Ok',
                  confirmButtonColor: '#6432C8'
                });
              }
            }
          });
        } else{
          $(this).attr('disabled', false);
        }
      });
    } else {
      Swal.fire({
        title: "Are you sure?",
        text: "Would you like to mark this vendor as booked?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Confirm"
      }).then((result) => {
        if (result.isConfirmed) {
          let formData = {
            vendor_uuid: $(this).data("vendor-id")
          };
          $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/client/book/vendor",
            data: formData,
            success: function (data) {
              if(!data['status']){
                Swal.fire({
                  title: 'Oops!',
                  text: "There was a problem moving this vendor, please try again!",
                  icon:  'error',
                  confirmButtonText: 'Ok',
                  confirmButtonColor: '#6432C8'
                });
              } else{
                Swal.fire({
                  title: "Success!",
                  text: "You have marked this vendor as booked!",
                  icon: "success",
                  confirmButtonText: 'Ok',
                  confirmButtonColor: '#6432C8'
                });
              }
            }
          });
        } else{
          $(this).attr('disabled', false);
        }
      });
    }
  });

  //open request inquiry/check date modal
  
  let check_date_open = false;
  let check_date_modal = $("#modal-check-date");
  $(document).on("click",".modal-check-date-toggle", function (event) {
      if(check_date_open){
          check_date_modal.fadeOut(250, () => {check_date_modal.toggleClass("hidden")}).css("display","flex");;
      } else {
          check_date_modal.fadeIn(250, () => {check_date_modal.toggleClass("hidden")}).css("display","flex");;
      }
      check_date_open = !check_date_open;
  });

  //edit notes
  $("#save-notes-btn").on("click", function(){
    let notes = $("#notes-input").val();
    console.log(notes);
    if(notes == null || notes == ""){
      return;
    }
    $.ajax({
      type: "POST",
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "/client/profile/notes",
      data: {notes: notes},
      success: function (data) {
        if(!data['status']){
          Swal.fire({
            title: 'Oops!',
            text: "There was a problem saving your notes, please try again!",
            icon:  'error',
            confirmButtonText: 'Ok',
            confirmButtonColor: '#6432C8'
          });
        } else{
          Swal.fire({
            title: "Success!",
            text: "Your notes have been saved!",
            icon: "success",
            confirmButtonText: 'Ok',
            confirmButtonColor: '#6432C8'
          });
        }
      }
    });
  });

  //tutorials
  

  function dashboardTutorial(){
    var intro = introJs();
    return intro.setOptions({
      steps: [
        {
          title: 'Welcome to Your WIN Client Dashboard!',
          intro: "Find your dream wedding team and book with confidence. Follow these steps to get started and make the most of your WIN dashboard."
        },
        {
          element: document.querySelector('#profile-tab'),
          title: 'Quick Access Features',
          intro: `Profile: View the profile shared with vendors.`
        },
        {
          element: document.querySelector('#inbox-tab'),
          title: 'Quick Access Features',
          intro: `Inbox: Manage messages with vendors.`
        },
        {
          element: document.querySelector('#search-vendors-tab'),
          title: 'Quick Access Features',
          intro: `Search Vendors: Explore and connect with wedding professionals.`
        },
        {
          element: document.querySelector('#dashboard-tab'),
          title: 'Quick Access Features',
          intro: `Dashboard: Access all tools and updates in one convenient place.`
        },
        {
          element: document.querySelector('#notification-bar'),
          title: 'Task #1: Stay Updated with the Notification Bar',
          intro: `<ul>
            <li>Countdown to Your Big Day: Stay on top of the timeline for your wedding.</li>
            <li>Messages &amp; Bookings: Get updates on your vendor booking status and new messages.</li>
          </ul>`
        },
        {
          element: document.querySelector('#profile-hover'),
          title: 'Task #2: Edit Your Profile',
          intro: `<ul>
            <li>Customize your bio and profile photo.</li>
            <li>Update your preferences and vendor search categories to match your wedding vision.</li>
          </ul>`
        },
        {
          element: document.querySelector('#savings-card'),
          title: 'Task #3: Preferred Pricing – Savings Worth Celebrating',
          intro: `<ul>
            <li>Some vendors offer preferred pricing to in-network couples.</li>
            <li>Book your first in-network vendor to unlock these savings.</li>
            <li>The more vendors you book within the network, the more you save — and WIN!</li>
          </ul>`
        },
        {
          element: document.querySelector('#vendor-status-card'),
          title: 'Task #4: Vendor Status – Manage Your Search with Ease',
          intro: `View &amp; Search Your Vendor Network:<br>
          <ul>
            <li>Keep tabs on your preferred vendors.</li>
            <li>Simplify the process as you search for and secure your dream team.</li>
          </ul>`
        },
        {
          element: document.querySelector('#messages-section'),
          title: 'Task #5: Inbox &amp; Wedding Appointments',
          intro: `Plan with Purpose:<br>
          <ul>
            <li>Stay connected with your dream team of vendors through messages.</li>
          </ul>`
        },
        {
          element: document.querySelector('#appointments-section'),
          title: 'Task #5: Inbox &amp; Wedding Appointments',
          intro: `Plan with Purpose:<br>
          <ul>
            <li>Manage your wedding consultation appointments.</li>
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

