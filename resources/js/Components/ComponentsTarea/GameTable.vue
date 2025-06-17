<script>
import { EyeIcon } from "@heroicons/vue/24/outline";

export default {
    props: {
        games: Array
    },
    emits: ['view'],
    components: {
        EyeIcon
    }
}
</script>

<template>
    <table class="w-full text-white">
        <thead>
        <tr>
            <th>Me</th>
            <th>Opponent</th>
            <th>Details</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="game in games" :key="game.id">
            <td>{{ game.me }}</td>
            <td>{{ game.opponent }}</td>
            <td>
                <button @click="$emit('view', game)">
                    <EyeIcon class="h-5 w-5 text-white hover:text-blue-600" />
                </button>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<style scoped>
/*PAra la table*/
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 12px;
    color: white;
    font-size: 0.95rem;
}

thead th {
    text-align: left;
    font-weight: 600;
    padding: 0.75rem 1rem;
    color: #cfd8dc;
    background: transparent;
}

tbody tr {
    background: rgb(17, 31, 35);
    transition: background 0.2s ease;
    border-radius: 8px;
}

tbody tr:hover {
    background: rgba(255, 255, 255, 0.08);
}

tbody td {
    padding: 0.75rem 1rem;
}

tbody td:first-child {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}

tbody td:last-child {
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
}

button {
    background: none;
    border: none;
    color: #84c5ff;
    font-size: 1rem;
    cursor: pointer;
    transition: color 0.2s ease;
}

button:hover {
    color: #42a5f5;
}

</style>
