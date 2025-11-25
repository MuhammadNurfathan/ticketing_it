// ====================================================================
//  BOOTSTRAP
// ====================================================================
import "./bootstrap";

// ====================================================================
//  DATATABLES (MODERN, TANPA JQUERY)
// ====================================================================
import DataTable from "datatables.net";
import DataTableResponsive from "datatables.net-responsive";
import Swal from "sweetalert2";


// CSS DataTables modern
import "datatables.net-dt/css/dataTables.dataTables.css";
import "datatables.net-responsive-dt/css/responsive.dataTables.css";

// Register plugin
DataTable.use(DataTableResponsive);

// Global untuk Blade
window.DataTable = DataTable;
window.Swal = Swal;

// ====================================================================
//  CHART.JS
// ====================================================================
import Chart from "chart.js/auto"; // ES Module lokal, auto-register
window.Chart = Chart;

// ====================================================================
//  COMPONENT CHART
// ====================================================================
import {
  PieChart,
  renderPieChart,
} from "./components/chart/tickets-by-category-chart.js";
import {
  LineChart,
  renderLineChart,
} from "./components/chart/tickets-closed-chart.js";
import { initBarChart } from "./components/chart/bar-chart.js";

window.PieChart = PieChart;
window.renderPieChart = renderPieChart;
window.LineChart = LineChart;
window.renderLineChart = renderLineChart;
window.initBarChart = initBarChart;

// ====================================================================
//  LIBRARY LAIN
// ====================================================================
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

import PerfectScrollbar from "perfect-scrollbar";
import "perfect-scrollbar/css/perfect-scrollbar.css";

window.PerfectScrollbar = PerfectScrollbar;

// ====================================================================
//  ALPINE SETUP
// ====================================================================
document.addEventListener("alpine:init", () => {
  Alpine.data("mainState", () => {
    let lastScrollTop = 0;

    const init = function () {
      window.addEventListener("scroll", () => {
        const st = window.pageYOffset || document.documentElement.scrollTop;

        this.scrollingDown = st > lastScrollTop && st > 0;
        this.scrollingUp = st < lastScrollTop;

        if (st === 0) {
          this.scrollingDown = false;
          this.scrollingUp = false;
        }

        lastScrollTop = Math.max(st, 0);
      });
    };

    const getTheme = () => {
      const darkMode = window.localStorage.getItem("dark");
      if (darkMode !== null) return JSON.parse(darkMode);
      return (
        window.matchMedia?.("(prefers-color-scheme: dark)").matches ?? false
      );
    };

    const setTheme = (value) => {
      window.localStorage.setItem("dark", value);
    };

    return {
      init,
      isDarkMode: getTheme(),
      toggleTheme() {
        this.isDarkMode = !this.isDarkMode;
        setTheme(this.isDarkMode);
      },
      isSidebarOpen: window.innerWidth > 1024,
      isSidebarHovered: false,
      handleSidebarHover(value) {
        if (window.innerWidth < 1024) return;
        this.isSidebarHovered = value;
      },
      handleWindowResize() {
        this.isSidebarOpen = window.innerWidth > 1024;
      },
      scrollingDown: false,
      scrollingUp: false,
    };
  });
});

// Start Alpine
Alpine.plugin(collapse);
Alpine.start();
