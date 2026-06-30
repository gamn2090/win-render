$( document ).ready(function() {
  var optionsCircle4 = {
      chart: {
        type: 'radialBar',
        height: '100%'
      },
      colors: ["#ED9A47", "#E6632B", "#D2C6E4", "#627DF6", "#5E34C1"],
      plotOptions: {
        radialBar: {
          size: undefined,
          inverseOrder: true,
          hollow: {
            margin: 5,
            size: '35%',
            background: 'transparent',
    
          },
          track: {
            show: true,
            background: "#F3F4F6",
            strokeWidth: '97%',
            opacity: 1,
          },
    
        },
      },
      stroke: {
        lineCap: 'round'
      },
      series: window.series,
      labels: window.labels,
      legend: {
        show: true,
        position: 'right',
      },
      responsive: [{
        breakpoint: 700,
        options: {
          legend: {
            position: 'top'
          }
        },
    }]
  }
  var chartCircle4 = new ApexCharts(document.querySelector('#rankingRadialChart'), optionsCircle4);
  //quick fix for chart not showing on first page view
  setTimeout(() => {
      chartCircle4.render();
  },500)
});