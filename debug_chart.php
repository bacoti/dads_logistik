<!DOCTYPE html>
<html>
<head>
    <title>Debug Chart API</title>
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
    <h1>Chart API Debug & Test</h1>
    
    <div class="test-section">
        <h2>1. API Response Test</h2>
        <div id="api-response"></div>
        <div id="api-status"></div>
    </div>
    
    <div class="test-section">
        <h2>2. Project Chart Test</h2>
        <div class="chart-container">
            <canvas id="testProjectChart"></canvas>
        </div>
        <div id="project-chart-status"></div>
    </div>
    
    <div class="test-section">
        <h2>3. Location Chart Test</h2>
        <div class="chart-container">
            <canvas id="testLocationChart"></canvas>
        </div>
        <div id="location-chart-status"></div>
    </div>

    <script>
        let testProjectChart = null;
        let testLocationChart = null;
        
        console.log('Starting chart debug test...');
        
        // Fetch and display API response
        fetch('/admin/transactions/chart-data')
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
                    document.getElementById('api-status').innerHTML += '<div class="error">❌ Project Data: No projects found</div>';
                }
                
                if (data.locationData && Object.keys(data.locationData).length > 0) {
                    document.getElementById('api-status').innerHTML += '<div class="success">✅ Location Data: ' + Object.keys(data.locationData).length + ' locations found</div>';
                } else {
                    document.getElementById('api-status').innerHTML += '<div class="error">❌ Location Data: No locations found</div>';
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
            
            // Project Chart
            try {
                const projectCtx = document.getElementById('testProjectChart');
                console.log('Project canvas element:', projectCtx);
                
                if (projectCtx && data.projectData && data.projectData.length > 0) {
                    const labels = data.projectData.map(item => item.name);
                    const chartData = data.projectData.map(item => item.count);
                    
                    console.log('Project chart - Labels:', labels, 'Data:', chartData);
                    
                    testProjectChart = new Chart(projectCtx, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: chartData,
                                backgroundColor: ['#8B5CF6', '#06B6D4', '#10B981', '#F59E0B', '#EF4444', '#6366F1'],
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
                                    text: 'Projects Distribution'
                                }
                            }
                        }
                    });
                    
                    document.getElementById('project-chart-status').innerHTML = '<div class="success">✅ Project Chart: Created successfully with ' + labels.length + ' projects</div>';
                    console.log('Project chart created successfully');
                } else {
                    document.getElementById('project-chart-status').innerHTML = '<div class="error">❌ Project Chart: No data available or canvas not found</div>';
                    console.log('Project chart failed - no data or canvas not found');
                }
            } catch (error) {
                console.error('Project chart error:', error);
                document.getElementById('project-chart-status').innerHTML = '<div class="error">❌ Project Chart Error: ' + error.message + '</div>';
            }
            
            // Location Chart
            try {
                const locationCtx = document.getElementById('testLocationChart');
                console.log('Location canvas element:', locationCtx);
                
                if (locationCtx && data.locationData && Object.keys(data.locationData).length > 0) {
                    const locations = Object.keys(data.locationData);
                    const totals = locations.map(location => {
                        const locationData = data.locationData[location];
                        return locationData.penerimaan + locationData.pengambilan + 
                               locationData.pengembalian + locationData.peminjaman;
                    });
                    
                    console.log('Location chart - Labels:', locations, 'Data:', totals);
                    
                    testLocationChart = new Chart(locationCtx, {
                        type: 'bar',
                        data: {
                            labels: locations,
                            datasets: [{
                                label: 'Total Transaksi',
                                data: totals,
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
                                    text: 'Transactions by Location'
                                }
                            }
                        }
                    });
                    
                    document.getElementById('location-chart-status').innerHTML = '<div class="success">✅ Location Chart: Created successfully with ' + locations.length + ' locations</div>';
                    console.log('Location chart created successfully');
                } else {
                    document.getElementById('location-chart-status').innerHTML = '<div class="error">❌ Location Chart: No data available or canvas not found</div>';
                    console.log('Location chart failed - no data or canvas not found');
                }
            } catch (error) {
                console.error('Location chart error:', error);
                document.getElementById('location-chart-status').innerHTML = '<div class="error">❌ Location Chart Error: ' + error.message + '</div>';
            }
        }
    </script>
</body>
</html>
