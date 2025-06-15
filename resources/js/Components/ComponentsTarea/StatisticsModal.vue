<script>
export default {
    props: {
        title: {
            type: String,
            default: 'Statistics'
        }
    },
}
</script>
<template>
    <div class="modal-backdrop" @click.self="$emit('close')">
        <div class="modal-content">
            <header class="modal-header">
                <h2 class="text-xl font-bold text-white">{{ title }}</h2>
                <button @click="$emit('close')" class="text-white">✖</button>
            </header>

            <div class="modal-body mt-4">
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
.modal-backdrop {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(6px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 50;
}
.modal-content {
    background-color: #13202c;
    padding: 2rem;
    border-radius: 1rem;
    width: 90%;
    max-width: 900px;
    box-shadow: 0 0 20px rgba(0,0,0,0.5);
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>
