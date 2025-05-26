/**
 * Admin One Chart Manager - Enhanced Version for Laravel Integration
 * Provides chart management functionality with Admin One styling
 */
class AdminOneChartManager {
    constructor() {
        this.chartInstances = new Map();
        this.chartColors = {
            primary: '#3b82f6',
            success: '#10b981',
            danger: '#ef4444',
            warning: '#f59e0b',
            info: '#06b6d4',
            light: '#f8fafc',
            dark: '#1e293b'
        };
        
        this.defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(30, 41, 59, 0.95)',
                    titleColor: '#e2e8f0',
                    bodyColor: '#e2e8f0',
                    borderColor: 'rgba(75, 85, 99, 0.4)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    titleFont: {
                        size: 13,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 12
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    grid: {
                        color: 'rgba(75, 85, 99, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: {
                            size: 11
                        },
                        maxTicksLimit: 8
                    }
                },
                y: {
                    display: true,
                    grid: {
                        color: 'rgba(75, 85, 99, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: {
                            size: 11
                        }
                    }
                }
            }
        };
    }

    /**
     * Initialize all charts for a signal
     */
    initializeCharts(signalId, chartData) {
        console.log('Initializing charts for signal:', signalId);
        console.log('Chart data received:', chartData);

        if (!chartData || chartData.length === 0) {
            console.warn('No chart data available');
            return;
        }

        try {
            // Process data
            const processedData = this.processChartData(chartData);
            
            // Initialize main performance chart
            this.initializePerformanceChart(signalId, processedData);
            
            // Initialize technical indicator charts
            this.initializeRSIChart(signalId, processedData);
            this.initializeMACDChart(signalId, processedData);
            this.initializeVolumeChart(signalId, processedData);
            
            console.log('All charts initialized successfully');
        } catch (error) {
            console.error('Error initializing charts:', error);
        }
    }

    /**
     * Process raw chart data into usable format
     */
    processChartData(rawData) {
        const labels = rawData.map((item, index) => {
            // Create time labels
            const date = new Date();
            date.setMinutes(date.getMinutes() - (rawData.length - index) * 5);
            return date.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: false
            });
        });

        const priceData = rawData.map(item => parseFloat(item.close) || 0);
        const volumeData = rawData.map(item => parseFloat(item.volume) || 0);
        const rsiData = rawData.map(item => parseFloat(item.rsi_value) || 50);
        
        // Calculate MACD data
        const macdData = this.calculateMACD(priceData);

        return {
            labels,
            prices: priceData,
            volumes: volumeData,
            rsi: rsiData,
            macd: macdData
        };
    }

    /**
     * Calculate MACD indicator
     */
    calculateMACD(prices) {
        if (prices.length < 26) {
            // Not enough data for MACD calculation, generate sample data
            return {
                macd: prices.map(() => Math.random() * 2 - 1),
                signal: prices.map(() => Math.random() * 1.5 - 0.75),
                histogram: prices.map(() => Math.random() * 0.8 - 0.4)
            };
        }

        // Simplified MACD calculation
        const ema12 = this.calculateEMA(prices, 12);
        const ema26 = this.calculateEMA(prices, 26);
        const macdLine = ema12.map((val, idx) => val - ema26[idx]);
        const signalLine = this.calculateEMA(macdLine, 9);
        const histogram = macdLine.map((val, idx) => val - signalLine[idx]);

        return {
            macd: macdLine,
            signal: signalLine,
            histogram: histogram
        };
    }

    /**
     * Calculate Exponential Moving Average
     */
    calculateEMA(data, period) {
        const alpha = 2 / (period + 1);
        const ema = [data[0]];
        
        for (let i = 1; i < data.length; i++) {
            ema[i] = alpha * data[i] + (1 - alpha) * ema[i - 1];
        }
        
        return ema;
    }

    /**
     * Initialize main performance chart
     */
    initializePerformanceChart(signalId, data) {
        const canvasId = `mainChart_${signalId}`;
        const canvas = document.getElementById(canvasId);
        
        if (!canvas) {
            console.warn(`Canvas ${canvasId} not found`);
            return;
        }

        this.createPerformanceChart(canvasId, {
            labels: data.labels,
            datasets: [{
                label: 'Price',
                data: data.prices,
                borderColor: this.chartColors.primary,
                backgroundColor: this.hexToRgba(this.chartColors.primary, 0.1),
                borderWidth: 2.5,
                fill: true,
                tension: 0.2,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointBackgroundColor: this.chartColors.primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        });
    }

    /**
     * Initialize RSI chart
     */
    initializeRSIChart(signalId, data) {
        const canvasId = `rsiChart_${signalId}`;
        const canvas = document.getElementById(canvasId);
        
        if (!canvas) {
            console.warn(`Canvas ${canvasId} not found`);
            return;
        }

        this.createRSIChart(canvasId, {
            labels: data.labels,
            values: data.rsi
        });
    }

    /**
     * Initialize MACD chart
     */
    initializeMACDChart(signalId, data) {
        const canvasId = `macdChart_${signalId}`;
        const canvas = document.getElementById(canvasId);
        
        if (!canvas) {
            console.warn(`Canvas ${canvasId} not found`);
            return;
        }

        this.createMACDChart(canvasId, {
            labels: data.labels,
            macd: data.macd.macd,
            signal: data.macd.signal,
            histogram: data.macd.histogram
        });
    }

    /**
     * Initialize Volume chart
     */
    initializeVolumeChart(signalId, data) {
        const canvasId = `volumeChart_${signalId}`;
        const canvas = document.getElementById(canvasId);
        
        if (!canvas) {
            console.warn(`Canvas ${canvasId} not found`);
            return;
        }

        this.createVolumeChart(canvasId, {
            labels: data.labels,
            values: data.volumes
        });
    }

    /**
     * Create performance chart
     */
    createPerformanceChart(canvasId, data, options = {}) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;

        // Destroy existing chart if it exists
        if (this.chartInstances.has(canvasId)) {
            this.chartInstances.get(canvasId).destroy();
        }

        const chartConfig = {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: data.datasets.map(dataset => ({
                    ...dataset,
                    borderColor: dataset.borderColor || this.chartColors.primary,
                    backgroundColor: dataset.backgroundColor || this.hexToRgba(this.chartColors.primary, 0.1)
                }))
            },
            options: {
                ...this.defaultOptions,
                ...options,
                scales: {
                    ...this.defaultOptions.scales,
                    ...(options.scales || {})
                }
            }
        };

        const chart = new Chart(ctx, chartConfig);
        this.chartInstances.set(canvasId, chart);
        return chart;
    }

    /**
     * Create RSI indicator chart
     */
    createRSIChart(canvasId, data, options = {}) {
        const rsiOptions = {
            ...this.defaultOptions,
            scales: {
                ...this.defaultOptions.scales,
                y: {
                    ...this.defaultOptions.scales.y,
                    min: 0,
                    max: 100,
                    ticks: {
                        ...this.defaultOptions.scales.y.ticks,
                        stepSize: 20
                    }
                }
            },
            plugins: {
                ...this.defaultOptions.plugins,
                annotation: {
                    annotations: {
                        overbought: {
                            type: 'line',
                            yMin: 70,
                            yMax: 70,
                            borderColor: this.chartColors.danger,
                            borderWidth: 1,
                            borderDash: [5, 5]
                        },
                        oversold: {
                            type: 'line',
                            yMin: 30,
                            yMax: 30,
                            borderColor: this.chartColors.success,
                            borderWidth: 1,
                            borderDash: [5, 5]
                        }
                    }
                }
            },
            ...options
        };

        return this.createPerformanceChart(canvasId, {
            labels: data.labels,
            datasets: [{
                data: data.values,
                borderColor: this.chartColors.warning,
                backgroundColor: this.hexToRgba(this.chartColors.warning, 0.1),
                fill: true,
                borderWidth: 2,
                tension: 0.2,
                pointRadius: 0,
                pointHoverRadius: 4
            }]
        }, rsiOptions);
    }

    /**
     * Create MACD indicator chart with proper line visualization
     */
    createMACDChart(canvasId, data, options = {}) {
        const macdOptions = {
            ...this.defaultOptions,
            scales: {
                ...this.defaultOptions.scales,
                y: {
                    ...this.defaultOptions.scales.y,
                    grid: {
                        color: 'rgba(75, 85, 99, 0.2)',
                        drawBorder: false
                    }
                }
            },
            plugins: {
                ...this.defaultOptions.plugins,
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#e2e8f0',
                        font: {
                            size: 11
                        },
                        usePointStyle: true,
                        pointStyle: 'line'
                    }
                }
            },
            ...options
        };

        return this.createPerformanceChart(canvasId, {
            labels: data.labels,
            datasets: [
                {
                    label: 'MACD Line',
                    data: data.macd,
                    borderColor: this.chartColors.info,
                    backgroundColor: 'transparent',
                    borderWidth: 2.5,
                    type: 'line',
                    tension: 0.2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    pointBackgroundColor: this.chartColors.info,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Signal Line',
                    data: data.signal,
                    borderColor: this.chartColors.danger,
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    type: 'line',
                    tension: 0.2,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    pointBackgroundColor: this.chartColors.danger,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Histogram',
                    data: data.histogram,
                    backgroundColor: (ctx) => {
                        const value = ctx.raw;
                        return value >= 0 ? 
                            this.hexToRgba(this.chartColors.success, 0.6) : 
                            this.hexToRgba(this.chartColors.danger, 0.6);
                    },
                    borderColor: (ctx) => {
                        const value = ctx.raw;
                        return value >= 0 ? this.chartColors.success : this.chartColors.danger;
                    },
                    borderWidth: 1,
                    type: 'bar',
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }
            ]
        }, macdOptions);
    }

    /**
     * Create Volume chart
     */
    createVolumeChart(canvasId, data, options = {}) {
        const volumeOptions = {
            ...this.defaultOptions,
            scales: {
                ...this.defaultOptions.scales,
                y: {
                    ...this.defaultOptions.scales.y,
                    beginAtZero: true
                }
            },
            ...options
        };

        return this.createPerformanceChart(canvasId, {
            labels: data.labels,
            datasets: [{
                label: 'Volume',
                data: data.values,
                backgroundColor: this.hexToRgba(this.chartColors.primary, 0.6),
                borderColor: this.chartColors.primary,
                borderWidth: 1,
                type: 'bar'
            }]
        }, volumeOptions);
    }

    /**
     * Utility function to convert hex color to rgba
     */
    hexToRgba(hex, alpha) {
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }

    /**
     * Destroy all chart instances
     */
    destroyAllCharts() {
        this.chartInstances.forEach(chart => chart.destroy());
        this.chartInstances.clear();
    }

    /**
     * Refresh charts with new data
     */
    refreshCharts(signalId, newData) {
        this.destroyAllCharts();
        this.initializeCharts(signalId, newData);
    }
}

