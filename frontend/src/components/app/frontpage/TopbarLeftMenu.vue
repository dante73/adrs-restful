<template>
    <b-container class="p-0 m-0" fluid>

        <TopbarDataMenu />

        <TopbarUtilsMenu />

    </b-container>
</template>

<script>
    import TopbarDataMenu from "@/components/app/frontpage/TopbarDataMenu";
    import TopbarUtilsMenu from "@/components/app/frontpage/TopbarUtilsMenu";

    export default {
        name: "TopbarLeftMenu",
        components: {
            TopbarDataMenu,
            TopbarUtilsMenu
        }
    };
</script>