/**
 * Theme: Approx - Bootstrap 5 Responsive Admin Dashboard
 * Author: Mannatthemes
 * Ecommerce Dashboard Js
 */


// line-1 

var options1 = {
  series: [{
  data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54]
}],
  chart: {
  type: 'line',
  width: 120,
  height: 35,
  sparkline: {
    enabled: true
  },
  dropShadow: {
    enabled: true,
    top: 4,
    left: 0,
    bottom: 0,
    right: 0,
    blur: 2,
    color: "rgba(132, 145, 183, 0.3)",
    opacity: 0.35,
  },
},
colors: [ "#95a0c5"],
stroke: {
  show: true,
  curve: 'smooth',
  width: [3],
  lineCap: 'round',
},
tooltip: {
  fixed: {
    enabled: false
  },
  x: {
    show: false
  },
  y: {
    title: {
      formatter: function (seriesName) {
        return ''
      }
    }
  },
  marker: {
    show: false
  }
}
};

var chart1 = new ApexCharts(document.querySelector("#line-1"), options1);
chart1.render();


// line-2 

var options2 = {
  series: [{
  data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54]
}],
  chart: {
  type: 'line',
  width: 120,
  height: 35,
  sparkline: {
    enabled: true
  },
  dropShadow: {
    enabled: true,
    top: 4,
    left: 0,
    bottom: 0,
    right: 0,
    blur: 2,
    color: "rgba(132, 145, 183, 0.3)",
    opacity: 0.35,
  },
},
colors: [ "#95a0c5"],
stroke: {
  show: true,
  curve: 'smooth',
  width: [3],
  lineCap: 'round',
},
tooltip: {
  fixed: {
    enabled: false
  },
  x: {
    show: false
  },
  y: {
    title: {
      formatter: function (seriesName) {
        return ''
      }
    }
  },
  marker: {
    show: false
  }
}
};

var chart2 = new ApexCharts(document.querySelector("#line-2"), options2);
chart2.render();

// Monthly income widget

var colors = ['#95a0c5', '#95a0c5', '#95a0c5', '#22c55e', '#95a0c5', '#95a0c5', '#95a0c5', '#95a0c5', '#95a0c5', '#95a0c5', '#95a0c5', '#95a0c5'];
var options = {
  chart: {
      height: 270,
      type: 'bar',
     
    toolbar:{
      show:false
    },
    dropShadow: {
      enabled: true,
      top: 0,
      left: 5,
      bottom: 5,
      right: 0,
      blur: 5,
      color: '#45404a2e',
      opacity: 0.35
  },
  },
  colors: colors,
  plotOptions: {
      bar: {
        borderRadius: 6,
          dataLabels: {
              position: 'top', // top, center, bottom              
          },
          columnWidth: '20',
          distributed: true,
      },
  },
  dataLabels: {
      enabled: true,
      formatter: function (val) {
          return val + "%";
      },
      offsetY: -20,
      style: {
          fontSize: '12px',
          colors: ["#8997bd"]
      }
  },
  series: [{
    name: 'Inflation',
    data: [2.3, 3.1, 4.0, 10.1, 4.0, 3.6, 3.2, 2.3, 1.4, 0.8, 0.5, 0.2]
  }],
  xaxis: {
    categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    position: 'top',
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false
    },
    crosshairs: {
      fill: {
        type: 'gradient',
        gradient: {
          colorFrom: '#D8E3F0',
          colorTo: '#BED1E6',
          stops: [0, 100],
          opacityFrom: 0.4,
          opacityTo: 0.5,
        }
      }
    },
    tooltip: {
      enabled: true,
    }
  },
  yaxis: {
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false,
    },
    labels: {
      show: true,
      formatter: function (val) {
        return "$" + val + "k";
      }
    }
  
  },
    grid: {
      row: {
          colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
          opacity: 0.2,           
      },
      strokeDashArray: 2.5,
    },
    legend: {
      show: false,
    },
}

var chart = new ApexCharts(
  document.querySelector("#monthly_income"),
  options
);

chart.render();

// categories widget

var options = {
  series: [
  {
    name: "Items",
    data: [1380, 1100, 990, 880, 740, 548, 330, 200],
  },
],
  chart: {
  type: 'bar',
  height: 275,
  toolbar:{
    show:false
  },
},
plotOptions: {
  bar: {
    borderRadius: 6,
    horizontal: true,
    distributed: true,
    barHeight: '85%',
    isFunnel: true,
    isFunnel3d: false,   
  },
},

dataLabels: {
  enabled: true,
  formatter: function (val, opt) {
    return opt.w.globals.labels[opt.dataPointIndex] 
  },
  dropShadow: {
    enabled: false,
  },
  style: {
    colors: ["#22c55e"],
    fontWeight: 400,
    fontSize: '13px',
  },
},
xaxis: {
  categories: [
    'Mobile',
    'Men Fishion',
    'Women Fishion',
    'Beauty',
    'Health',
    'Sports',
    'Kids',
    'Music',
  ],
},
colors: [
  'rgba(34, 197, 94, 0.45)',
  'rgba(34, 197, 94, 0.4)',
  'rgba(34, 197, 94, 0.35)',
  'rgba(34, 197, 94, 0.3)',
  'rgba(34, 197, 94, 0.25)',
  'rgba(34, 197, 94, 0.2)',
  'rgba(34, 197, 94, 0.15)',
  'rgba(34, 197, 94, 0.1)',
],
legend: {
  show: false,
},
};

var chart = new ApexCharts(document.querySelector("#categories"), options);
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
 
  series: [50, 25, 25,],
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
  labels: [ "Currenet","New", "Retargeted" ],
  colors: ["#22c55e", "#08b0e7", "#ffc728"],
 
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