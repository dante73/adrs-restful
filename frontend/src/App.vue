<template>
    <b-container class="p-0 m-0 h-100" fluid>

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

        <!-- Footer -->
        <b-container class="p-0 m-0" fluid v-if="isLogged">
            <Footer />
        </b-container>
        <!-- End of Footer -->

    </b-container>
</template>

<script>
    import store from '@/store';

    import Sidebar from "@/components/app/frontpage/Sidebar";
    import TopbarNav from "@/components/app/frontpage/TopbarNav";
    import Footer from "@/components/app/frontpage/Footer";

    export default {
        components: {
            Sidebar,
            TopbarNav,
            Footer
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