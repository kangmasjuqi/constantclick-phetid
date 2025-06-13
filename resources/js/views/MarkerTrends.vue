<template>
    <div class="min-h-screen bg-gray-100 flex flex-col">
        <nav class="bg-white shadow-sm p-4 flex justify-between items-center">
            <div class="text-xl font-bold text-gray-800">PHET-ID Trends</div>
            <div>
                <router-link :to="{ name: 'Dashboard' }" class="text-blue-500 hover:text-blue-700 mr-4">Dashboard</router-link>
                <router-link :to="{ name: 'AddTest' }" class="text-blue-500 hover:text-blue-700 mr-4">Add New Test</router-link>
                <router-link :to="{ name: 'MarkerTrendsOverview' }" class="text-blue-500 hover:text-blue-700 mr-4">View Trends</router-link> 
                <span v-if="authStore.currentUser" class="text-gray-700 mr-4">Welcome, {{ authStore.currentUser.name }}</span>
                <button
                    @click="authStore.logout()"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded transition duration-300"
                >
                    Logout
                </button>
            </div>
        </nav>

        <main class="flex-grow p-6">
            <div class="bg-white p-8 rounded-lg shadow-md max-w-6xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Blood Marker Trends</h1>

                <div class="mb-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <label for="marker-select" class="block text-gray-700 text-lg font-semibold">Select a Marker:</label>
                    <select
                        id="marker-select"
                        v-model="selectedMarkerId"
                        @change="handleMarkerChange"
                        class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-full sm:w-1/2 md:w-1/3"
                    >
                        <option value="" disabled>-- Choose a Marker --</option>
                        <option v-for="marker in testDataStore.allMarkers" :key="marker.id" :value="marker.id">
                            {{ marker.name }} ({{ marker.unit }})
                        </option>
                    </select>
                </div>

                <div v-if="testDataStore.isLoading" class="text-blue-500 text-center mb-4">Loading data...</div>
                <div v-if="testDataStore.getErrors.length" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc list-inside">
                        <li v-for="(error, index) in testDataStore.getErrors" :key="index">{{ error }}</li>
                    </ul>
                </div>
                <div v-if="!selectedMarkerId" class="text-center text-gray-600 text-lg">
                    Please select a blood marker to view its historical trend and insights.
                </div>

                <div v-if="selectedMarker && chartData.labels.length > 0">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">{{ selectedMarker.name }} Trend ({{ selectedMarker.unit }})</h2>

                    <div class="relative h-96 w-full mb-8">
                        <LineChart :chart-data="chartData" :options="chartOptions" />
                    </div>

                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-200 shadow-sm">
                        <h3 class="text-xl font-semibold text-blue-800 mb-3">Your Health Insights for {{ selectedMarker.name }}</h3>
                        <p class="text-blue-700 mb-2" v-if="insights.message">{{ insights.message }}</p>
                        <ul class="list-disc list-inside text-blue-700 space-y-1">
                            <li v-for="(item, i) in insights.recommendations" :key="i">{{ item }}</li>
                        </ul>
                        <p class="text-sm text-blue-600 mt-4 font-medium">
                            <i class="fas fa-info-circle mr-1"></i>
                            Disclaimer: These insights are based on general health guidelines and your provided data. They are NOT medical advice. Always consult with a healthcare professional for diagnosis, treatment, or any health concerns.
                        </p>
                    </div>

                </div>
                <div v-else-if="selectedMarkerId && chartData.labels.length === 0 && !testDataStore.isLoading">
                    <p class="text-center text-gray-600 text-lg">No historical data available for {{ selectedMarker ? selectedMarker.name : 'this marker' }}.</p>
                    <p class="text-center text-gray-500 text-sm mt-2">Add new test entries to see trends.</p>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useTestDataStore } from '../stores/testData';
import { useRoute, useRouter } from 'vue-router';

// Chart.js imports
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    LineController
} from 'chart.js';
import { LineChart } from 'vue-chart-3';

// Register Chart.js components
ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, LineController);

