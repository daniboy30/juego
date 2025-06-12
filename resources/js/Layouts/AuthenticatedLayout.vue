<script >
import {HomeIcon} from "@heroicons/vue/24/outline";
import {UserIcon, ArrowRightOnRectangleIcon} from "@heroicons/vue/24/outline";

import {Link} from '@inertiajs/vue3';
export default {
    components: {
        Link,
        HomeIcon,
        UserIcon,
        ArrowRightOnRectangleIcon,
    }
}

</script>

<template>
    <div class="app-layout">
        <div class="overlay"></div>
        <div class="flex min-h-screen relative z-10">
            <div class="w-40 p-10 bg-white/5 text-white shadow-sm backdrop-blur-2xl">
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

            <!-- Contenido principal -->
            <div class="flex-1">
                <header class="bg-white shadow" v-if="$slots.header">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header"/>
                    </div>
                </header>

                <main>
                    <slot/>
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
</style>
