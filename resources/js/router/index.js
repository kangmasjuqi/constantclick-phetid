// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Login from '../views/Auth/Login.vue';
import Register from '../views/Auth/Register.vue';
import Dashboard from '../views/Dashboard.vue';
import { useAuthStore } from '../stores/auth'; // Import auth store for navigation guards

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: { guestOnly: true } // Only visible if not logged in
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { guestOnly: true } // Only visible if not logged in
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta: { guestOnly: true } // Only visible if not logged in
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: { requiresAuth: true } // Requires user to be logged in
    },
    // Add more routes as you build features (e.g., /add-test, /marker-trends)
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// --- Navigation Guards ---
router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    // If there's a token but user data isn't loaded (e.g., on page refresh), try to fetch user
    if (authStore.token && !authStore.user) {
        await authStore.fetchUser();
    }

    // Redirect to login if route requires auth and user is not logged in
    if (to.meta.requiresAuth && !authStore.loggedIn) {
        next({ name: 'Login' });
    }
    // Redirect logged-in users from guest-only routes (login/register/home)
    else if (to.meta.guestOnly && authStore.loggedIn) {
        next({ name: 'Dashboard' });
    }
    else {
        next(); // Proceed to the route
    }
});

export default router;