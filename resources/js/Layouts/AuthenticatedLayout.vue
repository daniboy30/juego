<script>
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { HomeIcon, UserIcon, ArrowRightOnRectangleIcon, ChartBarIcon, Bars3Icon} from "@heroicons/vue/24/outline";

export default {
    name: 'AuthenticatedLayout',
    data() {
        return {
            sidebarOpen: true
        };
    },
    components: {
        Link,
        HomeIcon,
        UserIcon,
        ArrowRightOnRectangleIcon,
        ChartBarIcon,
        Bars3Icon,
    },
  methods: {
    route
  }
}
</script>

<template>
    <div class="app-layout">
        <div class="overlay"></div>
        <div class="flex min-h-screen relative z-10">
            <div
                class="transition-all duration-300"
                :class="sidebarOpen ? 'w-40 p-10' : 'w-0 p-0 overflow-hidden'"
                style="background-color: rgba(255,255,255,0.05); backdrop-filter: blur(16px);"
            >
                <Link :href="route('dashboard')" class="block mb-6">
                    <img src="../Images/logoBarco.png" alt="Logo" class="h-12 mx-auto"/>
                </Link>

                <Link
                    :href="route('dashboard')"
                    :class="[
                        'w-20 h-20 flex items-center justify-center rounded-full text-white shadow-lg backdrop-blur-md transition',
                        route().current('dashboard') ? 'bg-white/30' : 'bg-white/10 hover:bg-white/20'
                    ]"
                    title="Lobby"
                >
                    <HomeIcon class="h-5 w-5"/>
                </Link>

                <Link
                    :href="route('games.played')"
                    :class="[
                        'w-20 h-20 mt-6 flex items-center justify-center rounded-full text-white shadow-lg backdrop-blur-md transition',
                        route().current('games.played') ? 'bg-white/30' : 'bg-white/10 hover:bg-white/20'
                    ]"
                    title="Statistics"
                >
                    <ChartBarIcon class="h-5 w-5"/>
                </Link>

                <Link
                    :href="route('profile.edit')"
                    class="w-20 h-20 mt-6 flex items-center justify-center rounded-full text-white shadow-lg backdrop-blur-md transition bg-white/10 hover:bg-white/20"
                    title="Profile"
                >
                    <UserIcon class="h-5 w-5"/>
                </Link>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="w-20 h-20 mt-6 flex items-center justify-center rounded-full text-white shadow-lg backdrop-blur-md transition bg-white/10 hover:bg-white/20"
                    title="Log Out"
                >
                    <ArrowRightOnRectangleIcon class="h-5 w-5"/>
                </Link>
            </div>

            <div class="flex-1">
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="m-4 p-2 text-white transition"
                    title="Abrir/cerrar menú"
                >
                    <Bars3Icon class="w-6 h-6" />
                </button>

                <header class="bg-white/5 text-white shadow-sm backdrop-blur-2xl" v-if="$slots.header">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <main class="main-content">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>

<style scoped>
.app-layout {
    position: relative;
    min-height: 100vh;
    overflow: auto;
    color: white;
    animation: gradientAnimation 10s ease infinite;
    background: linear-gradient(-45deg, #005054, #04434f, #16585b, #059b8d);
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
    background: radial-gradient(circle at center, rgba(255, 255, 255, 0.05), rgba(0, 0, 0, 0.6));
    z-index: 1; /* Justo debajo del contenido */
}
.flex-1 {
    display: flex;
    flex-direction: column;
    height: 100vh; /* altura total viewport */
}

header {
    flex-shrink: 0;
}

.main-content {
    flex-grow: 1;
    overflow-y: auto;
}
</style>
