<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="guest-layout">
        <div class="overlay"></div>


        <main class="z-30 relative w-full flex justify-center items-center min-h-screen px-4">
            <div class="w-full sm:max-w-md bg-white/5 rounded-xl shadow-2xl backdrop-blur-md px-8 py-6">
                <header class=" top-0 left-0 w-full px-6 py-4 z-20 flex justify-center items-center">
                    <img style="max-height: 5em" src="../Images/logoBarco.png" alt="Logo">
                </header>
                <slot />
            </div>
        </main>
    </div>
</template>

<style scoped>
.guest-layout {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    color: white;
    animation: gradientAnimation 10s ease infinite;
    background: linear-gradient(-45deg, #2193b0, #6dd5ed, #1e3c72, #2a5298);
    background-size: 400% 400%;
}

@keyframes gradientAnimation {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

.overlay {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at center, rgba(255,255,255,0.05), rgba(0,0,0,0.6));
    z-index: 10;
}

main {
    z-index: 20;
}

h1 {
    @apply text-3xl font-bold mb-6 text-center;
}
</style>
