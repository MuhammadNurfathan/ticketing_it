// app.js
import './bootstrap';
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";
import PerfectScrollbar from "perfect-scrollbar";
import Chart from "chart.js/auto";

// Import Chart components
import { PieChart, renderPieChart } from './components/chart/tickets-by-category-chart.js';
import { LineChart, renderLineChart } from './components/chart/tickets-closed-chart.js';
import { initBarChart } from './components/chart/bar-chart.js'; // pastikan exportnya named export

// Make chart functions available globally
window.initBarChart = initBarChart;
window.PieChart = PieChart;
window.renderPieChart = renderPieChart;
window.LineChart = LineChart;
window.renderLineChart = renderLineChart;

// Alpine.js setup
document.addEventListener("alpine:init", () => {
    Alpine.data("mainState", () => {
        let lastScrollTop = 0;

        const init = function () {
            window.addEventListener("scroll", () => {
                let st = window.pageYOffset || document.documentElement.scrollTop;
                this.scrollingDown = st > lastScrollTop;
                this.scrollingUp = st < lastScrollTop;
                if (st === 0) {
                    this.scrollingDown = false;
                    this.scrollingUp = false;
                }
                lastScrollTop = Math.max(st, 0);
            });
        };

        const getTheme = () => {
            if (window.localStorage.getItem("dark")) {
                return JSON.parse(window.localStorage.getItem("dark"));
            }
            return !!window.matchMedia &&
                window.matchMedia("(prefers-color-scheme: dark)").matches;
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

Alpine.plugin(collapse);
Alpine.start();
