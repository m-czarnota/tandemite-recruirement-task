<script setup>
import { useRouter, useRoute } from 'vue-router'
import ClientsList from './../components/Client/ClientsList.vue';
import { Guard } from './../Authenticator/Guard';

if (!Guard.getToken()) {
    const router = useRouter();

    router.push({
        name: 'login',
    });
}

</script>

<template>
    <h1 class="font-bold text-3xl mb-3">Clients list</h1>
    <Suspense>
        <ClientsList/>

        <template #fallback>
            Loading...
        </template>
    </Suspense>
</template>