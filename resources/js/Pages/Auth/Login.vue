<script>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link } from '@inertiajs/vue3';

export default{
    components: {
        Checkbox,
        GuestLayout,
        InputError,
        InputLabel,
        PrimaryButton,
        TextInput,
        Head,
        Link,
    },
    props: {
        canResetPassword: {
            type: Boolean
        },
        status: {
            type: String
        }
    },
    data(){
        return {
            form: {
                email: '',
                password: '',
                remember: false,
                errors: {},
                processing: false,
            }
        }
    },
    methods:{
        submit() {
            this.form.processing = true;

            this.$inertia.post(route('login'), {
                email: this.form.email,
                password: this.form.password,
                remember: this.form.remember,
            }, {
                onError: (errors) => {
                    this.form.errors = errors;
                },
                onFinish: () => {
                    this.form.processing = false;
                    this.form.password = '';
                }
            });
        }
    }
}
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <h1 class="text-3xl font-bold text-center mb-6 text-white text-glow-hover">Sign in</h1>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Link
                        :href="route('register')"
                        class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-white font-semibold"
                    >
                        Don't have an account?
                    </Link>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    v-if="canResetPassword"
                    href="/"
                    class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-white font-semibold"
                >
                    Back
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<style scoped>
.text-glow-hover{
    transition: 0.5s;
}
.text-glow-hover:hover {
    text-shadow: 0 0 10px rgb(0, 255, 246), 0 0 20px rgb(38, 255, 223);
}
</style>
