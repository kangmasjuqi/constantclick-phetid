<template>
    <div class="min-h-screen bg-gray-100 flex flex-col">
        <nav class="bg-white shadow-sm p-4 flex justify-between items-center">
            <div class="text-xl font-bold text-gray-800">PHET-ID Trends</div>
            <div>
                <router-link :to="{ name: 'Dashboard' }" class="text-blue-500 hover:text-blue-700 mr-4">Dashboard</router-link>
                <router-link :to="{ name: 'AddTest' }" class="text-blue-500 hover:text-blue-700 mr-4">Add New Test</router-link>
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
    LineController // Ensure LineController is imported
} from 'chart.js';
import { LineChart } from 'vue-chart-3'; // Ensure LineChart is imported

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

// Watch route params for markerId changes (e.g., if navigating /trends/1 to /trends/2)
watch(() => route.params.markerId, (newMarkerId) => {
    selectedMarkerId.value = newMarkerId || '';
});

// Helper to get the selected marker's details from the store
const selectedMarker = computed(() => {
    if (!testDataStore.allMarkers || testDataStore.allMarkers.length === 0) {
        return null;
    }
    return testDataStore.allMarkers.find(m => m.id === selectedMarkerId.value);
});

// Function to fetch data for the selected marker
const fetchMarkerData = async (markerId) => {
    try {
        // --- FIX START ---
        // testDataStore.fetchUserMarkerValuesByMarker already returns the data array
        const rawData = await testDataStore.fetchUserMarkerValuesByMarker(markerId);
        
        // Ensure rawData is an array before filtering/sorting, default to empty array
        const dataToProcess = Array.isArray(rawData) ? rawData : []; 

        // Filter out any entries that might be missing user_test_entry or test_date
        // then sort them correctly.
        const filteredAndSortedData = dataToProcess
            .filter(d => d.user_test_entry && d.user_test_entry.test_date)
            .sort((a, b) => {
                const dateA = new Date(a.user_test_entry.test_date).getTime();
                const dateB = new Date(b.user_test_entry.test_date).getTime();
                
                // Handle invalid dates (which result in NaN from getTime())
                if (isNaN(dateA) || isNaN(dateB)) {
                    console.warn('Invalid date found during sorting:', a, b);
                    return 0; // Don't sort these, they should ideally be filtered out
                }
                return dateA - dateB;
            });

        markerData.value = filteredAndSortedData;
        // --- FIX END ---

        generateInsights();
    } catch (error) {
        console.error("Failed to fetch marker data:", error);
        markerData.value = [];
        insights.value = { message: '', recommendations: [] };
    }
};

// Handle marker selection change (redirect to update URL for shareability)
const handleMarkerChange = () => {
    if (selectedMarkerId.value) {
        router.push({ name: 'MarkerSpecificTrend', params: { markerId: selectedMarkerId.value } });
    } else {
        router.push({ name: 'MarkerTrendsOverview' }); // Go back to overview without ID
    }
};


// Prepare data for Chart.js
const chartData = computed(() => {
    // Also add a check for !selectedMarker.value.name, as it could be null if the marker hasn't loaded fully
    if (!selectedMarker.value || markerData.value.length === 0 || !selectedMarker.value.name) {
        return { labels: [], datasets: [] };
    }

    // Use optional chaining for safety just in case
    const labels = markerData.value.map(d => d.user_test_entry?.test_date || 'N/A');
    const values = markerData.value.map(d => parseFloat(d.value)); // Ensure values are numbers for charting

    const datasets = [
        {
            label: `${selectedMarker.value.name} (${selectedMarker.value.unit})`,
            data: values,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.2, // Smooth lines
            fill: false,
        }
    ];

    // Add healthy range lines if available
    if (selectedMarker.value.healthy_min !== null) {
        datasets.push({
            label: 'Healthy Min',
            data: labels.map(() => parseFloat(selectedMarker.value.healthy_min)), // Ensure number
            borderColor: 'rgb(0, 128, 0)', // Green
            borderDash: [5, 5], // Dashed line
            pointStyle: false, // No points for this line
            fill: false,
            tension: 0,
        });
    }
    if (selectedMarker.value.healthy_max !== null) {
        datasets.push({
            label: 'Healthy Max',
            data: labels.map(() => parseFloat(selectedMarker.value.healthy_max)), // Ensure number
            borderColor: 'rgb(255, 99, 132)', // Red
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
const chartOptions = computed(() => ({ // Use computed property for options if they depend on reactive data
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            title: {
                display: true,
                text: selectedMarker.value ? `Value (${selectedMarker.value.unit})` : 'Value',
            },
            beginAtZero: false, // Often good for health data to not start at 0 if ranges are high
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
        // Optional: Add a title plugin if desired
        // title: {
        //   display: true,
        //   text: selectedMarker.value ? `${selectedMarker.value.name} Trend` : 'Marker Trend'
        // }
    },
}));


// --- Basic Insights Logic ---
const generateInsights = () => {
    const marker = selectedMarker.value;
    const data = markerData.value;
    // Ensure latestValue is a number for comparisons
    const latestValue = data.length > 0 ? parseFloat(data[data.length - 1].value) : null;

    insights.value = { message: '', recommendations: [] }; // Reset insights

    if (!marker || data.length === 0 || latestValue === null) {
        insights.value.message = 'No data to generate insights.';
        return;
    }

    let status = 'Your recent results are generally within the healthy range.';
    const recommendations = [];

    // Ensure min/max are numbers for comparison
    const min = parseFloat(marker.healthy_min);
    const max = parseFloat(marker.healthy_max);

    // Check latest value against healthy range
    if (min !== null && latestValue < min) {
        status = `Your latest ${marker.name} level (${latestValue} ${marker.unit}) is below the healthy minimum (${min} ${marker.unit}).`;
        recommendations.push(`Consider discussing with your doctor if this persists.`);
    } else if (max !== null && latestValue > max) {
        status = `Your latest ${marker.name} level (${latestValue} ${marker.unit}) is above the healthy maximum (${max} ${marker.unit}).`;
        recommendations.push(`It's advisable to discuss this with your doctor.`);
    }

    // Simple trend analysis (requires at least 3 data points)
    if (data.length >= 3) {
        const lastThree = data.slice(-3);
        // Ensure values are numbers for comparison
        const [val1, val2, val3] = lastThree.map(d => parseFloat(d.value));

        // Use more robust trend detection: check for consistent direction and significant change
        const trendThreshold = (max !== null && min !== null) ? (max - min) * 0.1 : 5; // 10% of range or fixed value

        if (val1 < val2 && val2 < val3 && (val3 - val1 > trendThreshold)) { // Significant upward trend
            status += ` There's a noticeable upward trend over your last few tests.`;
            recommendations.push(`Monitor this trend closely and consider lifestyle adjustments.`);
        } else if (val1 > val2 && val2 > val3 && (val1 - val3 > trendThreshold)) { // Significant downward trend
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