<template>
    <div class="min-h-screen bg-gray-100 flex flex-col">
        <nav class="bg-white shadow-sm p-4 flex justify-between items-center">
            <div class="text-xl font-bold text-gray-800">PHET-ID</div>
            <div>
                <router-link :to="{ name: 'Dashboard' }" class="text-blue-500 hover:text-blue-700 mr-4">Dashboard</router-link>
                <router-link :to="{ name: 'AddTest' }" class="text-blue-500 hover:text-blue-700 mr-4">Add New Test</router-link>
                <router-link :to="{ name: 'MarkerTrendsOverview' }" class="text-blue-500 hover:text-blue-700 mr-4">View Trends</router-link> 
                <span v-if="authStore.currentUser" class="text-gray-700 mr-4">Welcome, {{ authStore.currentUser.name }}</span>
                <button @click="authStore.logout()" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded transition duration-300">
                    Logout
                </button>
            </div>
        </nav>
        <main class="flex-grow p-6">
            <div class="bg-white p-8 rounded-lg shadow-md max-w-4xl mx-auto text-center">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">Your Health Overview</h1>
                <p class="text-gray-600 text-lg mb-6">
                    This is your personal health dashboard. Here you will track your blood test trends
                    and get insights.
                </p>
                <div v-if="authStore.currentUser" class="text-gray-700 mb-4">
                    Logged in as: <span class="font-semibold">{{ authStore.currentUser.email }}</span>
                </div>
                <p class="text-sm text-gray-500">
                    (More features coming soon: Add New Test, Marker Trends, Insights!)
                </p>
            </div>
        </main>
    </div>
</template>
<script setup>
import { useAuthStore } from '../stores/auth';
const authStore = useAuthStore();
</script>
<style scoped></style>