<script setup>
import LoginForm from './../components/Login/LoginForm.vue';
import { Guard } from './../Authenticator/Guard';
import router from './../router';

if (Guard.getToken()) {
    router.push({
        name: 'listClients',
    });
}
</script>

<template>
    <div>
        <h1 class="font-bold text-3xl mb-3">Sign in to see list of clients</h1>
        <LoginForm/>
    </div>
</template>