// Make the class globally available
window.AdminOneChartManager = AdminOneChartManager;

// Initialize global instance for backwards compatibility
window.adminOneCharts = {
    chartManager: new AdminOneChartManager(),
    
    // Utility functions
    generateRandomData: function(count, min = 0, max = 100) {
        return Array.from({ length: count }, () => 
            Math.random() * (max - min) + min
        );
    },
    
    // Auto-initialize charts on DOM ready
    autoInit: function() {
        console.log('Auto-initializing Admin One charts...');
        
        // Look for chart containers and initialize them
        const chartContainers = document.querySelectorAll('[data-chart-type]');
        chartContainers.forEach(container => {
            const chartType = container.dataset.chartType;
            const canvasId = container.querySelector('canvas')?.id;
            
            if (!canvasId) return;
            
            // Generate sample data for demonstration
            const sampleData = {
                labels: Array.from({ length: 20 }, (_, i) => `${9 + Math.floor(i/2)}:${(i%2)*30}`),
                performance: {
                    values: this.generateRandomData(20, 100, 200)
                },
                rsi: {
                    values: this.generateRandomData(20, 0, 100)
                },
                macd: {
                    macd: this.generateRandomData(20, -2, 2),
                    signal: this.generateRandomData(20, -1.5, 1.5),
                    histogram: this.generateRandomData(20, -1, 1)
                },
                volume: {
                    values: this.generateRandomData(20, 1000, 10000)
                }
            };
            
            // Initialize based on chart type
            switch (chartType) {
                case 'performance':
                    this.chartManager.createPerformanceChart(canvasId, {
                        labels: sampleData.labels,
                        datasets: [{
                            data: sampleData.performance.values,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true
                        }]
                    });
                    break;
                    
                case 'rsi':
                    this.chartManager.createRSIChart(canvasId, {
                        labels: sampleData.labels,
                        values: sampleData.rsi.values
                    });
                    break;
                    
                case 'macd':
                    this.chartManager.createMACDChart(canvasId, {
                        labels: sampleData.labels,
                        macd: sampleData.macd.macd,
                        signal: sampleData.macd.signal,
                        histogram: sampleData.macd.histogram
                    });
                    break;
                    
                case 'volume':
                    this.chartManager.createVolumeChart(canvasId, {
                        labels: sampleData.labels,
                        values: sampleData.volume.values
                    });
                    break;
            }
        });
    }
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.adminOneCharts.autoInit();
    });
} else {
    window.adminOneCharts.autoInit();
}
