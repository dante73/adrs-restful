<template>
    <b-container class="p-0 m-0" fluid>

        <!-- Sidebar with logo, menu and company information  -->
        <b-sidebar id="sidebar" @change="sidebarChangesVisibility" type="dark" no-header shadow>
            <b-img :src="require('@/assets/img/logo.png')" fluid></b-img>
            <b-nav class="mb-3">
                <b-nav-item active>
                    <b-icon icon="shop"></b-icon>
                    <router-link to='/Clientes'> Cadastro de Clientes</router-link>
                </b-nav-item>
                <b-nav-item active>
                    <b-icon icon="person-fill"></b-icon>
                    <router-link to='/Usuarios'> Cadastro de Usuários</router-link>
                </b-nav-item>
            </b-nav>
            <template #footer>
                <div class="sb-footer py-3 px-3">
                    <p class="mr-auto">
                        <span class="small">Logado como:<br/></span>
                        <span>SmartControl ERP</span>
                    </p>
                </div>
            </template>
        </b-sidebar>

    </b-container>
</template>

<script>
    export default {
        name: "SideBar",
        data() {
            return {
                sidebarVisibility: false
            }
        },
        methods: {
            sidebarChangesVisibility(flag) {
                this.$set(this, 'sidebarVisibility', flag);
            }
        }
    }
</script>

<style scoped>
    #sidebar {
        margin-top: 35px;
    }
</style>