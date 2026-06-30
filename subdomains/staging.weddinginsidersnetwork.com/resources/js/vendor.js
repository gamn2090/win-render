$( document ).ready(function() {
    //favorites
    $("#request-consultation-btn").on("click", function () {
      let chosenDate = $("#reserve_date").val();
      //if date is in the past
      if(Date.parse(chosenDate) - Date.parse(new Date()) < 0){
        Swal.fire({
          title: 'Oops!',
          text: "The date you selected has already passed! Please check your chosen date again!",
          icon:  'error',
          confirmButtonText: 'Ok',
          confirmButtonColor: '#6432C8'
        });
      }
      $(this).attr("disabled", true);
      let formData = {
        vendor_id: $(this).data("vendor-id"),
        date: chosenDate
      };
      console.log(chosenDate);
      $.ajax({
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/client/meeting/request",
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
          } else {
            Swal.fire({
              title: 'Congratulations!',
              text: "You have requested a consultation with this vendor. They will get back to you shortly!",
              icon:  'success',
              confirmButtonText: 'Ok',
              confirmButtonColor: '#6432C8'
            });
          }
        }
      });
    });
    //lightbox image popup
    $('.lightbox').on('click', function() {
        const imgSrc = $(this).attr('src');
        $('#lightbox img').attr('src', imgSrc);
        $('#lightbox').fadeIn();
    });

    $('#lightbox, #close').on('click', function() {
        $('#lightbox').fadeOut();
    });
    
    $('#viewAllPortfolioImages').on('click', function() {
        $('#viewAllContainer').focus();
        $("html, body").css("overflow","hidden");
        $('#lightboxAll').fadeIn();
    });

    $('#closeAll').on('click', function() {
        $("html, body").css("overflow","auto");
        $('#lightboxAll').fadeOut();
    });

    //check date modal
    
    //TODO: replace blade consultation check script


    let index = 0;

    $("#match-stepper-next").on('click', () => {
        index += 1;
        if(index == 2){
            const defaults = {
                spread: 360,
                ticks: 100,
                gravity: 0,
                decay: 0.94,
                startVelocity: 30,
                shapes: ["heart"],
                colors: ["FFC0CB", "FF69B4", "FF1493", "C71585"],
            };

            confetti({
                ...defaults,
                particleCount: 50,
                scalar: 2,
            });

            confetti({
                ...defaults,
                particleCount: 25,
                scalar: 3,
            });

            confetti({
                ...defaults,
                particleCount: 10,
                scalar: 4,
            });
        }
    });

    $("#match-stepper-send").on('click', () => {
      $("#match-stepper-send").attr('disabled', true);
      let formData = {
        vendor_id: window.vendorID
      };
      $.ajax({
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/client/send/inquiry",
        data: formData,
        success: function (data) {
          if(data['status'] == false){
            Swal.fire({
              title: 'Oops!',
              text: "We couldn't send your inquiry to this vendor. Please try again later!",
              icon:  'error',
              confirmButtonText: 'Ok',
              confirmButtonColor: '#6432C8'
            });
          } else{
              Swal.fire({
                title: 'Congratulations!',
                text: "We sent an inquiry to this vendor with your profile details! The vendor will reach out to you shortly.",
                icon:  'success',
                confirmButtonText: 'Ok',
                confirmButtonColor: '#6432C8'
            });
          }
        }
      });
    });
});