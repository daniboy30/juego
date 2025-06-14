<template>
    <Head :title="`Game #${game.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-semibold text-white">
                Partida #{{ game.id }}
            </h2>
        </template>

        <div class="py-8 space-y-8">
            <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tu tablero -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-2">Tu tablero</h3>
                    <div class="grid grid-cols-8 gap-1">
                        <template v-for="row in rows" :key="row">
                            <div
                                v-for="col in cols"
                                :key="col"
                                class="w-8 h-8 border bg-gray-200 flex items-center justify-center"
                            >
                                <!-- Marca barco si tu grid incluye la posición -->
                                <span v-if="myGrid.includes(row + col)" class="text-blue-600">🚢</span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Tablero del oponente -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-2">
                        Tablero del oponente
                    </h3>
                    <div class="grid grid-cols-8 gap-1">
                        <template v-for="row in rows" :key="row">
                            <div
                                v-for="col in cols"
                                :key="col"
                                class="w-8 h-8 border bg-gray-700 flex items-center justify-center"
                            >
                                <!-- Aquí más adelante pintaremos aciertos/fallos -->
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Movimientos (placeholder) -->
            <div class="max-w-4xl mx-auto">
                <h3 class="text-lg font-semibold text-white mb-2">Movimientos</h3>
                <ul class="list-disc list-inside text-white">
                    <li v-for="move in game.moves" :key="move.id">
                        {{ move.user.name }} → {{ move.position }} — {{ move.hit ? '¡Tocado!' : 'Agua' }}
                    </li>
                    <li v-if="game.moves.length === 0" class="text-gray-400">
                        Aún no hay movimientos.
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

export default {
    props: {
        game: Object
    },
    components: {
        AuthenticatedLayout,
        Head
    },
    computed: {
        // Filas A-H
        rows() {
            return ['A','B','C','D','E','F','G','H']
        },
        // Columnas 1-8 (como strings)
        cols() {
            return ['1','2','3','4','5','6','7','8']
        },
        // El grid (array de posiciones) de mi tablero
        myGrid() {
            const meId = this.$page.props.auth.user.id
            const board = this.game.boards.find(b => b.user.id === meId)
            return board ? board.grid : []
        }
    }
}
</script>

<style scoped>
/* Ajustes opcionales de estilos */
</style>
