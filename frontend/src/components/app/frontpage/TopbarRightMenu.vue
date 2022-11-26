<template>
    <b-container class="p-0">

        <TopbarUserMenu />

        <SidebarToggleButtom />

    </b-container>
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