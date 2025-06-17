<script>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { defineComponent } from 'vue';

export default defineComponent({
    components: {
        InputError,
        InputLabel,
        PrimaryButton,
        TextInput,
        Link,
    },
    props: {
        mustVerifyEmail: {
            type: Boolean,
            required: true,
        },
        status: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            user: usePage().props.auth.user,
            form: null,
        };
    },
    created() {
        this.form = useForm({
            name: this.user.name,
            email: this.user.email,
        });
    },
    methods: {
        submit() {
            this.form.patch(route('profile.update'));
        },
    },
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-semibold text-white">Profile Information</h2>
            <p class="mt-1 text-sm text-teal-200">
                Update your name and email address.
            </p>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Name" class="text-black" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full bg-white text-black"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" class="text-white" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-white text-black"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-yellow-200">
                    Your email address is not verified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-teal-300 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500"
                    >
                        Click here to resend the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-400"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-from-class="opacity-0"
                    leave-to-class="opacity-0"
                    class="transition ease-in-out"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-green-300">
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<style scoped>
section {
    background: rgba(72, 111, 117, 0.26);
    padding: 2rem;
    border-radius: 0.75rem;
}
</style>
