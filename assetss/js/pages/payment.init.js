/**
 * Theme: Approx - Bootstrap 5 Responsive Admin Dashboard
 * Author: Mannatthemes
 * Payment Js
 */


  
   //customers-widget
  
   
   var options = {
    chart: {
        height: 285,
        type: 'donut',
    }, 
    plotOptions: {
      pie: {
        donut: {
          size: '80%'
        }
      }
    },
    dataLabels: {
      enabled: false,
    },
  
    stroke: {
      show: true,
      width: 2,
      colors: ['transparent']
    },
   
    series: [50, 25, 10, 15],
    legend: {
      show: true,
      position: 'bottom',
      horizontalAlign: 'center',
      verticalAlign: 'middle',
      floating: false,
      fontSize: '13px',
      fontFamily: "Be Vietnam Pro, sans-serif",
      offsetX: 0,
      offsetY: 0,
    },
    labels: [ "USD","Euro", "Pounds", "Dinar" ],
    colors: ["#0e2a89", "#d96345", "#ffb600", "#47cdea"],
   
    responsive: [{
        breakpoint: 600,
        options: {
          plotOptions: {
              donut: {
                customScale: 0.2
              }
            },        
            chart: {
                height: 240
            },
            legend: {
                show: false
            },
        }
    }],
    tooltip: {
      y: {
          formatter: function (val) {
              return   val + " %"
          }
      }
    }
    
  }
  
  var chart = new ApexCharts(
    document.querySelector("#customers"),
    options
  );
  
  chart.render();