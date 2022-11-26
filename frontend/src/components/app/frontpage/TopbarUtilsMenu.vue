<template>

    <b-nav-item-dropdown text="Utilitários">
        <b-dropdown-item href="#">
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