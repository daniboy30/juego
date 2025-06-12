<script>
import {Head, Link} from "@inertiajs/vue3";
export default {
    components:{
        Head,
        Link,
    },
    props:{
        canLogin:{
            type: Boolean
        },
        canRegister: {
            type:Boolean
        },
    }
}
</script>

<template>
    <div class="animated-bg">
        <Head title="Welcome" />

        <header class="fixed top-0 left-0 w-full z-50 bg-black/60 shadow-lg backdrop-blur py-4 px-6">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <img style="max-height: 5em" src="../Images/logoBarco.png" alt="Imagen">

                <nav v-if="canLogin" class="space-x-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-white font-semibold"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-white font-semibold"
                        >
                            Login
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-white font-semibold"
                        >
                            Register
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <div class="absolute inset-0 flex flex-col justify-center items-center px-4 z-40">
            <h1 class="text-6xl sm:text-7xl font-bold drop-shadow-lg animate-pulse">
                Ocean War
            </h1>
            <p class="mt-4 text-xl sm:text-2xl text-blue-200 drop-shadow">
                Crush the enemy fleet and dominate the seas!
            </p>
            <Link
                :href="route('login')"
            >
                <button type="button" class="glass-button"><span>Play Now</span></button>
            </Link>
        </div>
    </div>
</template>

<style>
.animated-bg {
    width: 100vw;
    height: 150vh;
    background: linear-gradient(-45deg, #005054, #04434f, #16585b, #059b8d);
    background-size: 400% 400%;
    animation: gradientAnimation 10s ease infinite;
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

.glass-button {
    margin-top: 10px;
    padding: 1rem 3rem;
    font-size: 1.5rem;
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
