<template>

    <b-col md="3" class="p-0 m-0 pt-1 small">

        <!-- Right aligned nav items -->
        <b-navbar-nav>

            <!--
            <b-nav-form>
            <b-input-group class="mb-2 mr-2">
            <b-form-input class="ml-sm-2" placeholder="Search"></b-form-input>
            <b-input-group-append is-text>
            <b-icon icon="search"></b-icon>
            </b-input-group-append>
            </b-input-group>
            </b-nav-form>
            -->

            <TopbarUserMenu />

            <SidebarToggleButtom />

        </b-navbar-nav>
        <!-- End of right aligned nav items -->

    </b-col>

</template>

<script>
    import TopbarUserMenu from "@/components/app/frontpage/TopbarUserMenu";
    import SidebarToggleButtom from "@/components/app/frontpage/SidebarToggleButtom";

    export default {
        name: "TopbarRightMenu",
        components: {
            TopbarUserMenu,
            SidebarToggleButtom
        }
    }
</script>

<style scoped>

</style>