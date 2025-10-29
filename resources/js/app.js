// app.js
import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import PerfectScrollbar from 'perfect-scrollbar';
import 'perfect-scrollbar/css/perfect-scrollbar.css';
import Chart from 'chart.js/auto';

// Import Chart components
import { PieChart, renderPieChart } from './components/chart/tickets-by-category-chart.js';
import { LineChart, renderLineChart } from './components/chart/tickets-closed-chart.js';
import { initBarChart } from './components/chart/bar-chart.js';

// Make libraries and functions available globally
window.PerfectScrollbar = PerfectScrollbar;
window.Chart = Chart;
window.initBarChart = initBarChart;
window.PieChart = PieChart;
window.renderPieChart = renderPieChart;
window.LineChart = LineChart;
window.renderLineChart = renderLineChart;

// Alpine.js setup
document.addEventListener('alpine:init', () => {
    Alpine.data('mainState', () => {
        let lastScrollTop = 0;

        const init = function () {
            // Scroll detection
            window.addEventListener('scroll', () => {
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
            const darkMode = window.localStorage.getItem('dark');
            if (darkMode !== null) {
                return JSON.parse(darkMode);
            }
            // Fallback to system preference
            return window.matchMedia && 
                   window.matchMedia('(prefers-color-scheme: dark)').matches;
        };

        const setTheme = (value) => {
            window.localStorage.setItem('dark', value);
        };

        return {
            init,
            
            // Theme
            isDarkMode: getTheme(),
            toggleTheme() {
                this.isDarkMode = !this.isDarkMode;
                setTheme(this.isDarkMode);
            },
            
            // Sidebar
            isSidebarOpen: window.innerWidth > 1024,
            isSidebarHovered: false,
            handleSidebarHover(value) {
                if (window.innerWidth < 1024) return;
                this.isSidebarHovered = value;
            },
            handleWindowResize() {
                this.isSidebarOpen = window.innerWidth > 1024;
            },
            
            // Scroll state
            scrollingDown: false,
            scrollingUp: false,
        };
    });
});

// Initialize Alpine with plugins
Alpine.plugin(collapse);
Alpine.start();