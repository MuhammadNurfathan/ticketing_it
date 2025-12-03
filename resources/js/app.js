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

// ===== SATU-SATUNYA ALPINE INITIALIZATION =====
document.addEventListener("alpine:init", () => {
  Alpine.data("mainState", () => {
    let lastScrollTop = 0;

    // Helper functions untuk theme
    const getTheme = () => {
      const darkMode = window.localStorage.getItem("dark");
      if (darkMode !== null) return JSON.parse(darkMode);
      return (
        window.matchMedia?.("(prefers-color-scheme: dark)").matches ?? false
      );
    };

    const setTheme = (value) => {
      window.localStorage.setItem("dark", value);
      window.localStorage.setItem("darkMode", value);
    };

    return {
      // ===== INITIALIZATION =====
      init() {
        // Watch for dark mode changes
        this.$watch("isDarkMode", (val) => {
          setTheme(val);
        });

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

        // Initial responsive check
        this.handleWindowResize();
      },

      // ===== STATE =====
      isDarkMode: getTheme(),
      isSidebarOpen: window.innerWidth >= 1024,
      isSidebarHovered: false,
      isMobile: window.innerWidth < 1024,
      scrollingDown: false,
      scrollingUp: false,
      showSearch: false, // 👈 Ini yang hilang!

      // ===== METHODS =====
      toggleTheme() {
        this.isDarkMode = !this.isDarkMode;
      },

      toggleDarkMode() {
        this.isDarkMode = !this.isDarkMode;
      },

      toggleSidebar() {
        this.isSidebarOpen = !this.isSidebarOpen;
      },

      toggleSearch() {
        this.showSearch = !this.showSearch;
      },

      handleSidebarHover(value) {
        if (window.innerWidth < 1024) return;
        this.isSidebarHovered = value;
      },

      handleWindowResize() {
        const width = window.innerWidth;
        this.isMobile = width < 1024;

        // Auto manage sidebar based on screen size
        if (this.isMobile) {
          this.isSidebarOpen = false;
          this.showSearch = false; // Close search on mobile
        } else {
          this.isSidebarOpen = true;
        }
      },
    };
  });
});

// ===== START ALPINE HANYA SEKALI! =====
Alpine.plugin(collapse);
Alpine.start();