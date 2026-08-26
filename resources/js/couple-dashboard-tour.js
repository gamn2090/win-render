$(document).ready(function () {
  function coupleDashboardTour() {
    var intro = introJs();
    return intro.setOptions({
      steps: [
        {
          title: 'Welcome to Your Wedding Dashboard',
          intro: "This is your home base on WIN. Let's walk through what each section shows you and how to use it."
        },
        {
          element: document.querySelector('#vd-stats-section'),
          title: 'Key Metrics',
          intro: `<ul>
            <li><strong>Days Until Wedding</strong>: your countdown, with a shortcut to your Timeline.</li>
            <li><strong>Unread Messages</strong>: new messages waiting from your vendors.</li>
            <li><strong>Vendors Booked</strong>: how many vendors you've confirmed so far.</li>
            <li><strong>Vendor Matches</strong>: vendors available for the categories you're looking for.</li>
            <li><strong>Total Savings</strong>: money saved through WIN's preferred pricing.</li>
          </ul>`
        },
        {
          element: document.querySelector('#vd-messages-card'),
          title: 'Messages',
          intro: `Your most recent conversations with vendors. Reply right from the preview, or click "View all" to open your full inbox.`
        },
        {
          element: document.querySelector('#vd-appointments-card'),
          title: 'My Consultations Appointments',
          intro: `Upcoming consultations and meetings with vendors. Click "View all" to see your full appointment list.`
        },
        {
          element: document.querySelector('#vd-planning-tools'),
          title: 'Wedding Planning Tools',
          intro: `<ul>
            <li><strong>WIN Wedding Investment Planner</strong>: build your budget and set spending priorities.</li>
            <li><strong>WIN Wedding Timeline Planner</strong>: build and share your day-of timeline with your vendors.</li>
          </ul>`
        },
        {
          element: document.querySelector('#vd-vendor-status-card'),
          title: 'Vendor Status',
          intro: `See which vendor categories you still need, which you're searching for, and which you've already booked. Click "Search" to find vendors for any category.`
        },
        {
          element: document.querySelector('#vd-wedding-team-section'),
          title: 'Your Wedding Team',
          intro: `Every vendor you've booked so far, with quick access to message them or view their storefront.`
        },
        {
          element: document.querySelector('#vd-booked-savings'),
          title: 'Booked Vendors Savings',
          intro: `A running tally of the preferred-pricing discounts each booked vendor is giving you, and your total savings through WIN.`
        },
        {
          element: document.querySelector('#vd-refer-promo'),
          title: 'Get Matched with Vendors',
          intro: `Browse vendors tailored to your wedding — click through to find and connect with the right ones for you.`
        },
        {
          title: 'Update Your Profile',
          intro: `Keep your wedding details up to date — your date, venue, and preferences help us match you with the right vendors. Click below to finish your profile now.`
        },
      ],
      buttonClass: 'rounded-lg bg-win-purple text-white py-1 px-3',
      doneLabel: 'Complete Profile'
    }).oncomplete(function () {
      window.location.href = '/profile/edit';
    });
  }

  $('#tutorial-btn').on('click', function () {
    coupleDashboardTour().start();
  });

  if (window.newUser) {
    coupleDashboardTour().setOption('dontShowAgain', true).start();
  }
});
