<script>
export default {
    props: {
        title: {
            type: String,
            default: 'Room'
        },
        creator: {
            type: String,
            default: 'Unknown'
        },
        status: {
            type: String,
            default: 'waiting'
        },
        currentUserId: {
            type: Number,
            required: true
        },
        game: {
            type: Object,
            required: true
        }
    },
    computed: {
        statusColor() {
            return {
                waiting: 'text-yellow-600',
                playing: 'text-green-600',
                finished: 'text-gray-600'
            }[this.status] || 'text-gray-600';
        },
        statusText() {
            return {
                waiting: 'Esperando',
                playing: 'Jugando',
                finished: 'Finalizado'
            }[this.status] || this.status;
        },
        canJoin() {
            // Obtener IDs de jugadores actuales
            const playerIds = this.game.boards.map(b => b.user.id);
            return (
                this.status === 'waiting' &&
                !playerIds.includes(this.currentUserId) &&
                playerIds.length < 2
            );
        }
    }
};
</script>

<template>
    <div class="w-full border border-gray-300 rounded-lg p-4 shadow hover:shadow-lg transition flex flex-col sm:flex-row justify-between items-start sm:items-center">
        <div>
            <h4 class="text-xl font-semibold text-gray-400">{{ title }}</h4>
            <p class="text-sm text-gray-400">Created by: {{ creator }}</p>
            <p class="text-sm text-gray-400 font-semibold mt-1">
                Status: <span :class="statusColor">{{ statusText }}</span>
            </p>
        </div>
        <button
            @click="$emit('join', game.id)"
            class="mt-2 px-4 py-2 bg-blue-500 text-white rounded disabled:opacity-50"
            :disabled="!canJoin"
        >
            Unirse
        </button>
    </div>
</template>
<style scoped>

</style>
