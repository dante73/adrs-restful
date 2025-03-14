<template>
    <b-nav-item-dropdown right class="p-0 m-0 small">

        <!-- Using 'button-content' slot -->
        <template #button-content>
            <b-icon icon="person-fill"></b-icon>&nbsp;
            <span>{{ userData.nome.split(' ')[0] }}</span>
        </template>

        <b-dropdown-item href="#" class="small">Meus Dados</b-dropdown-item>
        <b-dropdown-divider></b-dropdown-divider>
        <b-dropdown-item href="#" class="small">
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