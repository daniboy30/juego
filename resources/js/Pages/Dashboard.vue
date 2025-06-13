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
            <div class="max-w-4xl mx-auto px-4">
                <div class=" shadow-md sm:rounded-lg p-6">
                    <h3 class="text-2xl font-bold text-white mb-6 text-center">Available Rooms</h3>

                    <div class="space-y-4">
                        <div class="w-full border border-gray-300 rounded-lg p-4 shadow hover:shadow-lg transition flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div>
                                <h4 class="text-xl font-semibold text-gray-700">Room #1</h4>
                                <p class="text-sm text-gray-500">Created by: Juan</p>
                                <p class="text-sm text-gray-600 font-semibold mt-1">Status: <span class="text-yellow-600">Waiting</span></p>
                            </div>
                            <button class="mt-4 sm:mt-0 bg-teal-700 hover:bg-teal-800 text-white text-sm px-4 py-2 rounded">
                                Join Room
                            </button>
                        </div>

                        <!-- Card de Sala -->
                        <div class="w-full border border-gray-300 rounded-lg p-4 shadow hover:shadow-lg transition flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div>
                                <h4 class="text-xl font-semibold text-gray-700">Room #2</h4>
                                <p class="text-sm text-gray-500">Created by: María</p>
                                <p class="text-sm text-gray-600 font-semibold mt-1">Status: <span class="text-green-600">Playing</span></p>
                            </div>
                            <button class="mt-4 sm:mt-0 bg-teal-700 hover:bg-teal-800 text-white text-sm px-4 py-2 rounded">
                                Join Room
                            </button>
                        </div>

                        <!-- Card de Sala -->
                        <div class="w-full border border-gray-300 rounded-lg p-4 shadow hover:shadow-lg transition flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div>
                                <h4 class="text-xl font-semibold text-gray-700">Room #3</h4>
                                <p class="text-sm text-gray-500">Created by: Carlos</p>
                                <p class="text-sm text-gray-600 font-semibold mt-1">Status: <span class="text-red-600">Finished</span></p>
                            </div>
                            <button class="mt-4 sm:mt-0 bg-teal-700 hover:bg-teal-800 text-white text-sm px-4 py-2 rounded">
                                Join Room
                            </button>
                        </div>
                    </div>
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
