<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        AuthenticatedLayout, DeleteUserForm, UpdatePasswordForm, UpdateProfileInformationForm, Head,PublicLayout
    },
    props: {
        mustVerifyEmail: Boolean,
        status: String,
    },
};
</script>

<template>
    <Head title="Mon compte" />

    <PublicLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Mon compte</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
                </div>

                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <UpdatePasswordForm />
                </div>

                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <DeleteUserForm />
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
