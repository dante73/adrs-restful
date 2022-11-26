<template>
    <b-container class="p-0 m-0" fluid="xl">

        <!-- Navbar -->
        <b-navbar
                type="light"
                toggleable="sm"
                class="pl-1 py-0 border-bottom border-success shadow-sm"
                bg-transparent
                fixed-top>

            <ADRsLogo />

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav class="pb-0">
                <b-container class="p-0 m-0" fluid="xl">

                    <!-- Linha um da navbar, painel de informações gerais -->
                    <b-row class="p-0 m-0 w-100">
                        <TopbarLineOne />
                    </b-row>
                    <!-- End of painel de informações gerais -->

                    <!-- linha dois da navbar -->
                    <b-row class="p-0 m-0 w-100">
                        <b-col md="9" class="p-0 m-0 small">
                            <b-navbar-nav>

                                <TopbarDataMenu />

                                <TopbarUtilsMenu />

                            </b-navbar-nav>
                        </b-col>
                        <b-col md="3" class="p-0 m-0 pt-1 small">

                            <!-- Right aligned nav items -->
                            <b-navbar-nav>

                                <TopbarRightMenu />

                            </b-navbar-nav>
                            <!-- End of right aligned nav items -->

                        </b-col>
                    </b-row>
                    <!-- End of linha dois da navbar -->

                </b-container>
            </b-collapse>
        </b-navbar>

    </b-container>
</template>

<script>
    import ADRsLogo from "@/components/app/frontpage/ADRsLogo";
    import TopbarLineOne from "@/components/app/frontpage/TopbarLineOne";
    import TopbarDataMenu from "@/components/app/frontpage/TopbarDataMenu";
    import TopbarUtilsMenu from "@/components/app/frontpage/TopbarUtilsMenu";
    import TopbarRightMenu from "@/components/app/frontpage/TopbarRightMenu";

    export default {
        name: "TopbarNavBar",
        components: {
            ADRsLogo,
            TopbarLineOne,
            TopbarDataMenu,
            TopbarUtilsMenu,
            TopbarRightMenu
        }
    }
</script>