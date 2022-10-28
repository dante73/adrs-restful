<template>

    <b-nav-item-dropdown right class="ml-auto">

        <!-- Using 'button-content' slot -->
        <template #button-content>
            <b-icon icon="person-fill"></b-icon> <span>{{ userData.nome }}</span>
        </template>

        <b-dropdown-item href="#">Meus Dados</b-dropdown-item>
        <b-dropdown-divider></b-dropdown-divider>
        <b-dropdown-item href="#">
            <router-link to='/logout'>Sair</router-link>
        </b-dropdown-item>

    </b-nav-item-dropdown>

</template>

<script>
    import ls from "local-storage";

    export default {
        name: "TopBarUserMenu",
        data() {
            return {
                userData: this.$jwt.decode(ls('token'))
            };
        },
    }
</script>

<style scoped>

</style>