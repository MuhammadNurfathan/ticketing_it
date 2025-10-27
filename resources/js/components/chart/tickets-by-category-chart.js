import Chart from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';

export class PieChart {
async init(apiUrl) {
    const data = await this.fetchData(apiUrl);
    if (data && data.length > 0) {
        this.render(data);
        return true;
    } else {
        console.warn('No data available for chart');
        return false;
    }
}
    
    constructor(canvasId, options = {}) {
        this.canvasId = canvasId;
        this.chart = null;
        this.options = options;
        
        // Config untuk mapping data (biar flexible)
        this.config = {
            labelKey: options.labelKey || 'category',
            valueKey: options.valueKey || 'total',
        };
    }

    async fetchData(apiUrl) {
        try {
            const response = await fetch(apiUrl);
            const result = await response.json();
            
            if (result.success) {
                return result.data;
            } else {
                throw new Error('Failed to fetch data');
            }
        } catch (error) {
            console.error('Error fetching data:', error);
            return [];
        }
    }

    generateColors(count) {
        const colors = [
            '#FF6384',
            '#36A2EB',
            '#FFCE56',
            '#4BC0C0',
            '#9966FF',
            '#FF9F40',
            '#E74C3C',
            '#2ECC71',
            '#3498DB',
            '#8B5CF6'
        ];
        return colors.slice(0, count);
    }

    render(data) {
        const ctx = document.getElementById(this.canvasId);
        
        if (!ctx) {
            console.error(`Canvas with id "${this.canvasId}" not found`);
            return;
        }

        if (this.chart) {
            this.chart.destroy();
        }

        const labels = data.map(item => item[this.config.labelKey]);
        const values = data.map(item => item[this.config.valueKey]);
        const colors = this.generateColors(data.length);

        this.chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: this.options.datasetLabel || 'Total',
                    data: values,
                    backgroundColor: colors,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: this.options.title || 'Pie Chart',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    },
                    // ← TAMBAHIN INI: Label di dalam pie chart
                    datalabels: {
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            
                            // Tampilkan persentase & value
                            return percentage > 5 ? `${percentage}%` : '';  // Hide label kalau terlalu kecil
                        },
                        textAlign: 'center'
                    }
                },
                ...this.options
            },
            plugins: [ChartDataLabels]  // ← Register plugin
        });
    }

    async init(apiUrl) {
        const data = await this.fetchData(apiUrl);
        if (data.length > 0) {
            this.render(data);
        } else {
            console.warn('No data available for chart');
        }
    }

    destroy() {
        if (this.chart) {
            this.chart.destroy();
        }
    }
}

export async function renderPieChart(canvasId, apiUrl, options = {}) {
    const chart = new PieChart(canvasId, options);
    await chart.init(apiUrl);
    return chart;
}