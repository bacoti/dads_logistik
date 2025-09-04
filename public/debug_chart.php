<!DOCTYPE html>
<html>
<head>
    <title>Debug Chart API</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Chart API Debug</h1>
    <div id="debug-output"></div>
    
    <div style="width: 400px; height: 400px;">
        <canvas id="testChart"></canvas>
    </div>

    <script>
        // Test fetch chart API
        console.log('Testing chart API...');
        
        fetch('/admin/transactions/chart-data', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.text(); // Get as text first to see raw response
        })
        .then(text => {
            console.log('Raw response:', text);
            document.getElementById('debug-output').innerHTML = '<pre>' + text + '</pre>';
            
            try {
                const data = JSON.parse(text);
                console.log('Parsed data:', data);
                
                // Create simple test chart
                const ctx = document.getElementById('testChart');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.projectData ? data.projectData.map(item => item.name) : ['No Data'],
                        datasets: [{
                            data: data.projectData ? data.projectData.map(item => item.count) : [1],
                            backgroundColor: ['#8B5CF6', '#06B6D4', '#10B981', '#F59E0B']
                        }]
                    }
                });
            } catch (e) {
                console.error('JSON parse error:', e);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            document.getElementById('debug-output').innerHTML = '<div style="color: red;">Error: ' + error.message + '</div>';
        });
    </script>
</body>
</html>
