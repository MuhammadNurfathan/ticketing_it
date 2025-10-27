import Chart from 'chart.js/auto';

export class LineChart {
    constructor(canvasId, options = {}) {
        this.canvasId = canvasId;
        this.chart = null;
        this.options = options;
        
        this.config = {
            labelKey: options.labelKey || 'month',
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

        this.chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: this.options.datasetLabel || 'Total Tickets',
                    data: values,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4, // Smooth curve
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: this.options.title || 'Line Chart',
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
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y} ticket`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                ...this.options
            }
        });
    }

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

    destroy() {
        if (this.chart) {
            this.chart.destroy();
        }
    }
}

export async function renderLineChart(canvasId, apiUrl, options = {}) {
    const chart = new LineChart(canvasId, options);
    await chart.init(apiUrl);
    return chart;
}