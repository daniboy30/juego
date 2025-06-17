<script>
import { ArrowRightOnRectangleIcon, XCircleIcon } from '@heroicons/vue/24/outline'

export default {
    components:{
        ArrowRightOnRectangleIcon,
        XCircleIcon,
    },
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
                waiting: 'Waiting',
                playing: 'Playing',
                finished: 'Finished'
            }[this.status] || this.status;
        },
        canJoin() {
            const playerIds = this.game.boards.map(b => b.user.id);
            return (
                this.status === 'waiting' &&
                !playerIds.includes(this.currentUserId) &&
                playerIds.length < 2
            );
        },
        isCreator(){
            const creatorId = this.game.boards[0]?.user.id;
            return this.currentUserId === creatorId;
        }
    },
    methods:{
        async cancelGame(){
            try {
                await axios.put(route('games.cancel', this.game.id));
                this.$emit('refresh');
            } catch (e) {
                console.error('Error cancelling the game:', e);
            }
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
        <div class="flex gap-2 mt-2 sm:mt-0">
            <button
                @click="$emit('join', game.id)"
                class="px-4 py-2 bg-blue-500 text-white rounded flex items-center gap-2 disabled:opacity-50"
                :disabled="!canJoin"
                title="Join"
            >
                <ArrowRightOnRectangleIcon class="w-5 h-5" />
            </button>

            <button
                v-if="isCreator && status === 'waiting'"
                @click="cancelGame"
                title="Cancel"
                class="px-4 py-2 bg-red-500 text-white rounded flex items-center gap-2"
            >
                <XCircleIcon class="w-5 h-5" />
            </button>
        </div>
    </div>
</template>

<style scoped>
</style>
