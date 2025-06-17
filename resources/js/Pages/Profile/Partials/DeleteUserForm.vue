<script>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

export default {
    components: {
        DangerButton,
        InputError,
        InputLabel,
        Modal,
        SecondaryButton,
        TextInput,
    },
    data() {
        return {
            confirmingUserDeletion: false,
            form: useForm({
                password: '',
            }),
        };
    },
    methods: {
        confirmUserDeletion() {
            this.confirmingUserDeletion = true;
            this.$nextTick(() => {
                if (this.$refs.passwordInput) {
                    this.$refs.passwordInput.focus();
                }
            });
        },
        deleteUser() {
            this.form.delete(route('profile.destroy'), {
                preserveScroll: true,
                onSuccess: () => this.closeModal(),
                onError: () => {
                    if (this.$refs.passwordInput) {
                        this.$refs.passwordInput.focus();
                    }
                },
                onFinish: () => this.form.reset(),
            });
        },
        closeModal() {
            this.confirmingUserDeletion = false;
            this.form.reset();
        },
    },
};
</script>


<template>
    <section>
        <header>
            <h2 class="text-lg font-semibold text-white">Delete Account</h2>

            <p class="mt-1 text-sm text-teal-200">
                Once your account is deleted, all of your data will be permanently lost. Please make sure to save any information you want to keep before continuing.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">
            Delete Account
        </DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6 bg-white rounded-lg">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    This action cannot be undone. Please enter your password to confirm you want to permanently delete your account.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4 bg-white text-black"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Cancel</SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
<style scoped>
section {
    background: rgba(72, 111, 117, 0.26);
    padding: 2rem;
    border-radius: 0.75rem;
}
</style>
