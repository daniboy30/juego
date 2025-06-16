<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-semibold text-white">Eliminar cuenta</h2>

            <p class="mt-1 text-sm text-teal-200">
                Una vez que se elimine tu cuenta, todos tus datos se perderán permanentemente. Asegúrate de guardar
                cualquier información que desees conservar antes de continuar.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">
            Eliminar cuenta
        </DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6 bg-white rounded-lg">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Estás seguro de que deseas eliminar tu cuenta?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Esta acción no se puede deshacer. Por favor, introduce tu contraseña para confirmar que deseas
                    eliminar tu cuenta permanentemente.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Contraseña" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4 bg-white text-black"
                        placeholder="Contraseña"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Eliminar
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>

<style scoped>
section {
    background: linear-gradient(-45deg, #005054, #04434f, #16585b, #059b8d);
    padding: 2rem;
    border-radius: 0.75rem;
}
</style>
