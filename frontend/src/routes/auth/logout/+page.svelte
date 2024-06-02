<script>
    import { goto } from '$app/navigation';
	import { onMount } from "svelte";
    import { base } from '$app/paths';
    import { getConfig } from "$lib/api.js";

    async function logout() {
        const cfg = await getConfig();
        let backendAddress = cfg.backendAddress;

        let response = await fetch(backendAddress + "/auth/logout.php", {
            method: "POST",
            credentials: "include",
        });

        goto(base + "/auth/login");
    }

    onMount(() => {
        logout();
    })

</script>