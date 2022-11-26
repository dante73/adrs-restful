<template>

    <b-navbar-nav>
        <b-nav-item-dropdown text="Gerenciar Dados">
            <b-dropdown-item href="#">
                <b-icon icon="person-fill"></b-icon>
                <router-link to='/pessoas'> Cadastro de Pessoas</router-link>
            </b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item href="#">
                <b-icon icon="shop"></b-icon>
                <router-link to='/clientes'> Cadastro de Clientes</router-link>
            </b-dropdown-item>
            <b-dropdown-item href="#">
                <b-icon icon="person-fill"></b-icon>
                <router-link to='/usuarios'> Cadastro de Usuários</router-link>
            </b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item href="#">
                <router-link to='/estados'>de Estados da Federação</router-link>
            </b-dropdown-item>
            <b-dropdown-item href="#">
                <router-link to='/regioes'>de Regiões do Brasil</router-link>
            </b-dropdown-item>
            <b-dropdown-item href="#">
                <router-link to='/cidades'>de Cidades do Brasil *slow*</router-link>
            </b-dropdown-item>
            <b-dropdown-item href="#">
                <router-link to='/pessoas'>de Pessoas</router-link>
            </b-dropdown-item>
            <b-dropdown-item href="#">
                <router-link to='/users'>de Usuários</router-link>
            </b-dropdown-item>
        </b-nav-item-dropdown>
    </b-navbar-nav>

</template>

<script>
    export default {
        name: "TopbarDataMenu"
    }
</script>

<style scoped>

</style>