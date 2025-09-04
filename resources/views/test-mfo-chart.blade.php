<!DOCTYPE html>
<html>
<head>
    <title>Test MFO Chart API</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Test MFO Chart API</h1>
    <div id="result"></div>
    <button onclick="testAPI()">Test API</button>
    
    <div style="margin-top: 20px;">
        <canvas id="testChart" width="400" height="200"></canvas>
    </div>

    <script>
        async function testAPI() {
            const resultDiv = document.getElementById('result');
            
            try {
                resultDiv.innerHTML = 'Testing API...';
                
                // Test chart data API
                const response = await fetch('/admin/mfo-requests/chart-data?group_by=month', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('API Data:', data);
                
                resultDiv.innerHTML = `
                    <h3>API Test Result:</h3>
                    <p>Status: ${response.status}</p>
                    <p>Data Points: ${data.data ? data.data.length : 0}</p>
                    <pre>${JSON.stringify(data, null, 2)}</pre>
                `;
                
                // Create simple chart
                if (data.data && data.data.length > 0) {
                    const ctx = document.getElementById('testChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.data.map(d => d.period),
                            datasets: [{
                                label: 'MFO Requests',
                                data: data.data.map(d => d.count),
                                borderColor: 'rgb(75, 192, 192)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                tension: 0.1
                            }]
                        },
                        options: {
                            responsive: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    
                    resultDiv.innerHTML += '<p><strong>✅ Chart created successfully!</strong></p>';
                }
                
            } catch (error) {
                console.error('Error:', error);
                resultDiv.innerHTML = `
                    <h3>Error:</h3>
                    <p style="color: red;">${error.message}</p>
                `;
            }
        }
        
        // Auto test on load
        window.onload = function() {
            testAPI();
        };
    </script>
</body>
</html>
