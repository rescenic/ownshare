<script>
    import { PUBLIC_BACKEND_ADDRESS } from "$env/static/public"
    import { goto } from '$app/navigation';
	import { onMount } from "svelte";

    async function validateSession() {
        console.log("validating session...")
        let response = await fetch(PUBLIC_BACKEND_ADDRESS + "/auth/validateSession.php", {
            method: "GET",
            credentials: "include",
        });

        let result = await response.json();

        if(result.error) {
            goto("/auth/login");
        } else {
            console.log("valid session!");
        } 
    }

    onMount(() => {
        validateSession();
    });
</script>

<div class="flex h-screen">
    <nav class="bg-base-100 w-56 h-screen p-4 flex flex-col justify-between">
        <div>
            <a href="/" class="text-2xl text-center w-full block p-4">Own<span class="font-bold">Share</span></a>

            <ul class="menu p-0 w-full h-full">
                <li>
                    <a href="/admin/files" class="w-full"><img src="/icons/file.svg" alt="">Files</a>
                </li>
                <li>
                    <a href="/admin/users"><img src="/icons/users.svg" alt="">Users</a>
                </li>
                <li>
                    <a href="/admin/settings"><img src="/icons/settings.svg" alt="">Settings</a>
                </li>
            </ul>
        </div>

        <div>
            <a href="/auth/logout" class="btn btn-outline btn-sm w-full">Logout</a>
        </div>
    </nav>

    <main class="p-8 w-full bg-base-300 h-auto overflow-y-scroll">
        <slot/>
    </main>
</div>