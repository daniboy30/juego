<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
export default {
    components: {
        Head,
        AuthenticatedLayout,
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-xl text-white leading-tight">Welcome to Lobby {{$page.props.auth.user.name }}</h2>

            <button class="glass-button text-white">
                Create game
            </button>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

.glass-button {
    margin-top: 10px;
    padding: 0.6rem 1.5rem; /* más pequeño que antes */
    font-size: 1rem;         /* antes era 1.5rem */
    color: #ffffff;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    backdrop-filter: blur(50px);
    -webkit-backdrop-filter: blur(15px);
    box-shadow:
        inset 0 0 10px rgba(255, 255, 255, 0.3),
        0 8px 32px rgba(0, 0, 0, 0.37),
        0 0 20px rgba(255, 255, 255, 0.2);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.6);
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
}

.glass-button span {
    z-index: 1;
}

.glass-button:active {
    transform: scale(0.95) translateY(2px);
    box-shadow:
        inset 0 0 15px rgba(255, 255, 255, 0.4),
        0 4px 20px rgba(0, 0, 0, 0.5);
}

.glass-button::after {
    content: '';
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        120deg,
        transparent 30%,
        rgba(255, 255, 255, 0.6),
        transparent 70%
    );
    transform: rotate(25deg);
    opacity: 0;
    transition: opacity 0.3s;
    z-index: 2;
}

.glass-button:hover::after {
    animation: shine 1s forwards;
    opacity: 1;
}
</style>
