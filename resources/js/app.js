import "./bootstrap";
import DataTable from "datatables.net";
import DataTableResponsive from "datatables.net-responsive";
import Swal from "sweetalert2";
import "datatables.net-dt/css/dataTables.dataTables.css";
import "datatables.net-responsive-dt/css/responsive.dataTables.css";

DataTable.use(DataTableResponsive);

window.DataTable = DataTable;
window.Swal = Swal;

import Chart from "chart.js/auto";
window.Chart = Chart;

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

import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

import PerfectScrollbar from "perfect-scrollbar";
import "perfect-scrollbar/css/perfect-scrollbar.css";

window.PerfectScrollbar = PerfectScrollbar;

// ===== ALPINE INITIALIZATION =====
document.addEventListener("alpine:init", () => {
  Alpine.data("mainState", () => {
    let lastScrollTop = 0;

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
      // ===== STATE =====
      isDarkMode: getTheme(),
      isSidebarOpen: window.innerWidth >= 1024,
      isSidebarHovered: false,
      scrollingDown: false,
      scrollingUp: false,

      // ===== METHODS =====
      toggleTheme() {
        this.isDarkMode = !this.isDarkMode;
      },

      handleSidebarHover(value) {
        if (window.innerWidth < 1024) return;
        this.isSidebarHovered = value;
      },

      handleWindowResize() {
        const width = window.innerWidth;
        this.isSidebarOpen = width >= 1024;
      },

      // ===== INIT =====
      init() {
        // Watch untuk dark mode
        this.$watch("isDarkMode", (val) => setTheme(val));

        // Handle scroll events
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

        // ===== localStorage sebagai prioritas =====
        const stored = window.localStorage.getItem("dark");
        if (stored !== null) {
          this.isDarkMode = JSON.parse(stored);
        } else {
          // jika belum ada, baru pakai system preference
          this.isDarkMode = window.matchMedia(
            "(prefers-color-scheme: dark)"
          ).matches;
        }

        // Initial window resize
        this.handleWindowResize();
      },
    };
  });
});

// ===== START ALPINE =====
Alpine.plugin(collapse);
Alpine.start();
