<script>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

export default {
    components: { InputError, InputLabel, TextInput, PrimaryButton, Link },
    props: {
        mustVerifyEmail: Boolean,
        status: String,
    },
    data() {
        const user = usePage().props.auth.user;
        return {
            avatarPreview: user.avatar ? `/storage/${user.avatar}` : null,
            form: useForm({
                first_name: user.first_name ?? '',
                last_name: user.last_name ?? '',
                email: user.email ?? '',
                phone: user.phone ?? '',
                country: user.country ?? '',
                city: user.city ?? '',
                avatar: null,
            }),
        };
    },
    methods: {
        onAvatarChange(e) {
            const file = e.target.files[0];
            this.form.avatar = file;
            if (file) this.avatarPreview = URL.createObjectURL(file);
        },
        submit() {
            this.form
                .transform((data) => ({ ...data, _method: 'patch' }))
                .post(route('profile.update'), {
                    preserveScroll: true,
                });
        },
    },
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Informations du profil</h2>
            <p class="mt-1 text-sm text-gray-600">
                Mettez à jour votre identité, vos coordonnées et votre photo de profil.
            </p>
        </header>

        <form class="mt-6 space-y-6" @submit.prevent="submit">

            <!-- Avatar -->
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 shrink-0 overflow-hidden rounded-full bg-gray-100">
                    <img v-if="avatarPreview" :src="avatarPreview" class="h-full w-full object-cover" alt="" />
                    <span v-else class="flex h-full w-full items-center justify-center text-xl text-gray-400">
                        {{ form.first_name?.[0] }}{{ form.last_name?.[0] }}
                    </span>
                </div>
                <div>
                    <InputLabel value="Photo de profil" />
                    <input type="file" accept="image/*" class="mt-1 text-sm" @change="onAvatarChange" />
                    <InputError class="mt-1" :message="form.errors.avatar" />
                </div>
            </div>

            <!-- Prénom / Nom -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <InputLabel for="first_name" value="Prénom" />
                    <TextInput
                        id="first_name"
                        v-model="form.first_name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.first_name" />
                </div>
                <div>
                    <InputLabel for="last_name" value="Nom" />
                    <TextInput
                        id="last_name"
                        v-model="form.last_name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <InputError class="mt-2" :message="form.errors.last_name" />
                </div>
            </div>

            <!-- Email -->
            <div>
                <InputLabel for="email" value="Adresse e-mail" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && !$page.props.auth.user.email_verified_at" class="text-sm">
                <p class="mt-2 text-gray-800">
                    Votre adresse e-mail n'est pas vérifiée.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="text-sm text-primary-600 underline hover:text-primary-700"
                    >
                        Renvoyer l'e-mail de vérification.
                    </Link>
                </p>
                <div v-show="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                    Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
                </div>
            </div>

            <!-- Téléphone / Pays / Ville -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <InputLabel for="phone" value="Téléphone" />
                    <TextInput
                        id="phone"
                        v-model="form.phone"
                        type="tel"
                        class="mt-1 block w-full"
                        placeholder="+225..."
                    />
                    <InputError class="mt-2" :message="form.errors.phone" />
                </div>
                <div>
                    <InputLabel for="country" value="Pays de résidence" />
                    <TextInput
                        id="country"
                        v-model="form.country"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError class="mt-2" :message="form.errors.country" />
                </div>
                <div>
                    <InputLabel for="city" value="Ville de résidence" />
                    <TextInput
                        id="city"
                        v-model="form.city"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <InputError class="mt-2" :message="form.errors.city" />
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Enregistrer</PrimaryButton>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-green-600">Enregistré.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
