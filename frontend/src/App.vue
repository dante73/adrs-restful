<template>
    <b-container class="p-0 m-0" fluid>

        <!-- Sidebar and Topbar with all components, only for users who is logged in -->
        <b-container class="p-0 m-0" fluid v-if="isLogged">

            <Sidebar />

            <TopbarNav />

        </b-container>
        <!-- End of TopBar -->

        <!-- Main container with the view for router -->
        <b-container class="p-0 m-0" fluid>
            <router-view></router-view>
        </b-container>
        <!-- End of main container with the view for router -->

    </b-container>
</template>

<script>
    import store from '@/store';

    import Sidebar from "@/components/app/frontpage/Sidebar";
    import TopbarNav from "@/components/app/frontpage/TopbarNav";

    export default {
        components: {
            Sidebar,
            TopbarNav
        },
        beforeCreate() {
            if ( ! store.state.isLogged) {
                this.$router.push('/login')
                    .catch(()=>{
                        console.log('Not logged, push login !')
                    });
            }
        },
        computed: {
            isLogged() {
                return store.state.isLogged;
            }
        }
    }
</script>