import "./bootstrap";

/* ======================
   LIBRARIES
====================== */
import DataTable from "datatables.net";
import DataTableResponsive from "datatables.net-responsive";
import "datatables.net-dt/css/dataTables.dataTables.css";
import "datatables.net-responsive-dt/css/responsive.dataTables.css";

import Swal from "sweetalert2";
import Chart from "chart.js/auto";

import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

import PerfectScrollbar from "perfect-scrollbar";
import "perfect-scrollbar/css/perfect-scrollbar.css";

/* ======================
   CUSTOM MODULES
====================== */
import { initDataTable } from "./datatable";

import {
  PieChart,
  renderPieChart,
} from "./components/chart/tickets-by-category-chart.js";

import {
  LineChart,
  renderLineChart,
} from "./components/chart/tickets-closed-chart.js";

import { initBarChart } from "./components/chart/bar-chart.js";

/* ======================
   SETUP GLOBAL
====================== */
DataTable.use(DataTableResponsive);

window.DataTable = DataTable;
window.Swal = Swal;
window.Chart = Chart;
window.PerfectScrollbar = PerfectScrollbar;

window.PieChart = PieChart;
window.renderPieChart = renderPieChart;
window.LineChart = LineChart;
window.renderLineChart = renderLineChart;
window.initBarChart = initBarChart;

/* ======================
   INIT GLOBAL FUNCTION
====================== */
document.addEventListener("DOMContentLoaded", () => {
  initDataTable();
});

/* ======================
   ALPINE CONFIG
====================== */
document.addEventListener("alpine:init", () => {
  Alpine.data("mainState", () => {
    let lastScrollTop = 0;

    const getTheme = () => {
      const darkMode = localStorage.getItem("dark");
      if (darkMode !== null) return JSON.parse(darkMode);

      return window.matchMedia?.("(prefers-color-scheme: dark)").matches ?? false;
    };

    const setTheme = (value) => {
      localStorage.setItem("dark", value);
    };

    return {
      isDarkMode: getTheme(),
      isSidebarOpen: window.innerWidth >= 1024,
      isSidebarHovered: false,
      scrollingDown: false,
      scrollingUp: false,

      toggleTheme() {
        this.isDarkMode = !this.isDarkMode;
      },

      handleSidebarHover(value) {
        if (window.innerWidth < 1024) return;
        this.isSidebarHovered = value;
      },

      handleWindowResize() {
        this.isSidebarOpen = window.innerWidth >= 1024;
      },

      init() {
        this.$watch("isDarkMode", (val) => setTheme(val));

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

        const stored = localStorage.getItem("dark");
        if (stored !== null) {
          this.isDarkMode = JSON.parse(stored);
        }

        this.handleWindowResize();
      },
    };
  });
});

/* ======================
   START ALPINE
====================== */
Alpine.plugin(collapse);
Alpine.start();