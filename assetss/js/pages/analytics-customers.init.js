/**
 * Theme: Approx - Bootstrap 5 Responsive Admin Dashboard
 * Author: Mannatthemes
 * Analytics Customers Js
 */

var chart_customer = {
  series: [
    {
      name: 'New Customers ',
      data: [0, 20, 15, 19, 14, 25, 30],
    },
    {
      name: 'Returning Customers',
      data: [0, 8, 7, 13, 26, 16, 25],
    },
  ],
  chart: {
    fontFamily: "inherit",
    height: 270,
    type: "line",
    toolbar: {
      show: false,
    },
    sparkline: {
      enabled: true,
    },
  },
  colors: ["var(--bs-primary)", "var(--bs-primary-bg-subtle)"],
  grid: {
    show: true,
    strokeDashArray: 3,
  },
  stroke: {
    curve: "smooth",
    colors: ["var(--bs-primary)", "var(--bs-primary-bg-subtle)"],
    width: 2,
  },
  markers: {
    colors: ["var(--bs-primary)", "var(--bs-primary-bg-subtle)"],
    strokeColors: "transparent",
  },
  tooltip: {
    x: {
      show: false,
    },
    followCursor: true,
  },
};
var chart_line = new ApexCharts(
  document.querySelector("#customers-line"),
  chart_customer
);
chart_line.render();

