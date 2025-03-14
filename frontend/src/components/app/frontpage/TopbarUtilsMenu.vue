<template>
    <b-nav-item-dropdown text="Utilitários" class="p-0 m-0 small">

        <b-dropdown-item href="#" class="small">
            <b-icon icon="person-fill"></b-icon>
            <router-link to='/helloworld'> Vue's Hello World</router-link>
        </b-dropdown-item>

    </b-nav-item-dropdown>
</template>

<script>
    export default {
        name: "TopbarUtilsMenu"
    }
</script>