const authStore = useAuthStore();
const testDataStore = useTestDataStore();
const route = useRoute();
const router = useRouter();

const selectedMarkerId = ref(route.params.markerId || '');
const markerData = ref([]);
const insights = ref({ message: '', recommendations: [] });

// Fetch all markers when component mounts
onMounted(async () => {
    await testDataStore.fetchMarkers();
    if (selectedMarkerId.value) {
        await fetchMarkerData(selectedMarkerId.value);
    }
});

// Watch for changes in selectedMarkerId to fetch new data
watch(selectedMarkerId, async (newMarkerId) => {
    if (newMarkerId) {
        await fetchMarkerData(newMarkerId);
    } else {
        markerData.value = [];
        insights.value = { message: '', recommendations: [] };
    }
});

// Watch route params for markerId changes
watch(() => route.params.markerId, (newMarkerId) => {
    selectedMarkerId.value = newMarkerId || '';
});

// Helper to get the selected marker's details from the store
const selectedMarker = computed(() => {
    if (!testDataStore.allMarkers || testDataStore.allMarkers.length === 0) {
        return null;
    }
    return testDataStore.allMarkers.find(m => m.id == selectedMarkerId.value); // Use == for type coercion
});

// Function to fetch data for the selected marker
const fetchMarkerData = async (markerId) => {
    try {
        console.log('Fetching data for marker ID:', markerId); // Debug log
        
        // Fetch raw response from store
        const rawResponse = await testDataStore.fetchUserMarkerValuesByMarker(markerId);
        console.log('Raw API response:', rawResponse); // Debug log
        
        // Extract the actual data array from the response
        // Handle both direct array and wrapped { data: [...] } responses
        let dataArray;
        if (Array.isArray(rawResponse)) {
            dataArray = rawResponse;
        } else if (rawResponse && Array.isArray(rawResponse.data)) {
            dataArray = rawResponse.data; // Extract from { data: [...] } wrapper
        } else {
            console.warn('Unexpected API response structure:', rawResponse);
            dataArray = [];
        }
        
        console.log('Extracted data array:', dataArray); // Debug log
        
        // Validate and process the data
        const validData = dataArray
            .filter(item => {
                // More robust validation
                const hasValidEntry = item && 
                    item.user_test_entry && 
                    item.user_test_entry.test_date &&
                    item.value !== null && 
                    item.value !== undefined;
                
                if (!hasValidEntry) {
                    console.warn('Invalid data item filtered out:', item);
                }
                return hasValidEntry;
            })
            .sort((a, b) => {
                const dateA = new Date(a.user_test_entry.test_date);
                const dateB = new Date(b.user_test_entry.test_date);
                
                if (isNaN(dateA.getTime()) || isNaN(dateB.getTime())) {
                    console.warn('Invalid date found during sorting:', a, b);
                    return 0;
                }
                return dateA.getTime() - dateB.getTime();
            });

        console.log('Processed valid data:', validData); // Debug log
        
        markerData.value = validData;
        
        // Generate insights after setting data
        if (validData.length > 0) {
            generateInsights();
        } else {
            insights.value = { message: 'No valid data points found.', recommendations: [] };
        }
        
    } catch (error) {
        console.error("Failed to fetch marker data:", error);
        markerData.value = [];
        insights.value = { message: 'Error loading data.', recommendations: [] };
    }
};

// Handle marker selection change
const handleMarkerChange = () => {
    if (selectedMarkerId.value) {
        router.push({ name: 'MarkerSpecificTrend', params: { markerId: selectedMarkerId.value } });
    } else {
        router.push({ name: 'MarkerTrendsOverview' });
    }
};

