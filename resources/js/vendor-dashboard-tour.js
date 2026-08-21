$(document).ready(function () {
  function vendorDashboardTour() {
    var intro = introJs();
    return intro.setOptions({
      steps: [
        {
          title: 'Welcome to Your Vendor Dashboard',
          intro: "This is your home base on WIN. Let's walk through what each section shows you and how to use it."
        },
        {
          element: document.querySelector('#vd-stats-section'),
          title: 'Key Metrics',
          intro: `<ul>
            <li><strong>Storefront Views</strong>: how many times couples have viewed your public profile.</li>
            <li><strong>New Leads</strong>: fresh inquiries waiting on a response, with today's count highlighted.</li>
            <li><strong>Active Bookings</strong>: confirmed jobs booked this month.</li>
            <li><strong>Contact Credits</strong>: how many couple profiles you can still view via "Find Couples."</li>
          </ul>`
        },
        {
          element: document.querySelector('#vd-winfluence-card'),
          title: 'WINfluence Status',
          intro: `<ul>
            <li>Your overall ranking score and level, plus how many points you need to reach the next level.</li>
            <li>A breakdown by category — Badges, Endorsements, Reviews, and Vendor Community.</li>
            <li>Your earned badges, and a suggested "Top Action" to raise your score fastest.</li>
            <li>Click "View Insights" any time for the full breakdown and tips.</li>
          </ul>`
        },
        {
          element: document.querySelector('#vd-messages-card'),
          title: 'Messages',
          intro: `Your most recent conversations with clients and other vendors. Reply right from the preview, or click "View all" to open your full inbox.`
        },
        {
          element: document.querySelector('#vd-appointments-card'),
          title: 'My Wedding Appointments',
          intro: `A quick look at your upcoming meetings with clients. Click the calendar icon to open your full calendar and manage scheduling.`
        },
        {
          element: document.querySelector('#vd-promo-row'),
          title: 'Grow Your Network',
          intro: `<ul>
            <li><strong>Refer Vendors / Refer a Client</strong>: invite people to WIN and boost your ranking.</li>
            <li><strong>Find Couples</strong>: browse couples actively looking for vendors like you in your area.</li>
          </ul>`
        },
        {
          element: document.querySelector('#vd-clients-card'),
          title: 'Current Clients',
          intro: `Couples you're actively working with. Message any of them directly, or click "View all" to see your full client list.`
        },
        {
          element: document.querySelector('#vd-network-card'),
          title: 'Vendor Network',
          intro: `The vendors you're connected with — the preferred vendors shown on your storefront. Click "Explore" to grow this network.`
        },
        {
          element: document.querySelector('#community-vendors-section'),
          title: 'Browse & Connect with Other Vendors',
          intro: `Discover other vendors on WIN and connect with them to build referral relationships that boost both of your rankings.`
        },
        {
          element: document.querySelector('#vd-tools-section'),
          title: 'Planning Tools',
          intro: `<ul>
            <li><strong>My Calendar</strong>: schedule and track events with your booked couples.</li>
            <li><strong>WIN Wedding Investment Planner</strong>: a budgeting tool you can share with your clients.</li>
          </ul>`
        },
      ],
      buttonClass: 'rounded-lg bg-win-purple text-white py-1 px-3'
    });
  }

  $('#tutorial-btn').on('click', function () {
    vendorDashboardTour().start();
  });

  if (window.newUser) {
    vendorDashboardTour().setOption('dontShowAgain', true).start();
  }
});
