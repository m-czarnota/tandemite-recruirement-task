<script setup>
import ClientsList from './../components/Client/ClientsList.vue';
</script>

<template>
    <Suspense>
        <ClientsList/>

        <template #fallback>
            Loading...
        </template>
    </Suspense>
</template>