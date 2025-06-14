<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { Inertia } from '@inertiajs/inertia'
import { route } from 'ziggy-js'
import axios from "axios";
import Swal from "sweetalert2";

export default {
    data() {
        return {
            processingMove: false,
            error: '',
            gameData: JSON.parse(JSON.stringify(this.game)),
            polling: null,
            redirecting: false
        }
    },
    mounted() {
        this.polling = setInterval(this.fetchGame, 2000)
        console.log('Turno actual (ID):', this.gameData.current_turn)
        console.trace("Pila de error posible")
    },
    beforeUnmount() {
        clearInterval(this.polling)
    },
    props: {
        game: Object
    },
    components: {
        Head,
        AuthenticatedLayout,
    },
    computed: {
        rows() {
            return ['A','B','C','D','E','F','G','H']
        },
        cols() {
            return ['1','2','3','4','5','6','7','8']
        },
        myGrid() {
            const meId = this.$page.props.auth.user.id
            const board = this.gameData.boards.find(b => b.user.id === meId)
            return board ? board.grid : []
        },
        isMyTurn() {
            const meId = this.$page.props.auth.user.id
            const turn = this.gameData.current_turn
            return turn && meId === turn
        }
    },
    methods: {
        rowToIndex(row) {
            return this.rows.indexOf(row)
        },
        getMoveResult(row, col) {
            const meId = this.$page.props.auth.user.id
            const x = this.rowToIndex(row)
            const y = Number(col) - 1

            return this.gameData.moves.find(m =>
                m.user_id === meId && m.x === x && m.y === y
            )?.result || null
        },
        async shootAt(row, col) {
            if (this.processingMove) return;
            this.error = ''
            this.processingMove = true
            const x = this.rowToIndex(row)
            const y = Number(col) - 1

            try {
                await axios.post(route('moves.store', this.gameData.id), { x, y })
                await this.fetchGame()
            } catch (e) {
                this.error = e.response?.data?.message || 'Error al disparar.'
            } finally {
                this.processingMove = false
            }
        },
        async fetchGame() {
            if (this.redirecting) return;
            const meId = this.$page.props.auth.user.id
            try {
                const res = await axios.get(route('games.api', this.gameData.id))
                const newGame = res.data.game
                const myBoard = newGame.boards.find(b => b.user.id === meId)
                const myHits = newGame.moves.filter(m => m.result === 'hit' && m.user_id !== meId)
                const myHitsNow = myHits.map(m => {
                    const row = ['A','B','C','D','E','F','G','H'][m.x]
                    return row + (m.y + 1)
                })
                const prevHits = this.gameData.moves.filter(m => m.result === 'hit' && m.user_id !== meId)
                const prevHitsNow = prevHits.map(m => {
                    const row = ['A','B','C','D','E','F','G','H'][m.x]
                    return row + (m.y + 1)
                })

                const newHit = myHitsNow.find(pos => !prevHitsNow.includes(pos))
                if (newHit && myBoard.grid.includes(newHit)) {
                    Swal.fire({
                        icon: 'warning',
                        title: '🔥 Has sido tocado',
                        text: `¡Te dieron en la casilla ${newHit}!`,
                        confirmButtonText: 'Aceptar',
                        background: '#0b0e23',
                        color: '#fff',
                        iconColor: '#ff9a26',
                        confirmButtonColor: '#ff9a26',
                    })
                }
                this.gameData = newGame

                if (newGame.status === 'finished') {
                    this.redirecting = true;
                    clearInterval(this.polling);

                    let swalOptions = {};

                    if (newGame.winner_id === meId) {
                        swalOptions = {
                            icon: 'success',
                            title: '🏆 ¡Has ganado!',
                            text: 'Todos los barcos del oponente han sido destruidos.',
                            confirmButtonText: 'Aceptar',
                            background: '#0b0e23',
                            color: '#fff',
                            iconColor: '#2ecc71',
                            confirmButtonColor: '#2ecc71',
                            timerProgressBar: true,
                        };
                    } else {
                        swalOptions = {
                            icon: 'error',
                            title: '💀 Has perdido',
                            text: 'El oponente destruyó todos tus barcos.',
                            confirmButtonText: 'Aceptar',
                            background: '#0b0e23',
                            color: '#fff',
                            iconColor: '#e74c3c',
                            confirmButtonColor: '#e74c3c',
                            timerProgressBar: true,
                        };
                    }
                    await Swal.fire(swalOptions);
                }
            } catch (e) {
                console.error(e)
            }
        }
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`Game #${gameData.id}`" />

        <h2 class="text-2xl font-semibold text-white">
            Partida #{{ gameData.id }}
        </h2>

        <div class="text-center text-white text-lg mb-4">
            <span v-if="isMyTurn">Es tu turno</span>
            <span v-else>No es tu turno</span>
        </div>
        <div v-if="error" class="text-red-400 text-sm text-center mb-4">
            {{ error }}
        </div>

        <div class="py-8 space-y-8">
            <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Tu tablero</h3>
                    <div class="overflow-x-auto">
                        <div class="grid grid-cols-[40px_repeat(8,40px)]">
                            <div></div>
                            <div v-for="col in cols" :key="'col-header-' + col" class="text-center text-white font-bold">
                                {{ col }}
                            </div>

                            <template v-for="row in rows" :key="row">
                                <div class="text-center text-white font-bold">{{ row }}</div>
                                <div
                                    v-for="col in cols"
                                    :key="row + col"
                                    @click="isMyTurn ? shootAt(row, col) : null"
                                    class="w-10 h-10 border border-gray-500 bg-blue-100 hover:bg-blue-200 transition-all duration-200 flex items-center justify-center"
                                >
                                    <span v-if="myGrid.includes(row + col)" class="text-blue-700 text-xl">🚢</span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Tablero del oponente</h3>
                    <div class="overflow-x-auto">
                        <div class="grid grid-cols-[40px_repeat(8,40px)]">
                            <div></div>
                            <div v-for="col in cols" :key="'col-op-' + col" class="text-center text-white font-bold">
                                {{ col }}
                            </div>

                            <template v-for="row in rows" :key="'op-row-' + row">
                                <div class="text-center text-white font-bold">{{ row }}</div>
                                <div
                                    v-for="col in cols"
                                    :key="row + col"
                                    @click="isMyTurn ? shootAt(row, col) : null"
                                    class="w-10 h-10 border border-gray-600 bg-gray-800 hover:bg-gray-700 cursor-pointer transition-all duration-200 flex items-center justify-center"
                                >
                                    <template v-if="getMoveResult(row, col) === 'hit'">
                                        <span class="text-red-500 text-xl">🔥</span>
                                    </template>
                                    <template v-else-if="getMoveResult(row, col) === 'miss'">
                                        <span class="text-blue-300 text-xl">💦</span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-4xl mx-auto">
                <h3 class="text-lg font-semibold text-white mb-2">Movimientos</h3>
                <ul class="list-disc list-inside text-white">
                    <li v-for="move in gameData.moves" :key="move.id">
                        {{ move.user.name }} → {{ move.position }} — {{ move.result === 'hit' ? '¡Tocado!' : 'Agua' }}
                    </li>
                    <li v-if="gameData.moves.length === 0" class="text-gray-400">
                        Aún no hay movimientos.
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
