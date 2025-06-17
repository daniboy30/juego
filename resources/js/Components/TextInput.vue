<script>
import { ref, onMounted } from 'vue';

export default {
    props: ['modelValue'],
    emits: ['update:modelValue'],
    setup(props, { emit, expose }) {
        const input = ref(null);

        onMounted(() => {
            if (input.value && input.value.hasAttribute('autofocus')) {
                input.value.focus();
            }
        });

        expose({
            focus: () => input.value.focus(),
        });

        return {
            input,
        };
    },
};
</script>

<template>
    <input
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>
