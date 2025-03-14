<template>

    <b-navbar
            id="navbar-container"
            class="navbar navbar-expand-md navbar-light fixed-top bg-white pl-1 py-0 border-bottom border-success shadow-sm"
    >

        <ADRsLogo />

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <b-container class="p-0 m-0" fluid>
                <b-row class="p-0 m-0 w-100">

                    <b-col md="3" class="p-0 m-0">

                        <!-- Linha um da navbar, lado esquerdo -->
                        <b-row class="p-0 m-0 w-100">
                            <TopbarLeftLineOne />
                        </b-row>
                        <!-- End of painel de informações gerais -->

                        <b-row class="p-0 m-0 w-100">

                            <!-- Left aligned nav items -->
                            <b-navbar-nav>
                                <TopbarLeftMenu />
                            </b-navbar-nav>
                            <!-- End of left aligned nav items -->

                        </b-row>

                    </b-col>

                    <!-- Show current option -->
                    <b-col md="6" class="p-0 m-0 pt-2">
                        <b-container class="p-0 m-0 py-1 shadow-sm rounded-pill bg-light border border-default">
                            <span class="mx-auto font-weight-bold small">{{ currentRouteName }}</span>
                        </b-container>
                    </b-col>
                    <!-- End of show current option -->

                    <b-col md="3" class="p-0 m-0">

                        <!-- Linha um da navbar, painel de informações gerais -->
                        <b-row class="p-0 m-0 w-100">
                            <TopbarRightLineOne />
                        </b-row>
                        <!-- End of painel de informações gerais -->

                        <b-row class="p-0 m-0 w-100 justify-content-end">

                            <!-- Right aligned nav items -->
                            <b-navbar-nav>
                                <TopbarRightMenu />
                            </b-navbar-nav>
                            <!-- End of right aligned nav items -->

                        </b-row>

                    </b-col>

                </b-row>
            </b-container>
        </div>
    </b-navbar>

</template>

<script>
    import ADRsLogo from "@/components/app/frontpage/ADRsLogo";
    import TopbarLeftLineOne from "@/components/app/frontpage/TopbarLeftLineOne";
    import TopbarLeftMenu from "@/components/app/frontpage/TopbarLeftMenu";
    import TopbarRightLineOne from "@/components/app/frontpage/TopbarRightLineOne";
    import TopbarRightMenu from "@/components/app/frontpage/TopbarRightMenu";

    export default {
        name: "TopbarNavBar",
        components: {
            ADRsLogo,
            TopbarLeftLineOne,
            TopbarLeftMenu,
            TopbarRightLineOne,
            TopbarRightMenu
        },
        computed: {
            currentRouteName() {
                return this.$route.name;
            }
        }
    }
</script>