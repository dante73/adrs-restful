<template>
    <b-nav-item-dropdown text="Gerenciar Dados" class="p-0 m-0 small">

        <b-dropdown-item href="#" class="small">
            <b-icon icon="person-fill"></b-icon>
            <router-link to='/pessoas'> Cadastro de Pessoas</router-link>
        </b-dropdown-item>
        <b-dropdown-item href="#" class="small">
            <b-icon icon="shop"></b-icon>
            <router-link to='/clientes'> Cadastro de Clientes</router-link>
        </b-dropdown-item>
        <b-dropdown-divider></b-dropdown-divider>
        <b-dropdown-item href="#" class="small">
            <router-link to='/estados'>de Estados da Federação</router-link>
        </b-dropdown-item>

    </b-nav-item-dropdown>
</template>

<script>
    export default {
        name: "TopbarDataMenu"
    }
</script>