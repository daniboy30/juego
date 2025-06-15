<script>
export default {
    props: {
        game: Object,
    },
    emits: ['view'],
    computed: {
        rows() {
            return ['A','B','C','D','E','F','G','H'];
        },
        cols() {
            return ['1','2','3','4','5','6','7','8'];
        },
        meId() {
            return this.$page.props.auth.user.id;
        },
        myBoard() {
            return this.game.boards.find(b => b.user.id === this.meId) || { grid: [] };
        },
        opponentBoard() {
            return this.game.boards.find(b => b.user.id !== this.meId) || { grid: [] };
        },
        myMovesMap() {
            const map = {};
            this.game.moves
                .filter(m => m.user_id === this.meId)
                .forEach(m => {
                    const pos = this.rows[m.x] + (m.y + 1);
                    map[pos] = m.result;
                });
            return map;
        },
        opponentMovesMap() {
            const map = {};
            this.game.moves
                .filter(m => m.user_id !== this.meId)
                .forEach(m => {
                    const pos = this.rows[m.x] + (m.y + 1);
                    map[pos] = m.result;
                });
            return map;
        }
    }
}
</script>

<template>
    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-white text-lg font-semibold mb-2">Tu tablero</h3>
            <div class="grid grid-cols-[40px_repeat(8,40px)]">
                <div></div>
                <div v-for="col in cols" :key="'col-head-' + col" class="text-white text-center font-bold">{{ col }}</div>
                <template v-for="row in rows" :key="row">
                    <div class="text-white text-center font-bold">{{ row }}</div>
                    <div
                        v-for="col in cols"
                        :key="row + col"
                        class="w-10 h-10 border border-gray-600 bg-blue-100 flex justify-center items-center"
                    >
                        <template v-if="myBoard.grid.includes(row + col)">
                            🚢
                        </template>
                        <template v-if="myBoard.grid.includes(row + col)">
                            <span v-if="opponentMovesMap[row + col] === 'hit'">🔥</span>
                        </template>
                        <template v-else-if="opponentMovesMap[row + col] === 'miss'">
                            <span class="text-blue-400">💦</span>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <div>
            <h3 class="text-white text-lg font-semibold mb-2">Tablero del oponente</h3>
            <div class="grid grid-cols-[40px_repeat(8,40px)]">
                <div></div>
                <div v-for="col in cols" :key="'col-op-' + col" class="text-white text-center font-bold">{{ col }}</div>
                <template v-for="row in rows" :key="'op-' + row">
                    <div class="text-white text-center font-bold">{{ row }}</div>
                    <div
                        v-for="col in cols"
                        :key="row + col"
                        class="w-10 h-10 border border-gray-700 bg-gray-800 flex justify-center items-center space-x-1"
                    >
                        <template v-if="opponentBoard.grid.includes(row + col)">
                            <span>🚢</span>
                            <span v-if="myMovesMap[row + col] === 'hit'" class="text-red-500">🔥</span>
                        </template>

                        <template v-else-if="myMovesMap[row + col] === 'miss'">
                            <span class="text-blue-300">💦</span>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
