<!DOCTYPE html>
<html>
<head>
    <title>Debug Monthly Reports Chart API</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .chart-container { max-width: 400px; margin: 20px 0; }
        pre { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .test-section { margin: 30px 0; border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Monthly Reports Chart API Debug & Test</h1>
    
    <div class="test-section">
        <h2>1. API Response Test</h2>
        <div id="api-response"></div>
        <div id="api-status"></div>
    </div>
    
    <div class="test-section">
        <h2>2. Status Chart Test</h2>
        <div class="chart-container">
            <canvas id="testStatusChart"></canvas>
        </div>
        <div id="status-chart-status"></div>
    </div>
    
    <div class="test-section">
        <h2>3. User Chart Test</h2>
        <div class="chart-container">
            <canvas id="testUserChart"></canvas>
        </div>
        <div id="user-chart-status"></div>
    </div>

    <script>
        let testStatusChart = null;
        let testUserChart = null;
        
        console.log('Starting monthly reports chart debug test...');
        
        // Fetch and display API response
        fetch('/admin/monthly-reports/chart-data')
            .then(response => {
                console.log('API Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('API Data received:', data);
                
                document.getElementById('api-response').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                document.getElementById('api-status').innerHTML = '<div class="success">✅ API Response: SUCCESS</div>';
                
                // Test data validation
                if (data.projectData && data.projectData.length > 0) {
                    document.getElementById('api-status').innerHTML += '<div class="success">✅ Project Data: ' + data.projectData.length + ' projects found</div>';
                } else {
                    document.getElementById('api-status').innerHTML += '<div class="error">❌ Project Data: No project data found</div>';
                }

                if (data.locationData && data.locationData.length > 0) {
                    document.getElementById('api-status').innerHTML += '<div class="success">✅ Location Data: ' + data.locationData.length + ' locations found</div>';
                } else {
                    document.getElementById('api-status').innerHTML += '<div class="error">❌ Location Data: No location data found</div>';
                }
                
                // Initialize test charts with the data
                initTestCharts(data);
            })
            .catch(error => {
                console.error('API Error:', error);
                document.getElementById('api-response').innerHTML = '<pre class="error">Error: ' + error.message + '</pre>';
                document.getElementById('api-status').innerHTML = '<div class="error">❌ API Response: FAILED</div>';
            });
            
        function initTestCharts(data) {
            console.log('Initializing test charts with data:', data);
            
            // Status Chart
            try {
                const statusCtx = document.getElementById('testStatusChart');
                console.log('Status canvas element:', statusCtx);
                
                if (statusCtx && data.projectData && data.projectData.length > 0) {
                    const labels = data.projectData.map(item => item.name);
                    const chartData = data.projectData.map(item => item.count);
                    
                    console.log('Status chart - Labels:', labels, 'Data:', chartData);
                    
                    testStatusChart = new Chart(statusCtx, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: chartData,
                                backgroundColor: ['#FCD34D', '#60A5FA', '#34D399', '#F87171'],
                                borderWidth: 2,
                                borderColor: '#ffffff'
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                title: {
                                        display: true,
                                        text: 'Distribusi Laporan per Proyek'
                                    }
                            }
                        }
                    });
                    
                    document.getElementById('status-chart-status').innerHTML = '<div class="success">✅ Status Chart: Created successfully with ' + labels.length + ' status types</div>';
                    console.log('Status chart created successfully');
                } else {
                    document.getElementById('status-chart-status').innerHTML = '<div class="error">❌ Status Chart: No data available or canvas not found</div>';
                    console.log('Status chart failed - no data or canvas not found');
                }
            } catch (error) {
                console.error('Status chart error:', error);
                document.getElementById('status-chart-status').innerHTML = '<div class="error">❌ Status Chart Error: ' + error.message + '</div>';
            }
            
            // User Chart
            try {
                const userCtx = document.getElementById('testUserChart');
                console.log('User canvas element:', userCtx);
                
                if (userCtx && data.locationData && data.locationData.length > 0) {
                    const labels = data.locationData.map(item => item.name);
                    const chartData = data.locationData.map(item => item.count);
                    
                    console.log('User chart - Labels:', labels, 'Data:', chartData);
                    
                    testUserChart = new Chart(userCtx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Laporan',
                                data: chartData,
                                backgroundColor: '#8B5CF6',
                                borderColor: '#7C3AED',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Laporan Berdasarkan Lokasi'
                                }
                            }
                        }
                    });
                    
                    document.getElementById('user-chart-status').innerHTML = '<div class="success">✅ User Chart: Created successfully with ' + labels.length + ' users</div>';
                    console.log('User chart created successfully');
                } else {
                    document.getElementById('user-chart-status').innerHTML = '<div class="error">❌ User Chart: No data available or canvas not found</div>';
                    console.log('User chart failed - no data or canvas not found');
                }
            } catch (error) {
                console.error('User chart error:', error);
                document.getElementById('user-chart-status').innerHTML = '<div class="error">❌ User Chart Error: ' + error.message + '</div>';
            }
        }
    </script>
</body>
</html>
