<script>
    import { PUBLIC_BACKEND_ADDRESS } from "$env/static/public"
    import { goto } from '$app/navigation';
	import { onMount } from "svelte";

    async function logout() {
        let response = await fetch(PUBLIC_BACKEND_ADDRESS + "/auth/logout.php", {
            method: "POST",
            credentials: "include",
        });

        goto("/auth/login");
    }

    onMount(() => {
        logout();
    })

</script>