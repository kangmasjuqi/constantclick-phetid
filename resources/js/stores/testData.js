// resources/js/stores/testData.js
import { defineStore } from 'pinia';
import axios from 'axios';

export const useTestDataStore = defineStore('testData', {
    state: () => ({
        // State properties for storing various data and handling UI states
        testPanels: [],       // Stores all available test panels (e.g., Lipid Panel)
        markers: [],          // Stores all available individual markers (e.g., Glucose, Cholesterol)
        userMarkerValues: [], // Stores historical values for a selected marker for charting
        loading: false,       // Global loading indicator for API requests
        errors: [],           // Array to store API-related error messages
    }),    

    getters: {
        // Getters provide computed state based on the store's state
        allTestPanels: (state) => state.testPanels,
        allMarkers: (state) => state.markers,
        allUserMarkerValues: (state) => state.userMarkerValues,
        isLoading: (state) => state.loading,
        getErrors: (state) => state.errors,
    },

    actions: {
        // Actions are where you define asynchronous operations and state mutations

        /**
         * Fetches all available test panels from the backend.
         */
        async fetchTestPanels() {
            this.loading = true;
            this.errors = []; // Clear previous errors
            try {
                const response = await axios.get('/api/test-panels', {
                    headers: { Authorization: `Bearer ${localStorage.getItem('authToken')}` }
                });
                this.testPanels = response.data.data; // Assuming Laravel API Resource returns {data: [...]}
            } catch (error) {
                console.error('Error fetching test panels:', error);
                this.errors = ['Failed to load test panels. Please try again.'];
            } finally {
                this.loading = false;
            }
        },

        /**
         * Fetches all available individual markers from the backend.
         */
        async fetchMarkers() {
            this.loading = true;
            this.errors = []; // Clear previous errors
            try {
                const response = await axios.get('/api/markers', {
                    headers: { Authorization: `Bearer ${localStorage.getItem('authToken')}` }
                });
                this.markers = response.data.data; // Assuming Laravel API Resource returns {data: [...]}
            } catch (error) {
                console.error('Error fetching markers:', error);
                this.errors = ['Failed to load markers. Please try again.'];
            } finally {
                this.loading = false;
            }
        },

        /**
         * Submits a new user test entry with its associated marker values to the backend.
         * @param {object} testEntryData - The data for the new test entry.
         * @returns {Promise<object>} The data of the created test entry.
         */
        async submitTestEntry(testEntryData) {
            this.loading = true;
            this.errors = []; // Clear previous errors
            try {
                const response = await axios.post('/api/user-test-entries', testEntryData, {
                    headers: { Authorization: `Bearer ${localStorage.getItem('authToken')}` }
                });
                // Return the created entry data, component can use it for success message or redirect
                return response.data.data; 
            } catch (error) {
                console.error('Error submitting test entry:', error);
                if (error.response && error.response.status === 422) {
                    // Extract validation errors from Laravel's 422 response
                    this.errors = Object.values(error.response.data.errors).flat();
                } else if (error.response) {
                    // General API error message
                    this.errors = [error.response.data.message || 'Failed to submit test entry.'];
                } else {
                    // Network or unhandled error
                    this.errors = ['Network error or server unreachable. Please check your connection.'];
                }
                throw error; // Re-throw the error to allow the component to catch and handle it
            } finally {
                this.loading = false;
            }
        },

        /**
         * Fetches historical marker values for a specific marker for the authenticated user.
         * @param {number} markerId - The ID of the marker to fetch data for.
         * @returns {Promise<Array<object>>} The historical marker values.
         */
        async fetchUserMarkerValuesByMarker(markerId) {
            this.loading = true;
            this.errors = []; // Clear previous errors
            try {
                const response = await axios.get(`/api/user-marker-values/by-marker/${markerId}`, {
                    headers: { Authorization: `Bearer ${localStorage.getItem('authToken')}` }
                });
                // Assuming API Resource returns {data: [...]} with nested marker and user_test_entry details
                this.userMarkerValues = response.data.data;
                return response.data.data; // Return data for component to use directly
            } catch (error) {
                console.error(`Error fetching historical data for marker ${markerId}:`, error);
                if (error.response && error.response.status === 403) {
                    this.errors = ['Unauthorized to view this data.'];
                } else if (error.response && error.response.data.message) {
                    this.errors = [error.response.data.message];
                } else {
                    this.errors = ['Failed to load marker history. Please ensure data exists.'];
                }
                this.userMarkerValues = []; // Clear data on error
                throw error; // Re-throw to allow component to handle specific error UI
            } finally {
                this.loading = false;
            }
        },
    },
});