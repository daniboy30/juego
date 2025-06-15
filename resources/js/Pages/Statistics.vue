<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import StatisticsModal from "@/Components/ComponentsTarea/StatisticsModal.vue";
import {route} from "ziggy-js";
import GameTable from "@/Components/ComponentsTarea/GameTable.vue";
import GameBoard from "@/Components/ComponentsTarea/GameBoard.vue";
import axios from "axios";

export default {
    data() {
        return{
            showModal: false,
            modalContent: 'table',
            selectedType: null,
            gamesList:[],
            selectedGame: null,
        }
    },
    components:{
        GameTable,
        GameBoard,
        StatisticsModal,
        AuthenticatedLayout,
    },
    props:{
        wins: Number,
        losses: Number,
    },
    computed: {
        data() {
            return [
                { label: 'Ganadas', value: this.wins, color: '#b2d6ff' },
                { label: 'Perdidas', value: this.losses, color: '#ffbcec' },
            ];
        },
        maxValue() {
            return Math.max(...this.data.map(d => d.value), 1);
        }
    },
    methods: {
        async openModal(type){
            this.modalContent= 'table'
            this.selectedType = type;
            this.showModal = true;

            try{
                const res = await axios.get(route('games.results'), {
                    params: {
                        type: type,
                    }
                })
                console.log(res.data.results)
                this.gamesList = res.data.results.length === 0 ? [] : res.data.results;
            } catch (e) {
                console.error('Error al obtener las partidas:', e);
            }
        },
        closeModal(){
            this.showModal = false;
            this.selectedType = null;
            this.gamesList = [];
            this.selectedGame = null;
        },
        async viewGame(game){
            try {
                const res = await axios.get(route('games.api', game.id));
                this.selectedGame = res.data.game;
                this.modalContent = 'board'
            } catch (e) {
                console.error('Error al obtener la partida:', e);
            }

        },
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="chart-wrapper">
            <h2 class="chart-title">Estadísticas de Partidas</h2>
            <div class="chart">
                <div
                    v-for="item in data"
                    :key="item.label"
                    class="bar"
                    :style="{
                        height: ((item.value / maxValue) * 100) + '%',
                        backgroundColor: item.color
                    }"
                    @click="item.value !== 0 ? openModal(item.label === 'Ganadas' ? 'won' : 'lost') : null"
                >
                    <span class="bar-value">{{ item.value }}</span>
                </div>
            </div>
            <div class="labels">
                <div
                    v-for="item in data"
                    :key="item.label + '-label'"
                    class="label"
                >
                    {{ item.label }}
                </div>
            </div>
        </div>

        <StatisticsModal
            v-if="showModal"
            :title="modalContent === 'table' ? (selectedType === 'won' ? 'Partidas Ganadas' : 'Partidas Perdidas') : 'Última jugada'"
            @close="closeModal"
        >
            <template v-if="modalContent === 'table'">
                <GameTable :games="gamesList" @view="viewGame" />
            </template>

            <template v-else>
                <GameBoard :game="selectedGame" @view="viewGame" />
            </template>
        </StatisticsModal>


    </AuthenticatedLayout>
</template>

<style scoped>
.chart-wrapper {
    max-width: 90%;
    margin: 40px auto;
    padding: 20px;
    background: rgba(171, 208, 255, 0.27);
    border-radius: 8px;
}

.chart-title {
    text-align: center;
    font-size: 1.5rem;
    color: #f5f5f5;
    margin-bottom: 1.5rem;
}

.chart {
    display: flex;
    align-items: flex-end;
    justify-content: space-around;
    height: 220px;
    border-left: 1px solid #ffffff;
    border-bottom: 1px solid #ffffff;
    padding: 0 10px;
}

.bar {
    width: 20%;
    position: relative;
    transition: background-color 0.3s ease;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    cursor: pointer;
}

.bar-value {
    color: #000d15;
    font-size: 0.9rem;
    padding-bottom: 6px;
    font-weight: 500;
}

.labels {
    display: flex;
    justify-content: space-around;
    margin-top: 12px;
}

.label {
    font-size: 0.95rem;
    color: #ffffff;
    text-align: center;
    width: 60px;
}
</style>
