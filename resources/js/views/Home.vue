<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="text-center p-8 bg-white shadow-md rounded-lg">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to PHET-ID!</h1>
            <p class="text-lg text-gray-600 mb-6">Simplify blood testing & take care of your health.</p>
            <div class="space-x-4">
                <router-link
                    :to="{ name: 'Login' }"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md transition duration-300"
                >
                    Login
                </router-link>
                <router-link
                    :to="{ name: 'Register' }"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md transition duration-300"
                >
                    Register
                </router-link>
            </div>
        </div>
    </div>
</template>
<script setup></script>
<style scoped></style>