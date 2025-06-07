/**
 * Theme: Approx - Bootstrap 5 Responsive Admin Dashboard
 * Author: Mannatthemes
 * Analytics Dashboard Js
 */

var chart = {
  series: [
    {
      name: "Income",
      data: [ 2.7, 2.2, 1.3, 2.5, 1, 2.5, 1.2, 1.2, 2.7, 1, 3.6, 2.1,],
    },
    {
      name: "Expense",
      data: [ -2.3, -1.9, -1, -2.1, -1.3, -2.2, -1.1, -2.3, -2.8, -1.1, -2.5, -1.5,],
    },
  ],
  chart: {
    toolbar: {
      show: false,
    },
    type: "bar",
    fontFamily: "inherit",
    foreColor: "#adb0bb",
    height: 370,
    stacked: true,
    offsetX: -15,
  },
  colors: ["var(--bs-success)", "rgba(155, 171, 187, .25)"],
  plotOptions: {
    bar: {
      horizontal: false,
      barHeight: "80%",
      columnWidth: "20%",
      borderRadius: [3],
    },
  },
  dataLabels: {
    enabled: false,
  },
  legend: {
    show: false,
  },
  grid: {
    show: true,
    strokeDashArray: 3,
    padding: {
      top: 0,
      bottom: 0,
      right: 0,
    },
    borderColor: "rgba(0,0,0,0.05)",
    xaxis: {
      lines: {
        show: true,
      },
    },
    yaxis: {
      lines: {
        show: false,
      },
    },
  },
  yaxis: {
    min: -5,
    max: 5,
  },
  xaxis: {
    axisBorder: {
      show: false,
    },
    axisTicks: {
      show: false,
    },
    categories: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "July",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ],
  },
  yaxis: {
    tickAApprox: 4,
    labels: {
      show: true,
      formatter: function (val) {
        return "$" + val + "k";
      }
    }
  },
};

var chart = new ApexCharts(
  document.querySelector("#reports"),
  chart
);
chart.render();



var options = {
  series: [76],
  chart: {
  type: 'radialBar',
  offsetY: -20,
  sparkline: {
    enabled: true
  }
},
plotOptions: {
  radialBar: {
    startAngle: -90,
    endAngle: 90,  
    hollow: {
      size: '75%',
      position: 'front',
  },  
    track: {
      background: ["rgba(42, 118, 244, .18)"],
      strokeWidth: '80%',
      opacity: 0.5,
      margin: 5,
      lineCap: 'round'
    },
    dataLabels: {
      name: {
        show: false
      },
      value: {
        offsetY: -2,
        fontSize: '20px'
      }
    }
  }
},
stroke: {
  lineCap: 'round'
},
colors: ["var(--bs-primary)"],
grid: {
  padding: {
    top: -10
  }
},

labels: ['Average Results'],
};

var chart = new ApexCharts(document.querySelector("#cashflow"), options);
chart.render();


  
   //customers-widget
  
   
   var options = {
    chart: {
        height: 280,
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
    document.querySelector("#balance"),
    options
  );
  
  chart.render();