<?php
// Include the configuration file
require_once __DIR__ . '/config/config.php';

// Include the functions file
require_once __DIR__ . '/functions/functions.php';

// Include header
include __DIR__ . '/templates/header.php';

// Sample data for charts (in a real-world scenario, this would come from a database)
$propertySalesData = [
    ['month' => 'Jan', 'sales' => 4000, 'listings' => 2400],
    ['month' => 'Feb', 'sales' => 3000, 'listings' => 1398],
    ['month' => 'Mar', 'sales' => 2000, 'listings' => 9800],
    ['month' => 'Apr', 'sales' => 2780, 'listings' => 3908],
    ['month' => 'May', 'sales' => 1890, 'listings' => 4800],
    ['month' => 'Jun', 'sales' => 2390, 'listings' => 3800],
];

$propertyPriceData = [
    ['year' => '2020', 'price' => 300000],
    ['year' => '2021', 'price' => 320000],
    ['year' => '2022', 'price' => 350000],
    ['year' => '2023', 'price' => 390000],
    ['year' => '2024', 'price' => 410000],
];
?>

<div class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('uploads/homepage.jpg');">
    <div class="min-h-screen bg-black bg-opacity-50 backdrop-filter backdrop-blur-sm">
        <!-- Hero Section -->
        <div class="relative py-24">
            <div class="container mx-auto px-4">
                <h1 class="text-5xl font-bold text-white mb-4 text-center">Market Insights</h1>
                <p class="text-xl text-white text-center">Stay informed with the latest real estate trends</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Property Sales and Listings Chart -->
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold mb-4 text-white">Property Sales and Listings</h2>
                    <canvas id="propertySalesChart" width="400" height="200"></canvas>
                </div>

                <!-- Average Property Price Chart -->
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold mb-4 text-white">Average Property Price Trends</h2>
                    <canvas id="propertyPriceChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Market Trends -->
            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-2 text-white">Rising Demand</h3>
                    <p class="text-gray-200">Increased demand in suburban areas as more people seek spacious homes.</p>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-2 text-white">Low Interest Rates</h3>
                    <p class="text-gray-200">Historically low rates making home ownership more accessible.</p>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-filter backdrop-blur-lg rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-2 text-white">Shifting Preferences</h3>
                    <p class="text-gray-200">Trend towards homes with dedicated office spaces and outdoor areas.</p>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="mt-16 text-center">
                <h2 class="text-3xl font-bold mb-4 text-white">Ready to Make Your Move?</h2>
                <p class="text-xl text-gray-200 mb-8">Our experts are here to guide you through the current market conditions.</p>
                <a href="contact.php" class="bg-secondary text-white px-8 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition duration-300">Contact an Agent</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Property Sales and Listings Chart
var propertySalesCtx = document.getElementById('propertySalesChart').getContext('2d');
var propertySalesChart = new Chart(propertySalesCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($propertySalesData, 'month')); ?>,
        datasets: [{
            label: 'Sales',
            data: <?php echo json_encode(array_column($propertySalesData, 'sales')); ?>,
            borderColor: 'rgba(58, 90, 64, 0.8)',
            backgroundColor: 'rgba(58, 90, 64, 0.2)',
            tension: 0.1,
            fill: true
        }, {
            label: 'Listings',
            data: <?php echo json_encode(array_column($propertySalesData, 'listings')); ?>,
            borderColor: 'rgba(88, 129, 87, 0.8)',
            backgroundColor: 'rgba(88, 129, 87, 0.2)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                labels: {
                    color: 'white'
                }
            }
        },
        scales: {
            y: {
                ticks: { color: 'white' }
            },
            x: {
                ticks: { color: 'white' }
            }
        }
    }
});

// Property Price Chart
var propertyPriceCtx = document.getElementById('propertyPriceChart').getContext('2d');
var propertyPriceChart = new Chart(propertyPriceCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($propertyPriceData, 'year')); ?>,
        datasets: [{
            label: 'Average Price',
            data: <?php echo json_encode(array_column($propertyPriceData, 'price')); ?>,
            backgroundColor: 'rgba(58, 90, 64, 0.6)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                labels: {
                    color: 'white'
                }
            }
        },
        scales: {
            y: {
                ticks: { color: 'white' }
            },
            x: {
                ticks: { color: 'white' }
            }
        }
    }
});
</script>

<?php
// Include footer
include __DIR__ . '/templates/footer.php';
?>