// Prepare data for Chart.js
const chartData = computed(() => {
    console.log('Computing chart data. Selected marker:', selectedMarker.value); // Debug log
    console.log('Marker data length:', markerData.value.length); // Debug log
    
    if (!selectedMarker.value || markerData.value.length === 0) {
        console.log('No marker selected or no data available'); // Debug log
        return { labels: [], datasets: [] };
    }

    const labels = markerData.value.map(d => {
        const date = new Date(d.user_test_entry.test_date);
        return date.toLocaleDateString(); // Format date nicely
    });
    
    const values = markerData.value.map(d => parseFloat(d.value));
    
    console.log('Chart labels:', labels); // Debug log
    console.log('Chart values:', values); // Debug log

    const datasets = [
        {
            label: `${selectedMarker.value.name} (${selectedMarker.value.unit})`,
            data: values,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.2,
            fill: false,
        }
    ];

    // Add healthy range lines if available
    if (selectedMarker.value.healthy_min !== null && selectedMarker.value.healthy_min !== undefined) {
        datasets.push({
            label: 'Healthy Min',
            data: labels.map(() => parseFloat(selectedMarker.value.healthy_min)),
            borderColor: 'rgb(0, 128, 0)',
            borderDash: [5, 5],
            pointStyle: false,
            fill: false,
            tension: 0,
        });
    }
    
    if (selectedMarker.value.healthy_max !== null && selectedMarker.value.healthy_max !== undefined) {
        datasets.push({
            label: 'Healthy Max',
            data: labels.map(() => parseFloat(selectedMarker.value.healthy_max)),
            borderColor: 'rgb(255, 99, 132)',
            borderDash: [5, 5],
            pointStyle: false,
            fill: false,
            tension: 0,
        });
    }

    return {
        labels: labels,
        datasets: datasets,
    };
});

// Chart.js options
const chartOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            title: {
                display: true,
                text: selectedMarker.value ? `Value (${selectedMarker.value.unit})` : 'Value',
            },
            beginAtZero: false,
        },
        x: {
            title: {
                display: true,
                text: 'Test Date',
            },
        },
    },
    plugins: {
        tooltip: {
            mode: 'index',
            intersect: false,
        },
    },
}));

// Generate insights based on marker data
const generateInsights = () => {
    const marker = selectedMarker.value;
    const data = markerData.value;
    
    if (!marker || data.length === 0) {
        insights.value = { message: 'No data available for insights.', recommendations: [] };
        return;
    }
    
    const latestValue = parseFloat(data[data.length - 1].value);
    const min = parseFloat(marker.healthy_min);
    const max = parseFloat(marker.healthy_max);
    
    let status = 'Your recent results are generally within the healthy range.';
    const recommendations = [];

    // Check latest value against healthy range
    if (!isNaN(min) && latestValue < min) {
        status = `Your latest ${marker.name} level (${latestValue} ${marker.unit}) is below the healthy minimum (${min} ${marker.unit}).`;
        recommendations.push(`Consider discussing with your doctor if this persists.`);
    } else if (!isNaN(max) && latestValue > max) {
        status = `Your latest ${marker.name} level (${latestValue} ${marker.unit}) is above the healthy maximum (${max} ${marker.unit}).`;
        recommendations.push(`It's advisable to discuss this with your doctor.`);
    }

    // Simple trend analysis (requires at least 3 data points)
    if (data.length >= 3) {
        const lastThree = data.slice(-3);
        const [val1, val2, val3] = lastThree.map(d => parseFloat(d.value));

        const trendThreshold = (!isNaN(max) && !isNaN(min)) ? (max - min) * 0.1 : 5;

        if (val1 < val2 && val2 < val3 && (val3 - val1 > trendThreshold)) {
            status += ` There's a noticeable upward trend over your last few tests.`;
            recommendations.push(`Monitor this trend closely and consider lifestyle adjustments.`);
        } else if (val1 > val2 && val2 > val3 && (val1 - val3 > trendThreshold)) {
            status += ` There's a noticeable downward trend over your last few tests.`;
            recommendations.push(`Monitor this trend closely and consider lifestyle adjustments.`);
        }
    }

    if (recommendations.length === 0) {
        recommendations.push('Maintain your current active lifestyle and healthy habits.');
        recommendations.push('Regular testing is key for early detection!');
    }

    insights.value = { message: status, recommendations: recommendations };
};
</script>

<style scoped>
/* Scoped styles specific to this component */
</style>