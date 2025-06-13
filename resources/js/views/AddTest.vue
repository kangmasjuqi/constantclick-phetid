<template>
    <div class="min-h-screen bg-gray-100 flex flex-col">
        <nav class="bg-white shadow-sm p-4 flex justify-between items-center">
            <div class="text-xl font-bold text-gray-800">PHET-ID</div>
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
            <div class="bg-white p-8 rounded-lg shadow-md max-w-4xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Add New Blood Test Entry</h1>

                <div v-if="testDataStore.isLoading" class="text-blue-500 text-center mb-4">Loading data...</div>
                <div v-if="testDataStore.getErrors.length" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc list-inside">
                        <li v-for="(error, index) in testDataStore.getErrors" :key="index">{{ error }}</li>
                    </ul>
                </div>
                <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ successMessage }}
                </div>

                <form @submit.prevent="handleSubmit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label for="test_panel" class="block text-gray-700 text-sm font-bold mb-2">Test Panel</label>
                            <select
                                id="test_panel"
                                v-model="form.test_panel_id"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required
                            >
                                <option value="" disabled>Select a Test Panel</option>
                                <option v-for="panel in testDataStore.allTestPanels" :key="panel.id" :value="panel.id">
                                    {{ panel.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="test_date" class="block text-gray-700 text-sm font-bold mb-2">Test Date</label>
                            <input
                                type="date"
                                id="test_date"
                                v-model="form.test_date"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required
                            />
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Marker Results</h3>
                    <div v-for="(markerValue, index) in form.marker_values" :key="index" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 items-end bg-gray-50 p-4 rounded-md border border-gray-200">
                        <div>
                            <label :for="'marker_id_' + index" class="block text-gray-700 text-sm font-bold mb-2">Marker</label>
                            <select
                                :id="'marker_id_' + index"
                                v-model="markerValue.marker_id"
                                @change="setMarkerUnit(index)"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required
                            >
                                <option value="" disabled>Select a Marker</option>
                                <option v-for="marker in testDataStore.allMarkers" :key="marker.id" :value="marker.id">
                                    {{ marker.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label :for="'value_' + index" class="block text-gray-700 text-sm font-bold mb-2">Result</label>
                            <div class="flex">
                                <input
                                    type="number"
                                    step="0.01"
                                    :id="'value_' + index"
                                    v-model.number="markerValue.value"
                                    class="shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    required
                                />
                                <span class="inline-flex items-center px-3 rounded-r border border-l-0 border-gray-300 bg-gray-200 text-gray-600 text-sm">
                                    {{ getSelectedMarkerUnit(index) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center justify-end md:col-span-1">
                            <button
                                type="button"
                                @click="removeMarkerValue(index)"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            >
                                Remove
                            </button>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="addMarkerValue"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4"
                    >
                        Add Another Marker
                    </button>

                    <div class="mt-8 text-center">
                        <button
                            type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg text-lg focus:outline-none focus:shadow-outline"
                            :disabled="testDataStore.isLoading"
                        >
                            {{ testDataStore.isLoading ? 'Submitting...' : 'Submit Test Entry' }}
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useTestDataStore } from '../stores/testData';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const testDataStore = useTestDataStore();
const router = useRouter();

const form = ref({
    test_panel_id: '',
    test_date: '',
    marker_values: [
        { marker_id: '', value: null } // Initial empty marker field
    ],
});

const successMessage = ref('');

// Fetch initial data when the component mounts
onMounted(async () => {
    await testDataStore.fetchTestPanels();
    await testDataStore.fetchMarkers();

    // Set default test date to today
    form.value.test_date = new Date().toISOString().slice(0, 10);
});

// Computed property to map marker IDs to their units for dynamic display
const markerUnits = computed(() => {
    return testDataStore.allMarkers.reduce((acc, marker) => {
        acc[marker.id] = marker.unit;
        return acc;
    }, {});
});

const addMarkerValue = () => {
    form.value.marker_values.push({ marker_id: '', value: null });
};

const removeMarkerValue = (index) => {
    if (form.value.marker_values.length > 1) {
        form.value.marker_values.splice(index, 1);
    } else {
        // Optionally, prevent removing the last one or show a message
        alert('You must have at least one marker value.');
    }
};

const setMarkerUnit = (index) => {
    // This function can be used if you need to update other parts of the form
    // based on marker selection, e.g., displaying healthy range next to it.
    // For now, it simply ensures reactivity for the unit display.
};

const getSelectedMarkerUnit = (index) => {
    const selectedMarkerId = form.value.marker_values[index].marker_id;
    return markerUnits.value[selectedMarkerId] || '';
};


const handleSubmit = async () => {
    successMessage.value = ''; // Clear previous success message
    testDataStore.errors = []; // Clear previous errors from the store

    try {
        const response = await testDataStore.submitTestEntry(form.value);
        successMessage.value = 'Test entry submitted successfully!';
        // Optionally, reset form or redirect
        resetForm();
        // You might want to router.push({name: 'Dashboard'}) after successful submission
        // or to a 'view test entry' page.
    } catch (error) {
        // Errors are already handled and stored in testDataStore.errors by the action
        console.log("Form submission failed:", testDataStore.errors); // Log store errors for dev
    }
};

const resetForm = () => {
    form.value = {
        test_panel_id: '',
        test_date: new Date().toISOString().slice(0, 10), // Reset to today
        marker_values: [
            { marker_id: '', value: null }
        ],
    };
};
</script>

<style scoped>
/* Scoped styles specific to this component */
</style>