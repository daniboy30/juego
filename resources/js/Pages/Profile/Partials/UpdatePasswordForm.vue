<script>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

export default {
    components: {
        InputError,
        InputLabel,
        PrimaryButton,
        TextInput,
    },
    data() {
        return {
            form: useForm({
                current_password: '',
                password: '',
                password_confirmation: '',
            }),
        };
    },
    methods: {
        updatePassword() {
            this.form.put(route('password.update'), {
                preserveScroll: true,
                onSuccess: () => this.form.reset(),
                onError: () => {
                    if (this.form.errors.password) {
                        this.form.reset('password', 'password_confirmation');
                        this.$refs.passwordInput.focus();
                    }
                    if (this.form.errors.current_password) {
                        this.form.reset('current_password');
                        this.$refs.currentPasswordInput.focus();
                    }
                },
            });
        },
    },
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-semibold text-white">Update Password</h2>

            <p class="mt-1 text-sm text-teal-200">
                Use a long, secure password to protect your account.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <InputLabel for="current_password" value="Current Password" class="text-white" />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full bg-white text-black"
                    autocomplete="current-password"
                />

                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password" value="New Password" class="text-white" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full bg-white text-black"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" class="text-white" />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full bg-white text-black"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">
                    Save
                </PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-green-300">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<style scoped>
section {
    padding: 2rem;
    background: rgba(72, 111, 117, 0.26);
    border-radius: 0.75rem;
}
</style>
