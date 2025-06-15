<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'
import axios from 'axios'
import GameCard from '@/Components/ComponentsTarea/GameCard.vue'

export default {
    name: 'Dashboard',
    components: {
        AuthenticatedLayout,
        Head,
        GameCard,
    },
    data() {
        return {
            games: [],
            loading: false,
            error: '',
            polling: null,
        }
    },
    mounted() {
        this.fetchGames()
        this.polling = setInterval(this.fetchGames, 3000)
    },
    beforeUnmount() {
        clearInterval(this.polling)
    },
    methods: {
        async fetchGames() {
            try {
                const res = await axios.get(route('games.index'))
                this.games = res.data.games
            } catch (e) {
                console.error(e)
            }
        },
        async createGame() {
            this.loading = true
            this.error = ''

            try {
                await Inertia.visit(route('games.store'), {
                    method: 'post',
                    preserveState: false,
                    preserveScroll: false,
                })
            } catch (e) {
                console.error('Error al crear el juego:', e)
                this.error = 'No se pudo crear el juego.'
            } finally {
                this.loading = false
            }
        },
        async joinGame(gameId) {
            this.loading = true
            this.error = ''
            const game = this.games.find(g => g.id === gameId)
            const playerIds = game.boards.map(b => b.user.id)

            try {
                if (playerIds.includes(this.$page.props.auth.user.id)) {
                    // Ya es parte del juego, redirige directamente al show
                    await Inertia.visit(route('games.show', gameId))
                } else {
                    // No está en el juego, intenta unirse
                    await Inertia.visit(route('games.update', gameId), {
                        method: 'put',
                        preserveState: false,
                        preserveScroll: false,
                    })
                }
            } catch (e) {
                console.error('Error al unirse:', e)
                this.error = e.response?.data?.message || 'No se pudo unir al juego.'
            } finally {
                this.loading = false
            }
        },
    },
}
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard" />

        <template #header>
            <h2 class="font-bold text-xl text-white leading-tight">
                Welcome {{ $page.props.auth.user.name }}
            </h2>

            <button @click="createGame" class="glass-button text-white">
                {{ loading ? 'Creando...' : 'Create game' }}
            </button>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto px-4">
                <div class="shadow-md sm:rounded-lg p-6">
                    <h3 class="text-2xl font-bold text-white mb-6 text-center">
                        Available Rooms
                    </h3>

                    <div class="space-y-4">
                        <div v-if="games.length === 0">No hay juegos disponibles</div>
                        <div v-else>
                            <GameCard
                                v-for="game in games"
                                :key="game.id"
                                :title="`Juego #${game.id}`"
                                :creator="game.boards[0]?.user.name ?? 'Desconocido'"
                                :status="game.status"
                                :current-user-id="$page.props.auth.user.id"
                                :game="game"
                                @join="joinGame"
                            />
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
    padding: 0.6rem 1.5rem;
    font-size: 1rem;
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
