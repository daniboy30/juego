<script setup>
defineProps(['value']);
</script>

<template >
    <label class="block font-medium text-sm text-white">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
