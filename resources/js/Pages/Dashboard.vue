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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Welcome to Lobby {{$page.props.auth.user.name }}</h2>

            <button
                class="mt-2 px-3 py-1 bg-green-900 text-white text-sm rounded hover:bg-gray-700"
            >
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
